<script setup lang="ts">
import FloatingConfigurator from '@/components/FloatingConfigurator.vue';
</script>

<template>
    <FloatingConfigurator />
    <div class="bg-surface-50 dark:bg-surface-950 flex items-center justify-center min-h-screen min-w-[100vw] overflow-hidden">
        <div class="flex flex-col items-center justify-center">
            <div style="border-radius: 56px; padding: 0.3rem; background: linear-gradient(180deg, rgba(247, 149, 48, 0.4) 10%, rgba(247, 149, 48, 0) 30%)">
                <div class="flex flex-col items-center w-full px-8 py-20 bg-surface-0 dark:bg-surface-900 sm:px-20" style="border-radius: 53px">
                    <div class="flex flex-col items-center gap-4">
                        <div class="flex items-center justify-center border-2 border-orange-500 rounded-full" style="width: 3.2rem; height: 3.2rem">
                            <i class="text-orange-500 pi pi-fw pi-lock !text-2xl"></i>
                        </div>
                        <h1 class="mb-2 text-4xl font-bold text-surface-900 dark:text-surface-0 lg:text-5xl">Selecciona Una Empresa</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
