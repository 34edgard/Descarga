export interface SalaryType {
    id: number;
    uuid: string;
    name: string;
    created_at?: string;
    updated_at?: string;
    extra_attributes?: Record<string, any>;
}

export interface SalaryTypeFormData extends Omit<SalaryType, 'id' | 'created_at' | 'updated_at' | 'extra_attributes'> {
    id?: number;
    photo?: File | null;
    extra_attributes?: Record<string, any>;
}

export interface SalaryTypeResponse {
    data: SalaryType | SalaryType[];
    message?: string;
    success: boolean;
}