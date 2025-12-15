import { SelectOptions } from '@/types/Index';

export interface CompanyArea {
    id: number;
    name: string;
    company_department: string;
    description: string;
    created_at: string;
    updated_at: string;
}

export interface CompanyAreaFormData extends Omit<CompanyArea, 'id' | 'created_at'> {
    id?: number;
    photo?: File | null;
}

export interface CompanyAreaResponse {
    data: CompanyArea | CompanyArea[];
    message?: string;
    success: boolean;
}

export interface OptionsSelectCompanyDepartment {
    companyDepartmentOptions: SelectOptions[];
}
