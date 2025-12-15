<template>
    <div class="px-4 py-8 mx-auto max-h-max">
        <!-- Header -->
        <div class="flex flex-col mb-6 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="mb-4 text-3xl font-bold text-gray-800 dark:text-white sm:mb-0">
                {{ title || 'Detalles del Registro' }}
            </h1>
            <Button label="Volver al listado" icon="pi pi-arrow-left" severity="secondary" outlined @click="goBack" />
        </div>

        <!-- Card de Contenido -->
        <div
            class="overflow-hidden bg-white border border-gray-200 rounded-lg shadow-md dark:bg-gray-800 dark:border-gray-700">
            <div class="p-6">
                <!-- Cargando -->
                <div v-if="loading" class="flex justify-center py-8">
                    <ProgressSpinner />
                </div>

                <!-- Datos -->
                <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <div v-for="field in displayFields" :key="field.name" class="col-span-1">
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ field.label
                        }}</label>
                        <div
                            class="mt-1 text-base font-semibold text-gray-800 dark:text-gray-200 break-all min-h-[1.5rem]">
                            {{ field.value }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Acciones -->
        <div class="flex flex-wrap justify-end gap-3 mt-8">
            <Button v-if="editRoute" label="Editar" icon="pi pi-pencil" severity="info" @click="navigateToEdit"
                raised />
            <Button v-if="deleteItem" label="Eliminar" icon="pi pi-trash" severity="danger" @click="confirmDelete"
                :loading="isDeleting" raised />
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';

interface Field {
    name: string;
    label: string;
    type?: string;
    cols?: number;
    relation?: string;
    relationField?: string;
    format?: (value: any, item: any) => string;
}

interface Props {
    fetchItem: (id: number) => Promise<any>;
    deleteItem?: (id: number) => Promise<any>;
    title?: string;
    editRoute?: string;
    listRoute?: string;
    fields: Field[];
}

const props = defineProps<Props>();
const route = useRoute();
const router = useRouter();
const confirm = useConfirm();
const toast = useToast();

const itemData = ref<any>({});
const loading = ref(false);
const isDeleting = ref(false);

// Cargar datos del elemento
const loadItemData = async () => {
    try {
        loading.value = true;
        const response = await props.fetchItem(Number(route.params.id));
        itemData.value = response.data || response;
    } catch (error) {
        console.error('Error fetching item:', error);
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'No se pudieron cargar los datos del registro',
            life: 3000
        });
    } finally {
        loading.value = false;
    }
};

// Obtener valor anidado
const getNestedValue = (obj: any, path: string): any => {
    return path.split('.').reduce((acc, part) => acc?.[part], obj);
};

// Mostrar campos formateados
const displayFields = computed(() => {
    return props.fields.map(field => {
        let value = field.relation
            ? getNestedValue(itemData.value, `${field.relation}.${field.relationField || 'name'}`)
            : itemData.value[field.name];

        if (field.format) {
            value = field.format(value, itemData.value);
        }

        return {
            ...field,
            value: translateValue(value)
        };
    });
});

// Traducir booleanos a "Sí" o "No"
const translateValue = (value: any): string => {
    if (typeof value === 'boolean') {
        return value ? 'Sí' : 'No';
    }
    return value ?? 'N/A';
};

// Confirmar eliminación
const confirmDelete = () => {
    if (!props.deleteItem) {
        toast.add({
            severity: 'warn',
            summary: 'Advertencia',
            detail: 'Función de eliminación no disponible',
            life: 3000
        });
        return;
    }

    confirm.require({
        message: '¿Estás seguro de eliminar este registro?',
        header: 'Confirmar Eliminación',
        icon: 'pi pi-exclamation-triangle',
        acceptClass: 'p-button-danger',
        accept: async () => {
            try {
                isDeleting.value = true;
                await props.deleteItem(itemData.value.id);
                toast.add({
                    severity: 'success',
                    summary: 'Éxito',
                    detail: 'Registro eliminado correctamente',
                    life: 3000
                });
                goBack();
            } catch (error) {
                console.error('Error deleting item:', error);
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: 'No se pudo eliminar el registro',
                    life: 3000
                });
            } finally {
                isDeleting.value = false;
            }
        },
        reject: () => {
            toast.add({
                severity: 'info',
                summary: 'Cancelado',
                detail: 'Eliminación cancelada',
                life: 2000
            });
        }
    });
};

// Navegación
const navigateToEdit = () => {
    if (props.editRoute) {
        router.push({ name: props.editRoute, params: { id: route.params.id } });
    }
};

const goBack = () => {
    if (props.listRoute) {
        router.push({ name: props.listRoute });
    } else {
        router.back();
    }
};

onMounted(() => {
    loadItemData();
});
</script>