<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import DynamicForm from '@/components/GenericForm/DynamicForm2.vue';
import type { FormConfig } from '@/components/GenericForm/types2';
import { formTabs, flatFields } from './FieldsForm';
import { createSection, updateSection, getSection, getSectionsPaginated } from '@/services/section/SectionService';
import useCrudTable from '@/composables/useCrudOperations';
import { Section } from '@/interfaces/section.interfaces';

const router = useRouter();
const route = useRoute();

// Estado de carga
const initialized = ref(false);
const loadingSection = ref(false);
const sectionData = ref<Section>(null);

// Modo edición
const isEditMode = computed(() => route.name === 'edit_section');

const { loadItemData, handleSubmitForm, currentItem } = useCrudTable({
    fetchPaginated: getSectionsPaginated,
    getItem: getSection,
    createItem: createSection,
    updateItem: updateSection
});

const loadSectionData = async () => {
    if (!isEditMode.value) return;
    loadingSection.value = true;
    try {
        await loadItemData(Number(route.params.id));
        sectionData.value = currentItem.value;
    } catch (error) {
        console.error('Error loading data:', error);
        router.push('/section');
    } finally {
        loadingSection.value = false;
    }
};

const handleSubmit = async (formData: any) => {
    try {
        await handleSubmitForm(formData, isEditMode.value ? Number(route.params.id) : undefined);
        router.push({ name: 'section_list' });
    } catch (error) {
        console.error('Error submitting form:', error);
    }
};

onMounted(async () => {
    //await loadOptions();
    await loadSectionData();
    initialized.value = true;
});

const currentFormConfig = computed<FormConfig | null>(() => {
    if (
        !initialized.value ||
        // sectionsLoading.value ||
        (isEditMode.value && loadingSection.value)
    )
        return null;

    const baseConfig = {
        colsPerRow: 12,
        submitButtonText: 'Guardar',
        cancelButtonText: 'Cancelar',
        style: { formClass: 'p-fluid' },
        title: isEditMode.value ? 'Editar Sección' : 'Registro de Sección',
        onCancel: () => router.go(-1),
        onSubmit: handleSubmit,
        initialValues: isEditMode.value ? sectionData.value : {}
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
        <div v-if="loadingSection" class="flex justify-center p-8">
            <ProgressSpinner />
        </div>

        <template v-else-if="initialized && currentFormConfig">
            <DynamicForm :config="currentFormConfig" @submit="handleSubmit" />
        </template>
    </div>
</template>
