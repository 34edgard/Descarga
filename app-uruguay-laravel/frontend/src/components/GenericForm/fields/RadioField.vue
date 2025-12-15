<script setup lang="ts">
import RadioButton from 'primevue/radiobutton';
import type { FormField, FieldOption } from '../types'; // Ajusta la ruta

const props = defineProps<{
    fieldConfig: FormField; // Contains options, disabled, readonly, style
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
    <div class="flex flex-wrap gap-4 mt-2">
        <div v-for="option in fieldConfig.options" :key="option.value?.toString()" class="flex items-center">
            <RadioButton :inputId="`${fieldConfig.name}-${option.value}`" :name="fieldConfig.name" :value="option.value"
                :modelValue="modelValue" :disabled="disabled || fieldConfig.disabled || option.disabled"
                :class="fieldclass" {{-- Apply inherited classes (inc. p-invalid if needed, but radiobutton usually
                handles it)--}} @update:modelValue="handleInput" />
            <label :for="`${fieldConfig.name}-${option.value}`" class="ml-2">
                {{ option.label }}
            </label>
        </div>
    </div>
</template>

<style scoped>
/* Estilos :deep() específicos para RadioButton */
/* Copiar de DynamicField.vue original */
:deep(.p-radiobutton) {
    @apply mr-2;
    /* Spacing next to label */
}

/* Add p-invalid handling if necessary */
/* PrimeVue usually handles p-invalid internally for radio groups */
</style>