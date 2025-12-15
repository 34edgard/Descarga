import axios from '@/plugins/axios';
import { handleApiError } from '@/utils/errorHandler';
import { SchoolYear, SchoolYearFormData } from '@/interfaces/school_year.interfaces';
import { ApiResponse, PaginatedResponse } from '@/interfaces/shared.interfaces';

const API_BASE_URL = '/school_years';

// Tipos para requests con FormData
interface CreateSchoolYearRequest extends Omit<SchoolYearFormData, 'id'> {
    photo?: File;
}

interface UpdateSchoolYearRequest extends Partial<SchoolYearFormData> {
    id: number;
    photo?: File;
}

export const getSchoolYears = async (): Promise<SchoolYear[]> => {
    try {
        const response = await axios.get<ApiResponse<SchoolYear[]>>(API_BASE_URL);
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const getSchoolYearsPaginated = async (filters: any): Promise<PaginatedResponse<SchoolYear>> => {
    try {
        const response = await axios.get<ApiResponse<PaginatedResponse<SchoolYear>>>(`${API_BASE_URL}/paginated`, { params: filters });
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const getSchoolYear = async (id: number): Promise<SchoolYear> => {
    try {
        const response = await axios.get<ApiResponse<SchoolYear>>(`${API_BASE_URL}/${id}`);
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const createSchoolYear = async (data: CreateSchoolYearRequest): Promise<SchoolYear> => {
    try {
        const formData = new FormData();

        // Agregar campos normales
        Object.keys(data).forEach((key) => {
            if (key !== 'photo' && data[key as keyof CreateSchoolYearRequest] !== undefined) {
                const value = data[key as keyof CreateSchoolYearRequest];
                if (value !== null && value !== undefined) {
                    formData.append(key, value instanceof Blob ? value : String(value));
                }
            }
        });

        // Agregar foto si existe
        if (data.photo) {
            formData.append('photo', data.photo);
        }

        const response = await axios.post<ApiResponse<SchoolYear>>(API_BASE_URL, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const updateSchoolYear = async (id: number, data: UpdateSchoolYearRequest): Promise<SchoolYear> => {
    try {
        const formData = new FormData();

        // Agregar campos normales
        Object.keys(data).forEach((key) => {
            if (key !== 'photo' && key !== 'id' && data[key as keyof UpdateSchoolYearRequest] !== undefined) {
                const value = data[key as keyof UpdateSchoolYearRequest];
                if (value !== null && value !== undefined) {
                    formData.append(key, value instanceof Blob ? value : String(value));
                }
            }
        });

        // Agregar foto si existe
        if (data.photo) {
            formData.append('photo', data.photo);
        }

        const response = await axios.put<ApiResponse<SchoolYear>>(`${API_BASE_URL}/${id}`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const deleteSchoolYear = async (id: number): Promise<boolean> => {
    try {
        const response = await axios.delete<ApiResponse<{ deleted: boolean }>>(`${API_BASE_URL}/${id}`);
        return response.data.success;
    } catch (e) {
        return handleApiError(e);
    }
};

export const deleteSchoolYearMultiple = async (ids: number[]): Promise<boolean> => {
    try {
        const response = await axios.delete<ApiResponse<{ deleted: boolean }>>(`${API_BASE_URL}/delete`, {
            data: { ids }
        });
        return response.data.success;
    } catch (e) {
        return handleApiError(e);
    }
};
