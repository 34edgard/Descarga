<script setup lang="ts">
import { ref } from 'vue';
import useCrudTable from '@/composables/useCrudOperations';
import { getTeachersPaginated, deleteTeacher, deleteTeacherMultiple } from '@/services/teacher/TeacherService';
import GenericTable from '@/components/DataTables/GenericTable.vue';
import ConfirmationDialog from '@/components/ConfirmationDialog.vue';

const service = {
    fetchPaginated: getTeachersPaginated,
    deleteItem: deleteTeacher,
    deleteMultiple: deleteTeacherMultiple
};

const { selectedItems: selected, itemToDelete: itemToDelete, isDeleting, deleteDialogVisible, deleteMultipleDialogVisible, fetchData, confirmDelete, deleteItem, confirmDeleteMultiple, deleteMultipleItems } = useCrudTable(service);

const columns = ref([
    { key: 'first_name', label: 'Nombres', sortable: true, visible: true },
    { key: 'last_name', label: 'Apellido', sortable: true, visible: true },
    { key: 'birth_date', label: 'Fecha de Nacimiento', sortable: true, visible: true },
    { key: 'gender', label: 'Género', sortable: true, visible: true },
    { key: 'education_level', label: 'Nivel de Educación', sortable: true, visible: true }
]);

const refreshKey = ref(0);

const handleDeleteSelected = (rows: any[]) => {
    if (rows.length > 0) {
        selected.value = rows;
        confirmDeleteMultiple();
    }
};

const handleConfirmDelete = async () => {
    try {
        await deleteItem();
        refreshKey.value++;
    } catch (error) {
        console.error('Error deleting item:', error);
    }
};

const handleConfirmDeleteMultiple = async () => {
    try {
        await deleteMultipleItems();
        refreshKey.value++;
        selected.value = [];
    } catch (error) {
        console.error('Error deleting items:', error);
    }
};
</script>

<template>
    <div>
        <GenericTable :fetchDataFunction="fetchData" :columns="columns" :refreshKey="refreshKey" titleTable="Listado de Docentes" :selectableRows="true" v-model:selectedRows="selected" @delete-selected="handleDeleteSelected">
            <template #actions>
                <Button label="Nuevo" as="router-link" :to="{ name: 'create_teacher' }" icon="pi pi-plus" severity="info" raised />
            </template>

            <template #row-actions="{ row }">
                <Button icon="pi pi-search" as="router-link" title="Ver detalles" :to="{ name: 'show_teacher', params: { id: row.id } }" outlined rounded class="mr-2" raised severity="info" />
                <Button icon="pi pi-pencil" as="router-link" title="Editar" :to="{ name: 'edit_teacher', params: { id: row.id } }" outlined rounded class="mr-2" raised />
                <Button icon="pi pi-trash" outlined rounded severity="danger" title="Eliminar" @click="confirmDelete(row)" raised :loading="isDeleting && itemToDelete?.id === row.id" />
            </template>
        </GenericTable>

        <ConfirmationDialog
            v-model:visible="deleteDialogVisible"
            header="Confirmar Eliminación"
            iconClass="pi pi-exclamation-triangle text-orange-500"
            confirmLabel="Eliminar"
            confirmIcon="pi pi-trash"
            cancelLabel="Cancelar"
            @confirm="handleConfirmDelete"
            @cancel="deleteDialogVisible = false"
            :loading="isDeleting"
        >
            ¿Estás seguro de eliminar este registro?
        </ConfirmationDialog>

        <ConfirmationDialog
            v-model:visible="deleteMultipleDialogVisible"
            header="Confirmar Eliminación Múltiple"
            iconClass="pi pi-exclamation-triangle text-orange-500"
            confirmLabel="Eliminar"
            confirmIcon="pi pi-trash"
            cancelLabel="Cancelar"
            @confirm="handleConfirmDeleteMultiple"
            @cancel="deleteMultipleDialogVisible = false"
            :loading="isDeleting"
        >
            ¿Estás seguro de eliminar los ({{ selected.length }}) registros seleccionados?
            <ul class="mt-2 overflow-y-auto max-h-40">
                <li v-for="item in selected" :key="item.id" class="py-1 border-b border-gray-100">
                    {{ item.name }}
                </li>
            </ul>
        </ConfirmationDialog>
    </div>
</template>
