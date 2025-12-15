import axios from '@/plugins/axios';
import { handleApiError } from '@/utils/errorHandler';
import { Section, SectionFormData } from '@/interfaces/section.interfaces';
import { ApiResponse, PaginatedResponse } from '@/interfaces/shared.interfaces';

const API_BASE_URL = '/sections';

// Tipos para requests con FormData
interface CreateSectionRequest extends Omit<SectionFormData, 'id'> {
    photo?: File;
}

interface UpdateSectionRequest extends Partial<SectionFormData> {
    id: number;
    photo?: File;
}

export const getSections = async (): Promise<Section[]> => {
    try {
        const response = await axios.get<ApiResponse<Section[]>>(API_BASE_URL);
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const getSectionsPaginated = async (filters: any): Promise<PaginatedResponse<Section>> => {
    try {
        const response = await axios.get<ApiResponse<PaginatedResponse<Section>>>(`${API_BASE_URL}/paginated`, { params: filters });
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const getSection = async (id: number): Promise<Section> => {
    try {
        const response = await axios.get<ApiResponse<Section>>(`${API_BASE_URL}/${id}`);
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const createSection = async (data: CreateSectionRequest): Promise<Section> => {
    try {
        const formData = new FormData();

        // Agregar campos normales
        Object.keys(data).forEach((key) => {
            if (key !== 'photo' && data[key as keyof CreateSectionRequest] !== undefined) {
                const value = data[key as keyof CreateSectionRequest];
                if (value !== null && value !== undefined) {
                    formData.append(key, value instanceof Blob ? value : String(value));
                }
            }
        });

        // Agregar foto si existe
        if (data.photo) {
            formData.append('photo', data.photo);
        }

        const response = await axios.post<ApiResponse<Section>>(API_BASE_URL, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const updateSection = async (id: number, data: UpdateSectionRequest): Promise<Section> => {
    try {
        const formData = new FormData();

        // Agregar campos normales
        Object.keys(data).forEach((key) => {
            if (key !== 'photo' && key !== 'id' && data[key as keyof UpdateSectionRequest] !== undefined) {
                const value = data[key as keyof UpdateSectionRequest];
                if (value !== null && value !== undefined) {
                    formData.append(key, value instanceof Blob ? value : String(value));
                }
            }
        });

        // Agregar foto si existe
        if (data.photo) {
            formData.append('photo', data.photo);
        }

        const response = await axios.put<ApiResponse<Section>>(`${API_BASE_URL}/${id}`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const deleteSection = async (id: number): Promise<boolean> => {
    try {
        const response = await axios.delete<ApiResponse<{ deleted: boolean }>>(`${API_BASE_URL}/${id}`);
        return response.data.success;
    } catch (e) {
        return handleApiError(e);
    }
};

export const deleteSectionMultiple = async (ids: number[]): Promise<boolean> => {
    try {
        const response = await axios.delete<ApiResponse<{ deleted: boolean }>>(`${API_BASE_URL}/delete`, {
            data: { ids }
        });
        return response.data.success;
    } catch (e) {
        return handleApiError(e);
    }
};
