export interface InsurancePolicie {
    id: number;
    uuid: string;
    name: string;
    created_at?: string;
    updated_at?: string;
    extra_attributes?: Record<string, any>;
}

export interface InsurancePolicieFormData extends Omit<InsurancePolicie, 'id' | 'created_at' | 'updated_at' | 'extra_attributes'> {
    id?: number;
    photo?: File | null;
    extra_attributes?: Record<string, any>;
}

export interface InsurancePolicieResponse {
    data: InsurancePolicie | InsurancePolicie[];
    message?: string;
    success: boolean;
}