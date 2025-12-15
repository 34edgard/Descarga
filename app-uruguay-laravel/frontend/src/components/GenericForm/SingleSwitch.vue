<template>
    <div class="p-4 transition-all duration-200 bg-white rounded-lg shadow-sm dark:bg-gray-800" :class="{
        'bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-500': isChecked,
        'bg-gray-50 dark:bg-gray-700/50': !isChecked
    }">
        <!-- Contenedor de título y switch -->
        <div class="flex items-start justify-between p-3 cursor-pointer" :class="{ 'cursor-not-allowed': isDisabled }"
            @click.stop="toggleIfEnabled">
            <!-- Información -->
            <div class="flex flex-col flex-grow min-w-0">
                <!--<h3 class="text-base font-medium text-gray-800 truncate dark:text-white">
                    {{ fieldConfig.label }}
                </h3>-->
                <p v-if="fieldConfig.config.description"
                    class="mt-1 text-sm text-gray-600 dark:text-gray-300 line-clamp-2">
                    {{ fieldConfig.config.description }}
                </p>
            </div>

            <!-- Switch -->
            <div class="ml-4">
                <InputSwitch v-model="isChecked" :disabled="isDisabled" @update:modelValue="handleToggleChange"
                    @click.stop />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, watch, onMounted } from 'vue';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';
import type { FormField } from './types2';

// Props
const props = defineProps<{
    fieldConfig: FormField;
    modelValue?: boolean;
    disabled?: boolean;
}>()

// Emits
const emit = defineEmits<{
    (e: 'update:modelValue', value: boolean): void;
}>()

// Inyección de dependencias
const confirm = useConfirm();
const toast = useToast();

// Estado local
const isChecked = ref<boolean>(false);
const isDisabled = computed(() => props.disabled);

// Inicialización del valor inicial
onMounted(() => {
    isChecked.value = props.modelValue ?? false;
});

// Actualizar estado cuando cambie el modelo externo
watch(() => props.modelValue, (newVal) => {
    if (newVal !== undefined && newVal !== isChecked.value) {
        isChecked.value = newVal;
    }
});

// Manejar cambio de estado del switch
const handleToggleChange = (value: boolean) => {
    if (!props.fieldConfig?.config.confirmOnDeactivate) {
        isChecked.value = value;
        emit('update:modelValue', value);
        showToast(value ? 'Activado' : 'Desactivado');
        return;
    }

    if (!value) {
        // Mostrar confirmación antes de desactivar
        confirm.require({
            message: props.fieldConfig.config.confirmMessage || '¿Está seguro que desea desactivar esta opción?',
            header: 'Confirmar acción',
            icon: 'pi pi-exclamation-triangle',
            acceptClass: 'p-button-danger',
            accept: () => {
                isChecked.value = false;
                emit('update:modelValue', false);
                showToast('Desactivado');
            },
            reject: () => {
                isChecked.value = true;
                showToast('Acción cancelada', 'La opción permanece activa.', 'info');
            }
        });
    } else {
        isChecked.value = true;
        emit('update:modelValue', true);
        showToast('Activado');
    }
};

// Permite hacer clic en todo el contenedor para alternar el switch
const toggleIfEnabled = () => {
    if (!isDisabled.value) {
        handleToggleChange(!isChecked.value);
    }
};

// Mostrar notificaciones
const showToast = (action: string, detail?: string, severity: 'success' | 'warn' | 'error' | 'info' = 'success') => {
    const messages: Record<string, { summary: string; detail: string }> = {
        Activado: {
            summary: 'Opción activada',
            detail: 'La opción ha sido activada correctamente.'
        },
        Desactivado: {
            summary: 'Opción desactivada',
            detail: 'La opción ha sido desactivada correctamente.'
        },
        'Acción cancelada': {
            summary: 'Acción cancelada',
            detail: 'La opción permanece activa.'
        }
    };

    const message = detail ? { summary: action, detail } : messages[action] || { summary: action, detail: '' };

    toast.add({
        severity,
        summary: message.summary,
        detail: message.detail,
        life: 3000
    });
};
</script>

<style scoped>
.active-switch {
    --switch-checked-bg: var(--primary-color) !important;
}

.cursor-pointer {
    cursor: pointer;
}
</style>