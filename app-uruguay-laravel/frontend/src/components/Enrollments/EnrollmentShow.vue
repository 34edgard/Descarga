<script setup lang="ts">
import DynamicShowRelations from '@/components/GenericShow/DynamicShowRelations.vue';
import { getEnrollment, deleteEnrollment } from '@/services/enrollment/EnrollmentService';
import { getShowFields } from './FieldsForm';

const showFields = getShowFields();
</script>

<template>
    <DynamicShowRelations :fetchItem="getEnrollment" :deleteItem="deleteEnrollment" title="Detalles de Inscripción" editRoute="edit_enrollment" listRoute="enrollment_list" :fields="showFields" />
</template>
