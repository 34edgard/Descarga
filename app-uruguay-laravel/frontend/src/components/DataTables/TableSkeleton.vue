<template>
    <div class="space-y-4">
        <div class="flex items-center gap-4 pb-2 border-b border-gray-200 dark:border-gray-700">
            <Skeleton v-if="selectable" width="2rem" height="2rem" class="!rounded-md" />
            <Skeleton
                v-for="(col, i) in columns"
                :key="`header-${i}`"
                width="8rem"
                height="1.5rem"
                class="!rounded-md"
            />
            <Skeleton v-if="hasActions" width="6rem" height="1.5rem" class="!rounded-md" />
        </div>

        <div
            v-for="i in 5"
            :key="`row-${i}`"
            class="flex items-center gap-4 py-3 border-b border-gray-200 dark:border-gray-700"
        >
            <Skeleton v-if="selectable" width="2rem" height="2rem" class="!rounded-md" />
            <Skeleton
                v-for="(col, j) in columns"
                :key="`cell-${i}-${j}`"
                width="100%"
                height="1.5rem"
                class="!rounded-md"
            />
            <div v-if="hasActions" class="flex gap-2">
                <Skeleton width="2.5rem" height="2.5rem" shape="circle" />
                <Skeleton width="2.5rem" height="2.5rem" shape="circle" />
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import type { Column } from './types'

defineProps({
    columns: { type: Array as () => Column[], required: true },
    hasActions: { type: Boolean, default: false },
    selectable: { type: Boolean, default: false }
})
</script>
