<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import DynamicForm from '@/components/GenericForm/DynamicForm2.vue';
import type { FormConfig } from '@/components/GenericForm/types2';
import { formTabs, flatFields } from './FieldsForm';
import { createStudent, updateStudent, getStudent, getStudentsPaginated } from '@/services/student/StudentService';
import useCrudTable from '@/composables/useCrudOperations';
import { Student } from '@/interfaces/student.interfaces';

const router = useRouter();
const route = useRoute();

// Estado de carga
const initialized = ref(false);
const loadingStudent = ref(false);
const studentData = ref<Student>(null);

// Modo edición
const isEditMode = computed(() => route.name === 'edit_student');

const { loadItemData, handleSubmitForm, currentItem } = useCrudTable({
    fetchPaginated: getStudentsPaginated,
    getItem: getStudent,
    createItem: createStudent,
    updateItem: updateStudent
});

const loadStudentData = async () => {
    if (!isEditMode.value) return;
    loadingStudent.value = true;
    try {
        await loadItemData(Number(route.params.id));
        studentData.value = currentItem.value;
    } catch (error) {
        console.error('Error loading data:', error);
        router.push('/student');
    } finally {
        loadingStudent.value = false;
    }
};

const handleSubmit = async (formData: any) => {
    try {
        await handleSubmitForm(formData, isEditMode.value ? Number(route.params.id) : undefined);
        router.push({ name: 'student_list' });
    } catch (error) {
        console.error('Error submitting form:', error);
    }
};

onMounted(async () => {
    await loadStudentData();
    initialized.value = true;
});

const currentFormConfig = computed<FormConfig | null>(() => {
    if (!initialized.value || (isEditMode.value && loadingStudent.value)) return null;

    const baseConfig = {
        colsPerRow: 12,
        submitButtonText: 'Guardar',
        cancelButtonText: 'Cancelar',
        style: { formClass: 'p-fluid' },
        title: isEditMode.value ? 'Editar estudiante' : 'Registro de estudiante',
        onCancel: () => router.go(-1),
        onSubmit: handleSubmit,
        initialValues: isEditMode.value ? studentData.value : {}
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
        <div v-if="loadingStudent" class="flex justify-center p-8">
            <ProgressSpinner />
        </div>

        <template v-else-if="initialized && currentFormConfig">
            <div class="flex justify-end gap-4">
                <Button label="Ver en Pestañas" @click="() => switchView('tabs')" :severity="currentView === 'tabs' ? 'primary' : 'secondary'" outlined />
                <Button label="Ver Plano" @click="() => switchView('flat')" :severity="currentView === 'flat' ? 'primary' : 'secondary'" outlined />
            </div>

            <DynamicForm :config="currentFormConfig" @submit="handleSubmit" />
        </template>
    </div>
</template>
