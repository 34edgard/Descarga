import { useAuthStore } from '@/store/authStore';
import axios from 'axios';

// Configuración base de Axios
const api = axios.create({
    baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8000/api',
    headers: {
        Accept: 'application/json',
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    },
    withCredentials: true
});

// Variable para evitar múltiples redirecciones
let isRedirecting = false;

// Interceptor para añadir el token de autenticación
api.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('token');
        console.log('token', token);
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Interceptor para manejar errores globales
api.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        if (error.response?.status === 401 && !isRedirecting) {
            // Evitar redirección si ya estamos en login
            if (!window.location.pathname.includes('/login')) {
                isRedirecting = true;
                // Manejar logout cuando el token expire
                const authStore = useAuthStore();
                authStore.logout();
            }
        }
        return Promise.reject(error);
    }
);

export default api;
