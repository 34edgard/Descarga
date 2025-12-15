<template>
    <div class="p-6 transition-all duration-200 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <!-- Encabezado -->
        <div class="flex flex-col items-start justify-between gap-4 mb-6 sm:flex-row sm:items-center">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                <!--{{ fieldConfig.label }}-->
            </h3>
            <div class="flex gap-2">
                <Button icon="pi pi-check" label="Seleccionar todos" severity="secondary" size="small" outlined @click="handleSelectAll" />
                <Button icon="pi pi-times" label="Deseleccionar todos" severity="secondary" size="small" outlined @click="handleDeselectAll" />
            </div>
        </div>

        <!-- Lista de Opciones -->
        <div v-if="loading" class="flex justify-center py-4">
            <ProgressSpinner />
        </div>
        <div v-else class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div
                v-for="option in options"
                :key="option.id"
                class="p-4 transition-all duration-200 rounded-lg cursor-pointer"
                :class="{
                    'bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-500': selectedOptions[option.id],
                    'bg-gray-50 dark:bg-gray-700/50': !selectedOptions[option.id]
                }"
                @click="toggleOption(option, $event)"
            >
                <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
                    <div class="flex-1">
                        <span class="block font-medium text-gray-800 dark:text-gray-100">
                            {{ option.name }}
                        </span>
                        <span v-if="option.description" class="block mt-1 text-sm text-gray-600 dark:text-gray-300">
                            {{ option.description }}
                        </span>
                    </div>

                    <InputSwitch v-model="selectedOptions[option.id]" @update:modelValue="(val: boolean) => handleToggleChange(option, val)" @click.stop :class="{ 'active-switch': selectedOptions[option.id] }" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, watch, onMounted } from 'vue';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';
import axios from '@/plugins/axios';

interface Option {
    id: number | string;
    name: string;
    description?: string;
}

const props = defineProps<{
    fieldConfig: {
        name: string;
        label: string;
        type?: string;
        config?: {
            endpoint: string;
            relationName: string;
            optionLabel?: string;
            optionValue?: string;
        };
        options?: Option[];
    };
    modelValue?: (number | string)[];
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: (number | string)[]): void;
}>();

const confirm = useConfirm();
const toast = useToast();

// Estado reactivo
const selectedOptions = ref<Record<string | number, boolean>>({});
const options = ref<Option[]>([]);
const loading = ref(false);
const initialized = ref(false);

// Inicializar opciones
const initializeOptions = () => {
    if (!props.fieldConfig) return;

    // Usar opciones estáticas si están definidas
    if (props.fieldConfig.options?.length) {
        options.value = props.fieldConfig.options;
        initializeSelected();
        initialized.value = true;
        return;
    }

    // Cargar desde el endpoint si está configurado
    if (props.fieldConfig.config?.endpoint) {
        fetchOptions();
    } else {
        console.warn('No options or endpoint provided for checklist field');
        initialized.value = true;
    }
};

// Obtener opciones del backend
const fetchOptions = async () => {
    loading.value = true;
    try {
        const response = await axios.get(props.fieldConfig.config?.endpoint || '');
        options.value = response.data.data || response.data;
        initializeSelected();
    } catch (error) {
        console.error('Error fetching options:', error);
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'No se pudieron cargar las opciones',
            life: 5000
        });
    } finally {
        loading.value = false;
        initialized.value = true;
    }
};

// Inicializar estado seleccionado
const initializeSelected = () => {
    const selected = {} as Record<string | number, boolean>;
    const modelValue = props.modelValue || [];

    options.value.forEach((option) => {
        selected[option.id] = modelValue.includes(option.id);
    });

    selectedOptions.value = selected;
};

// Manejar cambio de toggle
const handleToggleChange = (option: Option, value: boolean) => {
    if (value) {
        // Si se está seleccionando, actualizar directamente
        selectedOptions.value[option.id] = true;
        updateSelectedIds();

        toast.add({
            severity: 'success',
            summary: 'Opción seleccionada',
            detail: `"${option.name}" ha sido añadido a la selección`,
            life: 2000
        });
    } else {
        // Si se está deseleccionando, mostrar confirmación
        handleConfirmDeselect(option);
    }
};

