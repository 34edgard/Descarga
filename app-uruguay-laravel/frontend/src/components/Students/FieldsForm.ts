import { z, type ZodTypeAny } from 'zod';
import type { FieldConfig, FormField, FormTab } from '@/components/GenericForm/types2';
import { Student } from '@/interfaces/student.interfaces';

export const createStudentInfoFields = (student?: Student): FormField[] => [
    {
        name: 'first_name',
        label: 'Nombre',
        type: 'text',
        required: true,
        cols: 6,
        placeholder: 'Ej: Nombre',
        validation: (zod: typeof z): ZodTypeAny => zod.string().min(1, { message: 'El nombre es requerido.' })
    },
    {
        name: 'last_name',
        label: 'Apellido',
        type: 'text',
        required: true,
        cols: 6,
        placeholder: 'Ej: Apellido',
        validation: (zod: typeof z): ZodTypeAny => zod.string().min(1, { message: 'El apellido es requerido.' })
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
        validation: (zod: typeof z) => zod.date().max(new Date(), 'Fecha no puede ser anterior')
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
        name: 'nationality_id',
        label: 'Nacionalidad',
        type: 'select',
        required: true,
        cols: 4,
        placeholder: 'Seleccione un hogar',
        style: {
            inputClass: 'w-full',
            containerClass: 'mb-4'
        },
        options: [],
        config: {
            endpoint: '/nationalities',
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

export const createStudentAdditionalInfoFields = (student?: Student): FormField[] => [
    {
        name: 'state_id',
        label: 'Estado',
        type: 'select',
        required: true,
        cols: 4,
        placeholder: 'Seleccione un estado',
        style: {
            inputClass: 'w-full',
            containerClass: 'mb-4'
        },
        options: [],
        config: {
            endpoint: '/states',
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
        name: 'municipality_id',
        label: 'Municipio',
        type: 'select',
        required: true,
        cols: 4,
        placeholder: 'Seleccione un municipio',
        style: {
            inputClass: 'w-full',
            containerClass: 'mb-4'
        },
        options: [],
        config: {
            endpoint: '/municipalities',
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
        name: 'parish_id',
        label: 'Parroquia',
        type: 'select',
        required: true,
        cols: 4,
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
        name: 'sector_id',
        label: 'Sector',
        type: 'select',
        required: true,
        cols: 4,
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
        name: 'number_home',
        label: 'Número de residencia',
        type: 'text',
        required: false,
        cols: 4,
        placeholder: 'Ej: 12B',
        style: {
            inputClass: 'w-full',
            containerClass: 'mb-4'
        },
        validation: (zod: typeof z): ZodTypeAny => zod.string().min(1, { message: 'El número de residencia es requerido.' })
    },
    {
        name: 'address',
        label: 'Dirección',
        type: 'textarea',
        required: false,
        cols: 4,
        placeholder: 'Ej: Calle 123, Piso 1',
        style: {
            inputClass: 'w-full',
            containerClass: 'mb-4'
        },
        validation: (zod: typeof z): ZodTypeAny => zod.string().min(1, { message: 'La dirección es requerida.' })
    },
    {
        name: 'provenance_id',
        label: 'Procedencia',
        type: 'select',
        required: true,
        cols: 6,
        placeholder: 'Seleccione un hogar',
        style: {
            inputClass: 'w-full',
            containerClass: 'mb-4'
        },
        options: [],
        config: {
            endpoint: '/provenances',
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
        name: 'previous_school',
        label: 'Escuela Anterior',
        type: 'text',
        cols: 6,
        placeholder: 'Escribe la escuela anterior',
        validation: (zod: typeof z): ZodTypeAny => zod.string().min(1, { message: 'La escuela anterior es requerida.' })
    }
];

export const createStudentMedicalConditionFields = (student?: Student): FormField[] => [
    {
        name: 'medical_condition_id',
        label: 'Condición Médica',
        type: 'select',
        required: true,
        cols: 6,
        placeholder: 'Seleccione una condición médica',
        style: {
            inputClass: 'w-full',
            containerClass: 'mb-4'
        },
        options: [],
        config: {
            endpoint: '/medical_conditions',
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
        name: 'nutritional_status',
        label: 'Estado Nutricional',
        type: 'select',
        required: true,
        cols: 6,
        placeholder: 'Seleccione un estado nutricional',
        style: {
            inputClass: 'w-full',
            containerClass: 'mb-4'
        },
        options: [],
        config: {
            endpoint: '/nutritional_statuses',
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
        name: 'disability',
        label: 'Discapacidad',
        type: 'select',
        required: true,
        cols: 6,
        placeholder: 'Seleccione una discapacidad',
        style: {
            inputClass: 'w-full',
            containerClass: 'mb-4'
        },
        options: [],
        config: {
            endpoint: '/disabilities',
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

export const formTabs = (student?: Student): FormTab[] => [
    {
        name: 'info',
        label: 'Básicos',
        fields: createStudentInfoFields(student),
        colsPerRow: 12
    },
    {
        name: 'additional_info',
        label: 'Dirección',
        fields: createStudentAdditionalInfoFields(student),
        colsPerRow: 12
    },
    {
        name: 'medical_condition',
        label: 'Condición Médica',
        fields: createStudentMedicalConditionFields(student),
        colsPerRow: 12
    }
];

export const flatFields = (student?: Student): FormField[] => [...createStudentInfoFields(student), ...createStudentAdditionalInfoFields(student), ...createStudentMedicalConditionFields(student)];

// Versión para show (sin opciones ni validaciones)
export const getShowFields = (): FormField[] => [
    { name: 'first_name', label: 'Nombres' },
    { name: 'last_name', label: 'Apellido' },
    { name: 'birth_date', label: 'Fecha de Nacimiento' },
    { name: 'gender', label: 'Género' },
    { name: 'nationality', label: 'Nacionalidad' },
    { name: 'provenance', label: 'Hogar' },
    { name: 'medical_condition', label: 'Condición Médica' },
    { name: 'nutritional_status', label: 'Estado Nutricional' },
    { name: 'disability', label: 'Discapacidad' },
    { name: 'previous_school', label: 'Escuela Anterior' }
];
