<template>
    <div class="mb-6 rounded-xl">
        <!-- Header
        <div class="mb-6">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-white">
                {{ fieldConfig.label }}
            </h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Agregue los miembros de la familia del empleado
            </p>
        </div>-->

        <!-- Items List -->
        <div class="space-y-4">
            <div v-for="(item, index) in safeModelValue" :key="index"
                class="p-5 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-600">
                <!-- Item Header -->
                <div class="flex items-center justify-between mb-4">
                    <span class="font-medium text-primary-600 dark:text-primary-400">
                        {{ fieldConfig.label }} #{{ index + 1 }}
                    </span>
                    <button type="button" v-if="safeModelValue.length > minItems" @click="removeItem(index)"
                        class="text-red-500 transition-colors hover:text-red-700 dark:hover:text-red-400"
                        title="Eliminar miembro">
                        <i class="text-sm pi pi-trash"></i>
                    </button>
                </div>

                <!-- Fields Grid - Dinámico según cols con mejor distribución -->
                <div class="grid gap-4" :class="getGridClass">
                    <div v-for="subField in fieldConfig.config?.dynamicList?.fields || []" :key="subField.name"
                        :class="getFieldClass(subField)">
                        <DynamicField :field-config="{
                            ...subField,
                            name: `${fieldConfig.name}.${index}.${subField.name}`,
                            labelType: 'float',
                            options: getFilteredOptions(subField, index)
                        }" :modelValue="item[subField.name]"
                            @update:modelValue="(val) => updateItem(index, subField.name, val)"
                            @blur="validateField(index, subField)" ref="fieldRefs"
                            :class="{ 'field-error': hasFieldError(index, subField.name) }" />

                        <!-- Error message -->
                        <small v-if="hasFieldError(index, subField.name)" class="p-error block mt-1">
                            {{ getFieldError(index, subField.name) }}
                        </small>
                    </div>
                </div>
            </div>

            <!-- Add Button -->
            <div class="flex items-center justify-between">
                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                    <span>{{ safeModelValue.length }} de {{ maxItems === Infinity ? '∞' : maxItems }} elementos</span>
                    <span class="ml-2" v-if="minItems > 0">(mínimo: {{ minItems }})</span>
                </div>
                <button type="button" @click="validateBeforeAdd" :disabled="isMaxItemsReached || isAdding"
                    class="flex items-center mt-2 transition-colors text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300"
                    :class="{ 'opacity-50 cursor-not-allowed': isAdding || isMaxItemsReached }">
                    <i class="mr-2 pi pi-plus"></i>
                    {{ getAddButtonText }}
                    <ProgressSpinner v-if="isAdding" class="w-4 h-4 ml-2" />
                </button>
            </div>

            <!-- Validation errors -->
            <div v-if="validationErrors.length > 0" class="mt-4">
                <Message v-for="(error, index) in validationErrors" :key="index" severity="error" :closable="false"
                    class="mb-2">
                    {{ error }}
                </Message>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, ref, nextTick } from 'vue';
import type { FormField } from './types2';
import DynamicField from './DynamicField2.vue';
import ProgressSpinner from 'primevue/progressspinner';
import Message from 'primevue/message';

interface ListItem {
    [key: string]: any;
}

interface FieldError {
    itemIndex: number;
    fieldName: string;
    message: string;
}

const props = defineProps<{
    fieldConfig: FormField;
    modelValue?: ListItem[];
    formMode?: 'create' | 'edit' | 'view';
    formContext?: Record<string, any>;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: ListItem[]): void;
    (e: 'validation-error', errors: Record<string, string>): void;
}>();

const fieldRefs = ref<any[]>([]);
const isAdding = ref(false);
const fieldErrors = ref<FieldError[]>([]);
const validationErrors = ref<string[]>([]);

const minItems = computed(() => {
    const config = props.fieldConfig.config?.dynamicList;
    const mode = props.formMode || 'create';

    // Priorizar configuración específica por modo
    if (config?.minItemsByMode) {
        return config.minItemsByMode[mode] ?? config.minItems ?? 0;
    }

    return config?.minItems ?? 0;
});

const maxItems = computed(() => {
    const config = props.fieldConfig.config?.dynamicList;
    const mode = props.formMode || 'create';

    // Priorizar configuración específica por modo
    if (config?.maxItemsByMode) {
        const modeMax = config.maxItemsByMode[mode];
        // Si se especifica como null o undefined, significa ilimitado
        if (modeMax === null || modeMax === undefined) {
            return Infinity;
        }
        return modeMax;
    }

    // Configuración general o ilimitado por defecto
    return config?.maxItems ?? Infinity;
});

const safeModelValue = computed<ListItem[]>(() => {
    return Array.isArray(props.modelValue) ? props.modelValue : [{}];
});

const isMaxItemsReached = computed(() => safeModelValue.value.length >= maxItems.value);

