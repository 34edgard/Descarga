<script setup lang="ts">
import DynamicShowRelations from '@/components/GenericShow/DynamicShowRelations.vue';
import { getStudent, deleteStudent } from '@/services/student/StudentService';
import { getShowFields } from './FieldsForm';

const showFields = getShowFields();
</script>

<template>
    <DynamicShowRelations :fetchItem="getStudent" :deleteItem="deleteStudent" title="Detalles del estudiante" editRoute="edit_student" listRoute="student_list" :fields="showFields" />
</template>
