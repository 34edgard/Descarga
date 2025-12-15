export interface CommissionCondition {
    id: number;
    uuid: string;
    name: string;
    created_at?: string;
    updated_at?: string;
    extra_attributes?: Record<string, any>;
}

export interface CommissionConditionFormData extends Omit<CommissionCondition, 'id' | 'created_at' | 'updated_at' | 'extra_attributes'> {
    id?: number;
    photo?: File | null;
    extra_attributes?: Record<string, any>;
}

export interface CommissionConditionResponse {
    data: CommissionCondition | CommissionCondition[];
    message?: string;
    success: boolean;
}