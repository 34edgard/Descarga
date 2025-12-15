export interface ExpandableRow extends TableRow {
    expanded?: boolean
    expandedData?: any[]
    expandedLoading?: boolean
    expandedError?: string
}

export interface ExpandableConfig {
    enabled: boolean
    fetchFunction?: (parentRow: TableRow) => Promise<any[]>
    expandedColumns?: Column[]
    expandedTitle?: string
    expandedEmptyMessage?: string
    loadOnExpand?: boolean
    cacheData?: boolean
}

export interface TableRow {
    id: string | number
    [key: string]: any
}

export interface ApiResponse<T = TableRow> {
    data: T[]
    meta: {
        total: number
        current_page: number
        last_page: number
        per_page: number
        from: number
        to: number
    }
}

export interface TableFilters {
    search?: string
    sort_by?: string
    sort_direction?: 'asc' | 'desc'
    page: number
    per_page: number
    [key: string]: any
}

export type FetchDataFunction = (filters: TableFilters) => Promise<ApiResponse>

export interface ConditionalRender {
    type: 'badge' | 'text' | 'icon' | 'custom'
    conditions: ConditionalRule[]
}

export interface ConditionalRule {
    condition: (value: any, row: TableRow) => boolean
    render: BadgeConfig | TextConfig | IconConfig | ((value: any, row: TableRow) => string)
}

export interface BadgeConfig {
    text: string
    severity: 'success' | 'info' | 'warning' | 'danger' | 'secondary' | 'primary'
    icon?: string
    class?: string
}

export interface TextConfig {
    text: string
    class?: string
    style?: Record<string, string>
}

export interface IconConfig {
    icon: string
    class?: string
    severity?: BadgeConfig['severity']
    tooltip?: string
}

export interface Column {
    key: string
    label: string
    sortable?: boolean
    visible?: boolean
    width?: string
    align?: 'left' | 'center' | 'right'
    conditionalStyle?: (value: any, row?: TableRow) => string
    conditionalRender?: ConditionalRender
    html?: boolean
    slotName?: string
    filterable?: boolean
    filterType?: 'text' | 'select' | 'date' | 'number' | 'boolean'
    filterOptions?: Array<{ label: string; value: any }>
    relation?: {
        table: string
        field: string
        sortKey?: string
    }
    switch?: {
        enabled: boolean
        endpoint?: string
        field?: string
    }
}

export interface PaginatorState {
    currentPage: number
    pageSize: number
    totalItems: number
    totalPages: number
}

export interface SortState {
    column: string
    order: 'asc' | 'desc'
}

export enum TableState {
    LOADING = 'loading',
    SUCCESS = 'success',
    ERROR = 'error',
    EMPTY = 'empty'
}

export interface ColumnFilter {
    key: string
    label: string
    filterType?: 'text' | 'select' | 'date' | 'number' | 'boolean'
    filterOptions?: Array<{ label: string; value: any }>
}
