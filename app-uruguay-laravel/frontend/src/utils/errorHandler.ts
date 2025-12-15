import { ValidationError, ConnectionError, AuthenticationError } from '@/interfaces/errors.interfaces';

export const handleApiError = (error: any) => {
    if (error.response) {
        // Error de validación (422)
        if (error.response.status === 422) {
            const { message, errors } = error.response.data;

            // Convertir el objeto de errores en un array de mensajes
            const errorMessages = Object.entries(errors).flatMap(([field, messages]) => {
                return Array.isArray(messages) ? messages.map((msg) => `${field}: ${msg}`) : [`${field}: ${messages}`];
            });

            throw new ValidationError(message, {
                rawErrors: errors,
                messages: errorMessages
            });
        }
        // Error de autenticación (401)
        else if (error.response.status === 401) {
            throw new AuthenticationError('No autorizado');
        }
        // Otros errores HTTP
        else {
            throw new Error(error.response.data.message || 'Error en la solicitud');
        }
    } else if (error.request) {
        // Error de conexión
        throw new ConnectionError('No se pudo conectar al servidor');
    } else {
        // Error inesperado
        throw new Error('Error inesperado');
    }
};
