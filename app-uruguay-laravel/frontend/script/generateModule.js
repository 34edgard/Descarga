#!/usr/bin/env node
/*
Generador de módulos Vue.js con TypeScript
Guía de uso: node script/generateModule.js [nombre_modulo]
Ejemplo: node script/generateModule.js employee

Características:
- Estructura completa CRUD con TypeScript
- Soporte para upload de archivos (fotos)
- Interfaces bien tipadas
- Servicios con FormData
- Convenciones consistentes (snake_case, PascalCase)
*/

const fs = require('fs');
const path = require('path');
const chalk = require('chalk');
const pluralize = require('pluralize');

/**
 *
 * @param {string} string
 * @returns string
 * @description Convierte una cadena a snake case.
 * @example employee_area -> employee_area
 * @example employeeArea -> employee_area
 * @example employee -> employee
 */
function toSnakeCase(string) {
    return string.replace(/([a-z0-9])([A-Z])/g, '$1_$2').toLowerCase();
}

/**
 *
 * @param {string} string
 * @returns string
 * @description Convierte una cadena a pascal case.
 * @example employee_area -> EmployeeArea
 * @example employeeArea -> EmployeeArea
 * @example employee -> Employee
 */
function toPascalCase(string) {
    const snakeCaseString = toSnakeCase(string);

    return snakeCaseString
        .split('_')
        .map((word) => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
        .join('');
}

/**
 * @param {string} string
 * @returns string
 * @description Convierte una cadena a camel case.
 * @example employee_area -> employeeArea
 * @example employeeArea -> employeeArea
 * @example employee -> employee
 */
function toCamelCase(string) {
    const snakeCaseString = toSnakeCase(string);

    return snakeCaseString
        .split('_')
        .map((word, index) => (index === 0 ? word : word.charAt(0).toUpperCase() + word.slice(1)))
        .join('');
}

/**
 *
 * @param {string} string
 * @returns string
 * @description Convierte una cadena a plural en camel case.
 * @example employee_area -> employeeAreas
 * @example employeeArea -> employeeAreas
 * @example employee -> employees
 */
function toCamelCasePlurals(string) {
    const snakeCaseString = toSnakeCase(string);

    const camelCaseSingular = snakeCaseString
        .split('_')
        .map((word, index) => (index === 0 ? word : word.charAt(0).toUpperCase() + word.slice(1)))
        .join('');

    const parts = camelCaseSingular.split(/(?=[A-Z])/);
    const lastPartSingular = parts.pop();
    const lastPartPlural = pluralize.plural(lastPartSingular);

    return [...parts, lastPartPlural].join('');
}

/**
 *
 * @param {string} string
 * @returns string
 * @description Convierte una cadena a plural en snake case.
 * @example employee_area -> employee_areas
 * @example employeeArea -> employee_areas
 * @example employee -> employees
 */
function toSnakeCasePlurals(string) {
    const snakeCaseName = toSnakeCase(string);

    const words = snakeCaseName
        .replace(/([A-Z])/g, '_$1')
        .toLowerCase()
        .split('_')
        .filter(Boolean);

    if (words.length > 0) {
        const lastWordSingular = words.pop();
        const lastWordPlural = pluralize.plural(lastWordSingular);
        words.push(lastWordPlural);
    }

    const pluralSnakeCaseName = words.join('_');

    return pluralSnakeCaseName;
}

/**
 * @param {string} string
 * @returns string
 * @description Convierte una cadena a PascalCase en plural.
 * @example employee_area -> EmployeeAreas
 * @example employeeArea -> EmployeeAreas
 * @example employee -> Employees
 */
function toPascalCasePlurals(string) {
    const pascalCaseName = toPascalCase(string);

    const parts = pascalCaseName.split(/(?=[A-Z])/);
    const lastPartSingular = parts.pop();
    const lastPartPlural = pluralize.plural(lastPartSingular);

    const pascalCasePlural = [...parts, lastPartPlural].map((word) => word.charAt(0).toUpperCase() + word.slice(1)).join('');

    return pascalCasePlural;
}

// Validar argumentos
const inputModuleName = process.argv[2];

if (!inputModuleName) {
    console.error(chalk.red('Error: Debes especificar un nombre de módulo (ej: "employee", "company_area")'));
    process.exit(1);
}

// Generar nombres en diferentes formatos
const snakeCaseName = toSnakeCase(inputModuleName);
const pascalCaseName = toPascalCase(inputModuleName);
const camelCaseName = toCamelCase(inputModuleName);

const snakeCaseNamePlurals = toSnakeCasePlurals(inputModuleName);
const pascalCasePlurals = toPascalCasePlurals(inputModuleName);
const cameCasePlurals = toCamelCasePlurals(inputModuleName);

// singular
// console.log('singular');
// console.log(snakeCaseName);
// console.log(pascalCaseName);
// console.log(camelCaseName);

// plurals
// console.log('plurals');
// console.log(snakeCaseNamePlurals);
// console.log(pascalCasePlurals);
// console.log(cameCasePlurals);

// --- Definir estructura de directorios ---
const baseDirs = {
    views: path.join(__dirname, '..', 'src', 'views', 'pages', snakeCaseName),
    components: path.join(__dirname, '..', 'src', 'components', pascalCasePlurals),
    services: path.join(__dirname, '..', 'src', 'services', snakeCaseName),
    interfaces: path.join(__dirname, '..', 'src', 'interfaces'),
    router: path.join(__dirname, '..', 'src', 'router')
};

// Crear directorios necesarios
Object.values(baseDirs).forEach((dir) => {
    if (!fs.existsSync(dir)) {
        fs.mkdirSync(dir, { recursive: true });
    }
});

// --- Definir rutas de archivos ---
const files = {
    // Views
    indexView: path.join(baseDirs.views, 'Index.vue'),
    createView: path.join(baseDirs.views, 'Create.vue'),
    editView: path.join(baseDirs.views, 'Edit.vue'),
    showView: path.join(baseDirs.views, 'Show.vue'),

    // Components
    formComponent: path.join(baseDirs.components, `${pascalCaseName}Form.vue`),
    listComponent: path.join(baseDirs.components, `${pascalCaseName}List.vue`),
    showComponent: path.join(baseDirs.components, `${pascalCaseName}Show.vue`),
    fieldsForm: path.join(baseDirs.components, 'FieldsForm.ts'),

    // Services
    serviceFile: path.join(baseDirs.services, `${pascalCaseName}Service.ts`),

    // Interfaces
    interfaceFile: path.join(baseDirs.interfaces, `${snakeCaseName}.interfaces.ts`),

    // Router
    routerFile: path.join(baseDirs.router, `${snakeCaseName}.routes.ts`)
};

// --- Plantillas de contenido ---
const templates = {
    // Views
    indexView: `
<script setup lang="ts">
import ${pascalCaseName}List from "@/components/${pascalCasePlurals}/${pascalCaseName}List.vue";
</script>

<template>
    <${pascalCaseName}List />
</template>
    `,

    // View create
    createView: `
<script setup lang="ts">
import ${pascalCaseName}Form from '@/components/${pascalCasePlurals}/${pascalCaseName}Form.vue';
</script>

<template>
    <${pascalCaseName}Form />
</template>
    `,

    // View edit
    editView: `
<script setup lang="ts">
import ${pascalCaseName}Form from '@/components/${pascalCasePlurals}/${pascalCaseName}Form.vue';
</script>

<template>
    <${pascalCaseName}Form />
</template>
    `,

    // View show
    showView: `
<script setup lang="ts">
import ${pascalCaseName}Show from '@/components/${pascalCasePlurals}/${pascalCaseName}Show.vue';
</script>

<template>
    <${pascalCaseName}Show />
</template>
    `,

    // Components
    formComponent: `
<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import DynamicForm from '@/components/GenericForm/DynamicForm.vue';
import type { FormConfig } from '@/components/GenericForm/types';
import { formTabs, flatFields } from './FieldsForm';
import {
    create${pascalCaseName},
    update${pascalCaseName},
    get${pascalCaseName},
    get${pascalCasePlurals}Paginated
} from '@/services/${snakeCaseName}/${pascalCaseName}Service';
import useCrudTable from '@/composables/useCrudOperations';
// import { useAsyncSelect } from '@/composables/useAsyncSelect';
import { ${pascalCaseName} } from '@/interfaces/${snakeCaseName}.interfaces';
// import { getOtherModule } from '@/services/other_module/OtherModuleService';

const router = useRouter();
const route = useRoute();

// Estado de carga
const initialized = ref(false);
const loading${pascalCaseName} = ref(false);
const ${camelCaseName}Data = ref<${pascalCaseName}>(null);

// Carga para los SELECT (Ejemplo de como consultar areas para un rellenar un selector)
// const {
//   options: otherModuleOptions,
//  loading: otherModuleLoading,
//  loadOptions
// } = useAsyncSelect(async () => ({ data: await getgetOtherModule() }), (val) => ({ label: val.name, value: val.id }));

// Modo edición
const isEditMode = computed(() => route.name === 'edit_${snakeCaseName}');

const {
    loadItemData,
    handleSubmitForm,
    currentItem,
    isLoading,
    isSubmitting
} = useCrudTable({
    fetchPaginated: get${pascalCasePlurals}Paginated,
    getItem: get${pascalCaseName},
    createItem: create${pascalCaseName},
    updateItem: update${pascalCaseName},
});

const load${pascalCaseName}Data = async () => {
    if (!isEditMode.value) return;
    loading${pascalCaseName}.value = true;
    try {
        await loadItemData(Number(route.params.id));
        ${camelCaseName}Data.value = currentItem.value;
    } catch (error) {
        console.error('Error loading data:', error);
        router.push('/${snakeCaseName}');
    } finally {
        loading${pascalCaseName}.value = false;
    }
};

const handleSubmit = async (formData: any) => {
    try {
        await handleSubmitForm(formData, isEditMode.value ? Number(route.params.id) : undefined);
        router.push({ name: '${snakeCaseName}_list' });
    } catch (error) {
        console.error('Error submitting form:', error);
    }
};

onMounted(async () => {
    //await loadOptions();
    await load${pascalCaseName}Data();
    initialized.value = true;
});

const currentFormConfig = computed<FormConfig | null>(() => {
    if (
        !initialized.value ||
        // ${cameCasePlurals}Loading.value ||
        (isEditMode.value && loading${pascalCaseName}.value)
        ) return null;

    const baseConfig = {
        colsPerRow: 12,
        submitButtonText: "Guardar",
        cancelButtonText: "Cancelar",
        style: { formClass: 'p-fluid' },
        title: isEditMode.value ? 'Editar ${pascalCaseName}' : 'Registro de ${pascalCaseName}',
        onCancel: () => router.go(-1),
        onSubmit: handleSubmit,
        initialValues: isEditMode.value ? ${camelCaseName}Data.value : {},
    };

    return currentView.value === 'tabs'
        ? {
            ...baseConfig,
            description: 'Complete los campos por sección',
            tabs: formTabs()
        }
        : {
            ...baseConfig,
            description: 'Complete todos los campos',
            fields: flatFields()
        };
});

// Control de vista
const currentView = ref<'tabs' | 'flat'>('tabs');
const switchView = (view: 'tabs' | 'flat') => {
    currentView.value = view;
};
</script>

<template>
    <div class="card">
        <div v-if="loading${pascalCaseName}" class="flex justify-center p-8">
            <ProgressSpinner />
        </div>

        <template v-else-if="initialized && currentFormConfig">
        <div class="flex justify-end gap-4">
            <Button label="Ver en Pestañas" @click="() => switchView('tabs')" :severity="currentView === 'tabs' ? 'primary' : 'secondary'" outlined />
            <Button label="Ver Plano" @click="() => switchView('flat')" :severity="currentView === 'flat' ? 'primary' : 'secondary'" outlined />
        </div>

        <DynamicForm :config="currentFormConfig" @submit="handleSubmit" />
    </template>
    </div>
</template>
    `,

    fieldsForm: `
import { z, type ZodTypeAny } from 'zod';
import type { FormField, FormTab } from '@/components/GenericForm/types';
import { ${pascalCaseName} } from '@/interfaces/${snakeCaseName}.interfaces';

export const create${pascalCaseName}InfoFields = (${snakeCaseName}?: ${pascalCaseName}): FormField[] => [
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

export const formTabs = (${snakeCaseName}?: ${pascalCaseName}): FormTab[] => [
        {
            name: 'info',
            label: 'Información',
            fields: create${pascalCaseName}InfoFields(${snakeCaseName}),
            colsPerRow: 12
        },
    ];

export const flatFields = (${snakeCaseName}?: ${pascalCaseName}): FormField[] => [
    ...create${pascalCaseName}InfoFields(${snakeCaseName}),
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
    `,

    listComponent: `
<script setup lang="ts">
import { ref } from 'vue';
import useCrudTable from '@/composables/useCrudOperations';
import {
    get${pascalCasePlurals}Paginated,
    delete${pascalCaseName},
    delete${pascalCaseName}Multiple
} from '@/services/${snakeCaseName}/${pascalCaseName}Service';
import GenericTable from "@/components/DataTables/GenericTable.vue";
import ConfirmationDialog from '@/components/ConfirmationDialog.vue';

const service = {
    fetchPaginated: get${pascalCasePlurals}Paginated,
    deleteItem: delete${pascalCaseName},
    deleteMultiple: delete${pascalCaseName}Multiple
};

const {
    selectedItems: selected,
    itemToDelete: itemToDelete,
    isDeleting,
    deleteDialogVisible,
    deleteMultipleDialogVisible,
    fetchData,
    confirmDelete,
    deleteItem,
    confirmDeleteMultiple,
    deleteMultipleItems
} = useCrudTable(service);

const columns = ref([
    { key: "name", label: "Nombres", sortable: true, visible: true },
    { key: "description", label: "Descripción", sortable: true, visible: true },
]);

const refreshKey = ref(0);

const handleDeleteSelected = (rows: any[]) => {
    if (rows.length > 0) {
        selected.value = rows;
        confirmDeleteMultiple();
    }
};

const handleConfirmDelete = async () => {
    try {
        await deleteItem();
        refreshKey.value++;
    } catch (error) {
        console.error('Error deleting item:', error);
    }
};

const handleConfirmDeleteMultiple = async () => {
    try {
        await deleteMultipleItems();
        refreshKey.value++;
        selected.value = [];
    } catch (error) {
        console.error('Error deleting items:', error);
    }
};
</script>

<template>
    <div>
        <GenericTable
            :fetchDataFunction="fetchData"
            :columns="columns"
            :refreshKey="refreshKey"
            titleTable="Listado de ${pascalCaseName}"
            :selectableRows="true"
            v-model:selectedRows="selected"
            @delete-selected="handleDeleteSelected"
        >
            <template #actions>
                <Button
                    label="Nuevo"
                    as="router-link"
                    :to="{ name: 'create_${snakeCaseName}' }"
                    icon="pi pi-plus"
                    severity="info"
                    raised
                />
            </template>

            <template #row-actions="{ row }">
                <Button
                    icon="pi pi-search"
                    as="router-link"
                    title="Ver detalles"
                    :to="{ name: 'show_${snakeCaseName}', params: { id: row.id } }"
                    outlined rounded class="mr-2" raised severity="info"
                />
                <Button
                    icon="pi pi-pencil"
                    as="router-link"
                    title="Editar"
                    :to="{ name: 'edit_${snakeCaseName}', params: { id: row.id } }"
                    outlined rounded class="mr-2" raised
                />
                <Button
                    icon="pi pi-trash"
                    outlined rounded
                    severity="danger"
                    title="Eliminar"
                    @click="confirmDelete(row)"
                    raised
                    :loading="isDeleting && itemToDelete?.id === row.id"
                />
            </template>
        </GenericTable>

        <ConfirmationDialog
            v-model:visible="deleteDialogVisible"
            header="Confirmar Eliminación"
            iconClass="pi pi-exclamation-triangle text-orange-500"
            confirmLabel="Eliminar"
            confirmIcon="pi pi-trash"
            cancelLabel="Cancelar"
            @confirm="handleConfirmDelete"
            @cancel="deleteDialogVisible = false"
            :loading="isDeleting"
        >
            ¿Estás seguro de eliminar este registro?
        </ConfirmationDialog>

        <ConfirmationDialog
            v-model:visible="deleteMultipleDialogVisible"
            header="Confirmar Eliminación Múltiple"
            iconClass="pi pi-exclamation-triangle text-orange-500"
            confirmLabel="Eliminar"
            confirmIcon="pi pi-trash"
            cancelLabel="Cancelar"
            @confirm="handleConfirmDeleteMultiple"
            @cancel="deleteMultipleDialogVisible = false"
            :loading="isDeleting"
        >
            ¿Estás seguro de eliminar los ({{ selected.length }}) registros seleccionados?
            <ul class="mt-2 overflow-y-auto max-h-40">
                <li v-for="item in selected" :key="item.id" class="py-1 border-b border-gray-100">
                    {{ item.name }}
                </li>
            </ul>
        </ConfirmationDialog>
    </div>
</template>
    `,

    showComponent: `
<script setup lang="ts">
import DynamicShowRelations from '@/components/GenericShow/DynamicShowRelations.vue';
import { get${pascalCaseName}, delete${pascalCaseName} } from '@/services/${snakeCaseName}/${pascalCaseName}Service';
import { getShowFields } from './FieldsForm';

const showFields = getShowFields();
</script>

<template>
    <DynamicShowRelations
        :fetchItem="get${pascalCaseName}"
        :deleteItem="delete${pascalCaseName}"
        title="Detalles del ${snakeCaseName}"
        editRoute="edit_${snakeCaseName}"
        listRoute="${snakeCaseName}_list"
        :fields="showFields"
    />
</template>
    `,

    serviceFile: `
import axios from '@/plugins/axios';
import { handleApiError } from '@/utils/errorHandler';
import { ${pascalCaseName}, ${pascalCaseName}FormData } from '@/interfaces/${snakeCaseName}.interfaces';
import { ApiResponse, PaginatedResponse } from '@/interfaces/shared.interfaces';

const API_BASE_URL = '/${snakeCaseNamePlurals}';

// Tipos para requests con FormData
interface Create${pascalCaseName}Request extends Omit<${pascalCaseName}FormData, 'id'> {
    photo?: File;
}

interface Update${pascalCaseName}Request extends Partial<${pascalCaseName}FormData> {
    id: number;
    photo?: File;
}

export const get${pascalCasePlurals} = async (): Promise<${pascalCaseName}[]> => {
    try {
        const response = await axios.get<ApiResponse<${pascalCaseName}[]>>(API_BASE_URL);
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const get${pascalCasePlurals}Paginated = async (filters: any): Promise<PaginatedResponse<${pascalCaseName}>> => {
    try {
        const response = await axios.get<ApiResponse<PaginatedResponse<${pascalCaseName}>>>(
            \`\${API_BASE_URL}/paginated\`,
            { params: filters }
        );
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const get${pascalCaseName} = async (id: number): Promise<${pascalCaseName}> => {
    try {
        const response = await axios.get<ApiResponse<${pascalCaseName}>>(\`\${API_BASE_URL}/\${id}\`);
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const create${pascalCaseName} = async (data: Create${pascalCaseName}Request): Promise<${pascalCaseName}> => {
    try {
        const formData = new FormData();

        // Agregar campos normales
        Object.keys(data).forEach(key => {
            if (key !== 'photo' && data[key as keyof Create${pascalCaseName}Request] !== undefined) {
                const value = data[key as keyof Create${pascalCaseName}Request];
                if (value !== null && value !== undefined) {
                    formData.append(key, value instanceof Blob ? value : String(value));
                }
            }
        });

        // Agregar foto si existe
        if (data.photo) {
            formData.append('photo', data.photo);
        }

        const response = await axios.post<ApiResponse<${pascalCaseName}>>(
            API_BASE_URL,
            formData,
            {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }
        );
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const update${pascalCaseName} = async (id: number, data: Update${pascalCaseName}Request): Promise<${pascalCaseName}> => {
    try {
        const formData = new FormData();

        // Agregar campos normales
        Object.keys(data).forEach(key => {
            if (key !== 'photo' && key !== 'id' && data[key as keyof Update${pascalCaseName}Request] !== undefined) {
                const value = data[key as keyof Update${pascalCaseName}Request];
                if (value !== null && value !== undefined) {
                    formData.append(key, value instanceof Blob ? value : String(value));
                }
            }
        });

        // Agregar foto si existe
        if (data.photo) {
            formData.append('photo', data.photo);
        }

        const response = await axios.put<ApiResponse<${pascalCaseName}>>(
            \`\${API_BASE_URL}/\${id}\`,
            formData,
            {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }
        );
        return response.data.data;
    } catch (e) {
        return handleApiError(e);
    }
};

export const delete${pascalCaseName} = async (id: number): Promise<boolean> => {
    try {
        const response = await axios.delete<ApiResponse<{ deleted: boolean }>>(\`\${API_BASE_URL}/\${id}\`);
        return response.data.success;
    } catch (e) {
        return handleApiError(e);
    }
};

export const delete${pascalCaseName}Multiple = async (ids: number[]): Promise<boolean> => {
    try {
        const response = await axios.delete<ApiResponse<{ deleted: boolean }>>(\`\${API_BASE_URL}/delete\`, {
            data: { ids }
        });
        return response.data.success;
    } catch (e) {
        return handleApiError(e);
    }
};
    `,

    interfaceFile: `
export interface ${pascalCaseName} {
    id: number;
    uuid: string;
    name: string;
    created_at?: string;
    updated_at?: string;
    extra_attributes?: Record<string, any>;
}

export interface ${pascalCaseName}FormData extends Omit<${pascalCaseName}, 'id' | 'created_at' | 'updated_at' | 'extra_attributes'> {
    id?: number;
    photo?: File | null;
    extra_attributes?: Record<string, any>;
}

export interface ${pascalCaseName}Response {
    data: ${pascalCaseName} | ${pascalCaseName}[];
    message?: string;
    success: boolean;
}
    `,

    routerFile: `
import { RouteRecordRaw } from 'vue-router';

const ${camelCaseName}Routes: RouteRecordRaw[] = [
    {
        path: '/${snakeCaseNamePlurals}',
        name: '${snakeCaseName}_list',
        component: () => import('@/views/pages/${snakeCaseName}/Index.vue'),
        meta: { title: '${pascalCasePlurals}' }
    },
    {
        path: '/${snakeCaseNamePlurals}/create',
        name: 'create_${snakeCaseName}',
        component: () => import('@/views/pages/${snakeCaseName}/Create.vue'),
        meta: { title: 'Crear ${pascalCaseName}' }
    },
    {
        path: '/${snakeCaseNamePlurals}/edit/:id',
        name: 'edit_${snakeCaseName}',
        component: () => import('@/views/pages/${snakeCaseName}/Edit.vue'),
        meta: { title: 'Editar ${pascalCaseName}' },
        props: true
    },
    {
        path: '/${snakeCaseNamePlurals}/show/:id',
        name: 'show_${snakeCaseName}',
        component: () => import('@/views/pages/${snakeCaseName}/Show.vue'),
        meta: { title: 'Detalles de ${pascalCaseName}' },
        props: true
    }
];

export default ${camelCaseName}Routes;
    `
};

// --- Escribir archivos ---
Object.entries(files).forEach(([key, filePath]) => {
    fs.writeFileSync(filePath, templates[key].trim(), 'utf8');
});

// --- Mostrar resumen en terminal ---
console.log(chalk.green.bold(`\n✅ Estructura del módulo "${pascalCaseName}" creada exitosamente!\n`));

console.log(chalk.blue.bold('📁 Directorios creados:'));
Object.entries(baseDirs).forEach(([key, dir]) => {
    console.log(chalk.blue(`- ${dir}`));
});

console.log(chalk.cyan.bold('\n📄 Archivos generados:'));
Object.entries(files).forEach(([key, file]) => {
    console.log(chalk.cyan(`- ${file}`));
});

console.log(chalk.yellow.bold('\n🔧 Pasos adicionales:'));
console.log(chalk.yellow(`1. Importa las rutas en src/router/index.ts:`));
console.log(chalk.gray(`   import ${snakeCaseName}Routes from './${snakeCaseName}.routes';`));
console.log(chalk.gray(`   // Luego agrégalas al array de rutas`));

console.log(chalk.yellow(`\n2. Actualiza las interfaces en ${files.interfaceFile}`));
console.log(chalk.gray('   Agrega todos los campos específicos de tu modelo'));

console.log(chalk.yellow(`\n3. Configura los campos del formulario en ${files.fieldsForm}`));
console.log(chalk.gray('   Define todos los campos necesarios para tu formulario'));

console.log(chalk.green.bold('\n🎉 ¡Módulo listo para ser implementado!'));
