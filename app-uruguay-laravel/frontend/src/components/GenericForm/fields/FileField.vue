<script setup lang="ts">
import type { FormField } from '../types'; // Ajusta la ruta

const props = defineProps<{
    fieldConfig: FormField; // Contains config (multiple, accept, maxSize, chooseLabel, uploadLabel, cancelLabel), disabled, readonly, style
    modelValue: any; // modelValue here will likely be FileList or Array<File>
    fieldClass?: string | object | Array<string | object>; // Recibe clases (incluyendo base y p-invalid)
    disabled?: boolean; // Recibe disabled state
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: any): void;
}>();

// FileUpload emits 'select' event with { files: File[] }
const handleSelect = (event: { files: File[] }) => {
    // Emit the array of files directly
    emit('update:modelValue', event.files);
    // Note: You might want to handle upload logic (e.g., to backend) outside this component
    // using FileUpload's auto="false" and @upload/@error events, or via a custom upload handler.
    // This example assumes you collect the files via v-model on submit.
};

// Optional: Clear the model value when files are cleared
const handleClear = () => {
    emit('update:modelValue', null); // Or []
};

// Optional: handle remove event for individual files if multiple
const handleRemove = (event: { file: File, files: File[] }) => {
    emit('update:modelValue', event.files);
};

</script>

<template>
    <FileUpload :id="props.fieldConfig.name" :multiple="props.fieldConfig.config?.multiple || false"
        :accept="props.fieldConfig.config?.accept" :maxFileSize="props.fieldConfig.config?.maxSize || 1000000"
        :chooseLabel="fieldConfig.config?.chooseLabelFileUpload || 'Seleccionar'"
        :uploadLabel="fieldConfig.config?.uploadLabelFileUpload || 'Subir'"
        :cancelLabel="fieldConfig.config?.cancelLabelFileUpload || 'Cancelar'"
        :disabled="disabled || fieldConfig.disabled" :class="fieldClass" mode="basic" @select="handleSelect"
        @clear="handleClear" @remove="handleRemove" :auto="true" url="/api/upload" />
</template>

<style scoped>
/* Estilos :deep() específicos para FileUpload */
/* Copiar de DynamicField.vue original */
:deep(.p-fileupload-basic) {
    @apply w-full;
}

:deep(.p-fileupload-basic .p-button) {
    @apply w-full flex-grow;
    /* Ensure the button itself grows */
}

/* Add p-invalid handling if necessary */
/* PrimeVue handles p-invalid internally */
</style>