<template>
    <div
        class="overflow-hidden bg-white border border-gray-200 rounded-lg shadow-md dark:bg-gray-800 dark:border-gray-700">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-4">
                <div v-for="field in fields" :key="field.name" class="col-span-1">
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">{{ field.label }}</label>
                    <div class="mt-1 text-base font-semibold text-gray-800 dark:text-white break-words min-h-[1.5rem]">
                        <template v-if="field.type === 'select'">
                            {{ getOptionLabel(field, data[field.name]) }}
                        </template>
                        <template v-else-if="field.type === 'textarea'">
                            <span class="whitespace-pre-line">{{ data[field.name] || '-' }}</span>
                        </template>
                        <template v-else>
                            {{ data[field.name] || '-' }}
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import type { FormField } from '@/components/GenericForm/types';

const props = defineProps({
    fields: {
        type: Array as () => FormField[],
        required: true
    },
    data: {
        type: Object as () => Record<string, any>,
        required: true
    }
});

const getOptionLabel = (field: FormField, value: any): string => {
    if (!field.options || !value) return '-';
    const option = field.options.find(opt => opt.value === value);
    return option?.label || value || '-';
};
</script>

<style scoped>
.break-words {
    word-break: break-word;
}

.whitespace-pre-line {
    white-space: pre-line;
}
</style>