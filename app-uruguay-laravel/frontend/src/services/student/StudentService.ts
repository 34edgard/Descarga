import axios from '@/plugins/axios';
import { handleApiError } from '@/utils/errorHandler';
import { Student, StudentFormData } from '@/interfaces/student.interfaces';
import { ApiResponse, PaginatedResponse } from '@/interfaces/shared.interfaces';

const API_BASE_URL = '/students';

// Tipos para requests con FormData
interface CreateStudentRequest extends Omit<StudentFormData, 'id'> {
    photo?: File;
}

interface UpdateStudentRequest extends Partial<StudentFormData> {
    id: number;
    photo?: File;
}

export const getStudents = async (): Promise<Student[]> => {
    try {
        const response = await axios.get<ApiResponse<Student[]>>(API_BASE_URL);
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const getStudentsPaginated = async (filters: any): Promise<PaginatedResponse<Student>> => {
    try {
        const response = await axios.get<ApiResponse<PaginatedResponse<Student>>>(`${API_BASE_URL}/paginated`, { params: filters });
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const getStudent = async (id: number): Promise<Student> => {
    try {
        const response = await axios.get<ApiResponse<Student>>(`${API_BASE_URL}/${id}`);
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const createStudent = async (data: CreateStudentRequest): Promise<Student> => {
    try {
        const formData = new FormData();

        // Agregar campos normales
        Object.keys(data).forEach((key) => {
            if (key !== 'photo' && data[key as keyof CreateStudentRequest] !== undefined) {
                const value = data[key as keyof CreateStudentRequest];
                if (value !== null && value !== undefined) {
                    formData.append(key, value instanceof Blob ? value : String(value));
                }
            }
        });

        // Agregar foto si existe
        if (data.photo) {
            formData.append('photo', data.photo);
        }

        const response = await axios.post<ApiResponse<Student>>(API_BASE_URL, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const updateStudent = async (id: number, data: UpdateStudentRequest): Promise<Student> => {
    try {
        const formData = new FormData();

        // Agregar campos normales
        Object.keys(data).forEach((key) => {
            if (key !== 'photo' && key !== 'id' && data[key as keyof UpdateStudentRequest] !== undefined) {
                const value = data[key as keyof UpdateStudentRequest];
                if (value !== null && value !== undefined) {
                    formData.append(key, value instanceof Blob ? value : String(value));
                }
            }
        });

        // Agregar foto si existe
        if (data.photo) {
            formData.append('photo', data.photo);
        }

        const response = await axios.put<ApiResponse<Student>>(`${API_BASE_URL}/${id}`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const deleteStudent = async (id: number): Promise<boolean> => {
    try {
        const response = await axios.delete<ApiResponse<{ deleted: boolean }>>(`${API_BASE_URL}/${id}`);
        return response.data.success;
    } catch (e) {
        return handleApiError(e);
    }
};

export const deleteStudentMultiple = async (ids: number[]): Promise<boolean> => {
    try {
        const response = await axios.delete<ApiResponse<{ deleted: boolean }>>(`${API_BASE_URL}/delete`, {
            data: { ids }
        });
        return response.data.success;
    } catch (e) {
        return handleApiError(e);
    }
};
