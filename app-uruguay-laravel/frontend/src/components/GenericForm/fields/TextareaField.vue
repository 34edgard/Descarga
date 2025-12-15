<script setup lang="ts">
import Textarea from 'primevue/textarea';
import type { FormField } from '../types'; // Ajusta la ruta

const props = defineProps<{
    fieldConfig: FormField; // Contains placeholder, config (for rows), disabled, readonly, style
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
</script>

<template>
    <Textarea :id="fieldConfig.name" :modelValue="modelValue" :placeholder="fieldConfig.placeholder"
        :disabled="disabled || fieldConfig.disabled" :readonly="fieldConfig.readonly" :class="fieldClass"
        :rows="fieldConfig.config?.rows || 3" @update:modelValue="handleInput" autoResize />
</template>

<style scoped>
/* Estilos :deep() específicos para Textarea */
/* Copiar de DynamicField.vue original */

:deep(.p-inputtextarea) {
    @apply w-full flex-grow;
    @apply p-2 border border-gray-300 rounded-md;
}

:deep(.p-inputtextarea:hover) {
    @apply border-blue-500;
}

:deep(.p-inputtextarea:focus) {
    @apply ring-2 ring-blue-200 border-blue-500 outline-none shadow-none;
}

:deep(.p-inputtextarea.p-invalid) {
    @apply border-red-400;
}
</style>