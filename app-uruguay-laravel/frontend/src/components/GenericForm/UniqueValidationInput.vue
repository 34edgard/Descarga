<script setup lang="ts">
import { ref, computed, watch, nextTick, onUnmounted } from 'vue';
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import ProgressSpinner from 'primevue/progressspinner';
import Message from 'primevue/message';
import axios from '@/plugins/axios';

// Types
interface UniqueValidationConfig {
    url: string;
    method?: 'GET' | 'POST';
    fieldName?: string;
    debounceTime?: number;
    headers?: Record<string, string>;
    transformRequest?: (value: any) => any;
    transformResponse?: (response: any) => boolean;
}

interface ValidationState {
    isValidating: boolean;
    isValid: boolean | null;
    message: string;
    hasBlurred: boolean;
}

interface Props {
    modelValue: any;
    type?: 'text' | 'number' | 'email' | 'password';
    placeholder?: string;
    disabled?: boolean;
    required?: boolean;
    class?: string;
    uniqueValidation?: UniqueValidationConfig;
    errorMessage?: string;
    id?: string;
    name?: string;
}

// Props con valores por defecto
const props = withDefaults(defineProps<Props>(), {
    type: 'text',
    disabled: false,
    required: false,
    class: '',
    placeholder: ''
});

// Emits
const emit = defineEmits<{
    'update:modelValue': [value: any];
    'validation-change': [isValid: boolean, message: string];
    focus: [event: FocusEvent];
    blur: [event: FocusEvent];
}>();

// Reactive state
const validationState = ref<ValidationState>({
    isValidating: false,
    isValid: null,
    message: '',
    hasBlurred: false
});

const inputRef = ref<HTMLInputElement | null>(null);
const abortController = ref<AbortController | null>(null);
const debounceTimer = ref<number | null>(null);

// Computed properties
const inputClasses = computed(() => [
    props.class,
    {
        'p-invalid': hasValidationError.value,
        'border-green-500': validationState.value.isValid === true && validationState.value.hasBlurred,
        'opacity-50': validationState.value.isValidating
    }
]);

const hasValidationError = computed(() => {
    return props.errorMessage || (validationState.value.isValid === false && validationState.value.hasBlurred);
});

const displayMessage = computed(() => {
    if (props.errorMessage) return props.errorMessage;
    if (validationState.value.isValid === false && validationState.value.hasBlurred) {
        return validationState.value.message;
    }
    return '';
});

const shouldShowValidationIcon = computed(() => {
    return validationState.value.hasBlurred && validationState.value.isValid !== null && !validationState.value.isValidating;
});

// Strategy Pattern: Validation strategies
class UniqueValidationStrategy {
    private static readonly DEFAULT_DEBOUNCE_TIME = 500;
    private static readonly DEFAULT_MESSAGES = {
        exists: 'Este valor ya existe. Por favor, ingrese otro.',
        error: 'Error al validar el valor. Inténtelo nuevamente.',
        networkError: 'Error de conexión. Verifique su conexión a internet.'
    };

    static async validate(value: any, config: UniqueValidationConfig, signal: AbortSignal): Promise<{ isValid: boolean; message: string }> {
        if (!value || !config.url) {
            return { isValid: true, message: '' };
        }

        try {
            const transformedValue = config.transformRequest ? config.transformRequest(value) : value;

            const response = await this.makeRequest(config, transformedValue, signal);
            const exists = config.transformResponse ? config.transformResponse(response) : this.defaultResponseTransform(response);

            return {
                isValid: !exists,
                message: exists ? this.DEFAULT_MESSAGES.exists : ''
            };
        } catch (error) {
            if (error instanceof Error && error.name === 'AbortError') {
                throw error; // Re-throw abort errors
            }

            console.error('Unique validation error:', error);
            return {
                isValid: false,
                message: this.getErrorMessage(error)
            };
        }
    }

    private static async makeRequest(config: UniqueValidationConfig, value: any, signal: AbortSignal): Promise<any> {
        const method = config.method || 'POST';
        const fieldName = config.fieldName || 'value';

        const requestOptions: RequestInit = {
            method,
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                ...config.headers
            },
            signal
        };

        if (method === 'POST') {
            requestOptions.body = JSON.stringify({ [fieldName]: value });
        }

        const url = method === 'GET' ? `${config.url}?${fieldName}=${encodeURIComponent(value)}` : config.url;

        const response = await fetch(url, requestOptions);

        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }

        return response.json();
    }

    private static defaultResponseTransform(response: any): boolean {
        // Asume que la respuesta tiene una propiedad 'exists' o 'found'
        return response?.exists || response?.found || false;
    }

    private static getErrorMessage(error: unknown): string {
        if (error instanceof Error) {
            if (error.message.includes('NetworkError') || error.message.includes('fetch')) {
                return this.DEFAULT_MESSAGES.networkError;
            }
        }
        return this.DEFAULT_MESSAGES.error;
    }
}

// Debounce utility with abort support
class DebounceValidator {
    private timer: number | null = null;

    execute(callback: () => Promise<void>, delay: number, onCancel?: () => void): void {
        if (this.timer) {
            clearTimeout(this.timer);
            onCancel?.();
        }

        this.timer = window.setTimeout(async () => {
            try {
                await callback();
            } catch (error) {
                console.error('Debounced validation error:', error);
            } finally {
                this.timer = null;
            }
        }, delay);
    }

