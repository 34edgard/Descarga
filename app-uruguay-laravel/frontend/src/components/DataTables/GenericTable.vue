<template>
    <div class="card">
        <div class="text-xl text-black-500 dark:text-white">{{ titleTable }}</div>
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <Button v-if="showColumnFilters" :label="columnFiltersVisible ? 'Ocultar filtros' : 'Mostrar filtros'" icon="pi pi-filter" severity="secondary" @click="columnFiltersVisible = !columnFiltersVisible" outlined />
            <slot name="actions" />
        </div>

        <div class="p-4">
            <!-- Filtros por columnas -->
            <div v-if="columnFiltersVisible && filterableColumns.length" class="p-4 mb-4 rounded-lg bg-gray-50 dark:bg-gray-700">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                    <div v-for="column in filterableColumns" :key="`filter-${column.key}`">
                        <label class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ column.label }}
                        </label>
                        <InputText v-if="!column.filterType || column.filterType === 'text'" v-model="columnFilters[column.key]" :placeholder="`Filtrar por ${column.label}`" class="w-full" @input="handleFilterChange" />
                        <Select
                            v-else-if="column.filterType === 'select'"
                            v-model="columnFilters[column.key]"
                            :options="column.filterOptions || []"
                            optionLabel="label"
                            optionValue="value"
                            :placeholder="`Filtrar por ${column.label}`"
                            class="w-full"
                            @change="handleFilterChange"
                            :showClear="true"
                        />
                    </div>
                </div>
                <div class="flex justify-end mt-4 space-x-2">
                    <Button label="Limpiar filtros" severity="secondary" @click="clearColumnFilters" outlined />
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 mb-4">
                <template v-if="selectableRows && selectedRows.length">
                    <Button label="Borrar" icon="pi pi-trash" class="p-button-danger" @click="handleDeleteSelected" :disabled="!selectedRows.length" />
                </template>

                <IconField v-if="searchable && !columnFiltersVisible" class="w-full md:w-64 md:mr-2">
                    <InputIcon class="pi pi-search" />
                    <InputText v-model="searchQuery" :placeholder="searchPlaceholder" class="w-full" @input="handleSearch" />
                    <Button v-if="searchQuery" icon="pi pi-times" class="p-button-text p-button-rounded p-button-sm" @click="clearSearch" :aria-label="'Limpiar búsqueda'" />
                </IconField>
            </div>

            <div class="overflow-auto">
                <TableSkeleton v-if="tableState === TableState.LOADING" :columns="displayedColumns" :has-actions="hasActionsSlot" />

                <div v-else-if="tableState === TableState.ERROR" class="p-4 text-center text-red-500">
                    <i class="mr-2 pi pi-exclamation-triangle"></i>
                    Error al cargar los datos
                </div>

                <template v-else>
                    <table class="w-full text-sm text-left text-gray-500 border-collapse dark:text-white">
                        <TableHeader :columns="displayedColumns" :sort-state="sortState" :selectable="selectableRows" :all-selected="allRowsSelected" @sort="handleSort" @toggle-all="handleToggleSelectAll" :has-actions="hasActionsSlot" />

                        <TableBody :data="paginatedData" :columns="displayedColumns" :selectable="selectableRows" v-model:selected-rows="selectedRows" :has-actions="hasActionsSlot" @row-select="handleRowSelection" @switch-change="handleSwitchChange">
                            <template #row-actions="{ row }">
                                <slot name="row-actions" :row="row" />
                            </template>

                            <template v-for="column in displayedColumns" #[`cell-${column.slotName}`]="{ value, row }">
                                <slot :name="`cell-${column.slotName}`" :value="value" :row="row" />
                            </template>
                        </TableBody>
                    </table>

                    <Paginator
                        v-if="paginated && paginatorState.totalPages > 1"
                        v-model:first="paginatorFirst"
                        :rows="paginatorState.pageSize"
                        :totalRecords="paginatorState.totalItems"
                        @page="handlePageChange"
                        class="mt-4"
                        :rowsPerPageOptions="pageSizeOptions"
                        template="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                        currentPageReportTemplate="Mostrando {first} a {last} de {totalRecords} registros"
                    />
                </template>
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import { ref, computed, watch, useSlots, reactive } from 'vue';
import type { PropType } from 'vue';
import { debounce } from 'lodash-es';
import { useRoute, useRouter } from 'vue-router';
import axios from '@/plugins/axios';
import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';
import TableSkeleton from './TableSkeleton.vue';
import TableHeader from './TableHeader.vue';
import TableBody from './TableBody.vue';
import type { Column, TableRow, FetchDataFunction, PaginatorState, SortState, TableFilters, ApiResponse, ColumnFilter } from './types';
import { TableState } from './types';
import api from '@/plugins/axios';

