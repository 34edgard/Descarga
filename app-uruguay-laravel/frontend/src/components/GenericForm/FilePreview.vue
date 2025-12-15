<template>
    <div class="file-preview-container">
        <div v-if="isImage" class="image-preview">
            <Button icon="pi pi-times" class="remove-button p-button-rounded p-button-danger p-button-text"
                @click="$emit('remove')" />
            <img :src="url" :alt="name" class="preview-image" />
        </div>
        <div v-else class="file-info">
            <Button icon="pi pi-times" class="remove-button p-button-rounded p-button-danger p-button-text"
                @click="$emit('remove')" />
            <i class="pi pi-file" style="font-size: 2rem"></i>
            <span class="file-name">{{ name }}</span>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps({
    url: {
        type: String,
        required: true
    },
    name: {
        type: String,
        required: true
    },
    type: {
        type: String,
        default: ''
    }
});

const emit = defineEmits(['remove']);

const isImage = computed(() => {
    return props.type.startsWith('image/');
});
</script>

<style scoped>
.file-preview-container {
    position: relative;
    margin-right: 1rem;
    margin-bottom: 1rem;
}

.image-preview {
    position: relative;
    width: 100px;
    height: 100px;
}

.preview-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 4px;
    border: 1px solid #e2e8f0;
}

.file-info {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 0.5rem;
    border: 1px solid #e2e8f0;
    border-radius: 4px;
    width: 100px;
}

.file-name {
    margin-top: 0.5rem;
    font-size: 0.75rem;
    text-align: center;
    word-break: break-word;
    width: 100%;
}

.remove-button {
    position: absolute;
    top: -0.5rem;
    right: -0.5rem;
    width: 1.5rem;
    height: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
}

.dark .preview-image,
.dark .file-info {
    border-color: #4a5568;
}
</style>