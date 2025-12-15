export interface SchoolYear {
    id: number;
    uuid: string;
    name: string;
    created_at?: string;
    updated_at?: string;
    extra_attributes?: Record<string, any>;
}

export interface SchoolYearFormData extends Omit<SchoolYear, 'id' | 'created_at' | 'updated_at' | 'extra_attributes'> {
    id?: number;
    photo?: File | null;
    extra_attributes?: Record<string, any>;
}

export interface SchoolYearResponse {
    data: SchoolYear | SchoolYear[];
    message?: string;
    success: boolean;
}