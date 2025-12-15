export interface ResignationCheck {
    id: number;
    uuid: string;
    name: string;
    created_at?: string;
    updated_at?: string;
    extra_attributes?: Record<string, any>;
}

export interface ResignationCheckFormData extends Omit<ResignationCheck, 'id' | 'created_at' | 'updated_at' | 'extra_attributes'> {
    id?: number;
    photo?: File | null;
    extra_attributes?: Record<string, any>;
}

export interface ResignationCheckResponse {
    data: ResignationCheck | ResignationCheck[];
    message?: string;
    success: boolean;
}