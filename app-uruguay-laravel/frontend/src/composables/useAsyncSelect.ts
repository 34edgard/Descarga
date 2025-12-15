// src/composables/useAsyncSelect.ts
import { ref } from 'vue';
import { useToast } from 'primevue/usetoast';

export interface SelectOption {
    label: string;
    value: any;
}

export function useAsyncSelect<T>(fetchFn: () => Promise<{ data: T[] }>, mapFn: (item: T) => SelectOption) {
    const options = ref<SelectOption[]>([]);
    const loading = ref(false);
    const error = ref<string | null>(null);
    const toast = useToast();

    const loadOptions = async () => {
        loading.value = true;
        error.value = null;
        options.value = [];

        try {
            const response = await fetchFn();

            if (!response.data || !Array.isArray(response.data)) {
                throw new Error('La respuesta no contiene un array válido');
            }

            options.value = response.data.map(mapFn);
            return options.value;
        } catch (err) {
            error.value = 'Error al cargar opciones';
            console.error('Error:', err);
            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: error.value,
                life: 3000
            });
            return [];
        } finally {
            loading.value = false;
        }
    };

    return {
        options,
        loading,
        error,
        loadOptions
    };
}
