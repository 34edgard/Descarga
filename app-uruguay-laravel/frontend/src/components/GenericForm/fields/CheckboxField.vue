<script setup lang="ts">
import { computed } from 'vue';
import InputSwitch from 'primevue/inputswitch';
import Checkbox from 'primevue/checkbox';
import ToggleButton from 'primevue/togglebutton';
import type { FormField } from '../types'; // Ajusta la ruta

const props = defineProps<{
    fieldConfig: FormField; // Contains config (asSwitch, binary, onLabel, offLabel), disabled, readonly, style
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
const isInputSwitch = computed(() => props.fieldConfig.type === 'switch' || props.fieldConfig.config?.asSwitch);
const isToggleButton = computed(() => props.fieldConfig.type === 'toggle');
const isCheckbox = computed(() => props.fieldConfig.type === 'checkbox' && !props.fieldConfig.config?.asSwitch);

</script>

<template>
    <div class="flex items-center gap-2">
        <InputSwitch v-if="isInputSwitch" :id="fieldConfig.name" :modelValue="modelValue"
            :disabled="disabled || fieldConfig.disabled" :readonly="fieldConfig.readonly" :class="fieldClass"
            @update:modelValue="handleInput" />
        <ToggleButton v-else-if="isToggleButton" :id="fieldConfig.name" :modelValue="modelValue"
            :disabled="disabled || fieldConfig.disabled" :readonly="fieldConfig.readonly"
            :onLabel="fieldConfig.config?.onLabel || 'Si'" :offLabel="fieldConfig.config?.offLabel || 'No'"
            :class="fieldClass" @update:modelValue="handleInput" />
        <Checkbox v-else-if="isCheckbox" :id="fieldConfig.name" :modelValue="modelValue"
            :binary="fieldConfig.config?.binary ?? true" :disabled="disabled || fieldConfig.disabled"
            :readonly="fieldConfig.readonly" :class="fieldClass" @update:modelValue="handleInput" />
    </div>
</template>

<style scoped>
/* Estilos :deep() específicos para Checkbox, InputSwitch, ToggleButton */
/* Copiar de DynamicField.vue original */
:deep(.p-checkbox),
:deep(.p-radiobutton) {
    /* RadioButton styles are here too in original */
    @apply mr-2;
}

:deep(.p-togglebutton .p-button) {
    @apply w-auto;
    /* ToggleButton button might need specific width */
}

/* Add p-invalid handling if necessary */
/* PrimeVue handles p-invalid internally for these components */
</style>