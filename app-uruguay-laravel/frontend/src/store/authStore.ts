// stores/authStore.ts
import { defineStore } from 'pinia';
import { ref } from 'vue';
import api from '@/plugins/axios';
import type { User } from '@/types/auth';
import { useErrorStore } from './errorStore';

interface LoginData {
    email: string;
    password: string;
    remember?: boolean;
}

interface RegisterData {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
}

export const useAuthStore = defineStore('auth', () => {
    const user = ref<User | null>(null);
    const token = ref<string | null>(null);
    const isAuthenticated = ref(false);
    const errorStore = useErrorStore();
    const isLoading = ref(false);

    // Initialize from localStorage
    const initAuth = () => {
        const storedToken = localStorage.getItem('token');
        if (storedToken) {
            token.value = storedToken;
            isAuthenticated.value = true;
            api.defaults.headers.common['Authorization'] = `Bearer ${storedToken}`;
        }
    };

    // Get CSRF token from Sanctum (necesario para protección CSRF)
    const getCsrfToken = async () => {
        try {
            await api.get('/sanctum/csrf-cookie', {
                withCredentials: true
            });
        } catch (error) {
            console.error('Error getting CSRF token:', error);
            throw error;
        }
    };

    // Register user
    const register = async (data: RegisterData) => {
        try {
            await getCsrfToken();
            const response = await api.post('/auth/register', data);
            setAuth(response.data);
            return response.data;
        } catch (error: any) {
            errorStore.handleApiError(error);
            throw error;
        }
    };

    // Login user
    const login = async (data: LoginData) => {
        try {
            isLoading.value = true;
            await getCsrfToken();
            const response = await api.post('/auth/login', data, {
                withCredentials: true
            });
            setAuth(response.data.data);

            // Guardar remember me
            if (data.remember) {
                localStorage.setItem('remember', 'true');
            }

            return response.data;
        } catch (error: any) {
            errorStore.handleApiError(error);
            throw error;
        } finally {
            isLoading.value = false;
        }
    };

    // Logout user
    const logout = async () => {
        try {
            await api.post('/auth/logout');
            clearAuth();
        } catch (error: any) {
            errorStore.handleApiError(error);
            clearAuth();
            throw error;
        }
    };

    // Fetch user data
    const fetchUser = async () => {
        try {
            const response = await api.get('/auth/user');
            user.value = response.data.data;
            return response.data;
        } catch (error: any) {
            errorStore.handleApiError(error);
            clearAuth();
            throw error;
        }
    };

    // Set auth data
    const setAuth = (data: { user: User; token: string }) => {
        user.value = data.user;
        token.value = data.token;
        isAuthenticated.value = true;
        localStorage.setItem('token', data.token);
        api.defaults.headers.common['Authorization'] = `Bearer ${data.token}`;
    };

    // Clear auth data
    const clearAuth = () => {
        user.value = null;
        token.value = null;
        isAuthenticated.value = false;
        localStorage.removeItem('token');
        localStorage.removeItem('remember');
        delete api.defaults.headers.common['Authorization'];
    };

    return {
        isLoading,
        user,
        token,
        isAuthenticated,
        initAuth,
        getCsrfToken,
        register,
        login,
        logout,
        fetchUser,
        clearAuth
    };
});
