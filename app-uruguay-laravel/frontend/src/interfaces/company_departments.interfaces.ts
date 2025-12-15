export interface CompanyDepartments {
    id: number;
    name: string;
    description: string;
    photo_url?: string;
    created_at: string;
    updated_at: string;
    extra_attributes?: Record<string, any>;
}

export interface CompanyDepartmentsFormData extends Omit<CompanyDepartments, 'id' | 'created_at' | 'updated_at' | 'extra_attributes'> {
    id?: number;
    photo?: File | null;
    extra_attributes?: Record<string, any>;
}

export interface CompanyDepartmentsResponse {
    data: CompanyDepartments | CompanyDepartments[];
    message?: string;
    success: boolean;
}
