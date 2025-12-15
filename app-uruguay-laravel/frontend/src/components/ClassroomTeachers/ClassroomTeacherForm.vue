<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import DynamicForm from '@/components/GenericForm/DynamicForm.vue';
import type { FormConfig } from '@/components/GenericForm/types';
import { formTabs, flatFields } from './FieldsForm';
import {
    createClassroomTeacher,
    updateClassroomTeacher,
    getClassroomTeacher,
    getClassroomTeachersPaginated
} from '@/services/classroom_teacher/ClassroomTeacherService';
import useCrudTable from '@/composables/useCrudOperations';
// import { useAsyncSelect } from '@/composables/useAsyncSelect';
import { ClassroomTeacher } from '@/interfaces/classroom_teacher.interfaces';
// import { getOtherModule } from '@/services/other_module/OtherModuleService';

const router = useRouter();
const route = useRoute();

// Estado de carga
const initialized = ref(false);
const loadingClassroomTeacher = ref(false);
const classroomTeacherData = ref<ClassroomTeacher>(null);

// Carga para los SELECT (Ejemplo de como consultar areas para un rellenar un selector)
// const { 
//   options: otherModuleOptions,
//  loading: otherModuleLoading,
//  loadOptions
// } = useAsyncSelect(async () => ({ data: await getgetOtherModule() }), (val) => ({ label: val.name, value: val.id }));

// Modo edición
const isEditMode = computed(() => route.name === 'edit_classroom_teacher');

const {
    loadItemData,
    handleSubmitForm,
    currentItem,
    isLoading,
    isSubmitting
} = useCrudTable({
    fetchPaginated: getClassroomTeachersPaginated,
    getItem: getClassroomTeacher,
    createItem: createClassroomTeacher,
    updateItem: updateClassroomTeacher,
});

const loadClassroomTeacherData = async () => {
    if (!isEditMode.value) return;
    loadingClassroomTeacher.value = true;
    try {
        await loadItemData(Number(route.params.id));
        classroomTeacherData.value = currentItem.value;
    } catch (error) {
        console.error('Error loading data:', error);
        router.push('/classroom_teacher');
    } finally {
        loadingClassroomTeacher.value = false;
    }
};

const handleSubmit = async (formData: any) => {
    try {
        await handleSubmitForm(formData, isEditMode.value ? Number(route.params.id) : undefined);
        router.push({ name: 'classroom_teacher_list' });
    } catch (error) {
        console.error('Error submitting form:', error);
    }
};

onMounted(async () => {
    //await loadOptions();
    await loadClassroomTeacherData();
    initialized.value = true;
});

const currentFormConfig = computed<FormConfig | null>(() => {
    if (
        !initialized.value || 
        // classroomTeachersLoading.value ||
        (isEditMode.value && loadingClassroomTeacher.value)
        ) return null;

    const baseConfig = {
        colsPerRow: 12,
        submitButtonText: "Guardar",
        cancelButtonText: "Cancelar",
        style: { formClass: 'p-fluid' },
        title: isEditMode.value ? 'Editar ClassroomTeacher' : 'Registro de ClassroomTeacher',
        onCancel: () => router.go(-1),
        onSubmit: handleSubmit,
        initialValues: isEditMode.value ? classroomTeacherData.value : {},
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
        <div v-if="loadingClassroomTeacher" class="flex justify-center p-8">
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