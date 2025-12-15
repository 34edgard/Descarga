<script setup>
import { computed, ref } from 'vue';
import AppMenuItem from './AppMenuItem.vue';
import { getMenuItems } from '@/services/UseDashboardService';

const model = ref(getMenuItems());
const menuFilter = computed(() => {
    if (!model?.value) return [];
    return model.value.filter((link) => {
        if (!link.middleware) return true;
        if (link.middleware()) {
            return true;
        }
    });
});
</script>

<template>
    <ul class="layout-menu">
        <template v-for="(item, i) in menuFilter" :key="item">
            <app-menu-item v-if="!item.separator" :item="item" :index="i"></app-menu-item>
            <li v-if="item.separator" class="menu-separator"></li>
        </template>
    </ul>
</template>

<style lang="scss" scoped></style>
