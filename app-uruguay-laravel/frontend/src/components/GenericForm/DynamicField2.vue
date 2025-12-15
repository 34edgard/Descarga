<script setup lang="ts">
import { computed, inject, defineAsyncComponent, h, ref, watch } from 'vue';
import type { FormField } from './types2';
import { FORM_VALUES_KEY, THEME_KEY } from '@/utils/injection-keys';
import { ProgressSpinner } from 'primevue';
import UniqueValidationInput from './UniqueValidationInput.vue';


// Carga diferida optimizada de componentes PrimeVue
const PrimeComponents = {
    InputText: defineAsyncComponent(() => import('primevue/inputtext')),
    Textarea: defineAsyncComponent(() => import('primevue/textarea')),
    InputNumber: defineAsyncComponent(() => import('primevue/inputnumber')),
    Calendar: defineAsyncComponent(() => import('primevue/datepicker')),
    RadioButton: defineAsyncComponent(() => import('primevue/radiobutton')),
    InputMask: defineAsyncComponent(() => import('primevue/inputmask')),
    InputSwitch: defineAsyncComponent(() => import('primevue/toggleswitch')),
    MultiSelect: defineAsyncComponent(() => import('primevue/multiselect')),
    ColorPicker: defineAsyncComponent(() => import('primevue/colorpicker')),
    Slider: defineAsyncComponent(() => import('primevue/slider')),
    Rating: defineAsyncComponent(() => import('primevue/rating')),
    Editor: defineAsyncComponent(() => import('primevue/editor')),
    CascadeSelect: defineAsyncComponent(() => import('primevue/cascadeselect')),
    InputGroup: defineAsyncComponent(() => import('primevue/inputgroup')),
    FileUpload: defineAsyncComponent(() => import('primevue/fileupload')),
    FloatLabel: defineAsyncComponent(() => import('primevue/floatlabel')),
    ToggleButton: defineAsyncComponent(() => import('primevue/togglebutton')),
    Checkbox: defineAsyncComponent(() => import('primevue/checkbox')),
    Autocomplete: defineAsyncComponent(() => import('primevue/autocomplete'))
};

const LazySelect = defineAsyncComponent({
    loader: () => import('./LazySelect.vue'),
    loadingComponent: {
        render() {
            return h('div', {
                style: {
                    minHeight: '40px',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center'
                }
            }, [
                h(ProgressSpinner, {
                    style: { width: '20px', height: '20px' }
                })
            ]);
        }
    },
    delay: 100,
    timeout: 3000,
    suspensible: true
});

const props = withDefaults(defineProps<{
    fieldConfig: FormField;
    modelValue: any;
    errorMessage?: string | string[];
    disabled?: boolean;
    loading?: boolean;
}>(), {
    labelType: 'normal',
    disabled: false,
    loading: false
});

const suggestions = ref<any[]>([]);

const emit = defineEmits<{
    (e: 'update:modelValue', value: any): void;
    (e: 'file-upload', event: any): void;
}>();

// Usar las claves únicas para inyección

const { formValues, updateValue } = inject(FORM_VALUES_KEY, {
    formValues: {} as Record<string, any>,
    updateValue: (key: string, value: any) => { }
});

const theme = inject(THEME_KEY, 'light');


// Computed properties
const shouldShowField = computed(() => {
    if (!props.fieldConfig.dependsOn) return true;

    const dependentValue = formValues[props.fieldConfig.dependsOn.field];
    const expectedValue = props.fieldConfig.dependsOn.value;

    return props.fieldConfig.dependsOn.action === 'show'
        ? dependentValue === expectedValue
        : true;
});

const isFieldDisabled = computed(() => {
    if (props.disabled || props.fieldConfig.disabled) return true;

    if (props.fieldConfig.dependsOn?.action === 'enable') {
        const dependentValue = formValues[props.fieldConfig.dependsOn.field];
        return dependentValue !== props.fieldConfig.dependsOn.value;
    }

    return false;
});

const fieldClasses = computed(() => [
    props.fieldConfig.style?.inputClass,
    props.fieldConfig.size === 'small' ? 'p-inputtext-sm' :
        props.fieldConfig.size === 'large' ? 'p-inputtext-lg' : '',
    { 'p-invalid': !!props.errorMessage },
    { 'opacity-50': props.loading }
]);

const firstErrorMessage = computed(() =>
    Array.isArray(props.errorMessage) ? props.errorMessage[0] : props.errorMessage
);

const handleInput = (value: any) => {
    updateValue(props.fieldConfig.name, value);
    emit('update:modelValue', value);
};

