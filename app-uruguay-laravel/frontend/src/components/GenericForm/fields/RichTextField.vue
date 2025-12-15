<script setup lang="ts">
import Editor from 'primevue/editor';
import type { FormField } from '../types'; // Ajusta la ruta

const props = defineProps<{
    fieldConfig: FormField; // Contains config (heightEditor), disabled, readonly, style
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
    <Editor :id="fieldConfig.name" :modelValue="modelValue" :disabled="disabled || fieldConfig.disabled"
        :readonly="fieldConfig.readonly" :class="fieldClass"
        :editorStyle="{ height: fieldConfig.config?.heightEditor || '200px' }" @update:modelValue="handleInput" />
</template>

<style scoped>
/* Estilos :deep() específicos para Editor (Richtext) */
/* Copiar de DynamicField.vue original */
:deep(.p-editor-container) {
    @apply w-full;
    /* Ensure the container takes full width */
}

:deep(.p-editor .p-editor-content) {
    @apply w-full flex-grow;
    /* Add border, padding etc. if needed */
    @apply p-2 border border-gray-300 rounded-md;
    /* Example */
}

/* Focus and Invalid styles might need targeting specific parts */
/* :deep(.p-editor-container.p-invalid) { ... } */
/* :deep(.p-editor-container.p-focus) { ... } */
</style>