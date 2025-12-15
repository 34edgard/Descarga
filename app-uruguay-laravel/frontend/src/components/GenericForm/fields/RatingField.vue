<script setup lang="ts">
import Rating from 'primevue/rating';
import type { FormField } from '../types'; // Ajusta la ruta

const props = defineProps<{
    fieldConfig: FormField; // Contains config (stars, cancel), disabled, readonly, style
    modelValue: any;
    fieldclass?: string | object | Array<string | object>; // Recibe clases (incluyendo base y p-invalid)
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
    <Rating :id="fieldConfig.name" :modelValue="modelValue" :disabled="disabled || fieldConfig.disabled"
        :readonly="fieldConfig.readonly" :class="fieldclass" :stars="fieldConfig.config?.stars || 5"
        :cancel="fieldConfig.config?.cancel ?? false" @update:modelValue="handleInput" />
</template>

<style scoped>
/* Estilos :deep() específicos para Rating */
/* Copiar de DynamicField.vue original */
:deep(.p-rating) {
    @apply inline-flex;
    /* Keep rating stars in a row */
}

/* Add p-invalid handling if necessary */
/* PrimeVue usually handles p-invalid internally */
</style>