const props = defineProps({
    fetchDataFunction: {
        type: Function as PropType<FetchDataFunction>,
        required: true,
        validator: (value: any) => typeof value === 'function'
    },
    columns: {
        type: Array as () => Column[],
        required: true,
        validator: (columns: Column[]) => columns.every((col) => col.key && col.label)
    },
    searchable: { type: Boolean, default: true },
    searchPlaceholder: { type: String, default: 'Buscar...' },
    paginated: { type: Boolean, default: true },
    pageSizeOptions: {
        type: Array as () => number[],
        default: () => [10, 25, 50, 100]
    },
    titleTable: { type: String, default: 'Listado' },
    selectableRows: { type: Boolean, default: false },
    refreshKey: { type: Number, default: 0 },
    initialPageSize: { type: Number, default: 10 },
    serverSide: { type: Boolean, default: true },
    showColumnFilters: { type: Boolean, default: false },
    filterableColumns: {
        type: Array as () => ColumnFilter[],
        default: () => []
    },
    baseUrl: { type: String, default: '' }, // URL base para endpoints de status
    statusEndpoint: { type: String, default: 'status' } // Sufijo para endpoint de status
});

const emit = defineEmits<{
    'update:page': [page: number];
    'delete-selected': [rows: TableRow[]];
    'row-select': [row: TableRow, selected: boolean];
    'sort-change': [sortState: SortState];
    search: [query: string];
    'data-loaded': [data: TableRow[], total: number];
    error: [error: Error];
    'filter-change': [filters: Record<string, any>];
    'switch-change': [row: TableRow, columnKey: string, value: boolean, success: boolean];
}>();

const confirm = useConfirm();
const toast = useToast();
const route = useRoute();
const router = useRouter();

// Leer parámetros iniciales de la URL
const initialSearchQuery = route.query.search?.toString() || '';
const initialPage = Math.max(parseInt(route.query.page?.toString() || '1'), 1);
const initialPageSize = Math.max(parseInt(route.query.pageSize?.toString() || props.initialPageSize.toString()), props.pageSizeOptions[0] || 10);
const initialSortColumn = route.query.sortColumn?.toString() || '';
const initialSortOrder = (['asc', 'desc'].includes(route.query.sortOrder?.toString() || '') ? route.query.sortOrder?.toString() : 'asc') as 'asc' | 'desc';

// Inicializar estados con valores de la URL
const searchQuery = ref(initialSearchQuery);
const sortState = reactive<SortState>({
    column: initialSortColumn,
    order: initialSortOrder
});

const paginatorState = reactive<PaginatorState>({
    currentPage: initialPage,
    pageSize: initialPageSize,
    totalItems: 0,
    totalPages: 0
});

// Controla la carga inicial
const isInitialLoad = ref(true);

// Estado para filtros por columna
const columnFiltersVisible = ref(!props.showColumnFilters);
const columnFilters = ref<Record<string, any>>({});

// Inicializar filtros desde la URL
Object.keys(route.query).forEach((key) => {
    if (props.filterableColumns.some((col) => col.key === key)) {
        columnFilters.value[key] = route.query[key];
    }
});

const tableState = ref<TableState>(TableState.LOADING);
const paginatedData = ref<TableRow[]>([]);
const selectedRows = ref<TableRow[]>([]);
const errorMessage = ref<string>('');

const paginatorFirst = computed({
    get: () => (paginatorState.currentPage - 1) * paginatorState.pageSize,
    set: (value: number) => {
        paginatorState.currentPage = Math.floor(value / paginatorState.pageSize) + 1;
    }
});

const displayedColumns = computed(() => props.columns.filter((column) => column.visible !== false));
const hasActionsSlot = computed(() => !!useSlots()['row-actions']);
const allRowsSelected = computed(() => {
    if (!paginatedData.value.length) return false;
    return paginatedData.value.every((row) => selectedRows.value.some((selected) => selected.id === row.id));
});

// Función para actualizar la URL
const updateUrlParams = debounce(() => {
    const queryParams: Record<string, string> = {
        page: paginatorState.currentPage.toString(),
        pageSize: paginatorState.pageSize.toString()
    };

    if (searchQuery.value) {
        queryParams.search = searchQuery.value;
    }

    if (sortState.column) {
        queryParams.sortColumn = sortState.column;
        queryParams.sortOrder = sortState.order;
    }

    // Añadir filtros por columna a la URL
    Object.entries(columnFilters.value).forEach(([key, value]) => {
        if (value !== undefined && value !== '') {
            queryParams[key] = String(value);
        }
    });

    // Actualizar URL sin recargar la página
    router.replace({ query: queryParams });
}, 300);

