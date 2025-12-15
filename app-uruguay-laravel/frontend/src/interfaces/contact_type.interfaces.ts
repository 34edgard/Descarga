export interface ContactType {
    id: number;
    uuid: string;
    name: string;
    created_at?: string;
    updated_at?: string;
    extra_attributes?: Record<string, any>;
}

export interface ContactTypeFormData extends Omit<ContactType, 'id' | 'created_at' | 'updated_at' | 'extra_attributes'> {
    id?: number;
    photo?: File | null;
    extra_attributes?: Record<string, any>;
}

export interface ContactTypeResponse {
    data: ContactType | ContactType[];
    message?: string;
    success: boolean;
}