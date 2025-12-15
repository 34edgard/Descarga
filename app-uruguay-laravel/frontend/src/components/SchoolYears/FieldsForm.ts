import { z, type ZodTypeAny } from 'zod';
import type { FormField, FormTab } from '@/components/GenericForm/types2';
import { SchoolYear } from '@/interfaces/school_year.interfaces';
import { FieldConfig } from '../GenericForm/types2';

export const createSchoolYearInfoFields = (school_year?: SchoolYear): FormField[] => [
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
        name: 'is_current',
        label: '¿Es Actual?',
        type: 'single-switch',
        defaultValue: false,
        cols: 6,
        style: {
            inputClass: 'w-full',
            containerClass: 'mb-4'
        },
        config: {
            description: 'Indica si el periodo es actual',
            confirmOnDeactivate: true
        }
    },
    {
        name: 'start_date',
        label: 'Fecha de Inicio',
        type: 'date',
        required: true,
        cols: 6,
        placeholder: 'Seleccione fecha de inicio',
        style: {
            inputClass: 'w-full',
            containerClass: 'mb-4'
        },
        config: {
            dateFormat: 'dd/mm/yy',
            showIcon: true
        } as FieldConfig,
        validation: (zod: typeof z) => zod.date().min(new Date(), 'Fecha no puede ser anterior')
    },
    {
        name: 'end_date',
        label: 'Fecha de Fin',
        type: 'date',
        required: true,
        cols: 6,
        placeholder: 'Seleccione fecha de fin',
        style: {
            inputClass: 'w-full',
            containerClass: 'mb-4'
        },
        config: {
            dateFormat: 'dd/mm/yy',
            showIcon: true
        } as FieldConfig,
        validation: (zod: typeof z) => zod.date().min(new Date(), 'Fecha no puede ser anterior')
    }
];

export const formTabs = (school_year?: SchoolYear): FormTab[] => [
    {
        name: 'info',
        label: 'Información',
        fields: createSchoolYearInfoFields(school_year),
        colsPerRow: 12
    }
];

export const flatFields = (school_year?: SchoolYear): FormField[] => [...createSchoolYearInfoFields(school_year)];

// Versión para show (sin opciones ni validaciones)
export const getShowFields = (): FormField[] => [
    {
        name: 'name',
        label: 'Nombres'
    },
    {
        name: 'status',
        label: 'Activo'
    },
    {
        name: 'start_date',
        label: 'Fecha de Inicio'
    },
    {
        name: 'end_date',
        label: 'Fecha de Fin'
    },
    {
        name: 'created_at',
        label: 'Fecha de Creación'
    },
    {
        name: 'updated_at',
        label: 'Fecha de Actualización'
    }
];
