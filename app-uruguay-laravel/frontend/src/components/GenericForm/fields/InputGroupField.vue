<script setup lang="ts">
import type { FormField, ButtonVariant } from '../types'; // Ajusta la ruta

const props = defineProps<{
    fieldConfig: FormField; // Contains inputGroup, placeholder, disabled, readonly, style
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

const getButtonClass = (variant?: ButtonVariant): string[] => {
    const classes = ['p-button'];
    switch (variant) {
        case 'secondary': classes.push('p-button-secondary', 'p-button-outlined'); break;
        case 'success': classes.push('p-button-success'); break;
        case 'info': classes.push('p-button-info'); break;
        case 'warning': classes.push('p-button-warning'); break;
        case 'danger': classes.push('p-button-danger'); break;
        case 'help': classes.push('p-button-help'); break;
    }
    // Puedes añadir lógica de tamaño si es necesario
    return classes;
};
</script>

<template>
    <InputGroup :class="fieldClass">
        <InputGroupAddon v-if="fieldConfig.inputGroup?.before">
            <template v-for="(addon, index) in fieldConfig.inputGroup.before" :key="`before-${index}`">
                <i v-if="addon.type === 'icon'" :class="addon.icon"></i>
                <span v-else-if="addon.type === 'text'">{{ addon.content }}</span>
                <Button v-else-if="addon.type === 'button'" :icon="addon.icon" :label="addon.content"
                    :class="getButtonClass(addon.buttonVariant)" :disabled="disabled || fieldConfig.disabled"
                    @click="addon.onClick" />
            </template>
        </InputGroupAddon>

        <InputText :id="fieldConfig.name" :modelValue="modelValue" :placeholder="fieldConfig.placeholder"
            :disabled="disabled || fieldConfig.disabled" :readonly="fieldConfig.readonly"
            @update:modelValue="handleInput" />

        <InputGroupAddon v-if="fieldConfig.inputGroup?.after">
            <template v-for="(addon, index) in fieldConfig.inputGroup.after" :key="`after-${index}`">
                <i v-if="addon.type === 'icon'" :class="addon.icon"></i>
                <span v-else-if="addon.type === 'text'">{{ addon.content }}</span>
                <Button v-else-if="addon.type === 'button'" :icon="addon.icon" :label="addon.content"
                    :class="getButtonClass(addon.buttonVariant)" :disabled="disabled || fieldConfig.disabled"
                    @click="addon.onClick" />
            </template>
        </InputGroupAddon>
    </InputGroup>
</template>

<style scoped>
/* Estilos :deep() específicos para InputGroup, InputGroupAddon, InputText (dentro del grupo) */
/* Copiar de DynamicField.vue original */
:deep(.p-inputgroup) {
    /* General styles for the group container */
    @apply w-full;
    /* Ensure the group takes full width */
}

:deep(.p-inputtext) {
    /* Styles for the InputText inside the group */
    @apply w-full flex-grow;
    /* Basic styles might be inherited or need specific overrides here */
}

:deep(.p-inputtext:focus) {
    /* Focus styles for the InputText */
    @apply ring-2 ring-blue-200 border-blue-500 outline-none shadow-none;
}

/* p-invalid class is likely applied to the InputGroup itself from DynamicField */
/* You might need specific styles if p-invalid on InputGroup affects inner elements */
:deep(.p-inputgroup.p-invalid .p-inputtext) {
    /* Example */
    @apply border-red-400;
}

/* Styles for addons */
:deep(.p-inputgroup-addon) {
    /* Addon specific styles */
}
</style>