export interface Profile {
    id: number;
    name: string;
    description: string;
    company_area_id: number;
    created_at: string;
    updated_at: string;
}

export interface ProfileFormData extends Omit<Profile, 'id' | 'created_at' | 'updated_at'> {
    id?: number;
    photo?: File | null;
}

export interface ProfileResponse {
    data: Profile | Profile[];
    message?: string;
    success: boolean;
}
