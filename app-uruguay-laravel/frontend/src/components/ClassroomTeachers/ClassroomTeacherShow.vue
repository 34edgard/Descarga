<script setup lang="ts">
import DynamicShowRelations from '@/components/GenericShow/DynamicShowRelations.vue';
import { getClassroomTeacher, deleteClassroomTeacher } from '@/services/classroom_teacher/ClassroomTeacherService';
import { getShowFields } from './FieldsForm';

const showFields = getShowFields();
</script>

<template>
    <DynamicShowRelations
        :fetchItem="getClassroomTeacher"
        :deleteItem="deleteClassroomTeacher"
        title="Detalles del classroom_teacher"
        editRoute="edit_classroom_teacher"
        listRoute="classroom_teacher_list"
        :fields="showFields"
    />
</template>