export interface IncomeCheck {
    id: number;
    uuid: string;
    name: string;
    created_at?: string;
    updated_at?: string;
    extra_attributes?: Record<string, any>;
}

export interface IncomeCheckFormData extends Omit<IncomeCheck, 'id' | 'created_at' | 'updated_at' | 'extra_attributes'> {
    id?: number;
    photo?: File | null;
    extra_attributes?: Record<string, any>;
}

export interface IncomeCheckResponse {
    data: IncomeCheck | IncomeCheck[];
    message?: string;
    success: boolean;
}