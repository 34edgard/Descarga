<script setup lang="ts">
import { ref, watch, inject } from 'vue';
import CustomSelect from '@/components/CustomSelect.vue';
import type { FormField, FieldOption } from './types2';
import axios from '@/plugins/axios';
import { FORM_VALUES_KEY, SELECT_OPTIONS_CACHE_KEY } from '@/utils/injection-keys';

const props = defineProps<{
    fieldConfig: FormField;
    modelValue: any;
    disabled?: boolean;
    errorMessage?: string;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: any): void;
}>();

const { formValues } = inject(FORM_VALUES_KEY, {
    formValues: {} as Record<string, any>
});

const selectOptionsCache = inject(SELECT_OPTIONS_CACHE_KEY, {} as Record<string, FieldOption[]>);

// Estado reactivo
const loading = ref(false);
const internalOptions = ref<FieldOption[]>([]);
const error = ref<string | null>(null);

// Cargar opciones desde API
const loadOptions = async () => {
    if (!props.fieldConfig.config?.endpoint) {
        internalOptions.value = props.fieldConfig.options || [];
        return;
    }

    const cacheKey = `${props.fieldConfig.config.endpoint}_${JSON.stringify(props.fieldConfig.config.params)}`;

    // Usar caché si está disponible
    if (selectOptionsCache[cacheKey] && !props.fieldConfig.dependsOn) {
        internalOptions.value = selectOptionsCache[cacheKey];
        return;
    }

    try {
        loading.value = true;
        error.value = null;

        const params = props.fieldConfig.config.params
            ? Object.fromEntries(Object.entries(props.fieldConfig.config.params).map(([key, value]) => [key, typeof value === 'function' ? (value as (values: Record<string, any>) => any)(formValues) : value]))
            : {};

        const response = await axios.get(props.fieldConfig.config.endpoint, { params });
        const apiData = response.data.data || response.data;

        if (!Array.isArray(apiData)) {
            throw new Error('La respuesta del API no es un array válido');
        }

        internalOptions.value = apiData.map((item: any) => ({
            label: item[props.fieldConfig.config?.optionLabel || 'name'],
            value: item[props.fieldConfig.config?.optionValue || 'id']
        }));

        // Almacenar en caché
        if (!props.fieldConfig.dependsOn) {
            selectOptionsCache[cacheKey] = internalOptions.value;
        }
    } catch (err: any) {
        console.error(`Error loading options for ${props.fieldConfig.name}:`, err);
        error.value = `Error al cargar opciones: ${err.message}`;
        internalOptions.value = [];
    } finally {
        loading.value = false;
    }
};

// Manejar agregar nueva opción
const handleAddNewOption = async (newOption: FieldOption) => {
    if (props.fieldConfig.config?.endpoint) {
        try {
            // Aquí iría la lógica para persistir la nueva opción en el backend
            // Por ahora solo la agregamos localmente
            internalOptions.value.push(newOption);

            if (props.fieldConfig.config.multiple) {
                const currentValue = Array.isArray(props.modelValue) ? [...props.modelValue] : [];
                currentValue.push(newOption.value);
                emit('update:modelValue', currentValue);
            } else {
                emit('update:modelValue', newOption.value);
            }
        } catch (error) {
            console.error('Error adding new option:', error);
        }
    } else {
        // Para opciones estáticas
        internalOptions.value.push(newOption);
        if (props.fieldConfig.config?.multiple) {
            const currentValue = Array.isArray(props.modelValue) ? [...props.modelValue] : [];
            currentValue.push(newOption.value);
            emit('update:modelValue', currentValue);
        } else {
            emit('update:modelValue', newOption.value);
        }
    }
};

// Cargar opciones cuando se abre el dropdown
const handleOpenDropdown = () => {
    if (internalOptions.value.length === 0) {
        loadOptions();
    }
};

// Observar cambios en campos dependientes
watch(
    () => (props.fieldConfig.dependsOn ? formValues[props.fieldConfig.dependsOn.field] : null),
    (newVal) => {
        if (props.fieldConfig.dependsOn && newVal) {
            loadOptions();
        } else if (props.fieldConfig.dependsOn) {
            internalOptions.value = [];
            emit('update:modelValue', props.fieldConfig.config?.multiple ? [] : null);
        }
    },
    { immediate: true }
);

// Observar cambios en las opciones del fieldConfig
watch(
    () => props.fieldConfig.options,
    (newOptions) => {
        if (newOptions) {
            internalOptions.value = newOptions;
        }
    },
    { immediate: true }
);
</script>

<template>
    <CustomSelect
        :modelValue="modelValue"
        @update:modelValue="emit('update:modelValue', $event)"
        :options="internalOptions"
        :placeholder="fieldConfig.placeholder"
        :searchable="fieldConfig.config?.searchable || false"
        :multiple="fieldConfig.config?.multiple || false"
        :allowAddNew="fieldConfig.config?.allowAddNew || false"
        :addNewText="fieldConfig.config?.addNewText || 'Agregar nueva opción'"
        :addNewModalTitle="fieldConfig.config?.addNewModalTitle || 'Agregar nueva opción'"
        :addNewPlaceholder="fieldConfig.config?.addNewPlaceholder || 'Ingrese el nombre'"
        :loading="loading"
        :errorMessage="errorMessage"
        :disabled="disabled"
        :itemsPerPage="fieldConfig.config?.itemsPerPage || 10"
        @open="handleOpenDropdown"
        @add-new-option="handleAddNewOption"
    />
</template>
