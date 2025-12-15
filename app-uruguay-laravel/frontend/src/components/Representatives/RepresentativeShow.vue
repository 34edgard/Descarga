<script setup lang="ts">
import DynamicShowRelations from '@/components/GenericShow/DynamicShowRelations.vue';
import { getRepresentative, deleteRepresentative } from '@/services/representative/RepresentativeService';
import { getShowFields } from './FieldsForm';

const showFields = getShowFields();
</script>

<template>
    <DynamicShowRelations
        :fetchItem="getRepresentative"
        :deleteItem="deleteRepresentative"
        title="Detalles del representative"
        editRoute="edit_representative"
        listRoute="representative_list"
        :fields="showFields"
    />
</template>