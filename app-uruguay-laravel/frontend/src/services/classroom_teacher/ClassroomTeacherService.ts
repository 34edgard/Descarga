import axios from '@/plugins/axios';
import { handleApiError } from '@/utils/errorHandler';
import { ClassroomTeacher, ClassroomTeacherFormData } from '@/interfaces/classroom_teacher.interfaces';
import { ApiResponse, PaginatedResponse } from '@/interfaces/shared.interfaces';

const API_BASE_URL = '/classroom_teachers';

// Tipos para requests con FormData
interface CreateClassroomTeacherRequest extends Omit<ClassroomTeacherFormData, 'id'> {
    photo?: File;
}

interface UpdateClassroomTeacherRequest extends Partial<ClassroomTeacherFormData> {
    id: number;
    photo?: File;
}

export const getClassroomTeachers = async (): Promise<ClassroomTeacher[]> => {
    try {
        const response = await axios.get<ApiResponse<ClassroomTeacher[]>>(API_BASE_URL);
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const getClassroomTeachersPaginated = async (filters: any): Promise<PaginatedResponse<ClassroomTeacher>> => {
    try {
        const response = await axios.get<ApiResponse<PaginatedResponse<ClassroomTeacher>>>(`${API_BASE_URL}/paginated`, { params: filters });
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const getClassroomTeacher = async (id: number): Promise<ClassroomTeacher> => {
    try {
        const response = await axios.get<ApiResponse<ClassroomTeacher>>(`${API_BASE_URL}/${id}`);
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const createClassroomTeacher = async (data: CreateClassroomTeacherRequest): Promise<ClassroomTeacher> => {
    try {
        const formData = new FormData();

        // Agregar campos normales
        Object.keys(data).forEach((key) => {
            if (key !== 'photo' && data[key as keyof CreateClassroomTeacherRequest] !== undefined) {
                const value = data[key as keyof CreateClassroomTeacherRequest];
                if (value !== null && value !== undefined) {
                    formData.append(key, value instanceof Blob ? value : String(value));
                }
            }
        });

        // Agregar foto si existe
        if (data.photo) {
            formData.append('photo', data.photo);
        }

        const response = await axios.post<ApiResponse<ClassroomTeacher>>(API_BASE_URL, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const updateClassroomTeacher = async (id: number, data: UpdateClassroomTeacherRequest): Promise<ClassroomTeacher> => {
    try {
        const formData = new FormData();

        // Agregar campos normales
        Object.keys(data).forEach((key) => {
            if (key !== 'photo' && key !== 'id' && data[key as keyof UpdateClassroomTeacherRequest] !== undefined) {
                const value = data[key as keyof UpdateClassroomTeacherRequest];
                if (value !== null && value !== undefined) {
                    formData.append(key, value instanceof Blob ? value : String(value));
                }
            }
        });

        // Agregar foto si existe
        if (data.photo) {
            formData.append('photo', data.photo);
        }

        const response = await axios.put<ApiResponse<ClassroomTeacher>>(`${API_BASE_URL}/${id}`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const deleteClassroomTeacher = async (id: number): Promise<boolean> => {
    try {
        const response = await axios.delete<ApiResponse<{ deleted: boolean }>>(`${API_BASE_URL}/${id}`);
        return response.data.success;
    } catch (e) {
        return handleApiError(e);
    }
};

export const deleteClassroomTeacherMultiple = async (ids: number[]): Promise<boolean> => {
    try {
        const response = await axios.delete<ApiResponse<{ deleted: boolean }>>(`${API_BASE_URL}/delete`, {
            data: { ids }
        });
        return response.data.success;
    } catch (e) {
        return handleApiError(e);
    }
};