// Texto dinámico para el botón de agregar
const getAddButtonText = computed(() => {
    const config = props.fieldConfig.config?.dynamicList;
    const mode = props.formMode || 'create';

    // Texto específico por modo
    if (config?.addButtonTextByMode) {
        const modeText = config.addButtonTextByMode[mode];
        if (modeText) return modeText;
    }

    // Texto general o por defecto
    return config?.addButtonText || 'Agregar elemento';
});

// Clase de grid responsiva
const getGridClass = computed(() => {
    const totalCols = props.fieldConfig.config?.dynamicList?.fields?.reduce((sum, field) => {
        return sum + (field.cols || 12);
    }, 0) || 12;

    if (totalCols <= 12) return 'grid-cols-12';
    if (totalCols <= 24) return 'grid-cols-24';
    return 'grid-cols-12'; // fallback
});

// Clase para cada campo
const getFieldClass = (subField: FormField) => {
    const cols = subField.cols || 12;
    const maxCols = getGridClass.value.includes('24') ? 24 : 12;

    // Asegurar que el campo use el ancho completo disponible si es necesario
    if (cols >= maxCols) {
        return `col-span-full`;
    }

    return `col-span-${Math.min(cols, maxCols)}`;
};

// Error handling
const hasFieldError = (itemIndex: number, fieldName: string) => {
    return fieldErrors.value.some(error =>
        error.itemIndex === itemIndex && error.fieldName === fieldName
    );
};

const getFieldError = (itemIndex: number, fieldName: string) => {
    const error = fieldErrors.value.find(error =>
        error.itemIndex === itemIndex && error.fieldName === fieldName
    );
    return error?.message || '';
};

const addFieldError = (itemIndex: number, fieldName: string, message: string) => {
    // Remover error existente si existe
    fieldErrors.value = fieldErrors.value.filter(error =>
        !(error.itemIndex === itemIndex && error.fieldName === fieldName)
    );
    // Agregar nuevo error
    fieldErrors.value.push({ itemIndex, fieldName, message });
};

const removeFieldError = (itemIndex: number, fieldName: string) => {
    fieldErrors.value = fieldErrors.value.filter(error =>
        !(error.itemIndex === itemIndex && error.fieldName === fieldName)
    );
};

const clearValidationErrors = () => {
    validationErrors.value = [];
    fieldErrors.value = [];
};

// Filtrar opciones para evitar duplicados
const getFilteredOptions = (subField: FormField, currentIndex: number) => {
    // Solo filtrar si el campo tiene la configuración para evitar duplicados
    if (!subField.config?.preventDuplicates || !subField.options) {
        return subField.options;
    }

    // Obtener valores ya seleccionados en otros items
    const selectedValues = safeModelValue.value
        .map((item, index) => index !== currentIndex ? item[subField.name] : null)
        .filter(value => value !== null && value !== undefined && value !== '');

    // Filtrar opciones para excluir valores ya seleccionados
    return subField.options.filter(option => {
        const optionValue = typeof option === 'object' ? option.value : option;
        return !selectedValues.includes(optionValue);
    });
};

// Validación de campo individual
const validateField = async (itemIndex: number, field: FormField): Promise<boolean> => {
    if (!field.name) return true;

    const fieldValue = safeModelValue.value[itemIndex]?.[field.name];
    const fieldName = field.name;

    // Limpiar error previo
    removeFieldError(itemIndex, fieldName);

    // Validar campo requerido
    if (field.required) {
        if (fieldValue === null || fieldValue === undefined || fieldValue === '') {
            addFieldError(itemIndex, fieldName, `${field.label || field.name} es requerido`);
            return false;
        }
    }

    // Validar duplicados si está configurado
    if (field.config?.preventDuplicates && fieldValue) {
        const isDuplicate = safeModelValue.value.some((item, index) =>
            index !== itemIndex && item[fieldName] === fieldValue
        );

        if (isDuplicate) {
            addFieldError(itemIndex, fieldName, `${field.label || field.name} ya está seleccionado en otro elemento`);
            return false;
        }
    }

    // Validar usando el fieldRef si existe
    const fieldRef = fieldRefs.value.find(
        ref => ref?.fieldConfig?.name === `${props.fieldConfig.name}.${itemIndex}.${field.name}`
    );

    if (fieldRef && typeof fieldRef.validateField === 'function') {
        try {
            const isValid = await fieldRef.validateField();
            if (!isValid) {
                addFieldError(itemIndex, fieldName, 'Campo inválido');
                return false;
            }
        } catch (error) {
            console.warn('Error validating field:', error);
        }
    }

    return true;
};

// Validar item completo
const validateCurrentItem = async (itemIndex: number): Promise<boolean> => {
    const fields = props.fieldConfig.config?.dynamicList?.fields || [];
    const results = await Promise.all(
        fields.map(field => validateField(itemIndex, field))
    );
    return results.every(Boolean);
};

// Validar todos los items
const validateAllItems = async (): Promise<boolean> => {
    const results = await Promise.all(
        safeModelValue.value.map((_, index) => validateCurrentItem(index))
    );
    return results.every(Boolean);
};

