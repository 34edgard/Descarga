<script setup lang="ts">
import DynamicShowRelations from '@/components/GenericShow/DynamicShowRelations.vue';
import { getGender, deleteGender } from '@/services/genders/GendersService';
import { getShowFields } from './FieldsForm';

const showFields = getShowFields();
</script>

<template>
    <DynamicShowRelations :fetchItem="getGender" :deleteItem="deleteGender" title="Detalles del género"
        editRoute="edit_genders" listRoute="genders_list" :fields="showFields" />
</template>