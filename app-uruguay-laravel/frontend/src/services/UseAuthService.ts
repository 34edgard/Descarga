import api from '@/plugins/axios';
import { AuthCredentials } from '@/interfaces';
import { AuthenticationError, ConnectionError } from '@/errors';

export const login = async (credentials: AuthCredentials) => {
    await api.get('/csrf-cookie');

    try {
        const response = await api.post('/login', credentials);
        return response.data;
    } catch (e) {
        if (e.response.status === 422) {
            return Promise.reject(new AuthenticationError(e.response.data.message, e.response.data.errors));
        }
        return Promise.reject(new ConnectionError('Error de Coneccion'));
    }
};

export const logout = async () => {
    try {
        await api.post('/logout');
    } catch (e) {
        if (e.response.status !== 401) {
            return Promise.reject(new ConnectionError('Error de Coneccion'));
        }
    }
};
