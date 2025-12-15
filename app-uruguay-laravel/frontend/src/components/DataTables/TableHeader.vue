<template>
    <thead class="bg-gray-50 dark:bg-gray-600">
        <tr>
            <th
                v-if="selectable"
                class="w-12 px-4 py-3 border-b border-gray-200 dark:border-gray-700"
            >
                <Checkbox
                    :modelValue="allSelected"
                    :binary="true"
                    @change="handleToggleAll"
                    :aria-label="allSelected ? 'Deseleccionar todo' : 'Seleccionar todo'"
                />
            </th>

            <th
                v-for="column in columns"
                :key="column.key"
                class="px-4 py-3 font-medium text-gray-700 border-b border-gray-200 select-none dark:text-white dark:border-gray-700"
                :class="[
                    column.sortable !== false
                        ? 'cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-500'
                        : '',
                    sortState.column === column.key ? 'text-blue-600 dark:text-blue-400' : '',
                    column.align === 'center' ? 'text-center' : '',
                    column.align === 'right' ? 'text-right' : ''
                ]"
                :style="{ width: column.width }"
                @click="handleColumnClick(column)"
                @keydown.enter="handleColumnClick(column)"
                @keydown.space.prevent="handleColumnClick(column)"
                :tabindex="column.sortable !== false ? 0 : -1"
                :aria-sort="getAriaSortValue(column)"
                :role="column.sortable !== false ? 'columnheader button' : 'columnheader'"
            >
                <div class="flex items-center justify-between">
                    <span>{{ column.label }}</span>

                    <div
                        v-if="column.sortable !== false"
                        class="flex flex-col ml-2"
                        :class="{ 'opacity-30': sortState.column !== column.key }"
                    >
                        <i
                            class="text-xs leading-none pi pi-caret-up"
                            :class="{
                                'text-blue-600 dark:text-blue-400':
                                    sortState.column === column.key && sortState.order === 'asc',
                                'text-gray-400':
                                    sortState.column !== column.key || sortState.order !== 'asc'
                            }"
                        />
                        <i
                            class="text-xs leading-none pi pi-caret-down"
                            :class="{
                                'text-blue-600 dark:text-blue-400':
                                    sortState.column === column.key && sortState.order === 'desc',
                                'text-gray-400':
                                    sortState.column !== column.key || sortState.order !== 'desc'
                            }"
                        />
                    </div>
                </div>
            </th>

            <th
                v-if="hasActions"
                class="px-4 py-3 font-medium text-center text-gray-700 border-b border-gray-200 dark:text-white dark:border-gray-700"
            >
                Acciones
            </th>
        </tr>
    </thead>
</template>

<script lang="ts" setup>
import type { Column, SortState } from './types'

interface Props {
    columns: Column[]
    sortState: SortState
    selectable?: boolean
    allSelected?: boolean
    hasActions?: boolean
}

const props = withDefaults(defineProps<Props>(), {
    selectable: false,
    allSelected: false,
    hasActions: false
})

const emit = defineEmits<{
    sort: [columnKey: string]
    'toggle-all': [selected: boolean]
}>()

const handleColumnClick = (column: Column): void => {
    if (column.sortable !== false) {
        emit('sort', column.key)
    }
}

const handleToggleAll = (): void => {
    emit('toggle-all', !props.allSelected)
}

const getAriaSortValue = (column: Column): 'none' | 'ascending' | 'descending' | 'other' => {
    if (column.sortable === false) return 'none'
    if (props.sortState.column !== column.key) return 'none'
    return props.sortState.order === 'asc' ? 'ascending' : 'descending'
}
</script>

<style scoped>
th {
    transition:
        background-color 0.2s ease,
        color 0.2s ease;
}

th[role='columnheader button']:focus {
    @apply outline-none ring-2 ring-blue-500 ring-opacity-50 ring-inset;
}

.pi-caret-up,
.pi-caret-down {
    transition: color 0.2s ease;
}
</style>
