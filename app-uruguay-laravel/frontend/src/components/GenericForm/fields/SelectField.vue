<script setup lang="ts">
import { computed } from 'vue';
import Dropdown from 'primevue/dropdown';
import MultiSelect from 'primevue/multiselect';
import CascadeSelect from 'primevue/cascadeselect';
import type { FormField } from '../types'; // Ajusta la ruta

const props = defineProps<{
    fieldConfig: FormField; // Contains type, options, placeholder, config (optionLabel, optionValue, optionGroupLabel, optionGroupChildren), disabled, readonly, style
    modelValue: any;
    fieldClass?: string | object | Array<string | object>; // Recibe clases (incluyendo base y p-invalid)
    disabled?: boolean; // Recibe disabled state
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: any): void;
}>();

const handleInput = (value: any) => {
    emit('update:modelValue', value);
};

// Determinar qué componente renderizar
const isMultiSelect = computed(() => props.fieldConfig.type === 'multiselect');
const isCascadeSelect = computed(() => props.fieldConfig.type === 'cascade');
const isDropdown = computed(() => props.fieldConfig.type === 'select');

// Props comunes para MultiSelect y CascadeSelect que no están en Dropdown
const commonSelectProps = computed(() => {
    if (isMultiSelect.value || isCascadeSelect.value) {
        return {
            optionGroupLabel: props.fieldConfig.config?.optionGroupLabel,
            optionGroupChildren: props.fieldConfig.config?.optionGroupChildren,
        };
    }
    return {};
});

</script>

<template>
    <Dropdown v-if="isDropdown" :id="fieldConfig.name" :modelValue="modelValue" :options="fieldConfig.options"
        :disabled="disabled || fieldConfig.disabled" :readonly="fieldConfig.readonly" :class="fieldClass"
        :optionLabel="fieldConfig.config?.optionLabel || 'label'"
        :optionValue="fieldConfig.config?.optionValue || 'value'" :placeholder="fieldConfig.placeholder"
        @update:modelValue="handleInput" />
    <MultiSelect v-else-if="isMultiSelect" :id="fieldConfig.name" :modelValue="modelValue"
        :options="fieldConfig.options" :disabled="disabled || fieldConfig.disabled" :readonly="fieldConfig.readonly"
        :class="fieldClass" :optionLabel="fieldConfig.config?.optionLabel || 'label'"
        :optionValue="fieldConfig.config?.optionValue || 'value'" :placeholder="fieldConfig.placeholder" display="chip"
        v-bind="commonSelectProps" @update:modelValue="handleInput" />
    <CascadeSelect v-else-if="isCascadeSelect" :id="fieldConfig.name" :modelValue="modelValue"
        :options="fieldConfig.options" :disabled="disabled || fieldConfig.disabled" :readonly="fieldConfig.readonly"
        :class="fieldClass" :optionLabel="fieldConfig.config?.optionLabel || 'label'"
        :optionValue="fieldConfig.config?.optionValue || 'value'" :placeholder="fieldConfig.placeholder"
        v-bind="commonSelectProps" @update:modelValue="handleInput" />
</template>

<style scoped>
/* Estilos :deep() específicos para Dropdown, MultiSelect, CascadeSelect */
/* Copiar de DynamicField.vue original */
:deep(.p-dropdown),
:deep(.p-multiselect),
:deep(.p-cascadeselect) {
    @apply w-full flex-grow;
    @apply p-2 border border-gray-300 rounded-md;
}

:deep(.p-dropdown:not(.p-disabled):hover),
:deep(.p-multiselect:not(.p-disabled):hover),
:deep(.p-cascadeselect:not(.p-disabled):hover) {
    @apply border-blue-500;
}

:deep(.p-dropdown.p-focus),
:deep(.p-multiselect.p-focus),
:deep(.p-cascadeselect.p-focus) {
    @apply ring-2 ring-blue-200 border-blue-500 outline-none shadow-none;
}

:deep(.p-dropdown.p-invalid),
:deep(.p-multiselect.p-invalid),
:deep(.p-cascadeselect.p-invalid) {
    @apply border-red-400;
}
</style>