<script setup lang="ts">
import DynamicShowRelations from '@/components/GenericShow/DynamicShowRelations.vue';
import { getUser, deleteUser } from '@/services/user/UserService';
import { getShowFields } from './FieldsForm';

const showFields = getShowFields();
</script>

<template>
    <DynamicShowRelations :fetchItem="getUser" :deleteItem="deleteUser" title="Detalles del usuario" editRoute="edit_user" listRoute="user_list" :fields="showFields" />
</template>
