export interface SellerGroupType {
    id: number;
    uuid: string;
    name: string;
    created_at?: string;
    updated_at?: string;
    extra_attributes?: Record<string, any>;
}

export interface SellerGroupTypeFormData extends Omit<SellerGroupType, 'id' | 'created_at' | 'updated_at' | 'extra_attributes'> {
    id?: number;
    photo?: File | null;
    extra_attributes?: Record<string, any>;
}

export interface SellerGroupTypeResponse {
    data: SellerGroupType | SellerGroupType[];
    message?: string;
    success: boolean;
}