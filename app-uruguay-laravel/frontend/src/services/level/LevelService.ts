import axios from '@/plugins/axios';
import { handleApiError } from '@/utils/errorHandler';
import { Level, LevelFormData } from '@/interfaces/level.interfaces';
import { ApiResponse, PaginatedResponse } from '@/interfaces/shared.interfaces';

const API_BASE_URL = '/levels';

// Tipos para requests con FormData
interface CreateLevelRequest extends Omit<LevelFormData, 'id'> {
    photo?: File;
}

interface UpdateLevelRequest extends Partial<LevelFormData> {
    id: number;
    photo?: File;
}

export const getLevels = async (): Promise<Level[]> => {
    try {
        const response = await axios.get<ApiResponse<Level[]>>(API_BASE_URL);
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const getLevelsPaginated = async (filters: any): Promise<PaginatedResponse<Level>> => {
    try {
        const response = await axios.get<ApiResponse<PaginatedResponse<Level>>>(`${API_BASE_URL}/paginated`, { params: filters });
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const getLevel = async (id: number): Promise<Level> => {
    try {
        const response = await axios.get<ApiResponse<Level>>(`${API_BASE_URL}/${id}`);
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const createLevel = async (data: CreateLevelRequest): Promise<Level> => {
    try {
        const formData = new FormData();

        // Agregar campos normales
        Object.keys(data).forEach((key) => {
            if (key !== 'photo' && data[key as keyof CreateLevelRequest] !== undefined) {
                const value = data[key as keyof CreateLevelRequest];
                if (value !== null && value !== undefined) {
                    formData.append(key, value instanceof Blob ? value : String(value));
                }
            }
        });

        // Agregar foto si existe
        if (data.photo) {
            formData.append('photo', data.photo);
        }

        const response = await axios.post<ApiResponse<Level>>(API_BASE_URL, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const updateLevel = async (id: number, data: UpdateLevelRequest): Promise<Level> => {
    try {
        const formData = new FormData();

        // Agregar campos normales
        Object.keys(data).forEach((key) => {
            if (key !== 'photo' && key !== 'id' && data[key as keyof UpdateLevelRequest] !== undefined) {
                const value = data[key as keyof UpdateLevelRequest];
                if (value !== null && value !== undefined) {
                    formData.append(key, value instanceof Blob ? value : String(value));
                }
            }
        });

        // Agregar foto si existe
        if (data.photo) {
            formData.append('photo', data.photo);
        }

        const response = await axios.put<ApiResponse<Level>>(`${API_BASE_URL}/${id}`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const deleteLevel = async (id: number): Promise<boolean> => {
    try {
        const response = await axios.delete<ApiResponse<{ deleted: boolean }>>(`${API_BASE_URL}/${id}`);
        return response.data.success;
    } catch (e) {
        return handleApiError(e);
    }
};

export const deleteLevelMultiple = async (ids: number[]): Promise<boolean> => {
    try {
        const response = await axios.delete<ApiResponse<{ deleted: boolean }>>(`${API_BASE_URL}/delete`, {
            data: { ids }
        });
        return response.data.success;
    } catch (e) {
        return handleApiError(e);
    }
};
