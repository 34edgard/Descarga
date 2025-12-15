<script setup lang="ts">
import DynamicShowRelations from '@/components/GenericShow/DynamicShowRelations.vue';
import { getSchoolYear, deleteSchoolYear } from '@/services/school_year/SchoolYearService';
import { getShowFields } from './FieldsForm';

const showFields = getShowFields();
</script>

<template>
    <DynamicShowRelations :fetchItem="getSchoolYear" :deleteItem="deleteSchoolYear" title="Detalles del periodo" editRoute="edit_school_year" listRoute="school_year_list" :fields="showFields" />
</template>
