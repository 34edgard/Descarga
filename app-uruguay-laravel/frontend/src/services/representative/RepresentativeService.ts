import axios from '@/plugins/axios';
import { handleApiError } from '@/utils/errorHandler';
import { Representative, RepresentativeFormData } from '@/interfaces/representative.interfaces';
import { ApiResponse, PaginatedResponse } from '@/interfaces/shared.interfaces';

const API_BASE_URL = '/representatives';

// Tipos para requests con FormData
interface CreateRepresentativeRequest extends Omit<RepresentativeFormData, 'id'> {
    photo?: File;
}

interface UpdateRepresentativeRequest extends Partial<RepresentativeFormData> {
    id: number;
    photo?: File;
}

export const getRepresentatives = async (): Promise<Representative[]> => {
    try {
        const response = await axios.get<ApiResponse<Representative[]>>(API_BASE_URL);
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const getRepresentativesPaginated = async (filters: any): Promise<PaginatedResponse<Representative>> => {
    try {
        const response = await axios.get<ApiResponse<PaginatedResponse<Representative>>>(`${API_BASE_URL}/paginated`, { params: filters });
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const getRepresentative = async (id: number): Promise<Representative> => {
    try {
        const response = await axios.get<ApiResponse<Representative>>(`${API_BASE_URL}/${id}`);
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const createRepresentative = async (data: CreateRepresentativeRequest): Promise<Representative> => {
    try {
        const formData = new FormData();

        // Agregar campos normales
        Object.keys(data).forEach((key) => {
            if (key !== 'photo' && data[key as keyof CreateRepresentativeRequest] !== undefined) {
                const value = data[key as keyof CreateRepresentativeRequest];
                if (value !== null && value !== undefined) {
                    formData.append(key, value instanceof Blob ? value : String(value));
                }
            }
        });

        // Agregar foto si existe
        if (data.photo) {
            formData.append('photo', data.photo);
        }

        const response = await axios.post<ApiResponse<Representative>>(API_BASE_URL, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const updateRepresentative = async (id: number, data: UpdateRepresentativeRequest): Promise<Representative> => {
    try {
        const formData = new FormData();

        // Agregar campos normales
        Object.keys(data).forEach((key) => {
            if (key !== 'photo' && key !== 'id' && data[key as keyof UpdateRepresentativeRequest] !== undefined) {
                const value = data[key as keyof UpdateRepresentativeRequest];
                if (value !== null && value !== undefined) {
                    formData.append(key, value instanceof Blob ? value : String(value));
                }
            }
        });

        // Agregar foto si existe
        if (data.photo) {
            formData.append('photo', data.photo);
        }

        const response = await axios.put<ApiResponse<Representative>>(`${API_BASE_URL}/${id}`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const deleteRepresentative = async (id: number): Promise<boolean> => {
    try {
        const response = await axios.delete<ApiResponse<{ deleted: boolean }>>(`${API_BASE_URL}/${id}`);
        return response.data.success;
    } catch (e) {
        return handleApiError(e);
    }
};

export const deleteRepresentativeMultiple = async (ids: number[]): Promise<boolean> => {
    try {
        const response = await axios.delete<ApiResponse<{ deleted: boolean }>>(`${API_BASE_URL}/delete`, {
            data: { ids }
        });
        return response.data.success;
    } catch (e) {
        return handleApiError(e);
    }
};