// Validar antes de agregar nuevo item
const validateBeforeAdd = async () => {
    if (isMaxItemsReached.value) {
        validationErrors.value.push(`Se ha alcanzado el límite máximo de ${maxItems.value} elementos para el modo ${props.formMode || 'create'}`);
        return;
    }

    isAdding.value = true;
    clearValidationErrors();

    try {
        // Validar todos los items existentes
        const isValid = await validateAllItems();

        if (!isValid) {
            validationErrors.value.push('Complete todos los campos requeridos antes de agregar otro elemento');

            // Scroll al primer error
            await nextTick();
            const firstErrorElement = document.querySelector('.field-error');
            if (firstErrorElement) {
                firstErrorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            return;
        }

        addItem();
    } finally {
        isAdding.value = false;
    }
};

// Agregar nuevo item
const addItem = () => {
    const newItem: ListItem = {};
    props.fieldConfig.config?.dynamicList?.fields?.forEach(field => {
        newItem[field.name] = field.defaultValue ?? null;
    });
    emit('update:modelValue', [...safeModelValue.value, newItem]);
    clearValidationErrors();
};

// Remover item
const removeItem = (index: number) => {
    if (safeModelValue.value.length <= minItems.value) return;

    const newItems = safeModelValue.value.filter((_, i) => i !== index);
    emit('update:modelValue', newItems);

    // Limpiar errores del item removido y reindexar
    fieldErrors.value = fieldErrors.value
        .filter(error => error.itemIndex !== index)
        .map(error => ({
            ...error,
            itemIndex: error.itemIndex > index ? error.itemIndex - 1 : error.itemIndex
        }));

    clearValidationErrors();
};

// Actualizar valor de item
const updateItem = (index: number, fieldName: string, value: any) => {
    const newItems = [...safeModelValue.value];
    newItems[index] = { ...newItems[index], [fieldName]: value };
    emit('update:modelValue', newItems);

    // Limpiar error del campo si se actualiza
    removeFieldError(index, fieldName);

    // Re-validar duplicados en todos los items si es necesario
    const field = props.fieldConfig.config?.dynamicList?.fields?.find(f => f.name === fieldName);
    if (field?.config?.preventDuplicates) {
        // Validar todos los items para verificar duplicados
        safeModelValue.value.forEach((_, itemIndex) => {
            if (itemIndex !== index) {
                validateField(itemIndex, field);
            }
        });
    }
};

// Limpiar errores cuando el componente se monta
clearValidationErrors();
</script>

<style scoped>
.field-error :deep(.p-inputtext),
.field-error :deep(.p-dropdown),
.field-error :deep(.p-calendar),
.field-error :deep(.p-inputnumber) {
    border-color: #ef4444;
    box-shadow: 0 0 0 1px #ef4444;
}

.field-error :deep(.p-inputtext:focus),
.field-error :deep(.p-dropdown:focus),
.field-error :deep(.p-calendar:focus),
.field-error :deep(.p-inputnumber:focus) {
    border-color: #ef4444;
    box-shadow: 0 0 0 2px rgba(239, 68, 68, 0.2);
}

/* Grid de 24 columnas para mayor flexibilidad */
.grid-cols-24 {
    display: grid;
    grid-template-columns: repeat(24, minmax(0, 1fr));
}

/* Clases para columnas de 24 */
.col-span-13 {
    grid-column: span 13 / span 13;
}

.col-span-14 {
    grid-column: span 14 / span 14;
}

.col-span-15 {
    grid-column: span 15 / span 15;
}

.col-span-16 {
    grid-column: span 16 / span 16;
}

.col-span-17 {
    grid-column: span 17 / span 17;
}

.col-span-18 {
    grid-column: span 18 / span 18;
}

.col-span-19 {
    grid-column: span 19 / span 19;
}

.col-span-20 {
    grid-column: span 20 / span 20;
}

.col-span-21 {
    grid-column: span 21 / span 21;
}

.col-span-22 {
    grid-column: span 22 / span 22;
}

.col-span-23 {
    grid-column: span 23 / span 23;
}

.col-span-24 {
    grid-column: span 24 / span 24;
}

@media (max-width: 768px) {

    .grid-cols-24,
    .grid-cols-12 {
        grid-template-columns: 1fr;
    }

    .col-span-1,
    .col-span-2,
    .col-span-3,
    .col-span-4,
    .col-span-5,
    .col-span-6,
    .col-span-7,
    .col-span-8,
    .col-span-9,
    .col-span-10,
    .col-span-11,
    .col-span-12,
    .col-span-13,
    .col-span-14,
    .col-span-15,
    .col-span-16,
    .col-span-17,
    .col-span-18,
    .col-span-19,
    .col-span-20,
    .col-span-21,
    .col-span-22,
    .col-span-23,
    .col-span-24,
    .col-span-full {
        grid-column: span 1 / span 1;
    }
}
</style>