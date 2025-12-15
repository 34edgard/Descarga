import axios from '@/plugins/axios';
import { handleApiError } from '@/utils/errorHandler';
import { Enrollment, EnrollmentFormData } from '@/interfaces/enrollment.interfaces';
import { ApiResponse, PaginatedResponse } from '@/interfaces/shared.interfaces';

const API_BASE_URL = '/enrollments';

// Tipos para requests con FormData
interface CreateEnrollmentRequest extends Omit<EnrollmentFormData, 'id'> {
    photo?: File;
}

interface UpdateEnrollmentRequest extends Partial<EnrollmentFormData> {
    id: number;
    photo?: File;
}

export const getEnrollments = async (): Promise<Enrollment[]> => {
    try {
        const response = await axios.get<ApiResponse<Enrollment[]>>(API_BASE_URL);
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const getEnrollmentsPaginated = async (filters: any): Promise<PaginatedResponse<Enrollment>> => {
    try {
        const response = await axios.get<ApiResponse<PaginatedResponse<Enrollment>>>(`${API_BASE_URL}/paginated`, { params: filters });
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const getEnrollment = async (id: number): Promise<Enrollment> => {
    try {
        const response = await axios.get<ApiResponse<Enrollment>>(`${API_BASE_URL}/${id}`);
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const createEnrollment = async (data: CreateEnrollmentRequest): Promise<Enrollment> => {
    try {
        const formData = new FormData();

        // Agregar campos normales
        Object.keys(data).forEach((key) => {
            if (key !== 'photo' && data[key as keyof CreateEnrollmentRequest] !== undefined) {
                const value = data[key as keyof CreateEnrollmentRequest];
                if (value !== null && value !== undefined) {
                    formData.append(key, value instanceof Blob ? value : String(value));
                }
            }
        });

        // Agregar foto si existe
        if (data.photo) {
            formData.append('photo', data.photo);
        }

        const response = await axios.post<ApiResponse<Enrollment>>(API_BASE_URL, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const updateEnrollment = async (id: number, data: UpdateEnrollmentRequest): Promise<Enrollment> => {
    try {
        const formData = new FormData();

        // Agregar campos normales
        Object.keys(data).forEach((key) => {
            if (key !== 'photo' && key !== 'id' && data[key as keyof UpdateEnrollmentRequest] !== undefined) {
                const value = data[key as keyof UpdateEnrollmentRequest];
                if (value !== null && value !== undefined) {
                    formData.append(key, value instanceof Blob ? value : String(value));
                }
            }
        });

        // Agregar foto si existe
        if (data.photo) {
            formData.append('photo', data.photo);
        }

        const response = await axios.put<ApiResponse<Enrollment>>(`${API_BASE_URL}/${id}`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const deleteEnrollment = async (id: number): Promise<boolean> => {
    try {
        const response = await axios.delete<ApiResponse<{ deleted: boolean }>>(`${API_BASE_URL}/${id}`);
        return response.data.success;
    } catch (e) {
        return handleApiError(e);
    }
};

export const deleteEnrollmentMultiple = async (ids: number[]): Promise<boolean> => {
    try {
        const response = await axios.delete<ApiResponse<{ deleted: boolean }>>(`${API_BASE_URL}/delete`, {
            data: { ids }
        });
        return response.data.success;
    } catch (e) {
        return handleApiError(e);
    }
};
