import { z, type ZodTypeAny } from 'zod';
import type { FormField, FormTab } from '@/components/GenericForm/types2';
import { User } from '@/interfaces/user.interfaces';

export const createUserInfoFields = (user?: User): FormField[] => [
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
        name: 'email',
        label: 'Email',
        type: 'email',
        required: true,
        cols: 6,
        placeholder: 'Ej: email@ejemplo.com',
        validation: (zod: typeof z): ZodTypeAny => zod.string().email({ message: 'El email es invalido.' })
    },
    {
        name: 'role',
        label: 'Rol',
        type: 'select',
        required: true,
        cols: 6,
        placeholder: 'Seleccione un rol',
        style: {
            inputClass: 'w-full',
            containerClass: 'mb-4'
        },
        options: [],
        config: {
            endpoint: '/roles',
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
        name: 'password',
        label: 'Contraseña',
        type: 'password',
        required: true,
        cols: 6,
        placeholder: 'Contraseña',
        validation: (zod: typeof z): ZodTypeAny => zod.string().min(8, { message: 'La contraseña es muy corta.' })
    },
    {
        name: 'password_confirmation',
        label: 'Confirmar Contraseña',
        type: 'password',
        required: true,
        cols: 6,
        placeholder: 'Confirmar Contraseña',
        validation: (zod: typeof z): ZodTypeAny => zod.string().min(8, { message: 'La contraseña es muy corta.' })
    }
];

export const formTabs = (user?: User): FormTab[] => [
    {
        name: 'info',
        label: 'Información',
        fields: createUserInfoFields(user),
        colsPerRow: 12
    }
];

export const flatFields = (user?: User): FormField[] => [...createUserInfoFields(user)];

// Versión para show (sin opciones ni validaciones)
export const getShowFields = (): FormField[] => [
    {
        name: 'first_name',
        label: 'Nombres'
    },
    {
        name: 'last_name',
        label: 'Apellido'
    },
    {
        name: 'email',
        label: 'Email'
    },
    {
        name: 'status',
        label: 'Estado'
    },
    {
        name: 'created_at',
        label: 'Fecha de Creación'
    },
    {
        name: 'updated_at',
        label: 'Fecha de Actualización'
    },
    {
        name: 'soft_deleted_at',
        label: 'Fecha de Borrado'
    }
];
