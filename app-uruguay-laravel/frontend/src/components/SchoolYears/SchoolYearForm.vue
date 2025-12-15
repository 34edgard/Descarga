<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import DynamicForm from '@/components/GenericForm/DynamicForm2.vue';
import type { FormConfig } from '@/components/GenericForm/types2';
import { formTabs, flatFields } from './FieldsForm';
import { createSchoolYear, updateSchoolYear, getSchoolYear, getSchoolYearsPaginated } from '@/services/school_year/SchoolYearService';
import useCrudTable from '@/composables/useCrudOperations';
import { SchoolYear } from '@/interfaces/school_year.interfaces';

const router = useRouter();
const route = useRoute();

// Estado de carga
const initialized = ref(false);
const loadingSchoolYear = ref(false);
const schoolYearData = ref<SchoolYear>(null);

// Modo edición
const isEditMode = computed(() => route.name === 'edit_school_year');

const { loadItemData, handleSubmitForm, currentItem } = useCrudTable({
    fetchPaginated: getSchoolYearsPaginated,
    getItem: getSchoolYear,
    createItem: createSchoolYear,
    updateItem: updateSchoolYear
});

const loadSchoolYearData = async () => {
    if (!isEditMode.value) return;
    loadingSchoolYear.value = true;
    try {
        await loadItemData(Number(route.params.id));
        schoolYearData.value = currentItem.value;
    } catch (error) {
        console.error('Error loading data:', error);
        router.push('/school_year');
    } finally {
        loadingSchoolYear.value = false;
    }
};

const handleSubmit = async (formData: any) => {
    try {
        await handleSubmitForm(formData, isEditMode.value ? Number(route.params.id) : undefined);
        router.push({ name: 'school_year_list' });
    } catch (error) {
        console.error('Error submitting form:', error);
    }
};

onMounted(async () => {
    //await loadOptions();
    await loadSchoolYearData();
    initialized.value = true;
});

const currentFormConfig = computed<FormConfig | null>(() => {
    if (!initialized.value || (isEditMode.value && loadingSchoolYear.value)) return null;

    const baseConfig = {
        colsPerRow: 12,
        submitButtonText: 'Guardar',
        cancelButtonText: 'Cancelar',
        style: { formClass: 'p-fluid' },
        title: isEditMode.value ? 'Editar Periodo' : 'Registro de Periodo',
        onCancel: () => router.go(-1),
        onSubmit: handleSubmit,
        initialValues: isEditMode.value ? schoolYearData.value : {}
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
const currentView = ref<'tabs' | 'flat'>('tabs');
const switchView = (view: 'tabs' | 'flat') => {
    currentView.value = view;
};
</script>

<template>
    <div class="card">
        <div v-if="loadingSchoolYear" class="flex justify-center p-8">
            <ProgressSpinner />
        </div>

        <template v-else-if="initialized && currentFormConfig">
            <DynamicForm :config="currentFormConfig" @submit="handleSubmit" />
        </template>
    </div>
</template>
