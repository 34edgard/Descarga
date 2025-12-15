<template>
    <tbody>
        <tr
            v-for="(row, index) in data"
            :key="getRowKey(row, index)"
            class="transition-colors duration-150 bg-white dark:bg-gray-800 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-700"
            :class="{ 'bg-blue-50 dark:bg-blue-900/20': isRowSelected(row) }"
        >
            <td v-if="selectable" class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                <Checkbox
                    :modelValue="isRowSelected(row)"
                    :binary="true"
                    @change="
                        handleRowSelection(
                            row,
                            ($event as CustomEvent)?.detail !== undefined
                                ? ($event as CustomEvent).detail
                                : $event
                        )
                    "
                    :aria-label="`Seleccionar fila ${index + 1}`"
                />
            </td>

            <td
                v-for="column in columns"
                :key="column.key"
                class="px-4 py-3 border-b border-gray-200 dark:border-gray-700"
                :class="[
                    column.conditionalStyle?.(getCellValue(row, column), row) || '',
                    column.align === 'center' ? 'text-center' : '',
                    column.align === 'right' ? 'text-right' : ''
                ]"
            >
                <template v-if="column.slotName">
                    <slot
                        :name="`cell-${column.slotName}`"
                        :row="row"
                        :value="getCellValue(row, column)"
                    />
                </template>

                <template v-else-if="column.conditionalRender">
                    <component
                        :is="getRenderComponent(column, row)"
                        v-bind="getRenderProps(column, row)"
                        class="inline-flex items-center"
                    >
                        {{ getRenderText(column, row) }}
                    </component>
                </template>

                <template v-else-if="column.html">
                    <div v-html="formatCellValue(getCellValue(row, column))" />
                </template>

                <template v-else-if="column.switch?.enabled">
                    <div class="flex justify-center">
                        <Switch
                            :modelValue="getCellValue(row, column)"
                            @update:modelValue="
                                (value) => $emit('switch-change', row, column.key, value)
                            "
                            :aria-label="`Cambiar estado de ${column.label}`"
                            :disabled="row[`${column.key}Loading`]"
                        />
                        <ProgressSpinner
                            v-if="row[`${column.key}Loading`]"
                            style="width: 20px; height: 20px"
                            class="ml-2"
                        />
                    </div>
                </template>

                <template v-else>
                    {{ formatCellValue(getCellValue(row, column)) }}
                </template>
            </td>

            <td v-if="hasActions" class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-center space-x-2">
                    <slot name="row-actions" :row="row" :index="index" />
                </div>
            </td>
        </tr>

        <tr v-if="!data.length">
            <td
                :colspan="totalColumns"
                class="px-4 py-8 text-center text-gray-500 dark:text-gray-400 dark:bg-gray-800"
            >
                <div class="flex flex-col items-center space-y-2">
                    <i class="text-3xl text-gray-300 pi pi-inbox dark:text-gray-600"></i>
                    <span class="text-sm">No se encontraron resultados</span>
                </div>
            </td>
        </tr>
    </tbody>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import type { Column, TableRow } from './types'
import Switch from 'primevue/inputswitch'
import ProgressSpinner from 'primevue/progressspinner'

interface Props {
    data: TableRow[]
    columns: Column[]
    selectable?: boolean
    hasActions?: boolean
}

const props = withDefaults(defineProps<Props>(), {
    selectable: false,
    hasActions: false
})

const emit = defineEmits<{
    'row-select': [row: TableRow, selected: boolean]
    'switch-change': [row: TableRow, columnKey: string, value: boolean]
}>()

const selectedRows = defineModel<TableRow[]>('selectedRows', { default: [] })

const totalColumns = computed(() => {
    let count = props.columns.length
    if (props.selectable) count++
    if (props.hasActions) count++
    return count
})

const getRowKey = (row: TableRow, index: number): string | number => {
    return row.id || `row-${index}`
}

const getCellValue = (row: TableRow, column: Column): any => {
    return column.key.split('.').reduce((obj, key) => obj?.[key], row)
}

const formatCellValue = (value: any): string => {
    if (value === null || value === undefined) return ''
    if (typeof value === 'boolean') return value ? 'Sí' : 'No'
    if (typeof value === 'object') return JSON.stringify(value)
    return String(value)
}

const isRowSelected = (row: TableRow): boolean => {
    return selectedRows.value.some((selected) => selected.id === row.id)
}

const handleRowSelection = (row: TableRow, selected: boolean): void => {
    if (selected) {
        if (!isRowSelected(row)) {
            selectedRows.value = [...selectedRows.value, row]
        }
    } else {
        selectedRows.value = selectedRows.value.filter((selected) => selected.id !== row.id)
    }

    emit('row-select', row, selected)
}

const getRenderComponent = (column: Column, row: TableRow): string => {
    const render = column.conditionalRender
    if (!render) return 'span'

    const matchingRule = render.conditions.find((rule) =>
        rule.condition(getCellValue(row, column), row)
    )

    if (!matchingRule) return 'span'

    switch (render.type) {
        case 'badge':
            return 'Badge'
        case 'icon':
            return 'i'
        default:
            return 'span'
    }
}

const getRenderProps = (column: Column, row: TableRow): Record<string, any> => {
    const render = column.conditionalRender
    if (!render) return {}

    const cellValue = getCellValue(row, column)
    const matchingRule = render.conditions.find((rule) => rule.condition(cellValue, row))

    if (!matchingRule) return {}

    if (typeof matchingRule.render === 'function') return {}

    switch (render.type) {
        case 'badge':
            const badgeConfig = matchingRule.render
            if ('severity' in badgeConfig) {
                return {
                    severity: badgeConfig.severity,
                    class: `px-2 py-1 text-xs font-medium rounded-full ${badgeConfig.class || ''}`
                }
            }
            return {
                class: `px-2 py-1 text-xs font-medium rounded-full ${badgeConfig.class || ''}`
            }
        case 'icon':
            const iconConfig = matchingRule.render
            if ('icon' in iconConfig) {
                return {
                    class: `${iconConfig.icon} ${iconConfig.class || ''}`,
                    style: iconConfig.severity ? `color: var(--${iconConfig.severity})` : ''
                }
            }
            return {}
        case 'text':
            const textConfig = matchingRule.render
            return {
                class: textConfig.class,
                ...(typeof textConfig === 'object' && 'style' in textConfig && textConfig.style
                    ? { style: textConfig.style }
                    : {})
            }
        default:
            return {}
    }
}

const getRenderText = (column: Column, row: TableRow): string => {
    const render = column.conditionalRender
    if (!render) return formatCellValue(getCellValue(row, column))

    const cellValue = getCellValue(row, column)
    const matchingRule = render.conditions.find((rule) => rule.condition(cellValue, row))

    if (!matchingRule) return formatCellValue(cellValue)

    if (typeof matchingRule.render === 'function') {
        return matchingRule.render(cellValue, row)
    }

    if (render.type === 'badge' || render.type === 'text') {
        return typeof matchingRule.render === 'object' && 'text' in matchingRule.render
            ? matchingRule.render.text
            : formatCellValue(cellValue)
    }

    return formatCellValue(cellValue)
}
</script>

<style scoped>
tr {
    transition: background-color 0.15s ease;
}

tr.bg-blue-50 {
    border-left: 3px solid theme('colors.blue.500');
}

.dark tr.bg-blue-900\/20 {
    border-left: 3px solid theme('colors.blue.400');
}

td:focus-within {
    @apply ring-2 ring-blue-500 ring-opacity-50 ring-inset;
}
</style>