const handleFileUpload = (event: any) => {
    emit('file-upload', event);
};


watch(
    () => props.modelValue,
    (val) => {
        if (props.fieldConfig.type === 'autocomplete' && val != null) {
            const optionValue = props.fieldConfig.config?.optionValue || 'value';
            const optionLabel = props.fieldConfig.config?.optionLabel || 'label';
            let obj: any = null;
            if (typeof val === 'object') {
                obj = { ...val };
                obj[optionValue] = obj[optionValue] ?? obj.id ?? obj.value;
                obj[optionLabel] = obj[optionLabel] ?? obj.name ?? obj.label;
            } else {
                let found = null;
                if (Array.isArray(props.fieldConfig.options)) {
                    found = props.fieldConfig.options.find(opt => opt[optionValue] === val);
                }
                if (!found) {
                    found = suggestions.value.find(opt => opt[optionValue] === val);
                }
                if (found) {
                    obj = { ...found };
                } else {
                    obj = { [optionValue]: val, [optionLabel]: String(val) };
                }
            }
            if (obj && !suggestions.value.some(opt => opt[optionValue] === obj[optionValue])) {
                suggestions.value.push({
                    [optionLabel]: obj[optionLabel],
                    [optionValue]: obj[optionValue]
                });
            }
        }
    },
    { immediate: true }
);

const search = async (event: { query?: string } = {}) => {
    const query = event.query ?? '';
    console.log('AutoComplete search triggered. Query:', query);
    if (props.fieldConfig.config?.remoteSearch && typeof props.fieldConfig.config.remoteSearch === 'function') {
        const result = await props.fieldConfig.config.remoteSearch(query);
        console.log('remoteSearch result:', result);
        suggestions.value = result || [];
    } else if (Array.isArray(props.fieldConfig.options)) {
        const q = query.toLowerCase();
        suggestions.value = props.fieldConfig.options.filter((option: any) => {
            const label = option[props.fieldConfig.config?.optionLabel || 'name'] || '';
            return label.toLowerCase().includes(q);
        });
    } else {
        suggestions.value = [];
    }
};

function getSelectedOptionObject(value: any, options: any[], fieldConfig: any) {
    if (value == null) return null;
    const optionValue = fieldConfig.config?.optionValue || 'value';
    const optionLabel = fieldConfig.config?.optionLabel || 'label';
    if (typeof value === 'object') {
        // Si ya tiene label, úsalo tal cual
        if (value[optionLabel]) return value;
        // Si tiene name, mapea a label
        if (value.name) return { ...value, [optionLabel]: value.name };
        // Si tiene id, mapea a value
        if (value.id) return { ...value, [optionValue]: value.id };
        // Si tiene value, úsalo
        if (value[optionValue]) return value;
    }
    // Si es primitivo, busca primero en fieldConfig.options, luego en suggestions
    let found = null;
    if (Array.isArray(fieldConfig.options)) {
        found = fieldConfig.options.find(opt => opt[optionValue] === value);
    }
    if (!found) {
        found = options.find(opt => opt[optionValue] === value);
    }
    if (found) return found;
    return { [optionValue]: value, [optionLabel]: String(value) };
}


// Mapeo de componentes dinámicos
const DynamicComponent = computed(() => {
    if (!shouldShowField.value) return null;

    const componentMap: Record<string, any> = {
        'text': props.fieldConfig.uniqueValidation ? UniqueValidationInput : PrimeComponents.InputText,
        'email': props.fieldConfig.uniqueValidation ? UniqueValidationInput : PrimeComponents.InputText,
        'password': PrimeComponents.InputText,
        'url': PrimeComponents.InputText,
        'textarea': PrimeComponents.Textarea,
        'select': LazySelect,
        'number': props.fieldConfig.uniqueValidation ? UniqueValidationInput : PrimeComponents.InputNumber,
        'currency': PrimeComponents.InputNumber,
        'date': PrimeComponents.Calendar,
        'time': PrimeComponents.Calendar,
        'radio': PrimeComponents.RadioButton,
        'checkbox': PrimeComponents.Checkbox,
        'switch': PrimeComponents.InputSwitch,
        'toggle': PrimeComponents.ToggleButton,
        'multiselect': PrimeComponents.MultiSelect,
        'color': PrimeComponents.ColorPicker,
        'range': PrimeComponents.Slider,
        'rating': PrimeComponents.Rating,
        'richtext': PrimeComponents.Editor,
        'cascade': PrimeComponents.CascadeSelect,
        'inputgroup': PrimeComponents.InputGroup,
        'inputmask': PrimeComponents.InputMask,
        'phone': PrimeComponents.InputMask,
        'file': PrimeComponents.FileUpload,
        'autocomplete': PrimeComponents.Autocomplete,
        'dynamic-list': defineAsyncComponent(() => import('./DynamicListField.vue')),
        'dynamic-list-search': defineAsyncComponent(() => import('./DynamicListFieldWithSearch.vue')),
        'checklist': defineAsyncComponent(() => import('./ChecklistField.vue')),
        'single-switch': defineAsyncComponent(() => import('./SingleSwitch.vue'))
    };

    return componentMap[props.fieldConfig.type || ''] || null;
});

