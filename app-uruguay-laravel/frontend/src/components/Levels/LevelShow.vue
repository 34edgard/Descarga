<script setup lang="ts">
import DynamicShowRelations from '@/components/GenericShow/DynamicShowRelations.vue';
import { getLevel, deleteLevel } from '@/services/level/LevelService';
import { getShowFields } from './FieldsForm';

const showFields = getShowFields();
</script>

<template>
    <DynamicShowRelations :fetchItem="getLevel" :deleteItem="deleteLevel" title="Detalles del Nivel" editRoute="edit_level" listRoute="level_list" :fields="showFields" />
</template>
