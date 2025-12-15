export interface City {
    id: number;
    name: string;
    created_at?: string;
    updated_at?: string;
    extra_attributes?: Record<string, any>;
}

export interface CityFormData extends Omit<City, 'id' | 'created_at' | 'updated_at' | 'extra_attributes'> {
    id?: number;
    photo?: File | null;
    extra_attributes?: Record<string, any>;
}

export interface CityResponse {
    data: City | City[];
    message?: string;
    success: boolean;
}