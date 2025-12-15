export interface DiscountType {
    id: number;
    name: string;
    description: string;
    created_at?: string;
    updated_at?: string;
}

export interface DiscountTypeFormData extends Omit<DiscountType, 'id' | 'created_at' | 'updated_at' | 'extra_attributes'> {
    id?: number;
    photo?: File | null;
}

export interface DiscountTypeResponse {
    data: DiscountType | DiscountType[];
    message?: string;
    success: boolean;
}