// types.ts
import { z, type ZodTypeAny } from 'zod';

export type FieldType =
    | 'text'
    | 'number'
    | 'email'
    | 'password'
    | 'textarea'
    | 'select'
    | 'autocomplete'
    | 'checkbox'
    | 'radio'
    | 'date'
    | 'file'
    | 'toggle'
    | 'multiselect'
    | 'color'
    | 'range'
    | 'rating'
    | 'switch'
    | 'time'
    | 'currency'
    | 'phone'
    | 'url'
    | 'richtext'
    | 'cascade'
    | 'inputgroup'
    | 'inputmask';

export type FieldSize = 'small' | 'normal' | 'large';
export type LabelType = 'normal' | 'float';
export type ButtonVariant = 'primary' | 'secondary' | 'success' | 'info' | 'warning' | 'danger' | 'help';
export type LayoutType = 'vertical' | 'horizontal' | 'grid';

export interface FieldOption {
    label: string;
    value: string | number | boolean | object | null;
    icon?: string;
    disabled?: boolean;
    options?: any[];
    items?: FieldOption[];
}

export interface InputGroupAddon {
    type: 'text' | 'icon' | 'button';
    content: string;
    icon?: string;
    buttonVariant?: ButtonVariant;
    onClick?: () => void;
}

export interface StyleConfig {
    containerClass?: string;
    labelClass?: string;
    inputClass?: string;
    errorClass?: string;
    helpText?: string;
    helpTextClass?: string;
}

export interface FieldConfig {
    rows?: number;
    min?: number;
    max?: number;
    step?: number;
    accept?: string;
    multiple?: boolean;
    currency?: string;
    locale?: string;
    stars?: number;
    cancel?: boolean;
    mask?: string;
    slotChar?: string;
    autoClear?: boolean;
    feedback?: boolean;
    toggleMask?: boolean;
    optionLabel?: string;
    optionValue?: string;
    optionGroupLabel?: string;
    optionGroupChildren?: string;
    maxSize?: number;
    dateFormat?: string;
    hourFormat?: string;
    asSwitch?: boolean;
    binary?: boolean;
    onLabel?: string;
    offLabel?: string;
    formatColor?: 'hex' | 'rgb' | 'hsb';
    heightEditor?: string;
    minFractionDigits?: number;
    maxFractionDigits?: number;
    chooseLabelFileUpload?: string;
    uploadLabelFileUpload?: string;
    cancelLabelFileUpload?: string;
    remoteSearch?: (query: string) => Promise<FieldOption[]> | FieldOption[];
    remoteOptions?: () => Promise<FieldOption[]> | FieldOption[];
}

export interface FormField {
    name: string;
    label: string;
    type?: FieldType;
    placeholder?: string;
    required?: boolean;
    disabled?: boolean;
    readonly?: boolean;
    defaultValue?: any;
    options?: FieldOption[];
    cols?: number; // Columnas que ocupa DENTRO del grid de su sección/tab/layout plano
    size?: FieldSize;
    labelType?: LabelType;
    style?: StyleConfig;
    inputGroup?: {
        before?: InputGroupAddon[];
        after?: InputGroupAddon[];
    };
    config?: FieldConfig;
    dependsOn?: {
        field: string;
        value: any;
        action: 'show' | 'hide' | 'enable' | 'disable';
    };
    validation?: (zod: typeof z) => ZodTypeAny;
    onChange?: (value: any, formValues: Record<string, any>) => void;
    relation?: string;
    relationField?: string;
}

// INTERFAZ para una Pestaña del Formulario
export interface FormTab {
    name: string; // Identificador único de la pestaña
    label: string; // Título visible en la pestaña
    fields: FormField[]; // Array de campos que pertenecen a esta pestaña
    colsPerRow?: number; // Opcional: Número de columnas para el layout grid DENTRO de ESTA pestaña
    description?: string; // Descripción específica de la pestaña (opcional)
}

// INTERFAZ para la Configuración del Formulario
export interface FormConfig {
    title?: string;
    description?: string;
    // Prioridad 1: Si se proporciona, renderiza con pestañas
    tabs?: FormTab[];
    // Prioridad 2: Si no hay pestañas pero sí campos planos, renderiza plano
    fields?: FormField[];

    submitButtonText?: string;
    submitAndStayText?: string;
    cancelButtonText?: string;
    layout?: LayoutType; // layout global (puede usarse para definir el contenedor principal o layout por defecto)
    colsPerRow?: number; // Número de columnas por defecto (para pestañas sin su propio colsPerRow O para layout plano)
    size?: FieldSize;
    labelType?: LabelType;
    style?: {
        formClass?: string; // Clase para el div contenedor del formulario
        titleClass?: string;
        descriptionClass?: string;
        rowClass?: string; // Aplicado a los contenedores de fila del grid
        fieldClass?: string; // Aplicado al contenedor de cada DynamicField
        actionsClass?: string;
        submitButtonClass?: string;
        cancelButtonClass?: string;
        submitAndStayButtonClass?: string;
    };
    onSubmit?: (data: Record<string, any>) => Promise<void> | void;
    onCancel?: () => void;
    redirectOnSubmit?: string;
    beforeSubmit?: (data: Record<string, any>) => Record<string, any> | Promise<Record<string, any>>;
    afterSubmit?: (result: { success: boolean; error?: any; action?: 'submit' | 'stay' }) => void;
    initialValues?: Record<string, any>;
}

export type FormErrors = Record<string, string | string[] | undefined>;

export { z };
