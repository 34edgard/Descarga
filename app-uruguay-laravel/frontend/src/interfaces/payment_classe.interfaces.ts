export interface PaymentClasse {
    id: number;
    uuid: string;
    name: string;
    created_at?: string;
    updated_at?: string;
    extra_attributes?: Record<string, any>;
}

export interface PaymentClasseFormData extends Omit<PaymentClasse, 'id' | 'created_at' | 'updated_at' | 'extra_attributes'> {
    id?: number;
    photo?: File | null;
    extra_attributes?: Record<string, any>;
}

export interface PaymentClasseResponse {
    data: PaymentClasse | PaymentClasse[];
    message?: string;
    success: boolean;
}