import { ref } from 'vue';
import { useToast } from 'primevue/usetoast';
import { ValidationError } from '@/interfaces/errors.interfaces';

interface CrudService {
    fetchPaginated: (filters: any) => Promise<any>;
    getItem?: (id: string | number) => Promise<any>;
    createItem?: (data: any) => Promise<any>;
    updateItem?: (id: string | number, data: any) => Promise<any>;
    deleteItem?: (id: string | number) => Promise<any>;
    deleteMultiple?: (ids: (string | number)[]) => Promise<any>;
}

export default function useCrudTable(service: CrudService) {
    const toast = useToast();
    const data = ref<any[]>([]);
    const selectedItems = ref<any[]>([]);
    const itemToDelete = ref<any | null>(null);
    const currentItem = ref<any | null>(null);
    const isDeleting = ref(false);
    const isSubmitting = ref(false);
    const isLoading = ref(false);
    const deleteDialogVisible = ref(false);
    const deleteMultipleDialogVisible = ref(false);

    // Manejador genérico de errores
    const handleError = (error: unknown, defaultMessage: string) => {
        toast.add({
            severity: 'error',
            summary: error instanceof ValidationError ? 'Error de validación' : 'Error',
            detail: error instanceof Error ? error.message : defaultMessage,
            life: error instanceof ValidationError ? 5000 : 3000
        });

        return error;
    };

    // Mostrar toast de éxito
    const showSuccessToast = (message: string) => {
        toast.add({
            severity: 'success',
            summary: 'Éxito',
            detail: message,
            life: 3000
        });
    };

    const fetchData = async (filters: any) => {
        try {
            return await service.fetchPaginated(filters);
        } catch (error: any) {
            throw handleError(error, 'Error al cargar los datos');
        }
    };

    const loadItemData = async (id: number) => {
        if (!service.getItem) {
            throw new Error('El servicio no implementa getItem');
        }

        isLoading.value = true;
        try {
            const response = await service.getItem(Number(id));
            currentItem.value = response;
            // console.log('Current item:', currentItem.value);
            return currentItem.value;
        } catch (error) {
            throw handleError(error, 'Error al cargar el elemento');
        } finally {
            isLoading.value = false;
        }
    };

    const handleSubmitForm = async (formData: any, id?: string | number) => {
        if (!service.createItem || !service.updateItem) {
            throw new Error('El servicio no implementa createItem o updateItem');
        }

        isSubmitting.value = true;
        try {
            if (id) {
                await service.updateItem(id, formData);
                showSuccessToast('Elemento actualizado correctamente');
            } else {
                await service.createItem(formData);
                showSuccessToast('Elemento creado correctamente');
            }
            return true;
        } catch (error) {
            throw handleError(error, 'Error al guardar el elemento');
        } finally {
            isSubmitting.value = false;
        }
    };

    const confirmDelete = (item: any) => {
        itemToDelete.value = item;
        deleteDialogVisible.value = true;
    };

    const deleteItem = async () => {
        if (itemToDelete.value && service.deleteItem) {
            isDeleting.value = true;
            try {
                await service.deleteItem(itemToDelete.value.id);
                data.value = data.value.filter((item) => item.id !== itemToDelete.value?.id);
                showSuccessToast('Elemento eliminado');
            } catch (error) {
                throw handleError(error, 'Error al eliminar el elemento');
            } finally {
                isDeleting.value = false;
                deleteDialogVisible.value = false;
                itemToDelete.value = null;
            }
        }
    };

    const confirmDeleteMultiple = () => {
        if (selectedItems.value.length > 0) {
            deleteMultipleDialogVisible.value = true;
        } else {
            toast.add({
                severity: 'warn',
                summary: 'Advertencia',
                detail: 'No se han seleccionado elementos para eliminar.',
                life: 3000
            });
        }
    };

    const deleteMultipleItems = async () => {
        if (selectedItems.value.length > 0 && service.deleteMultiple) {
            isDeleting.value = true;
            const idsToDelete = selectedItems.value.map((item) => item.id);
            try {
                await service.deleteMultiple(idsToDelete);
                data.value = data.value.filter((item) => !idsToDelete.includes(item.id));
                showSuccessToast('Elementos eliminados');
                selectedItems.value = [];
            } catch (error) {
                throw handleError(error, 'Error al eliminar los elementos');
            } finally {
                isDeleting.value = false;
                deleteMultipleDialogVisible.value = false;
            }
        }
    };

    return {
        data,
        currentItem,
        selectedItems,
        itemToDelete,
        isDeleting,
        isSubmitting,
        isLoading,
        deleteDialogVisible,
        deleteMultipleDialogVisible,
        fetchData,
        loadItemData,
        handleSubmitForm,
        confirmDelete,
        deleteItem,
        confirmDeleteMultiple,
        deleteMultipleItems,
        handleError,
        showSuccessToast
    };
}
