export interface FamilyMemberType {
    id: number;
    uuid: string;
    name: string;
    created_at?: string;
    updated_at?: string;
    extra_attributes?: Record<string, any>;
}

export interface FamilyMemberTypeFormData extends Omit<FamilyMemberType, 'id' | 'created_at' | 'updated_at' | 'extra_attributes'> {
    id?: number;
    photo?: File | null;
    extra_attributes?: Record<string, any>;
}

export interface FamilyMemberTypeResponse {
    data: FamilyMemberType | FamilyMemberType[];
    message?: string;
    success: boolean;
}