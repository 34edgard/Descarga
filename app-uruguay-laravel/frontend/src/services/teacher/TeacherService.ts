import axios from '@/plugins/axios';
import { handleApiError } from '@/utils/errorHandler';
import { Teacher, TeacherFormData } from '@/interfaces/teacher.interfaces';
import { ApiResponse, PaginatedResponse } from '@/interfaces/shared.interfaces';

const API_BASE_URL = '/teachers';

// Tipos para requests con FormData
interface CreateTeacherRequest extends Omit<TeacherFormData, 'id'> {
    photo?: File;
}

interface UpdateTeacherRequest extends Partial<TeacherFormData> {
    id: number;
    photo?: File;
}

export const getTeachers = async (): Promise<Teacher[]> => {
    try {
        const response = await axios.get<ApiResponse<Teacher[]>>(API_BASE_URL);
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const getTeachersPaginated = async (filters: any): Promise<PaginatedResponse<Teacher>> => {
    try {
        const response = await axios.get<ApiResponse<PaginatedResponse<Teacher>>>(`${API_BASE_URL}/paginated`, { params: filters });
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const getTeacher = async (id: number): Promise<Teacher> => {
    try {
        const response = await axios.get<ApiResponse<Teacher>>(`${API_BASE_URL}/${id}`);
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const createTeacher = async (data: CreateTeacherRequest): Promise<Teacher> => {
    try {
        const formData = new FormData();

        // Agregar campos normales
        Object.keys(data).forEach((key) => {
            if (key !== 'photo' && data[key as keyof CreateTeacherRequest] !== undefined) {
                const value = data[key as keyof CreateTeacherRequest];
                if (value !== null && value !== undefined) {
                    formData.append(key, value instanceof Blob ? value : String(value));
                }
            }
        });

        // Agregar foto si existe
        if (data.photo) {
            formData.append('photo', data.photo);
        }

        const response = await axios.post<ApiResponse<Teacher>>(API_BASE_URL, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const updateTeacher = async (id: number, data: UpdateTeacherRequest): Promise<Teacher> => {
    try {
        const formData = new FormData();

        // Agregar campos normales
        Object.keys(data).forEach((key) => {
            if (key !== 'photo' && key !== 'id' && data[key as keyof UpdateTeacherRequest] !== undefined) {
                const value = data[key as keyof UpdateTeacherRequest];
                if (value !== null && value !== undefined) {
                    formData.append(key, value instanceof Blob ? value : String(value));
                }
            }
        });

        // Agregar foto si existe
        if (data.photo) {
            formData.append('photo', data.photo);
        }

        const response = await axios.put<ApiResponse<Teacher>>(`${API_BASE_URL}/${id}`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const deleteTeacher = async (id: number): Promise<boolean> => {
    try {
        const response = await axios.delete<ApiResponse<{ deleted: boolean }>>(`${API_BASE_URL}/${id}`);
        return response.data.success;
    } catch (e) {
        return handleApiError(e);
    }
};

export const deleteTeacherMultiple = async (ids: number[]): Promise<boolean> => {
    try {
        const response = await axios.delete<ApiResponse<{ deleted: boolean }>>(`${API_BASE_URL}/delete`, {
            data: { ids }
        });
        return response.data.success;
    } catch (e) {
        return handleApiError(e);
    }
};
