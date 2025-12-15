export interface InsurancePolicyType {
    id: number;
    uuid: string;
    name: string;
    created_at?: string;
    updated_at?: string;
    extra_attributes?: Record<string, any>;
}

export interface InsurancePolicyTypeFormData extends Omit<InsurancePolicyType, 'id' | 'created_at' | 'updated_at' | 'extra_attributes'> {
    id?: number;
    photo?: File | null;
    extra_attributes?: Record<string, any>;
}

export interface InsurancePolicyTypeResponse {
    data: InsurancePolicyType | InsurancePolicyType[];
    message?: string;
    success: boolean;
}