// Alternar opción al hacer clic en el texto o descripción
const toggleOption = (option: Option, event: Event) => {
    // Verificar si el clic fue en el InputSwitch o en sus elementos hijos
    const target = event.target as HTMLElement;
    if (target.closest('.p-inputswitch')) {
        return; // No hacer nada si el clic fue en el switch, ya se maneja con @update:modelValue
    }

    const currentValue = selectedOptions.value[option.id];
    const newValue = !currentValue;

    if (newValue) {
        // Si se está seleccionando, actualizar directamente
        selectedOptions.value[option.id] = true;
        updateSelectedIds();

        toast.add({
            severity: 'success',
            summary: 'Opción seleccionada',
            detail: `"${option.name}" ha sido añadido a la selección`,
            life: 2000
        });
    } else {
        // Si se está deseleccionando, mostrar confirmación
        handleConfirmDeselect(option);
    }
};

// Confirmar deselección
const handleConfirmDeselect = (option: Option) => {
    confirm.require({
        message: `¿Está seguro que desea deseleccionar "${option.name}"?`,
        header: 'Confirmar acción',
        icon: 'pi pi-exclamation-triangle',
        acceptClass: 'p-button-danger',
        accept: () => {
            // Confirmar deselección
            selectedOptions.value[option.id] = false;
            updateSelectedIds();

            toast.add({
                severity: 'warn',
                summary: 'Opción deseleccionada',
                detail: `"${option.name}" ha sido removido de la selección`,
                life: 3000
            });
        },
        reject: () => {
            // Mantener seleccionada si cancela
            selectedOptions.value[option.id] = true;
            toast.add({
                severity: 'info',
                summary: 'Acción cancelada',
                detail: `La opción "${option.name}" permanece seleccionada`,
                life: 3000
            });
        }
    });
};
const updateSelectedIds = () => {
    const ids = Object.entries(selectedOptions.value)
        .filter(([_, value]) => value)
        .map(([id]) => {
            // Convertir a número si es posible, mantener como string si no
            const numId = Number(id);
            return isNaN(numId) ? id : numId;
        });
    console.log('ids list checker', ids);
    emit('update:modelValue', ids);
};

// Seleccionar todos
const handleSelectAll = () => {
    Object.keys(selectedOptions.value).forEach((key) => {
        selectedOptions.value[key] = true;
    });
    updateSelectedIds();

    toast.add({
        severity: 'success',
        summary: 'Todas las opciones seleccionadas',
        detail: 'Se han seleccionado todas las opciones disponibles',
        life: 2000
    });
};

// Deseleccionar todos
const handleDeselectAll = () => {
    const selectedCount = Object.values(selectedOptions.value).filter(Boolean).length;

    if (selectedCount === 0) {
        toast.add({
            severity: 'info',
            summary: 'Sin cambios',
            detail: 'No hay opciones seleccionadas para deseleccionar',
            life: 2000
        });
        return;
    }

    confirm.require({
        message: `¿Está seguro que desea deseleccionar todas las opciones? (${selectedCount} opciones seleccionadas)`,
        header: 'Confirmar deselección masiva',
        icon: 'pi pi-exclamation-triangle',
        acceptClass: 'p-button-danger',
        accept: () => {
            Object.keys(selectedOptions.value).forEach((key) => {
                selectedOptions.value[key] = false;
            });
            updateSelectedIds();

            toast.add({
                severity: 'warn',
                summary: 'Todas las opciones deseleccionadas',
                detail: `Se han deseleccionado ${selectedCount} opciones`,
                life: 3000
            });
        },
        reject: () => {
            toast.add({
                severity: 'info',
                summary: 'Acción cancelada',
                detail: 'Las opciones permanecen seleccionadas',
                life: 2000
            });
        }
    });
};

// Watchers
watch(() => props.modelValue, initializeSelected, { immediate: true });

watch(
    () => props.fieldConfig,
    (newConfig) => {
        if (newConfig) {
            initializeOptions();
        }
    },
    { immediate: true, deep: true }
);

// Inicialización
onMounted(() => {
    if (props.fieldConfig) {
        initializeOptions();
    }
});
</script>

<style scoped>
.grid-cols-1.md\:grid-cols-2 {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1rem;
}

.cursor-pointer {
    cursor: pointer;
}

.active-switch {
    --switch-checked-bg: var(--primary-color) !important;
}
</style>
