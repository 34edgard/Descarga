export interface ClientType {
    id: number;
    name: string;
    created_at?: string;
    updated_at?: string;
    extra_attributes?: Record<string, any>;
}

export interface ClientTypeFormData extends Omit<ClientType, 'id' | 'created_at' | 'updated_at' | 'extra_attributes'> {
    id?: number;
    photo?: File | null;
    extra_attributes?: Record<string, any>;
}

export interface ClientTypeResponse {
    data: ClientType | ClientType[];
    message?: string;
    success: boolean;
}