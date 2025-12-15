import { z, type ZodTypeAny } from 'zod';
import type { FieldConfig, FormField, FormTab } from '@/components/GenericForm/types2';
import { Enrollment } from '@/interfaces/enrollment.interfaces';

export const createEnrollmentInfoFields = (enrollment?: Enrollment): FormField[] => [
    {
        name: 'student_id',
        label: 'Nombre del estudiante',
        type: 'select',
        required: true,
        cols: 6,
        placeholder: 'Seleccione un estudiante',
        style: {
            inputClass: 'w-full',
            containerClass: 'mb-4'
        },
        options: [],
        config: {
            endpoint: '/students',
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
        name: 'school_year_id',
        label: 'Año Escolar',
        type: 'select',
        required: true,
        cols: 6,
        placeholder: 'Seleccione un año escolar',
        style: {
            inputClass: 'w-full',
            containerClass: 'mb-4'
        },
        options: [],
        config: {
            endpoint: '/school_years',
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
    },
    {
        name: 'classroom_id',
        label: 'Aula',
        type: 'select',
        required: true,
        cols: 6,
        placeholder: 'Seleccione una aula',
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
        name: 'age',
        label: 'Edad Actual',
        type: 'number',
        required: true,
        cols: 4,
        placeholder: 'Ej: 5',
        style: {
            inputClass: 'w-full',
            containerClass: 'mb-10'
        }
    },
    {
        name: 'shirt_size',
        label: 'Talla de camiseta',
        type: 'number',
        required: true,
        cols: 4,
        placeholder: 'Ej: 12',
        style: {
            inputClass: 'w-full',
            containerClass: 'mb-10'
        }
    },
    {
        name: 'pants_size',
        label: 'Talla de pantalones',
        type: 'number',
        required: true,
        cols: 4,
        placeholder: 'Ej: 20',
        style: {
            inputClass: 'w-full',
            containerClass: 'mb-10'
        }
    },
    {
        name: 'shoe_size',
        label: 'Talla de zapatos',
        type: 'number',
        required: true,
        cols: 4,
        placeholder: 'Ej: 20',
        style: {
            inputClass: 'w-full',
            containerClass: 'mb-10'
        }
    },
    {
        name: 'brachial_circumference',
        label: 'Circunferencia de bracio',
        type: 'number',
        required: true,
        cols: 4,
        placeholder: 'Ej: 20',
        style: {
            inputClass: 'w-full',
            containerClass: 'mb-10'
        }
    },
    {
        name: 'observations',
        label: 'Observaciones',
        type: 'textarea',
        required: true,
        cols: 4,
        placeholder: 'Ej: El niño no ha tenido problemas de salud',
        style: {
            inputClass: 'w-full',
            containerClass: 'mb-4'
        },
        validation: (zod: typeof z) => zod.string().min(10, 'Mínimo 10 caracteres')
    }
];

export const createRepresentativeInfoFields = (enrollment?: Enrollment): FormField[] => [
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
        validation: (zod: typeof z) => zod.date().max(new Date(), 'Fecha no puede ser anterior')
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
    },
    {
        name: 'relationship_id',
        label: 'Parentesco',
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
            endpoint: '/relationships',
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
        name: 'occupation_id',
        label: 'Ocupación',
        type: 'select',
        required: true,
        cols: 4,
        placeholder: 'Seleccione una ocupación',
        style: {
            inputClass: 'w-full',
            containerClass: 'mb-4'
        },
        options: [],
        config: {
            endpoint: '/occupations',
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
        cols: 4,
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
    },
    {
        name: 'parish_id_work',
        label: 'Parroquia donde trabaja',
        type: 'select',
        required: false,
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
        name: 'sector_id_work',
        label: 'Sector donde trabaja',
        type: 'select',
        required: false,
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
        name: 'number_work',
        label: 'Número de oficina',
        type: 'text',
        required: false,
        cols: 3,
        placeholder: 'Ej: 12B',
        style: {
            inputClass: 'w-full',
            containerClass: 'mb-4'
        },
        validation: (zod: typeof z): ZodTypeAny => zod.string().min(1, { message: 'El número de residencia es requerido.' })
    },
    {
        name: 'address4',
        label: 'Dirección del trabajo',
        type: 'textarea',
        required: false,
        cols: 3,
        placeholder: 'Ej: Calle 200, Piso 2',
        style: {
            inputClass: 'w-full',
            containerClass: 'mb-4'
        },
        validation: (zod: typeof z): ZodTypeAny => zod.string().min(1, { message: 'La dirección es requerida.' })
    }
];

export const formTabs = (enrollment?: Enrollment): FormTab[] => [
    {
        name: 'info',
        label: 'Estudiante',
        fields: createEnrollmentInfoFields(enrollment),
        colsPerRow: 12
    },
    {
        name: 'representative',
        label: 'Representante',
        fields: createRepresentativeInfoFields(enrollment),
        colsPerRow: 12
    }
];

export const flatFields = (enrollment?: Enrollment): FormField[] => [...createEnrollmentInfoFields(enrollment), ...createRepresentativeInfoFields(enrollment)];

// Versión para show (sin opciones ni validaciones)
export const getShowFields = (): FormField[] => [
    {
        name: 'name',
        label: 'Nombres'
    },
    {
        name: 'description',
        label: 'Descripción'
    }
];
