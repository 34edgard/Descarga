export interface Department {
    id: number;
    uuid: string;
    name: string;
    created_at?: string;
    updated_at?: string;
    extra_attributes?: Record<string, any>;
}

export interface DepartmentFormData extends Omit<Department, 'id' | 'created_at' | 'updated_at' | 'extra_attributes'> {
    id?: number;
    photo?: File | null;
    extra_attributes?: Record<string, any>;
}

export interface DepartmentResponse {
    data: Department | Department[];
    message?: string;
    success: boolean;
}