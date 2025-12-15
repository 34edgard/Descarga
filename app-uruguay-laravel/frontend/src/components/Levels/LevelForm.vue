<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import DynamicForm from '@/components/GenericForm/DynamicForm2.vue';
import type { FormConfig } from '@/components/GenericForm/types2';
import { formTabs, flatFields } from './FieldsForm';
import { createLevel, updateLevel, getLevel, getLevelsPaginated } from '@/services/level/LevelService';
import useCrudTable from '@/composables/useCrudOperations';
import { Level } from '@/interfaces/level.interfaces';

const router = useRouter();
const route = useRoute();

// Estado de carga
const initialized = ref(false);
const loadingLevel = ref(false);
const levelData = ref<Level>(null);

// Modo edición
const isEditMode = computed(() => route.name === 'edit_level');

const { loadItemData, handleSubmitForm, currentItem } = useCrudTable({
    fetchPaginated: getLevelsPaginated,
    getItem: getLevel,
    createItem: createLevel,
    updateItem: updateLevel
});

const loadLevelData = async () => {
    if (!isEditMode.value) return;
    loadingLevel.value = true;
    try {
        await loadItemData(Number(route.params.id));
        levelData.value = currentItem.value;
    } catch (error) {
        console.error('Error loading data:', error);
        router.push('/level');
    } finally {
        loadingLevel.value = false;
    }
};

const handleSubmit = async (formData: any) => {
    try {
        await handleSubmitForm(formData, isEditMode.value ? Number(route.params.id) : undefined);
        router.push({ name: 'level_list' });
    } catch (error) {
        console.error('Error submitting form:', error);
    }
};

onMounted(async () => {
    await loadLevelData();
    initialized.value = true;
});

const currentFormConfig = computed<FormConfig | null>(() => {
    if (!initialized.value || (isEditMode.value && loadingLevel.value)) return null;

    const baseConfig = {
        colsPerRow: 12,
        submitButtonText: 'Guardar',
        cancelButtonText: 'Cancelar',
        style: { formClass: 'p-fluid' },
        title: isEditMode.value ? 'Editar Nivel' : 'Registro de Nivel',
        onCancel: () => router.go(-1),
        onSubmit: handleSubmit,
        initialValues: isEditMode.value ? levelData.value : {}
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
        <div v-if="loadingLevel" class="flex justify-center p-8">
            <ProgressSpinner />
        </div>

        <template v-else-if="initialized && currentFormConfig">
            <DynamicForm :config="currentFormConfig" @submit="handleSubmit" />
        </template>
    </div>
</template>