    cancel(): void {
        if (this.timer) {
            clearTimeout(this.timer);
            this.timer = null;
        }
    }
}

const debounceValidator = new DebounceValidator();

// Event handlers
const handleInput = (value: any): void => {
    emit('update:modelValue', value);

    // Reset validation state when user types
    if (validationState.value.hasBlurred) {
        validationState.value.isValid = null;
        validationState.value.message = '';
    }
};

const handleFocus = (event: FocusEvent): void => {
    emit('focus', event);
};

const handleBlur = async (event: FocusEvent): Promise<void> => {
    validationState.value.hasBlurred = true;
    emit('blur', event);

    // Trigger validation on blur
    if (props.uniqueValidation && props.modelValue) {
        await validateUniqueness(props.modelValue);
    }
};

// Validation logic
const validateUniqueness = async (value: any): Promise<void> => {
    if (!props.uniqueValidation || !value) {
        return;
    }

    // Cancel previous validation
    if (abortController.value) {
        abortController.value.abort();
    }
    debounceValidator.cancel();

    const config = props.uniqueValidation;
    const debounceTime = config.debounceTime || 500;

    validationState.value.isValidating = true;
    validationState.value.isValid = null;
    validationState.value.message = '';

    debounceValidator.execute(
        async () => {
            abortController.value = new AbortController();

            try {
                const result = await UniqueValidationStrategy.validate(value, config, abortController.value.signal);

                validationState.value.isValid = result.isValid;
                validationState.value.message = result.message;

                // Emit validation change event
                emit('validation-change', result.isValid, result.message);

                // Focus back to input if validation fails
                if (!result.isValid && inputRef.value) {
                    await nextTick();
                    inputRef.value.focus();
                }
            } catch (error) {
                if (error instanceof Error && error.name !== 'AbortError') {
                    validationState.value.isValid = false;
                    validationState.value.message = 'Error al validar el valor';
                    emit('validation-change', false, 'Error al validar el valor');
                }
            } finally {
                validationState.value.isValidating = false;
                abortController.value = null;
            }
        },
        debounceTime,
        () => {
            if (abortController.value) {
                abortController.value.abort();
                abortController.value = null;
            }
        }
    );
};

// Watch for external model value changes
watch(
    () => props.modelValue,
    (newValue, oldValue) => {
        if (newValue !== oldValue && validationState.value.hasBlurred) {
            // Reset validation when value changes externally
            validationState.value.isValid = null;
            validationState.value.message = '';
        }
    }
);

// Cleanup on unmount
onUnmounted(() => {
    if (abortController.value) {
        abortController.value.abort();
    }
    debounceValidator.cancel();
});

// Dynamic component selection
const InputComponent = computed(() => {
    return props.type === 'number' ? InputNumber : InputText;
});

const componentProps = computed(() => {
    const baseProps = {
        ref: inputRef,
        id: props.id,
        name: props.name,
        modelValue: props.modelValue,
        placeholder: props.placeholder,
        disabled: props.disabled || validationState.value.isValidating,
        class: inputClasses.value,
        'onUpdate:modelValue': handleInput,
        onFocus: handleFocus,
        onBlur: handleBlur
    };

    if (props.type === 'number') {
        return {
            ...baseProps,
            showButtons: false,
            useGrouping: false
        };
    }

    return {
        ...baseProps,
        type: props.type
    };
});
</script>

<template>
    <div class="unique-validation-input">
        <div class="relative">
            <!-- Input Component -->
            <component :is="InputComponent" v-bind="componentProps" :aria-describedby="displayMessage ? `${id || name}-error` : undefined" :aria-invalid="hasValidationError" />

            <!-- Validation Status Icons -->
            <div v-if="shouldShowValidationIcon || validationState.isValidating" class="absolute flex items-center transform -translate-y-1/2 right-3 top-1/2">
                <!-- Loading Spinner -->
                <ProgressSpinner v-if="validationState.isValidating" style="width: 16px; height: 16px" stroke-width="4" />

                <!-- Success Icon -->
                <i v-else-if="validationState.isValid === true" class="text-green-500 pi pi-check-circle" aria-label="Valor válido" />

                <!-- Error Icon -->
                <i v-else-if="validationState.isValid === false" class="text-red-500 pi pi-times-circle" aria-label="Valor inválido" />
            </div>
        </div>

        <!-- Error Message -->
        <Message v-if="displayMessage" severity="error" :closable="false" class="mt-2" :id="`${id || name}-error`">
            {{ displayMessage }}
        </Message>
    </div>
</template>

<style scoped>
.unique-validation-input {
    @apply w-full;
}

.unique-validation-input :deep(.p-inputtext),
.unique-validation-input :deep(.p-inputnumber-input) {
    @apply pr-10;
    /* Space for validation icons */
}

.unique-validation-input :deep(.p-message) {
    @apply text-sm;
}

.unique-validation-input :deep(.p-message .p-message-wrapper) {
    @apply p-2;
}

/* Animation for validation states */
.unique-validation-input .relative {
    @apply transition-all duration-200 ease-in-out;
}

/* Success state styling */
/*.unique-validation-input :deep(.border-green-500) {
    @apply border-green-500 focus:ring-green-200;
}*/

/* Loading state styling */
.unique-validation-input :deep(.opacity-50) {
    @apply transition-opacity duration-200;
}
</style>
