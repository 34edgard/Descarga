<template>
    <div class="max-w-6xl px-4 py-8 mx-auto">
        <!-- Header -->
        <div class="flex flex-col mb-6 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="mb-4 text-3xl font-bold text-gray-800 dark:text-white sm:mb-0">
                {{ title || 'Detalles del Registro' }}
            </h1>
            <Button label="Volver al listado" icon="pi pi-arrow-left" severity="secondary" @click="navigateBack"
                outlined size="small" />
        </div>
        <!-- No Tabs Layout -->
        <DetailCard :fields="config.fields" :data="itemData" />
        <!-- Acciones -->
        <div class="flex flex-wrap justify-end gap-3 mt-8">
            <Button v-if="editRoute" label="Editar" icon="pi pi-pencil" severity="info" @click="navigateToEdit"
                raised />
            <Button label="Eliminar" icon="pi pi-trash" severity="danger" @click="confirmDelete" :loading="isDeleting"
                raised />
        </div>
    </div>
</template>


<script setup lang="ts">
import { ref, computed, onMounted, PropType } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';
import type { FormConfig } from '@/components/GenericForm/types';

const props = defineProps({
    config: {
        type: Object as () => FormConfig,
    },
    fetchItem: {
        type: Function as PropType<(id: number) => Promise<any>>,
    },
    deleteItem: {
        type: Function as PropType<(id: number) => Promise<any>>,
    },
    title: {
        type: String,
        default: ''
    },
    editRoute: {
        type: String,
        default: ''
    },
    listRoute: {
        type: String,
        default: ''
    }
});

const route = useRoute();
const router = useRouter();
const confirm = useConfirm();
const toast = useToast();

const itemData = ref<any>({});
const isDeleting = ref(false);
const isLoading = ref(false);

const isTabbedLayout = computed(() => props.config?.tabs?.length > 0);

// Cargar datos del item
const loadItemData = async () => {
    try {
        isLoading.value = true;
        const response = await props.fetchItem(Number(route.params.id));
        itemData.value = response.data || response;
    } catch (error) {
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Error al cargar los detalles',
            life: 3000
        });
        navigateBack();
    } finally {
        isLoading.value = false;
    }
};

// Navegación
const navigateBack = () => {
    if (props.listRoute) {
        router.push(props.listRoute);
    } else {
        router.go(-1);
    }
};

const navigateToEdit = () => {
    if (props.editRoute) {
        router.push({ name: props.editRoute, params: { id: route.params.id } });
    }
};

// Eliminación
const confirmDelete = () => {
    confirm.require({
        message: '¿Estás seguro de eliminar este registro?',
        header: 'Confirmar Eliminación',
        icon: 'pi pi-exclamation-triangle',
        acceptClass: 'p-button-danger',
        accept: async () => {
            try {
                isDeleting.value = true;
                if (props.deleteItem) {
                    await props.deleteItem(itemData.value.id);
                }
                toast.add({
                    severity: 'success',
                    summary: 'Éxito',
                    detail: 'Registro eliminado correctamente',
                    life: 3000
                });
                navigateBack();
            } catch (error) {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: 'Error al eliminar el registro',
                    life: 3000
                });
            } finally {
                isDeleting.value = false;
            }
        }
    });
};

onMounted(() => {
    loadItemData();
});
</script>