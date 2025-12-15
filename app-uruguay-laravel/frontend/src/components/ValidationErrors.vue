<script setup lang="ts">
import { computed } from 'vue';
import { useErrorStore } from '@/store/errorStore';

const errorStore = useErrorStore();

const props = defineProps<{
    field: string;
    customMessages?: Record<string, string>;
}>();

const errors = computed(() => {
    const rawErrors = errorStore.getErrorsForField(props.field);

    if (props.customMessages) {
        return rawErrors.map((error) => props.customMessages[error] || error);
    }

    return rawErrors;
});
</script>

<template>
    <div v-if="errors.length > 0" class="validation-errors">
        <div v-for="(error, index) in errors" :key="index" class="error-message">
            {{ error }}
        </div>
    </div>
</template>

<style scoped>
.validation-errors {
    margin-top: 0.25rem;
}

.error-message {
    color: var(--red-500);
    font-size: 0.875rem;
    line-height: 1.25;
}
</style>
