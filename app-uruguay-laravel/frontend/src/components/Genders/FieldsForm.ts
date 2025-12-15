import { z, type ZodTypeAny } from 'zod';
import type { FormField, FormTab } from '@/components/GenericForm/types2';

export const createPersonalInfoFields = (): FormField[] => [
    {
        name: 'name',
        label: 'Nombre',
        type: 'text',
        required: true,
        style: {
            containerClass: 'w-1/2'
        },
        cols: 12,
        placeholder: 'Ej: Nombre completo',
        validation: (zod: typeof z): ZodTypeAny => zod.string().min(1, { message: 'El nombre es requerido.' })
    }
];

export const formTabs = (): FormTab[] => [
    {
        name: 'info',
        label: 'Información',
        fields: createPersonalInfoFields(),
        colsPerRow: 12
    }
];

export const flatFields = (): FormField[] => [...createPersonalInfoFields()];

// Versión para show (sin opciones ni validaciones)
export const getShowFields = (): FormField[] => [
    {
        name: 'name',
        label: 'Nombre'
    }
];
