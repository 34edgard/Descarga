import { z, type ZodTypeAny } from 'zod';
import type { FieldConfig, FormField, FormTab } from '@/components/GenericForm/types2';
import { Teacher } from '@/interfaces/teacher.interfaces';

export const createTeacherInfoFields = (teacher?: Teacher): FormField[] => [
    {
        name: 'first_name',
        label: 'Nombre',
        type: 'text',
        required: true,
        cols: 4,
        placeholder: 'Ej: Nombre',
        validation: (zod: typeof z): ZodTypeAny => zod.string().min(1, { message: 'El nombre es requerido.' })
    },
    {
        name: 'last_name',
        label: 'Apellido',
        type: 'text',
        required: true,
        cols: 4,
        placeholder: 'Ej: Apellido',
        validation: (zod: typeof z): ZodTypeAny => zod.string().min(1, { message: 'El apellido es requerido.' })
    },
    {
        name: 'id_number',
        label: 'Cédula de Identidad',
        type: 'text',
        required: true,
        cols: 4,
        placeholder: 'Ej: 0801199901234',
        style: {
            inputClass: 'w-full',
            containerClass: 'mb-10',
            helpText: 'Ingrese su número de identidad único'
        },
        validation: (zod: typeof z) => zod.string().min(6, 'Debe tener exactamente (13) caracteres').regex(/^\d+$/, 'Solo se permiten números')
    },
    {
        name: 'birth_date',
        label: 'Fecha de Nacimiento',
        type: 'date',
        required: true,
        cols: 4,
        placeholder: 'Seleccione fecha de nacimiento',
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
        name: 'gender',
        label: 'Género',
        type: 'select',
        required: true,
        cols: 4,
        placeholder: 'Seleccione género',
        style: {
            inputClass: 'w-full',
            containerClass: 'mb-4'
        },
        options: [
            { value: 'male', label: 'Masculino' },
            { value: 'female', label: 'Femenino' }
        ],
        validation: (zod: typeof z) => zod.enum(['male', 'female'])
    },
    {
        name: 'education_level_id',
        label: 'Nivel académico',
        type: 'select',
        required: true,
        cols: 4,
        placeholder: 'Seleccione un nivel',
        style: {
            inputClass: 'w-full',
            containerClass: 'mb-4'
        },
        options: [],
        config: {
            endpoint: '/education_levels',
            optionValue: 'id',
            searchable: true,
            multiple: false,
            allowAddNew: false,
            addNewText: 'Nuevo',
            itemsPerPage: 4
        },
        validation: (zod: typeof z) => zod.number().positive('Seleccione una opción')
    },
    {
        name: 'phones',
        label: 'Teléfono',
        type: 'text',
        required: true,
        cols: 6,
        placeholder: 'Ej: 22223333',
        style: {
            inputClass: 'w-full',
            containerClass: 'mb-4'
        },
        config: {
            mask: '9999-9999'
        } as FieldConfig,
        validation: (zod: typeof z) => zod.string().min(8, 'Mínimo 8 dígitos').regex(/^\d+$/, 'Solo números')
    },
    {
        name: 'classroom_id',
        label: 'Aula',
        type: 'select',
        required: true,
        cols: 6,
        placeholder: 'Seleccione un aula',
        style: {
            inputClass: 'w-full',
            containerClass: 'mb-4'
        },
        options: [],
        config: {
            endpoint: '/classrooms',
            optionValue: 'id',
            searchable: true,
            multiple: false,
            allowAddNew: false,
            addNewText: 'Nuevo',
            itemsPerPage: 4
        },
        validation: (zod: typeof z) => zod.number().positive('Seleccione una opción')
    },

    {
        name: 'parish_id_home',
        label: 'Parroquia',
        type: 'select',
        required: true,
        cols: 3,
        placeholder: 'Seleccione una parroquia',
        style: {
            inputClass: 'w-full',
            containerClass: 'mb-4'
        },
        options: [],
        config: {
            endpoint: '/parishes',
            optionValue: 'id',
            searchable: true,
            multiple: false,
            allowAddNew: false,
            addNewText: 'Nuevo',
            itemsPerPage: 4
        },
        validation: (zod: typeof z) => zod.number().positive('Seleccione una opción')
    },
    {
        name: 'sector_id_home',
        label: 'Sector',
        type: 'select',
        required: true,
        cols: 3,
        placeholder: 'Seleccione un sector',
        style: {
            inputClass: 'w-full',
            containerClass: 'mb-4'
        },
        options: [],
        config: {
            endpoint: '/sectors',
            optionValue: 'id',
            searchable: true,
            multiple: false,
            allowAddNew: false,
            addNewText: 'Nuevo',
            itemsPerPage: 4
        },
        validation: (zod: typeof z) => zod.number().positive('Seleccione una opción')
    },
    {
        name: 'number_home_home',
        label: 'Número de residencia',
        type: 'text',
        required: true,
        cols: 3,
        placeholder: 'Ej: 12B',
        style: {
            inputClass: 'w-full',
            containerClass: 'mb-4'
        },
        validation: (zod: typeof z): ZodTypeAny => zod.string().min(1, { message: 'El número de residencia es requerido.' })
    },
    {
        name: 'address_home',
        label: 'Dirección del habitación',
        type: 'textarea',
        required: true,
        cols: 3,
        placeholder: 'Ej: Calle 123, Piso 1',
        style: {
            inputClass: 'w-full',
            containerClass: 'mb-4'
        },
        validation: (zod: typeof z): ZodTypeAny => zod.string().min(1, { message: 'La dirección es requerida.' })
    }
];

export const formTabs = (teacher?: Teacher): FormTab[] => [
    {
        name: 'info',
        label: 'Información',
        fields: createTeacherInfoFields(teacher),
        colsPerRow: 12
    }
];

export const flatFields = (teacher?: Teacher): FormField[] => [...createTeacherInfoFields(teacher)];

// Versión para show (sin opciones ni validaciones)
export const getShowFields = (): FormField[] => [
    { name: 'first_name', label: 'Nombres' },
    { name: 'last_name', label: 'Apellido' },
    { name: 'id_number', label: 'Cédula de Identidad' },
    { name: 'birth_date', label: 'Fecha de Nacimiento' },
    { name: 'gender', label: 'Género' },
    { name: 'education_level', label: 'Nivel de Educación' }
];
