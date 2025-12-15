import { z, type ZodTypeAny } from 'zod';
import type { FormField, FormTab } from '@/components/GenericForm/types2';
import { Section } from '@/interfaces/section.interfaces';

export const createSectionInfoFields = (section?: Section): FormField[] => [
    {
        name: 'name',
        label: 'Nombre',
        type: 'text',
        required: true,
        cols: 6,
        placeholder: 'Ej: Nombre',
        validation: (zod: typeof z): ZodTypeAny => zod.string().min(1, { message: 'El nombre es requerido.' })
    },
    {
        name: 'level_id',
        label: 'Nivel',
        type: 'select',
        required: true,
        cols: 6,
        placeholder: 'Seleccione un nivel',
        style: {
            inputClass: 'w-full',
            containerClass: 'mb-4'
        },
        options: [],
        config: {
            endpoint: '/levels',
            optionLabel: 'name',
            optionValue: 'id',
            searchable: true,
            multiple: false,
            allowAddNew: false,
            addNewText: 'Nuevo',
            itemsPerPage: 4
        },
        validation: (zod: typeof z) => zod.number().positive('Seleccione una opción')
    }
];

export const formTabs = (section?: Section): FormTab[] => [
    {
        name: 'info',
        label: 'Información',
        fields: createSectionInfoFields(section),
        colsPerRow: 12
    }
];

export const flatFields = (section?: Section): FormField[] => [...createSectionInfoFields(section)];

// Versión para show (sin opciones ni validaciones)
export const getShowFields = (): FormField[] => [
    {
        name: 'name',
        label: 'Nombres'
    },
    {
        name: 'level',
        label: 'Nivel'
    }
];
