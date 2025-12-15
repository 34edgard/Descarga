import api from '@/plugins/axios';
import { handleApiError } from '@/utils/errorHandler';
import { User, UserFormData } from '@/interfaces/user.interfaces';
import { ApiResponse, PaginatedResponse } from '@/interfaces/shared.interfaces';

const API_BASE_URL = '/users';

// Tipos para requests con FormData
interface CreateUserRequest extends Omit<UserFormData, 'id'> {
    photo?: File;
}

interface UpdateUserRequest extends Partial<UserFormData> {
    id: number;
    photo?: File;
}

export const getUsers = async (): Promise<User[]> => {
    try {
        const response = await api.get<ApiResponse<User[]>>(API_BASE_URL);
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const getUsersPaginated = async (filters: any): Promise<PaginatedResponse<User>> => {
    try {
        const response = await api.get<ApiResponse<PaginatedResponse<User>>>(`${API_BASE_URL}/paginated`, { params: filters });
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const getUser = async (id: number): Promise<User> => {
    try {
        const response = await api.get<ApiResponse<User>>(`${API_BASE_URL}/${id}`);
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const createUser = async (data: CreateUserRequest): Promise<User> => {
    try {
        const formData = new FormData();

        // Agregar campos normales
        Object.keys(data).forEach((key) => {
            if (key !== 'photo' && data[key as keyof CreateUserRequest] !== undefined) {
                const value = data[key as keyof CreateUserRequest];
                if (value !== null && value !== undefined) {
                    formData.append(key, value instanceof Blob ? value : String(value));
                }
            }
        });

        // Agregar foto si existe
        if (data.photo) {
            formData.append('photo', data.photo);
        }

        const response = await api.post<ApiResponse<User>>(API_BASE_URL, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const updateUser = async (id: number, data: UpdateUserRequest): Promise<User> => {
    try {
        const formData = new FormData();

        // Agregar campos normales
        Object.keys(data).forEach((key) => {
            if (key !== 'photo' && key !== 'id' && data[key as keyof UpdateUserRequest] !== undefined) {
                const value = data[key as keyof UpdateUserRequest];
                if (value !== null && value !== undefined) {
                    formData.append(key, value instanceof Blob ? value : String(value));
                }
            }
        });

        // Agregar foto si existe
        if (data.photo) {
            formData.append('photo', data.photo);
        }

        const response = await api.put<ApiResponse<User>>(`${API_BASE_URL}/${id}`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const deleteUser = async (id: number): Promise<boolean> => {
    try {
        const response = await api.delete<ApiResponse<{ deleted: boolean }>>(`${API_BASE_URL}/${id}`);
        return response.data.success;
    } catch (e) {
        return handleApiError(e);
    }
};

export const deleteUserMultiple = async (ids: number[]): Promise<boolean> => {
    try {
        const response = await api.delete<ApiResponse<{ deleted: boolean }>>(`${API_BASE_URL}/delete`, {
            data: { ids }
        });
        return response.data.success;
    } catch (e) {
        return handleApiError(e);
    }
};
