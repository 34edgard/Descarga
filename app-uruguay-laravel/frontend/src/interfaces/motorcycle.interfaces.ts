export interface Motorcycle {
    id: number;
    name: string;
    brand: string;
    model: string;
    year: number;
    created_at?: string;
    updated_at?: string;
    extra_attributes?: Record<string, any>;
}

export interface MotorcycleFormData extends Omit<Motorcycle, 'id' | 'created_at' | 'updated_at' | 'extra_attributes'> {
    id?: number;
    photo?: File | null;
    extra_attributes?: Record<string, any>;
}

export interface MotorcycleResponse {
    data: Motorcycle | Motorcycle[];
    message?: string;
    success: boolean;
}