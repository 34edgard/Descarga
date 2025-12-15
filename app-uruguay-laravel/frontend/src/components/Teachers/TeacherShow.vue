<script setup lang="ts">
import DynamicShowRelations from '@/components/GenericShow/DynamicShowRelations.vue';
import { getTeacher, deleteTeacher } from '@/services/teacher/TeacherService';
import { getShowFields } from './FieldsForm';

const showFields = getShowFields();
</script>

<template>
    <DynamicShowRelations :fetchItem="getTeacher" :deleteItem="deleteTeacher" title="Detalles del profesor" editRoute="edit_teacher" listRoute="teacher_list" :fields="showFields" />
</template>
