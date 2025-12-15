<script setup lang="ts">
import DynamicShowRelations from '@/components/GenericShow/DynamicShowRelations.vue';
import { getClassroom, deleteClassroom } from '@/services/classroom/ClassroomService';
import { getShowFields } from './FieldsForm';

const showFields = getShowFields();
</script>

<template>
    <DynamicShowRelations :fetchItem="getClassroom" :deleteItem="deleteClassroom" title="Detalles del salón de clase" editRoute="edit_classroom" listRoute="classroom_list" :fields="showFields" />
</template>
