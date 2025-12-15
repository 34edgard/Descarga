<script setup lang="ts">
import DynamicShowRelations from '@/components/GenericShow/DynamicShowRelations.vue';
import { getSection, deleteSection } from '@/services/section/SectionService';
import { getShowFields } from './FieldsForm';

const showFields = getShowFields();
</script>

<template>
    <DynamicShowRelations :fetchItem="getSection" :deleteItem="deleteSection" title="Detalles del sección" editRoute="edit_section" listRoute="section_list" :fields="showFields" />
</template>
