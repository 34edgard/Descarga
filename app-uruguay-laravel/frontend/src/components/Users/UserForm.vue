<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import DynamicForm from '@/components/GenericForm/DynamicForm2.vue';
import type { FormConfig } from '@/components/GenericForm/types2';
import { formTabs, flatFields } from './FieldsForm';
import { createUser, updateUser, getUser, getUsersPaginated } from '@/services/user/UserService';
import useCrudTable from '@/composables/useCrudOperations';
import { User } from '@/interfaces/user.interfaces';

const router = useRouter();
const route = useRoute();

// Estado de carga
const initialized = ref(false);
const loadingUser = ref(false);
const userData = ref<User>(null);

// Modo edición
const isEditMode = computed(() => route.name === 'edit_user');

const { loadItemData, handleSubmitForm, currentItem } = useCrudTable({
    fetchPaginated: getUsersPaginated,
    getItem: getUser,
    createItem: createUser,
    updateItem: updateUser
});

const loadUserData = async () => {
    if (!isEditMode.value) return;
    loadingUser.value = true;
    try {
        await loadItemData(Number(route.params.id));
        userData.value = currentItem.value;
    } catch (error) {
        console.error('Error loading data:', error);
        router.push('/user');
    } finally {
        loadingUser.value = false;
    }
};

const handleSubmit = async (formData: any) => {
    try {
        await handleSubmitForm(formData, isEditMode.value ? Number(route.params.id) : undefined);
        router.push({ name: 'user_list' });
    } catch (error) {
        console.error('Error submitting form:', error);
    }
};

onMounted(async () => {
    //await loadOptions();
    await loadUserData();
    initialized.value = true;
});

const currentFormConfig = computed<FormConfig | null>(() => {
    if (!initialized.value || (isEditMode.value && loadingUser.value)) return null;

    const baseConfig = {
        colsPerRow: 12,
        submitButtonText: 'Guardar',
        cancelButtonText: 'Cancelar',
        style: { formClass: 'p-fluid' },
        title: isEditMode.value ? 'Editar Usaurio' : 'Registro de Usuario',
        onCancel: () => router.go(-1),
        onSubmit: handleSubmit,
        initialValues: isEditMode.value ? userData.value : {}
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
        <div v-if="loadingUser" class="flex justify-center p-8">
            <ProgressSpinner />
        </div>

        <template v-else-if="initialized && currentFormConfig">
            <DynamicForm :config="currentFormConfig" @submit="handleSubmit" />
        </template>
    </div>
</template>
