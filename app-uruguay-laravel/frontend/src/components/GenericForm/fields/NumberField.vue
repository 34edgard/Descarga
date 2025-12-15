<script setup lang="ts">
import { computed } from 'vue';
import type { FormField } from '../types'; // Ajusta la ruta

const props = defineProps<{
    fieldConfig: FormField; // Contains config (mode, currency, locale, minFractionDigits, maxFractionDigits, min, max, step), disabled, readonly, style
    modelValue: any;
    fieldClass?: string | object | Array<string | object>; // Recibe clases (incluyendo base y p-invalid)
    disabled?: boolean; // Recibe disabled state
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: any): void;
}>();

const handleInput = (value: any) => {
    // InputNumber emits null for empty string, which is good for nullable numbers
    emit('update:modelValue', value);
};

// Determine the mode for InputNumber
const inputMode = computed(() => {
    if (props.fieldConfig.type === 'currency') return 'currency';
    // if (props.fieldConfig.type === 'number') return 'decimal'; // Decimal is default
    return 'decimal';
});

</script>

<template>
    <InputNumber :id="fieldConfig.name" :modelValue="modelValue" :disabled="disabled || fieldConfig.disabled"
        :readonly="fieldConfig.readonly" :class="fieldClass" :mode="inputMode"
        :currency="fieldConfig.config?.currency || 'USD'" :locale="fieldConfig.config?.locale || 'en-US'"
        :minFractionDigits="fieldConfig.config?.minFractionDigits ?? (inputMode === 'currency' ? 2 : 0)"
        :maxFractionDigits="fieldConfig.config?.maxFractionDigits ?? (inputMode === 'currency' ? 2 : 6)"
        :min="fieldConfig.config?.min" :max="fieldConfig.config?.max" :step="fieldConfig.config?.step"
        @update:modelValue="handleInput" showButtons />
</template>

<style scoped>
/* Estilos :deep() específicos para InputNumber */
/* Copiar de DynamicField.vue original */

:deep(.p-inputnumber) {
    @apply w-full;
    /* Ensure the container takes full width */
}

:deep(.p-inputnumber-input) {
    @apply w-full flex-grow;
    /* Ensure the input itself grows */
    @apply p-2 border border-gray-300 rounded-md;
}

:deep(.p-inputnumber-input:hover) {
    @apply border-blue-500;
}

:deep(.p-inputnumber-input:focus) {
    @apply ring-2 ring-blue-200 border-blue-500 outline-none shadow-none;
}

:deep(.p-inputnumber-input.p-invalid) {
    @apply border-red-400;
}

/* If needed, style the buttons */
/* :deep(.p-inputnumber-button) { ... } */
</style>