const fetchData = async (page = initialPage, resetSelection = true): Promise<void> => {
    if (resetSelection) {
        selectedRows.value = [];
    }

    tableState.value = TableState.LOADING;

    try {
        const filters: TableFilters = {
            search: searchQuery.value || undefined,
            sort_by: getSortKey(),
            sort_direction: sortState.column ? sortState.order : undefined,
            page: isInitialLoad.value ? initialPage : page,
            per_page: paginatorState.pageSize,
            ...Object.fromEntries(Object.entries(columnFilters.value).filter(([_, value]) => value !== undefined && value !== ''))
        };

        const response: ApiResponse = await props.fetchDataFunction(filters);

        if (!response?.data || !Array.isArray(response.data)) {
            throw new Error('Formato de respuesta inválido');
        }

        paginatedData.value = response.data;
        paginatorState.currentPage = response.meta?.current_page || (isInitialLoad.value ? initialPage : page);
        paginatorState.totalItems = response.meta?.total || 0;
        paginatorState.pageSize = response.meta?.per_page || paginatorState.pageSize;
        paginatorState.totalPages = response.meta?.last_page || Math.ceil(paginatorState.totalItems / paginatorState.pageSize);

        emit('update:page', paginatorState.currentPage);
        emit('data-loaded', paginatedData.value, paginatorState.totalItems);

        tableState.value = paginatedData.value.length ? TableState.SUCCESS : TableState.EMPTY;
    } catch (error) {
        console.error('Error fetching data:', error);
        tableState.value = TableState.ERROR;
        errorMessage.value = error instanceof Error ? error.message : 'Error desconocido';
        emit('error', error instanceof Error ? error : new Error('Error desconocido'));
        paginatedData.value = [];
        paginatorState.totalItems = 0;
    } finally {
        isInitialLoad.value = false;
    }
};

const getSortKey = (): string | undefined => {
    if (!sortState.column) return undefined;
    const column = props.columns.find((c) => c.key === sortState.column);
    return column?.relation?.sortKey || sortState.column;
};

const handleSort = async (columnKey: string): Promise<void> => {
    const column = props.columns.find((c) => c.key === columnKey);
    if (!column?.sortable) return;

    if (sortState.column === columnKey) {
        sortState.order = sortState.order === 'asc' ? 'desc' : 'asc';
    } else {
        sortState.column = columnKey;
        sortState.order = 'asc';
    }

    emit('sort-change', { ...sortState });
    await fetchData(1, false);
};

const handlePageChange = async (event: { page: number; rows: number }): Promise<void> => {
    if (paginatorState.pageSize !== event.rows) {
        paginatorState.pageSize = event.rows;
        await fetchData(1, false);
    } else {
        await fetchData(event.page + 1, false);
    }
};

const handleSearch = debounce(async () => {
    emit('search', searchQuery.value);
    await fetchData(1, false);
}, 300);

const clearSearch = async (): Promise<void> => {
    searchQuery.value = '';
    await fetchData(1, false);
};

const handleDeleteSelected = (): void => {
    if (selectedRows.value.length > 0) {
        emit('delete-selected', [...selectedRows.value]);
    }
};

const handleToggleSelectAll = (shouldSelect: boolean): void => {
    if (shouldSelect) {
        const newSelections = paginatedData.value.filter((row) => !selectedRows.value.some((selected) => selected.id === row.id));
        selectedRows.value = [...selectedRows.value, ...newSelections];
    } else {
        const pageIds = new Set(paginatedData.value.map((row) => row.id));
        selectedRows.value = selectedRows.value.filter((row) => !pageIds.has(row.id));
    }
};

const handleRowSelection = (row: TableRow, selected: boolean): void => {
    emit('row-select', row, selected);
};

const handleFilterChange = debounce(() => {
    emit('filter-change', { ...columnFilters.value });
    fetchData(1, false);
}, 500);

const clearColumnFilters = () => {
    columnFilters.value = {};
    fetchData(1, false);
};

