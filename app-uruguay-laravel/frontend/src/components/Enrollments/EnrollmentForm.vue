<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import DynamicForm from '@/components/GenericForm/DynamicForm2.vue';
import type { FormConfig } from '@/components/GenericForm/types2';
import { formTabs, flatFields } from './FieldsForm';
import { createEnrollment, updateEnrollment, getEnrollment, getEnrollmentsPaginated } from '@/services/enrollment/EnrollmentService';
import useCrudTable from '@/composables/useCrudOperations';
import { Enrollment } from '@/interfaces/enrollment.interfaces';

const router = useRouter();
const route = useRoute();

// Estado de carga
const initialized = ref(false);
const loadingEnrollment = ref(false);
const enrollmentData = ref<Enrollment>(null);

// Modo edición
const isEditMode = computed(() => route.name === 'edit_enrollment');

const { loadItemData, handleSubmitForm, currentItem } = useCrudTable({
    fetchPaginated: getEnrollmentsPaginated,
    getItem: getEnrollment,
    createItem: createEnrollment,
    updateItem: updateEnrollment
});

const loadEnrollmentData = async () => {
    if (!isEditMode.value) return;
    loadingEnrollment.value = true;
    try {
        await loadItemData(Number(route.params.id));
        enrollmentData.value = currentItem.value;
    } catch (error) {
        console.error('Error loading data:', error);
        router.push('/enrollment');
    } finally {
        loadingEnrollment.value = false;
    }
};

const handleSubmit = async (formData: any) => {
    try {
        await handleSubmitForm(formData, isEditMode.value ? Number(route.params.id) : undefined);
        router.push({ name: 'enrollment_list' });
    } catch (error) {
        console.error('Error submitting form:', error);
    }
};

onMounted(async () => {
    await loadEnrollmentData();
    initialized.value = true;
});

const currentFormConfig = computed<FormConfig | null>(() => {
    if (!initialized.value || (isEditMode.value && loadingEnrollment.value)) return null;

    const baseConfig = {
        colsPerRow: 12,
        submitButtonText: 'Guardar',
        cancelButtonText: 'Cancelar',
        style: { formClass: 'p-fluid' },
        title: isEditMode.value ? 'Editar Inscripción' : 'Registro de Inscripción',
        onCancel: () => router.go(-1),
        onSubmit: handleSubmit,
        initialValues: isEditMode.value ? enrollmentData.value : {}
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
        <div v-if="loadingEnrollment" class="flex justify-center p-8">
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
