import { SelectOptions } from '@/types/Index';

export interface MaritalStatuses {
    id: number;
    name: string;
    created_at: string;
    updated_at: string;
}

export interface MaritalStatusesFormData extends Omit<MaritalStatuses, 'id' | 'created_at' | 'updated_at'> {
    id?: number;
    photo?: File | null;
}

export interface MaritalStatusesResponse {
    data: MaritalStatuses | MaritalStatuses[];
    message?: string;
    success: boolean;
}

export interface OptionsSelectMaritalStatuses {
    /*maritalStatusOptions: SelectOptions[];
    gendersOptions: SelectOptions[];*/
}
