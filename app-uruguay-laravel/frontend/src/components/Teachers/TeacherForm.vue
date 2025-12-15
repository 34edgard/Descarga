<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import DynamicForm from '@/components/GenericForm/DynamicForm2.vue';
import type { FormConfig } from '@/components/GenericForm/types2';
import { formTabs, flatFields } from './FieldsForm';
import { createTeacher, updateTeacher, getTeacher, getTeachersPaginated } from '@/services/teacher/TeacherService';
import useCrudTable from '@/composables/useCrudOperations';
import { Teacher } from '@/interfaces/teacher.interfaces';

const router = useRouter();
const route = useRoute();

// Estado de carga
const initialized = ref(false);
const loadingTeacher = ref(false);
const teacherData = ref<Teacher>(null);

// Modo edición
const isEditMode = computed(() => route.name === 'edit_teacher');

const { loadItemData, handleSubmitForm, currentItem } = useCrudTable({
    fetchPaginated: getTeachersPaginated,
    getItem: getTeacher,
    createItem: createTeacher,
    updateItem: updateTeacher
});

const loadTeacherData = async () => {
    if (!isEditMode.value) return;
    loadingTeacher.value = true;
    try {
        await loadItemData(Number(route.params.id));
        teacherData.value = currentItem.value;
    } catch (error) {
        console.error('Error loading data:', error);
        router.push('/teacher');
    } finally {
        loadingTeacher.value = false;
    }
};

const handleSubmit = async (formData: any) => {
    try {
        await handleSubmitForm(formData, isEditMode.value ? Number(route.params.id) : undefined);
        router.push({ name: 'teacher_list' });
    } catch (error) {
        console.error('Error submitting form:', error);
    }
};

onMounted(async () => {
    //await loadOptions();
    await loadTeacherData();
    initialized.value = true;
});

const currentFormConfig = computed<FormConfig | null>(() => {
    if (
        !initialized.value ||
        // teachersLoading.value ||
        (isEditMode.value && loadingTeacher.value)
    )
        return null;

    const baseConfig = {
        colsPerRow: 12,
        submitButtonText: 'Guardar',
        cancelButtonText: 'Cancelar',
        style: { formClass: 'p-fluid' },
        title: isEditMode.value ? 'Editar docente' : 'Registro de docente',
        onCancel: () => router.go(-1),
        onSubmit: handleSubmit,
        initialValues: isEditMode.value ? teacherData.value : {}
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
        <div v-if="loadingTeacher" class="flex justify-center p-8">
            <ProgressSpinner />
        </div>

        <template v-else-if="initialized && currentFormConfig">
            <DynamicForm :config="currentFormConfig" @submit="handleSubmit" />
        </template>
    </div>
</template>