const componentProps = computed(() => {
    const baseProps: any = {
        id: props.fieldConfig.name,
        modelValue: props.modelValue,
        disabled: isFieldDisabled.value || props.loading,
        class: fieldClasses.value,
        placeholder: props.fieldConfig.labelType !== 'float' ? props.fieldConfig.placeholder : undefined,
        'onUpdate:modelValue': handleInput
    };

    // Si el campo tiene validación única, usar las props específicas
    if (props.fieldConfig.uniqueValidation &&
        ['text', 'email', 'number'].includes(props.fieldConfig.type || '')) {
        return {
            ...baseProps,
            type: props.fieldConfig.type,
            uniqueValidation: props.fieldConfig.uniqueValidation,
            errorMessage: props.errorMessage,
            required: props.fieldConfig.required,
            'onValidation-change': (isValid: boolean, message: string) => {
                // Emit validation change event if needed
                console.log('Validation changed:', { isValid, message });
            }
        };
    }

    switch (props.fieldConfig.type) {
        case 'number':
        case 'currency':
            // Solo aplicar estas props si NO es un campo con validación única
            if (!props.fieldConfig.uniqueValidation) {
                return {
                    ...baseProps,
                    mode: props.fieldConfig.type === 'currency' ? 'currency' : 'decimal',
                    currency: props.fieldConfig.config?.currency || 'USD',
                    locale: props.fieldConfig.config?.locale || 'en-US',
                    minFractionDigits: props.fieldConfig.config?.minFractionDigits,
                    maxFractionDigits: props.fieldConfig.config?.maxFractionDigits,
                    min: props.fieldConfig.config?.min,
                    max: props.fieldConfig.config?.max,
                    showButtons: true
                };
            }
            return baseProps;

        case 'select':
            return {
                ...baseProps,
                fieldConfig: props.fieldConfig,
                errorMessage: props.errorMessage,
                modelValue: props.modelValue === undefined ? null : props.modelValue
            };

        case 'date':
            return {
                ...baseProps,
                dateFormat: props.fieldConfig.config?.dateFormat || 'dd/mm/yy',
                showIcon: true,
                modelValue: props.modelValue instanceof Date ? props.modelValue : props.modelValue ? new Date(props.modelValue) : null,
                'onUpdate:modelValue': (val: Date) => {
                    handleInput(val);
                }
            };

        case 'time':
            return {
                ...baseProps,
                timeOnly: true,
                showTime: true,
                hourFormat: props.fieldConfig.config?.hourFormat || '24'
            };

        case 'inputmask':
        case 'phone':
            return {
                ...baseProps,
                mask: props.fieldConfig.config?.mask || (props.fieldConfig.type === 'phone' ? '(999) 999-9999' : ''),
                slotChar: props.fieldConfig.config?.slotChar || '_',
                autoClear: props.fieldConfig.config?.autoClear ?? true
            };

        case 'file':
            return {
                ...baseProps,
                multiple: props.fieldConfig.config?.multiple || false,
                accept: props.fieldConfig.config?.accept || '.pdf,.doc,.docx,.xls,.xlsx,.jpeg,.jpg,.png,.gif,.webp',
                maxFileSize: props.fieldConfig.config?.maxSize || 10240000, // 10MB
                chooseLabel: props.fieldConfig.config?.chooseLabelFileUpload || 'Seleccionar',
                uploadLabel: props.fieldConfig.config?.uploadLabelFileUpload || 'Subir',
                cancelLabel: props.fieldConfig.config?.cancelLabelFileUpload || 'Cancelar',
                mode: 'basic',
                'onSelect': handleFileUpload,
                url: props.modelValue && typeof props.modelValue === 'string' ? props.modelValue : undefined
            };

        case 'dynamic-list':
        case 'checklist':
        case 'single-switch':
        case 'dynamic-list-search':
            return {
                ...baseProps,
                fieldConfig: props.fieldConfig
            };
        case 'autocomplete':
            return {
                ...baseProps,
                suggestions: suggestions,
                optionLabel: props.fieldConfig.config?.optionLabel || 'label',
                optionValue: props.fieldConfig.config?.optionValue || 'value',
                complete: search,
                minLength: "0",
                forceSelection: "false"
            };

        default:
            return baseProps;
    }
});

