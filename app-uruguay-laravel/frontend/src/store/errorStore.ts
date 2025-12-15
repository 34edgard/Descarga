// stores/errorStore.ts
import { defineStore } from 'pinia';
import { ref } from 'vue';
import type { AxiosError } from 'axios';
import { useToast } from 'primevue/usetoast';

interface ValidationError {
    field: string;
    message: string;
}

export const useErrorStore = defineStore('error', () => {
    const toast = useToast();
    const validationErrors = ref<ValidationError[]>([]);
    const generalError = ref<string | null>(null);

    const handleApiError = (error: AxiosError) => {
        resetErrors();

        if (!error.response) {
            generalError.value = 'No se pudo conectar con el servidor. Verifique su conexión a internet.';
            showToast('error', 'Error de conexión', generalError.value);
            return;
        }

        const status = error.response.status;
        const data = error.response.data as any;

        switch (status) {
            case 422:
                // Manejar errores de validación
                if (data.errors) {
                    for (const [field, messages] of Object.entries(data.errors as Record<string, string[]>)) {
                        for (const message of messages) {
                            validationErrors.value.push({ field, message });
                        }
                    }
                }
                showToast('error', 'Error de validación', data.message || 'Por favor corrija los errores en el formulario');
                break;

            case 401:
                generalError.value = data.message || 'No autorizado. Por favor inicie sesión.';
                showToast('error', 'Autenticación requerida', generalError.value);
                break;

            case 403:
                generalError.value = data.message || 'No tiene permisos para realizar esta acción.';
                showToast('error', 'Permiso denegado', generalError.value);
                break;

            case 404:
                generalError.value = data.message || 'Recurso no encontrado.';
                showToast('error', 'No encontrado', generalError.value);
                break;

            case 500:
                generalError.value = 'Error interno del servidor. Por favor intente más tarde.';
                showToast('error', 'Error del servidor', generalError.value);
                break;

            default:
                generalError.value = data.message || 'Ocurrió un error inesperado.';
                showToast('error', 'Error', generalError.value);
        }
    };

    const showToast = (severity: 'success' | 'info' | 'warn' | 'error', summary: string, detail: string) => {
        toast.add({
            severity,
            summary,
            detail,
            life: 5000
        });
    };

    const resetErrors = () => {
        validationErrors.value = [];
        generalError.value = null;
    };

    const getErrorsForField = (field: string): string[] => {
        return validationErrors.value.filter((e) => e.field === field).map((e) => e.message);
    };

    return {
        validationErrors,
        generalError,
        handleApiError,
        resetErrors,
        getErrorsForField
    };
});
