import axios from '@/plugins/axios';
import { handleApiError } from '@/utils/errorHandler';
import { Classroom, ClassroomFormData } from '@/interfaces/classroom.interfaces';
import { ApiResponse, PaginatedResponse } from '@/interfaces/shared.interfaces';

const API_BASE_URL = '/classrooms';

// Tipos para requests con FormData
interface CreateClassroomRequest extends Omit<ClassroomFormData, 'id'> {
    photo?: File;
}

interface UpdateClassroomRequest extends Partial<ClassroomFormData> {
    id: number;
    photo?: File;
}

export const getClassrooms = async (): Promise<Classroom[]> => {
    try {
        const response = await axios.get<ApiResponse<Classroom[]>>(API_BASE_URL);
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const getClassroomsPaginated = async (filters: any): Promise<PaginatedResponse<Classroom>> => {
    try {
        const response = await axios.get<ApiResponse<PaginatedResponse<Classroom>>>(`${API_BASE_URL}/paginated`, { params: filters });
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const getClassroom = async (id: number): Promise<Classroom> => {
    try {
        const response = await axios.get<ApiResponse<Classroom>>(`${API_BASE_URL}/${id}`);
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const createClassroom = async (data: CreateClassroomRequest): Promise<Classroom> => {
    try {
        const formData = new FormData();

        // Agregar campos normales
        Object.keys(data).forEach((key) => {
            if (key !== 'photo' && data[key as keyof CreateClassroomRequest] !== undefined) {
                const value = data[key as keyof CreateClassroomRequest];
                if (value !== null && value !== undefined) {
                    formData.append(key, value instanceof Blob ? value : String(value));
                }
            }
        });

        // Agregar foto si existe
        if (data.photo) {
            formData.append('photo', data.photo);
        }

        const response = await axios.post<ApiResponse<Classroom>>(API_BASE_URL, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const updateClassroom = async (id: number, data: UpdateClassroomRequest): Promise<Classroom> => {
    try {
        const formData = new FormData();

        // Agregar campos normales
        Object.keys(data).forEach((key) => {
            if (key !== 'photo' && key !== 'id' && data[key as keyof UpdateClassroomRequest] !== undefined) {
                const value = data[key as keyof UpdateClassroomRequest];
                if (value !== null && value !== undefined) {
                    formData.append(key, value instanceof Blob ? value : String(value));
                }
            }
        });

        // Agregar foto si existe
        if (data.photo) {
            formData.append('photo', data.photo);
        }

        const response = await axios.put<ApiResponse<Classroom>>(`${API_BASE_URL}/${id}`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const deleteClassroom = async (id: number): Promise<boolean> => {
    try {
        const response = await axios.delete<ApiResponse<{ deleted: boolean }>>(`${API_BASE_URL}/${id}`);
        return response.data.success;
    } catch (e) {
        return handleApiError(e);
    }
};

export const deleteClassroomMultiple = async (ids: number[]): Promise<boolean> => {
    try {
        const response = await axios.delete<ApiResponse<{ deleted: boolean }>>(`${API_BASE_URL}/delete`, {
            data: { ids }
        });
        return response.data.success;
    } catch (e) {
        return handleApiError(e);
    }
};
