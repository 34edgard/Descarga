import { z, type ZodTypeAny } from 'zod';
import type { FormField, FormTab } from '@/components/GenericForm/types';
import { Representative } from '@/interfaces/representative.interfaces';

export const createRepresentativeInfoFields = (representative?: Representative): FormField[] => [
    {
        name: 'name',
        label: 'Nombre',
        type: 'text',
        required: true,
        cols: 6,
        placeholder: 'Ej: Nombre',
        validation: (zod: typeof z): ZodTypeAny =>
            zod.string().min(1, { message: 'El nombre es requerido.' })
    },
    {
        name: 'description',
        label: 'Descripción',
        type: 'textarea',
        placeholder: 'Escribe una descripción',
        required: true,
        cols: 6,
        style: { inputClass: 'w-full' },
        validation: (zod: typeof z): ZodTypeAny => 
            zod
                .string()
                .min(1, { message: 'La descripción es requerida.' })
                .max(500, { message: 'La descripción no debe exceder 500 caracteres.' })
    }
];

export const formTabs = (representative?: Representative): FormTab[] => [
        {
            name: 'info',
            label: 'Información',
            fields: createRepresentativeInfoFields(representative),
            colsPerRow: 12
        },
    ];

export const flatFields = (representative?: Representative): FormField[] => [
    ...createRepresentativeInfoFields(representative),
];

// Versión para show (sin opciones ni validaciones)
export const getShowFields = (): FormField[] => [
    {
        name: 'name',
        label: 'Nombres'
    },
    {
        name: 'description',
        label: 'Descripción'
    },
];