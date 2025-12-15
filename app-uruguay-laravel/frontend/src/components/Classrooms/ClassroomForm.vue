<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import DynamicForm from '@/components/GenericForm/DynamicForm2.vue';
import type { FormConfig } from '@/components/GenericForm/types2';
import { formTabs, flatFields } from './FieldsForm';
import { createClassroom, updateClassroom, getClassroom, getClassroomsPaginated } from '@/services/classroom/ClassroomService';
import useCrudTable from '@/composables/useCrudOperations';
import { Classroom } from '@/interfaces/classroom.interfaces';

const router = useRouter();
const route = useRoute();

// Estado de carga
const initialized = ref(false);
const loadingClassroom = ref(false);
const classroomData = ref<Classroom>(null);

// Modo edición
const isEditMode = computed(() => route.name === 'edit_classroom');

const { loadItemData, handleSubmitForm, currentItem } = useCrudTable({
    fetchPaginated: getClassroomsPaginated,
    getItem: getClassroom,
    createItem: createClassroom,
    updateItem: updateClassroom
});

const loadClassroomData = async () => {
    if (!isEditMode.value) return;
    loadingClassroom.value = true;
    try {
        await loadItemData(Number(route.params.id));
        classroomData.value = currentItem.value;
    } catch (error) {
        console.error('Error loading data:', error);
        router.push('/classroom');
    } finally {
        loadingClassroom.value = false;
    }
};

const handleSubmit = async (formData: any) => {
    try {
        await handleSubmitForm(formData, isEditMode.value ? Number(route.params.id) : undefined);
        router.push({ name: 'classroom_list' });
    } catch (error) {
        console.error('Error submitting form:', error);
    }
};

onMounted(async () => {
    //await loadOptions();
    await loadClassroomData();
    initialized.value = true;
});

const currentFormConfig = computed<FormConfig | null>(() => {
    if (!initialized.value || (isEditMode.value && loadingClassroom.value)) return null;

    const baseConfig = {
        colsPerRow: 12,
        submitButtonText: 'Guardar',
        cancelButtonText: 'Cancelar',
        style: { formClass: 'p-fluid' },
        title: isEditMode.value ? 'Editar salón de clase' : 'Registro de salón de clase',
        onCancel: () => router.go(-1),
        onSubmit: handleSubmit,
        initialValues: isEditMode.value ? classroomData.value : {}
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
        <div v-if="loadingClassroom" class="flex justify-center p-8">
            <ProgressSpinner />
        </div>

        <template v-else-if="initialized && currentFormConfig">
            <DynamicForm :config="currentFormConfig" @submit="handleSubmit" />
        </template>
    </div>
</template>
