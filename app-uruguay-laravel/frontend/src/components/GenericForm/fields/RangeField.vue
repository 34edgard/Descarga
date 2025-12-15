<script setup lang="ts">
import Slider from 'primevue/slider';
import type { FormField } from '../types'; // Ajusta la ruta

const props = defineProps<{
    fieldConfig: FormField; // Contains config (min, max, step), disabled, readonly, style
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
    <Slider :id="fieldConfig.name" :modelValue="modelValue" :disabled="disabled || fieldConfig.disabled"
        :readonly="fieldConfig.readonly" :class="fieldclass" :min="fieldConfig.config?.min || 0"
        :max="fieldConfig.config?.max || 100" :step="fieldConfig.config?.step || 1" @update:modelValue="handleInput" />
</template>

<style scoped>
/* Estilos :deep() específicos para Slider */
/* Copiar de DynamicField.vue original */
/* :deep(.p-slider) { ... } */

/* Add p-invalid handling if necessary */
/* PrimeVue usually handles p-invalid internally */
</style>