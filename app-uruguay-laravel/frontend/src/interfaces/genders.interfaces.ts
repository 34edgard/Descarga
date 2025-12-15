export interface Genders {
    id: number;
    name: string;
    description: string;
    photo_url?: string;
    created_at: string;
    updated_at: string;
    extra_attributes?: Record<string, any>;
}

export interface GendersFormData extends Omit<Genders, 'id' | 'created_at' | 'updated_at' | 'extra_attributes'> {
    id?: number;
    photo?: File | null;
    extra_attributes?: Record<string, any>;
}

export interface GendersResponse {
    data: Genders | Genders[];
    message?: string;
    success: boolean;
}