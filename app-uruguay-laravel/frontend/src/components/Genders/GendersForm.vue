<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import DynamicForm from '@/components/GenericForm/DynamicForm2.vue';
import type { FormConfig } from '@/components/GenericForm/types2';
import { formTabs, flatFields } from './FieldsForm';
import {
    createGender,
    updateGender,
    getGender,
    getGenderssPaginated
} from '@/services/genders/GendersService';
import useCrudTable from '@/composables/useCrudOperations';

const router = useRouter();
const route = useRoute();

// Estado de carga
const initialized = ref(false);
const loadingGenders = ref(false);
const gendersData = ref<any>(null);

// Modo edición
const isEditMode = computed(() => route.name === 'edit_genders');

const {
    loadItemData,
    handleSubmitForm,
    currentItem,
} = useCrudTable({
    fetchPaginated: getGenderssPaginated,
    getItem: getGender,
    createItem: createGender,
    updateItem: updateGender,
});

const loadGendersData = async () => {
    if (!isEditMode.value) return;
    loadingGenders.value = true;
    try {
        await loadItemData(Number(route.params.id));
        gendersData.value = currentItem.value;
    } catch (error) {
        console.error('Error loading data:', error);
        router.push('/genders');
    } finally {
        loadingGenders.value = false;
    }
};

const handleSubmit = async (formData: any) => {
    try {
        await handleSubmitForm(formData, isEditMode.value ? Number(route.params.id) : undefined);
        router.push({ name: 'genders_list' });
    } catch (error) {
        console.error('Error submitting form:', error);
    }
};

onMounted(async () => {
    await loadGendersData();
    initialized.value = true;
});

const currentFormConfig = computed<FormConfig | null>(() => {
    if (!initialized.value || (isEditMode.value && loadingGenders.value)) return null;

    const baseConfig = {
        colsPerRow: 12,
        submitButtonText: "Guardar",
        cancelButtonText: "Cancelar",
        style: { formClass: 'p-fluid justify-center' },
        title: isEditMode.value ? 'Editar Género' : 'Registro de Género',
        onCancel: () => router.go(-1),
        onSubmit: handleSubmit,
        initialValues: isEditMode.value ? gendersData.value : {},
    };

    return currentView.value === 'tabs'
        ? {
            ...baseConfig,
            description: 'Complete los campos por sección',
            tabs: formTabs()
        }
        : {
            ...baseConfig,
            description: 'Complete todos los campos',
            fields: flatFields()
        };
});

// Control de vista
const currentView = ref<'tabs' | 'flat'>('flat');
const switchView = (view: 'tabs' | 'flat') => {
    currentView.value = view;
};
</script>

<template>
    <div class="card">
        <div v-if="loadingGenders" class="flex justify-center p-8">
            <ProgressSpinner />
        </div>

        <template v-else-if="initialized && currentFormConfig">
            <DynamicForm :config="currentFormConfig" @submit="handleSubmit" />
        </template>
    </div>
</template>