const handleSwitchChange = async (row: TableRow, columnKey: string, newValue: boolean): Promise<void> => {
    const column = props.columns.find((col) => col.key === columnKey);
    if (!column?.switch?.enabled) return;

    // Marcar el estado de carga para esta fila y columna
    row[`${columnKey}Loading`] = true;

    // Guardar el valor anterior para revertir si cancela
    const previousValue = row[columnKey];

    try {
        confirm.require({
            message: `¿Estás seguro que deseas ${newValue ? 'activar' : 'desactivar'} este elemento?`,
            header: 'Confirmar acción',
            icon: 'pi pi-exclamation-triangle',
            acceptLabel: 'Sí, Acepto',
            rejectLabel: 'No, rechazar',
            accept: async () => {
                try {
                    // Determinar el endpoint
                    const endpoint = column.switch?.endpoint ? `${column.switch.endpoint}/${row.id}/${props.statusEndpoint}` : `${props.baseUrl}/${row.id}/${props.statusEndpoint}`;

                    // Determinar el campo a actualizar
                    const field = column.switch?.field || columnKey;
                    console.log(endpoint);
                    // Realizar la petición enviando el nombre del campo y el valor
                    const response = await api.post(endpoint, {
                        field,
                        value: newValue
                    });

                    row[`${columnKey}Loading`] = false;

                    if (response.data.success) {
                        // Actualizar el valor localmente
                        row[columnKey] = newValue;
                        toast.add({
                            severity: 'success',
                            summary: 'Éxito',
                            detail: 'Estado actualizado correctamente',
                            life: 3000
                        });
                        emit('switch-change', row, columnKey, newValue, true);
                    } else {
                        throw new Error(response.data.message || 'Error al actualizar el estado');
                    }
                } catch (error) {
                    console.error('Error updating status:', error);
                    row[`${columnKey}Loading`] = false;
                    row[columnKey] = previousValue; // Revertir valor
                    toast.add({
                        severity: 'error',
                        summary: 'Error',
                        detail: 'No se pudo actualizar el estado',
                        life: 3000
                    });
                    emit('switch-change', row, columnKey, previousValue, false);
                    emit('error', error instanceof Error ? error : new Error('Error desconocido'));
                }
            },
            reject: () => {
                // Revertir el cambio si el usuario cancela
                row[`${columnKey}Loading`] = false;
                row[columnKey] = previousValue;
                emit('switch-change', row, columnKey, previousValue, false);
            },
            onHide: () => {
                // Quitar el estado de loading cuando el diálogo se cierre
                row[`${columnKey}Loading`] = false;
            }
        });
    } catch (error) {
        console.error('Error showing confirmation:', error);
        row[`${columnKey}Loading`] = false;
        row[columnKey] = previousValue;
    }
};

const refresh = async (): Promise<void> => {
    await fetchData(paginatorState.currentPage, false);
};

const clearSelection = (): void => {
    selectedRows.value = [];
};

defineExpose({
    refresh,
    clearSelection,
    fetchData
});

// Observar cambios y actualizar URL
watch(() => [paginatorState.currentPage, paginatorState.pageSize, searchQuery.value, sortState.column, sortState.order, columnFilters.value], updateUrlParams, { deep: true });

watch(
    () => props.refreshKey,
    async () => {
        await fetchData(1, true);
    }
);

watch(
    () => props.fetchDataFunction,
    async () => {
        await fetchData(1, true);
    }
);

watch(
    () => props.initialPageSize,
    (newSize) => {
        if (paginatorState.pageSize !== newSize) {
            paginatorState.pageSize = newSize;
            fetchData(1, true);
        }
    }
);

watch(
    () => route.query,
    (newQuery) => {
        // Solo manejar cambios si no es la carga inicial
        if (!isInitialLoad.value) {
            const newPage = parseInt(newQuery.page?.toString() || '1');
            const newPageSize = parseInt(newQuery.pageSize?.toString() || paginatorState.pageSize.toString());
            const newSortColumn = newQuery.sortColumn?.toString() || '';
            const newSortOrder = (['asc', 'desc'].includes(newQuery.sortOrder?.toString() || '') ? newQuery.sortOrder?.toString() : 'asc') as 'asc' | 'desc';

            // Actualizar estados solo si hay cambios
            if (newPage !== paginatorState.currentPage || newPageSize !== paginatorState.pageSize) {
                paginatorState.currentPage = newPage;
                paginatorState.pageSize = newPageSize;
            }

            if (newSortColumn !== sortState.column || newSortOrder !== sortState.order) {
                sortState.column = newSortColumn;
                sortState.order = newSortOrder;
            }

            // Actualizar filtros de columnas
            Object.keys(newQuery).forEach((key) => {
                if (props.filterableColumns.some((col) => col.key === key) && newQuery[key] !== columnFilters.value[key]) {
                    columnFilters.value[key] = newQuery[key];
                }
            });

            // Forzar recarga si hay cambios relevantes
            if (newPage !== paginatorState.currentPage || newPageSize !== paginatorState.pageSize || newSortColumn !== sortState.column || newSortOrder !== sortState.order) {
                fetchData(newPage, false);
            }
        }
    },
    { deep: true }
);

fetchData(1, true);
</script>

<style scoped>
.card {
    @apply bg-white dark:bg-gray-800 rounded-lg shadow-sm;
}

.cursor-pointer:focus {
    @apply outline-none ring-2 ring-blue-500 ring-opacity-50;
}
</style>
