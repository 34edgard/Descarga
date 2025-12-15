<template>
    <Dialog v-model:visible="visible" :header="header" :modal="true" :style="{ width: width }">
        <div class="flex items-center gap-4">
            <i :class="iconClass" :style="{ fontSize: iconSize }" />
            <span>
                <slot></slot>
            </span>
        </div>
        <template #footer>
            <Button :label="cancelLabel ?? 'Cancelar'" :icon="cancelIcon ?? 'pi pi-times'" text @click="onCancel"
                raised />
            <Button :label="confirmLabel" :icon="confirmIcon" @click="onConfirm" severity="danger" raised />
            <slot name="footer-buttons"></slot>
        </template>
    </Dialog>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';


interface Props {
    visible: boolean;
    header?: string;
    width?: string;
    iconClass?: string;
    iconSize?: string;
    confirmLabel?: string;
    confirmIcon?: string;
    cancelLabel?: string;
    cancelIcon?: string;
    showCancelButton?: boolean;
}

interface Emits {
    (e: 'update:visible', value: boolean): void;
    (e: 'confirm'): void;
    (e: 'cancel'): void;
}

const props = defineProps<Props>();
const emit = defineEmits<Emits>();

const visible = ref(props.visible);

watch(() => props.visible, (newValue) => {
    visible.value = newValue;
});

const onConfirm = () => {
    emit('confirm');
};

const onCancel = () => {
    emit('update:visible', false);
    emit('cancel');
};
</script>