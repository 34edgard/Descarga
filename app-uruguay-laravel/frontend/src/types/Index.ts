export interface Filters {
    search?: string;
    sort_by?: string;
    sort_direction?: 'asc' | 'desc';
    page?: number;
    per_page?: number;
}

export interface PaginationMeta {
    total: number;
}

export interface ApiResponse<T> {
    data: T;
    meta: PaginationMeta;
}

export interface ApiRespData<T> {
    data: ApiResponse<T>;
}

export interface SelectOptions {
    label: string;
    value: string;
}
