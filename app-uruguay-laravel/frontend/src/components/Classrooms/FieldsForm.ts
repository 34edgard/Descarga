import { z, type ZodTypeAny } from 'zod';
import type { FormField, FormTab } from '@/components/GenericForm/types2';
import { Classroom } from '@/interfaces/classroom.interfaces';

export const createClassroomInfoFields = (classroom?: Classroom): FormField[] => [
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
        name: 'section_id',
        label: 'Sección',
        type: 'select',
        required: true,
        cols: 6,
        placeholder: 'Seleccione un sección',
        style: {
            inputClass: 'w-full',
            containerClass: 'mb-4'
        },
        options: [],
        config: {
            endpoint: '/sections',
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

export const formTabs = (classroom?: Classroom): FormTab[] => [
    {
        name: 'info',
        label: 'Información',
        fields: createClassroomInfoFields(classroom),
        colsPerRow: 12
    }
];

export const flatFields = (classroom?: Classroom): FormField[] => [...createClassroomInfoFields(classroom)];

// Versión para show (sin opciones ni validaciones)
export const getShowFields = (): FormField[] => [
    {
        name: 'name',
        label: 'Nombres'
    },
    {
        name: 'section',
        label: 'Sección'
    }
];
