import axios from '@/plugins/axios';
import { handleApiError } from '@/utils/errorHandler';
import { Genders, GendersFormData } from '@/interfaces/genders.interfaces';
import { ApiResponse, PaginatedResponse } from '@/interfaces/shared.interfaces';

const API_BASE_URL = '/genders';

// Tipos para requests con FormData
interface CreateGendersRequest extends Omit<GendersFormData, 'id'> {
    photo?: File;
}

interface UpdateGendersRequest extends Partial<GendersFormData> {
    id: number;
    photo?: File;
}

export const getGenders = async (): Promise<Genders[]> => {
    try {
        const response = await axios.get<ApiResponse<Genders[]>>(API_BASE_URL);
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const getGenderssPaginated = async (filters: any): Promise<PaginatedResponse<Genders>> => {
    try {
        const response = await axios.get<ApiResponse<PaginatedResponse<Genders>>>(`${API_BASE_URL}/paginated`, { params: filters });
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const getGender = async (id: number): Promise<Genders> => {
    try {
        const response = await axios.get<ApiResponse<Genders>>(`${API_BASE_URL}/${id}`);
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const createGender = async (data: CreateGendersRequest): Promise<Genders> => {
    try {
        const formData = new FormData();

        // Agregar campos normales
        Object.keys(data).forEach((key) => {
            if (key !== 'photo' && data[key as keyof CreateGendersRequest] !== undefined) {
                const value = data[key as keyof CreateGendersRequest];
                if (value !== null && value !== undefined) {
                    formData.append(key, value instanceof Blob ? value : String(value));
                }
            }
        });

        // Agregar foto si existe
        if (data.photo) {
            formData.append('photo', data.photo);
        }

        const response = await axios.post<ApiResponse<Genders>>(API_BASE_URL, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const updateGender = async (id: number, data: UpdateGendersRequest): Promise<Genders> => {
    try {
        const formData = new FormData();

        // Agregar campos normales
        Object.keys(data).forEach((key) => {
            if (key !== 'photo' && key !== 'id' && data[key as keyof UpdateGendersRequest] !== undefined) {
                const value = data[key as keyof UpdateGendersRequest];
                if (value !== null && value !== undefined) {
                    formData.append(key, value instanceof Blob ? value : String(value));
                }
            }
        });

        // Agregar foto si existe
        if (data.photo) {
            formData.append('photo', data.photo);
        }

        const response = await axios.put<ApiResponse<Genders>>(`${API_BASE_URL}/${id}`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const deleteGender = async (id: number): Promise<boolean> => {
    try {
        const response = await axios.delete<ApiResponse<{ deleted: boolean }>>(`${API_BASE_URL}/${id}`);
        return response.data.success;
    } catch (e) {
        return handleApiError(e);
    }
};

export const deleteGendersMultiple = async (ids: number[]): Promise<boolean> => {
    try {
        const response = await axios.delete<ApiResponse<{ deleted: boolean }>>(`${API_BASE_URL}/delete`, {
            data: { ids }
        });
        return response.data.success;
    } catch (e) {
        return handleApiError(e);
    }
};
