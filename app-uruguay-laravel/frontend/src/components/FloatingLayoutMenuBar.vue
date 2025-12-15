<script setup lang="ts">

</script>

<template>
    <div
        class="config-panel absolute top-[3.25rem] right-0 w-64 p-4 bg-surface-0 dark:bg-surface-900 border border-surface rounded-border origin-top shadow-[0px_3px_5px_rgba(0,0,0,0.02),0px_0px_2px_rgba(0,0,0,0.05),0px_1px_4px_rgba(0,0,0,0.08)] hidden">
        <div class="flex gap-2 flex-wrap justify-center">
            <slot />
        </div>
    </div>
</template>


<style lang="css" scoped></style>