const shouldRenderSpecialFields = computed(() => {
    return ['checkbox', 'radio', 'switch', 'toggle'].includes(props.fieldConfig.type || '');
});
</script>

<template>
    <div v-if="shouldShowField" class="field-container" :class="[fieldConfig.style?.containerClass, `theme-${theme}`]">
        <!-- Float Label -->
        <template v-if="props.fieldConfig.labelType === 'float'">
            <component :is="PrimeComponents.FloatLabel">
                <component :is="DynamicComponent" v-if="DynamicComponent" v-bind="componentProps" />
                <label :for="fieldConfig.name">{{ fieldConfig.label }}</label>
            </component>
        </template>

        <!-- Normal Label -->
        <template v-else>
            <label v-if="fieldConfig.label" :for="fieldConfig.name" :class="fieldConfig.style?.labelClass">
                {{ fieldConfig.label }}
                <span v-if="fieldConfig.required" class="required-asterisk">*</span>
            </label>

            <component :is="DynamicComponent" v-if="DynamicComponent" v-bind="componentProps" />

            <small v-if="props.errorMessage" :id="`${fieldConfig.name}-error`" class="error-message">
                {{ firstErrorMessage }}
            </small>
            <small v-if="fieldConfig.style?.helpText" :id="`${fieldConfig.name}-help`" class="help-text"
                :class="fieldConfig.style?.helpTextClass">
                {{ fieldConfig.style.helpText }}
            </small>
        </template>
    </div>
</template>

<style scoped>
/* Ocultar placeholder cuando el label está flotando */
:deep(.p-float-label input::placeholder) {
    opacity: 0;
    transition: opacity 0.2s;
}

/* Mostrar placeholder solo cuando el input está enfocado y vacío */
:deep(.p-float-label input:focus::placeholder),
:deep(.p-float-label input.p-filled::placeholder) {
    opacity: 1;
}

.field-container {
    @apply mb-4 transition-all duration-200;
}

.field-label {
    @apply block mb-1 text-sm font-medium;
}

.required-asterisk {
    @apply text-red-500 ml-1;
}

.error-message {
    @apply mt-1 text-xs text-red-600 block;
}

.help-text {
    @apply mt-1 text-xs text-gray-500 block;
}

/* Estilos específicos para temas */
.theme-light {
    .field-label {
        @apply text-gray-700;
    }
}

.theme-dark {
    .field-label {
        @apply text-white;
    }

    .help-text {
        @apply text-gray-400;
    }
}

/* Estilos para componentes PrimeVue */
:deep(.p-inputtext),
:deep(.p-dropdown),
:deep(.p-multiselect),
:deep(.p-calendar .p-inputtext),
:deep(.p-inputtextarea),
:deep(.p-inputnumber-input),
:deep(.p-inputmask),
:deep(.p-cascadeselect),
:deep(.p-editor .p-editor-content),
:deep(.p-fileupload-basic .p-button) {
    @apply w-full;
}

:deep(.p-inputtext),
:deep(.p-dropdown:not(.p-togglebutton):not(.p-selectbutton)),
:deep(.p-calendar .p-inputtext),
:deep(.p-inputtextarea),
:deep(.p-inputnumber-input),
:deep(.p-inputmask),
:deep(.p-multiselect.p-focus),
:deep(.p-cascadeselect.p-focus) {
    @apply p-2 border border-gray-300 rounded-md;
}

:deep(.p-inputtext:hover),
:deep(.p-dropdown:not(.p-disabled):hover) {
    @apply border-blue-500;
}

:deep(.p-inputtext:focus),
:deep(.p-dropdown.p-focus),
:deep(.p-calendar .p-inputtext:focus),
:deep(.p-inputtextarea:focus),
:deep(.p-inputnumber-input:focus),
:deep(.p-inputmask:focus),
:deep(.p-multiselect.p-focus),
:deep(.p-cascadeselect.p-focus) {
    @apply ring-2 ring-blue-200 border-blue-500 outline-none shadow-none;
}

:deep(.p-invalid) {
    @apply border-red-400 ring-red-200;
}

:deep(.p-float-label label) {
    @apply text-gray-600;
}

:deep(.p-float-label input:focus~label),
:deep(.p-float-label .p-filled~label) {
    @apply text-blue-500;
}

:deep(.p-float-label .p-invalid~label) {
    @apply text-red-600;
}
</style>
