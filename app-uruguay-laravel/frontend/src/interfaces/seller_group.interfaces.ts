export interface SellerGroup {
    id: number;
    uuid: string;
    name: string;
    created_at?: string;
    updated_at?: string;
    extra_attributes?: Record<string, any>;
}

export interface SellerGroupFormData extends Omit<SellerGroup, 'id' | 'created_at' | 'updated_at' | 'extra_attributes'> {
    id?: number;
    photo?: File | null;
    extra_attributes?: Record<string, any>;
}

export interface SellerGroupResponse {
    data: SellerGroup | SellerGroup[];
    message?: string;
    success: boolean;
}