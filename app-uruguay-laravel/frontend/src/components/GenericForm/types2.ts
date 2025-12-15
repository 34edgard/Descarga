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
    | 'inputmask'
    | 'dynamic-list'
    | 'checklist'
    | 'single-switch'
    | 'dynamic-list-search';

export type FieldSize = 'small' | 'normal' | 'large';
export type LabelType = 'normal' | 'float';
export type ButtonVariant = 'primary' | 'secondary' | 'success' | 'info' | 'warning' | 'danger' | 'help';
export type LayoutType = 'vertical' | 'horizontal' | 'grid';
export type ThemeType = 'light' | 'dark' | 'custom';

export interface FieldOption {
    label: string;
    value: string | number | boolean | object | null;
    icon?: string;
    disabled?: boolean;
    items?: FieldOption[];
}

export interface RelationWithDataConfig {
    // Configuración principal
    relationEndpoint: string; // Endpoint para obtener las opciones
    relationKey?: string; // Campo que relaciona con la otra tabla (default: '[tabla]_id')

    // Configuración de campos adicionales
    extraFields: Array<{
        name: string;
        label: string;
        type: FieldType;
        required?: boolean;
        validation?: (zod: typeof z) => ZodTypeAny;
        config?: any; // Config específica del tipo de campo
    }>;

    // Configuración de opciones
    optionLabel?: string;
    optionValue?: string;
    multiple?: boolean;
    minItems?: number;
    maxItems?: number;
}

export interface DynamicListConfig {
    searchPlaceholder?: string;
    searchable?: string;
    searchableFields?: string[];
    fields: FormField[];
    minItems?: number;
    maxItems?: number;
    addButtonText?: string;
    removeButtonText?: string;
    searchConfig?: {
        endpoint: string;
        searchFields: string[];
        displayField: string;
        secondaryField?: string;
        relatedField?: string;
        resultKey?: string;
        existingItemMessage?: string;
        noResultsMessage?: string;
        minQueryLength?: number;
        debounceTime?: number;
        relatedFieldLabel?: string;
    };
    readOnlyFields?: string[];
    minItemsByMode?: {
        create?: number;
        edit?: number;
        view?: number;
    };
    maxItemsByMode?: {
        create?: number | null; // null = ilimitado
        edit?: number | null; // null = ilimitado
        view?: number | null; // null = ilimitado
    };
    addButtonTextByMode?: {
        create?: string;
        edit?: string;
        view?: string;
    };
}

export interface SearchCreateListConfig {
    searchEndpoint: string;
    searchFields: string[];
    optionLabel: string;
    optionValue: string;
    createFields: FormField[];
    addButtonText?: string;
    removeButtonText?: string;
    searchParams?: Record<string, any>;
    optionDescription?: string;
}

export interface SearchCreateListItem {
    [key: string]: any;
    _searchTerm?: string;
    _searchResults?: any[];
    _searchLoading?: boolean;
    _selected?: boolean;
    _creating?: boolean;
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

export interface SingleSwitchFieldConfig {
    name: string;
    label: string;
    description?: string;
    value?: boolean;
    required?: boolean;
    disabled?: boolean;
    confirmOnDeactivate?: boolean; // Si se debe mostrar confirmación al desactivar
    confirmMessage?: string; // Mensaje personalizado de confirmación
}

export interface FieldConfig {
    confirmMessage?: string;
    confirmOnDeactivate?: boolean;
    description?: string;
    relationName?: any;
    options?: any;
    relationEndpoint?: any;
    addNewModalTitle?: string;
    addNewPlaceholder?: string;
    itemsPerPage?: number;
    addNewText?: string;
    allowAddNew?: boolean;
    maxOptions?: number;
    searchable?: boolean;
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
    endpoint?: string;
    params?: Record<string, any>;
    dynamicList?: DynamicListConfig;
    preventDuplicates?: boolean;
    searchCreateList?: SearchCreateListConfig;
    remoteSearch?: (query: string) => Promise<FieldOption[]> | FieldOption[];
    remoteOptions?: () => Promise<FieldOption[]> | FieldOption[];
}

export interface UniqueValidationConfig {
    /** URL del endpoint para validar unicidad */
    url: string;
    /** Método HTTP a usar (default: POST) */
    method?: 'GET' | 'POST';
    /** Nombre del campo en la petición (default: 'value') */
    fieldName?: string;
    /** Tiempo de debounce en milisegundos (default: 500) */
    debounceTime?: number;
    /** Headers adicionales para la petición */
    headers?: Record<string, string>;
    /** Función para transformar el valor antes de enviarlo */
    transformRequest?: (value: any) => any;
    /** Función para interpretar la respuesta del servidor */
    transformResponse?: (response: any) => boolean;
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
    cols?: number;
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
        action: 'show' | 'hide' | 'enable' | 'disable' | 'update';
    };
    validation?: (zod: typeof z, values?: Record<string, any>) => ZodTypeAny;
    onChange?: (value: any, formValues: Record<string, any>) => void;
    relation?: string;
    relationField?: string;
    [key: string]: any;
    uniqueValidation?: UniqueValidationConfig;
}

export interface FormStep {
    label: string;
    description?: string;
    fields: FormField[];
    colsPerRow?: number;
}

export interface FormTab {
    name: string;
    label: string;
    description?: string;
    fields: FormField[];
    colsPerRow?: number;
}

export interface FormTheme {
    type?: ThemeType;
    classes?: string;
}

export interface FormConfig {
    title?: string;
    description?: string;
    steps?: FormStep[];
    tabs?: FormTab[];
    fields?: FormField[];
    initialValues?: Record<string, any>;
    colsPerRow?: number;
    size?: FieldSize;
    labelType?: LabelType;
    theme?: FormTheme;
    style?: {
        formClass?: string;
        titleClass?: string;
        descriptionClass?: string;
        rowClass?: string;
        fieldClass?: string;
        actionsClass?: string;
        submitButtonClass?: string;
        cancelButtonClass?: string;
        submitAndStayButtonClass?: string;
    };
    submitButtonText?: string | null;
    submitAndStayText?: string | null;
    cancelButtonText?: string | null;
    redirectOnSubmit?: string;
    beforeSubmit?: (data: Record<string, any>) => Promise<Record<string, any>> | Record<string, any>;
    onSubmit?: (data: Record<string, any>) => Promise<void> | void;
    onCancel?: () => void;
    afterSubmit?: (result: { success: boolean; error?: any; action?: 'submit' | 'stay' }) => void;
}

export type FormErrors = Record<string, string | string[] | undefined>;

export interface FilePreviewItem {
    type: any;
    name: any;
    url: string;
    file: File;
}

export { z };
