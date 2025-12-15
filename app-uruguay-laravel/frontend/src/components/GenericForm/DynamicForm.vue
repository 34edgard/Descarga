<script setup lang="ts">
import { computed, provide, reactive, ref, watch, type ComputedRef } from 'vue';
import { useRouter } from 'vue-router';
import { z, type SafeParseReturnType, type ZodIssue, type ZodObject, type ZodRawShape, type ZodTypeAny } from 'zod';
import DynamicField from './DynamicField.vue';
import type { ButtonVariant, FieldSize, FieldType, FormConfig, FormErrors, FormField } from './types';

// Importa un icono de PrimeIcons para el indicador de error y v-tooltip
//import { PrimeIcons } from 'primevue/api'; // PrimeIcons es un enum de nombres de iconos
// Si usas v-tooltip, asegúrate de que el directiva esté registrada globalmente (común con PrimeVue)
// Si no está global, descomenta y registra:
// import Tooltip from 'primevue/tooltip';
// import { App } from 'vue'; // O importa tu instancia de App si registras aquí
// Vue.directive('tooltip', Tooltip); // Si registras localmente o en el setup

const props = defineProps<{
    config?: FormConfig; // La configuración ahora puede tener 'tabs' o 'fields'
}>();

const emit = defineEmits<{
    (e: 'submit', data: Record<string, any>): void;
    (e: 'submit-and-stay', data: Record<string, any>): void;
    (e: 'cancel'): void;
}>();

const router = useRouter();

// === ESTADO MANUAL DEL FORMULARIO ===
const formValues = reactive<Record<string, any>>({});
const formErrors = reactive<FormErrors>({});
const isSubmitting = ref(false);

const tabsComponentRef = ref<any>(null);

// Ref para el componente Tabs para controlar la pestaña activa programáticamente
// En v4, a menudo se controla con v-model:value en <Tabs> y una ref local
const activeTabIndex = ref('0'); // Usamos índice como valor inicial por defecto para Tabs

// === COMPUTED: DETERMINAR SI ES MODO PESTAÑAS ===
const isTabbedLayout = computed(() => {
    // Es modo pestañas si la config existe y tiene un array de pestañas con elementos
    return props.config?.tabs && props.config.tabs.length > 0;
});

// === COMPUTED: OBTENER TODOS LOS CAMPOS (sea de tabs o fields planos) ===
const allFields = computed<FormField[]>(() => {
    if (isTabbedLayout.value) {
        // Si es modo pestañas, aplanamos todos los campos de todas las pestañas
        return props.config!.tabs!.flatMap((tab) => tab.fields); // Usamos ! porque isTabbedLayout ya verificó existencia
    } else if (props.config?.fields && props.config.fields.length > 0) {
        // Si no es modo pestañas pero hay campos planos, los usamos
        return props.config.fields;
    }
    // Si no hay ni pestañas ni campos planos, retornamos un array vacío
    return [];
});

// === COMPUTED: SCHEMA ZOD (Generado de todos los campos) ===
const formSchema: ComputedRef<ZodObject<ZodRawShape, 'strip', ZodTypeAny>> = computed(() => {
    const shape: ZodRawShape = {};
    const fields = allFields.value; // Usamos el computed allFields

    fields.forEach((field) => {
        if (field.name) {
            if (field.validation) {
                try {
                    shape[field.name] = field.validation(z);
                } catch (e) {
                    console.error(`Error creando schema Zod para el campo '${field.name}':`, e);
                    shape[field.name] = z.any().optional().nullable();
                }
            } else {
                shape[field.name] = z.any().optional().nullable();
            }
        }
    });

    const finalSchema = z.object(shape).strip();
    return finalSchema;
});

// === HELPER: AGRUPAR CAMPOS EN FILAS PARA LAYOUT GRID ===
// Función que toma un array de campos y un número de columnas y los agrupa en subarrays para renderizado grid.
// Ahora usa el colsPerRow proporcionado para la fila/sección actual.
const groupFieldsIntoRows = (fields: FormField[], sectionColsPerRow: number): FormField[][] => {
    const groups: FormField[][] = [];
    let currentRow: FormField[] = [];
    let currentCols = 0;

    // Asegura un valor válido para las columnas de la sección
    if (sectionColsPerRow <= 0) sectionColsPerRow = 12;

    fields.forEach((field) => {
        // Usar las columnas definidas en el campo, o el total de columnas de la sección si no está definido/válido
        // Un campo no debería pedir más columnas de las que tiene la sección, lo limitamos
        const fieldCols = field.cols && field.cols > 0 && field.cols <= sectionColsPerRow ? field.cols : sectionColsPerRow;

        // Si añadir este campo excede el número de columnas de la fila actual
        // O si el campo es de ancho completo (respecto a sectionColsPerRow) y no es el primer campo de la fila
        if (currentCols + fieldCols > sectionColsPerRow || (fieldCols === sectionColsPerRow && currentRow.length > 0)) {
            if (currentRow.length > 0) {
                // No añadir filas vacías
                groups.push([...currentRow]); // Añade una copia de la fila actual
            }
            currentRow = []; // Empieza una nueva fila
            currentCols = 0;
        }

        currentRow.push(field);
        currentCols += fieldCols;

        // Si el campo es de ancho completo (respecto a sectionColsPerRow), terminar la fila después de él
        if (fieldCols === sectionColsPerRow) {
            if (currentRow.length > 0) {
                // No añadir filas vacías
                groups.push([...currentRow]);
            }
            currentRow = [];
            currentCols = 0;
        }
    });

    // Añadir la última fila si no está vacía
    if (currentRow.length > 0) {
        groups.push([...currentRow]);
    }

    return groups;
};

// === REACTIVO: PESTAÑAS CON ERRORES ===
const tabsWithErrors = reactive(new Set<string>());

// === WATCHER: SINCRONIZAR CONFIGURACIÓN Y ESTADO ===
watch(
    () => props.config,
    (newConfig) => {
        // console.log('Config watcher triggered:', newConfig ? 'Config available' : 'Config not available');
        // Solo inicializa si hay config Y tiene campos (sea en tabs o planos)
        if (newConfig && (newConfig.tabs?.length > 0 || newConfig.fields?.length > 0)) {
            // console.log('Config with fields/tabs received/updated. Initializing form values and errors.');

            // Limpiar errores anteriores
            Object.keys(formErrors).forEach((key) => delete formErrors[key]);
            tabsWithErrors.clear(); // Limpiar errores de pestañas

            // Limpiar valores anteriores (opcional, si quieres resetear el form al cambiar la config)
            // Object.keys(formValues).forEach(key => delete formValues[key]);

            const fieldsToInitialize = allFields.value; // Usamos el computed allFields
            const initialValuesFromConfig = newConfig.initialValues || {};

            fieldsToInitialize.forEach((field) => {
                if (field.name) {
                    const initialValue = initialValuesFromConfig[field.name] !== undefined ? initialValuesFromConfig[field.name] : field.defaultValue !== undefined ? field.defaultValue : getDefaultValueForType(field.type);

                    // Usamos Object.assign o Vue.set si no estamos seguros de que la clave existe
                    // para asegurar la reactividad en objetos reactive inicializados vacíos {}
                    // Aunque con Vue 3+ reactive({}) y asignación directa suele ser suficiente.
                    formValues[field.name] = initialValue;
                }
            });

            // Opcional: Validar el formulario completo justo después de inicializar
            //validateForm(); // <-- Llamar validación inicial aquí para mostrar errores al cargar
        } else {
            // console.log('Config or fields/tabs not available. Clearing form state.');
            Object.keys(formValues).forEach((key) => delete formValues[key]);
            Object.keys(formErrors).forEach((key) => delete formErrors[key]);
            tabsWithErrors.clear();
        }
    },
    { immediate: true, deep: true }
); // Ejecutar inmediatamente y observar cambios profundos en la config

// Helper para obtener valores por defecto razonables basados en el tipo de campo
function getDefaultValueForType(type: FieldType): any {
    switch (type) {
        case 'checkbox':
        case 'switch':
        case 'toggle':
            return false;
        case 'number':
        case 'currency':
        case 'range':
        case 'rating':
        case 'date':
        case 'time':
            return null;
        case 'multiselect':
        case 'file':
            return [];
        default:
            return '';
    }
}

// === WATCHER: ACTUALIZAR ERRORES POR PESTAÑA CUANDO formErrors CAMBIA ===
watch(
    formErrors,
    () => {
        // console.log('formErrors updated. Recalculating tabs with errors...');
        tabsWithErrors.clear(); // Limpiar el Set actual

        // Solo si estamos en modo pestañas
        if (isTabbedLayout.value) {
            const tabs = props.config!.tabs!;
            tabs.forEach((tab) => {
                // Para cada pestaña, verificar si ALGUNO de sus campos tiene un error en formErrors
                const tabHasError = tab.fields.some((field) => formErrors[field.name] !== undefined && formErrors[field.name] !== null);
                if (tabHasError) {
                    tabsWithErrors.add(tab.name); // Añadir el nombre/identificador de la pestaña al Set
                }
            });
            // console.log('Tabs with errors:', Array.from(tabsWithErrors));
        }
    },
    { deep: true }
); // Observar cambios profundos en el objeto formErrors

// === VALIDACIÓN MANUAL ===

// Valida un campo específico y actualiza formErrors
async function validateField(fieldName: string): Promise<boolean> {
    // Asegura que config y schema están listos
    if (!props.config || !formSchema.value) {
        delete formErrors[fieldName]; // Limpiar error si no se puede validar
        return false;
    }

    const fieldConfig = allFields.value.find((f) => f.name === fieldName); // Usamos allFields computed

    if (!fieldConfig || !fieldConfig.validation) {
        delete formErrors[fieldName];
        return true; // Se considera válido si no hay validación definida
    }

    const fieldSchema = fieldConfig.validation(z);

    // Usa safeParseAsync para validar el valor actual del campo
    const result: SafeParseReturnType<any, any> = await fieldSchema.safeParseAsync(formValues[fieldName]);

    if (result.success) {
        delete formErrors[fieldName];
        return true;
    } else {
        formErrors[fieldName] = result.error.issues.map((issue) => issue.message).join(', ');
        return false;
    }
}

// Valida todos los campos del formulario y actualiza formErrors
async function validateForm(): Promise<boolean> {
    if (!props.config || !formSchema.value) {
        // console.warn('Full form validation skipped: config or schema not ready.');
        Object.keys(formErrors).forEach((key) => delete formErrors[key]);
        return false;
    }

    // console.log('Running full form validation...');
    const result: SafeParseReturnType<Record<string, any>, Record<string, any>> = await formSchema.value.safeParseAsync(formValues);

    Object.keys(formErrors).forEach((key) => delete formErrors[key]); // Limpiar errores actuales

    if (result.success) {
        // console.log('Full form validation successful.');
        return true;
    } else {
        // console.log('Full form validation failed.', result.error.issues);
        result.error.issues.forEach((issue: ZodIssue) => {
            const fieldName = issue.path.join('.');
            if (fieldName) {
                if (formErrors[fieldName]) {
                    if (Array.isArray(formErrors[fieldName])) {
                        (formErrors[fieldName] as string[]).push(issue.message);
                    } else {
                        formErrors[fieldName] = [formErrors[fieldName] as string, issue.message];
                    }
                } else {
                    formErrors[fieldName] = issue.message;
                }
            }
        });
        return false;
    }
}

// === MANEJO DE EVENTOS ===
const handleFieldChange = async (fieldName: string, value: any) => {
    // Asegura que el campo exista en formValues antes de setear (puede no existir si dependsOn lo oculta inicialmente)
    // Aunque formValues se inicializa con todos los campos al cargar la config.
    if (fieldName in formValues) {
        formValues[fieldName] = value;
        await validateField(fieldName); // Validar campo individual
        // Ejecutar onChange del campo si existe
        const fieldConfig = allFields.value.find((f) => f.name === fieldName); // Usamos allFields computed
        if (fieldConfig?.onChange) {
            fieldConfig.onChange(value, formValues);
        }
    } else {
        console.warn(`Campo ${fieldName} no encontrado en formValues. Config lista?`, !!props.config);
    }
};

const handleSubmit = async () => {
    // console.log('Submit triggered. Starting manual process...');
    isSubmitting.value = true;

    try {
        const isValid = await validateForm(); // Validar formulario completo

        if (!isValid) {
            // console.log('Manual validation failed. Stopping submit.');
            if (props.config?.afterSubmit) {
                props.config.afterSubmit({ success: false, error: formErrors, action: 'submit' });
            }
            // Navegar a la primera pestaña con error al fallar la validación (solo en modo pestañas)
            if (isTabbedLayout.value && tabsComponentRef.value) {
                const firstTabWithError = props.config!.tabs!.find((tab) => tabsWithErrors.has(tab.name));
                if (firstTabWithError) {
                    const firstTabWithErrorIndex = props.config!.tabs!.findIndex((tab) => tab.name === firstTabWithError.name);
                    if (firstTabWithErrorIndex !== -1) {
                        // PrimeVue Tabs v4 usa v-model:value, que a menudo es el índice como string.
                        // Actualizamos la ref local que está enlazada con v-model:value en <Tabs>.
                        activeTabIndex.value = firstTabWithErrorIndex.toString();
                    }
                }
            }

            return;
        }

        // console.log('Manual validation successful. Proceeding with submit logic.');
        let dataToSubmit = formValues;
        if (props.config?.beforeSubmit) {
            dataToSubmit = (await props.config.beforeSubmit(formValues)) || formValues;
        }

        if (props.config?.onSubmit) {
            await props.config.onSubmit(dataToSubmit);
        } else {
            emit('submit', dataToSubmit);
            console.warn('No onSubmit handler provided.');
        }

        // console.log('Formulario enviado:', dataToSubmit);

        if (props.config?.redirectOnSubmit) {
            router.push(props.config.redirectOnSubmit);
        }
        if (props.config?.afterSubmit) {
            props.config.afterSubmit({ success: true, error: undefined, action: 'submit' });
        }
    } catch (error) {
        console.error('Error during submit process:', error);
        if (props.config?.afterSubmit) {
            props.config.afterSubmit({ success: false, error: error, action: 'submit' });
        }
    } finally {
        isSubmitting.value = false;
        // console.log('Manual submit process finished.');
    }
};

const onCancel = () => {
    console.log('Cancel button clicked.');
    if (props.config?.onCancel) {
        props.config.onCancel();
    } else if (props.config?.redirectOnSubmit) {
        // console.log('Cancel clicked. No onCancel handler provided.');
    } else {
        // console.log('Cancel clicked. No onCancel handler or redirect configured.');
    }
    emit('cancel');
};

// Helper para obtener el mensaje de error para mostrar
const getErrorMessage = (fieldName: string): string | undefined => {
    const fieldErrors = formErrors[fieldName];
    if (!fieldErrors) {
        return undefined;
    }
    if (Array.isArray(fieldErrors)) {
        return fieldErrors[0];
    }
    return fieldErrors;
};

// Helper para generar clases de botón
const getButtonClass = (variant?: ButtonVariant, customClass?: string): string[] => {
    const classes = ['action-button', 'p-button'];
    if (customClass) classes.push(customClass);
    switch (variant) {
        case 'secondary':
            classes.push('p-button-secondary', 'p-button-outlined');
            break;
        case 'success':
            classes.push('p-button-success');
            break;
        case 'info':
            classes.push('p-button-info');
            break;
        case 'warning':
            classes.push('p-button-warning');
            break;
        case 'danger':
            classes.push('p-button-danger');
            break;
        case 'help':
            classes.push('p-button-help');
            break;
    }
    if (props.config?.size) {
        const buttonSizeSuffix: Record<FieldSize, string> = { small: 'sm', normal: '', large: 'lg' };
        const suffix = buttonSizeSuffix[props.config.size];
        if (suffix) {
            classes.push(`p-button-${suffix}`);
        }
    }
    return classes;
};

// === PROVIDE ===
// Proveer formValues para la lógica de dependsOn en DynamicField
provide(
    'formValues',
    computed(() => (props.config && (props.config.tabs?.length > 0 || props.config.fields?.length > 0) ? formValues : {}))
);

// === DIAGNOSTICO: LOGGING INICIAL ===
// console.log('DynamicForm Setup Started (Manual + Tabs/Flat)');
// console.log('Initial props.config:', props.config);
</script>

<template>
    <div v-if="props.config && (props.config.tabs?.length > 0 || props.config.fields?.length > 0)"
        :class="props.config.style?.formClass">
        <div v-if="props.config.title || props.config.description"
            class="pb-4 mb-6 border-b border-gray-200 form-header">
            <h2 v-if="props.config.title" class="text-xl font-semibold text-gray-800 dark:text-white"
                :class="props.config.style?.titleClass">
                {{ props.config.title }}
            </h2>
            <p v-if="props.config.description" class="mt-1 text-sm text-gray-600 dark:text-gray-400"
                :class="props.config.style?.descriptionClass">
                {{ props.config.description }}
            </p>
        </div>

        <form @submit.prevent="handleSubmit" class="form-body" novalidate>
            <div v-if="isTabbedLayout">
                <Tabs ref="tabsComponentRef" v-model:value="activeTabIndex">
                    <TabList>
                        <Tab v-for="(tab, index) in props.config!.tabs!" :key="tab.name" :value="index.toString()">
                            <div class="flex items-center gap-1">
                                <span :class="{ 'text-red-500': tabsWithErrors.has(tab.name) }">
                                    {{ tab.label }}
                                </span>
                                <i v-if="tabsWithErrors.has(tab.name)" class="text-red-500 pi pi-exclamation-triangle"
                                    v-tooltip.top="'Esta sección contiene errores.'"></i>
                            </div>
                        </Tab>
                    </TabList>
                    <TabPanels>
                        <TabPanel v-for="(tab, index) in props.config!.tabs!" :key="tab.name" :value="index.toString()">
                            <div class="">
                                <div v-if="tab.description" class="mb-4 text-sm text-gray-600 dark:text-white">
                                    {{ tab.description }}
                                </div>
                                <template v-if="tab.fields && tab.fields.length > 0">
                                    <div v-for="(row, rowIndex) in groupFieldsIntoRows(tab.fields, tab.colsPerRow || props.config?.colsPerRow || 12)"
                                        :key="`tab-${tab.name}-row-${rowIndex}`" class="grid gap-4 md:gap-6 form-row"
                                        :style="{ 'grid-template-columns': `repeat(${tab.colsPerRow || props.config?.colsPerRow || 12}, minmax(0, 1fr))` }"
                                        :class="props.config?.style?.rowClass">
                                        <DynamicField v-for="field in row" :key="field.name" :field-config="field"
                                            :label-type="field.labelType || props.config?.labelType || 'normal'"
                                            :modelValue="formValues[field.name]"
                                            :error-message="getErrorMessage(field.name)"
                                            @update:modelValue="(val: any) => handleFieldChange(field.name, val)"
                                            :disabled="isSubmitting"
                                            :class="field.style?.containerClass || props.config?.style?.fieldClass"
                                            :style="{ 'grid-column': `span ${field.cols || tab.colsPerRow || props.config?.colsPerRow || 12}` }" />
                                    </div>
                                </template>
                                <div v-else class="p-4 text-center text-gray-500">No hay campos definidos para esta
                                    pestaña.</div>
                            </div>
                        </TabPanel>
                    </TabPanels>
                </Tabs>
            </div>

            <div v-else-if="props.config?.fields && props.config.fields.length > 0" class="p-4">
                <template v-if="allFields.length > 0">
                    <div v-for="(row, rowIndex) in groupFieldsIntoRows(allFields, props.config?.colsPerRow || 12)"
                        :key="`flat-row-${rowIndex}`" class="grid gap-4 md:gap-6 form-row"
                        :style="{ 'grid-template-columns': `repeat(${props.config?.colsPerRow || 12}, minmax(0, 1fr))` }"
                        :class="props.config?.style?.rowClass">
                        <DynamicField v-for="field in row" :key="field.name" :field-config="field"
                            :label-type="field.labelType || props.config?.labelType || 'normal'"
                            :modelValue="formValues[field.name]" :error-message="getErrorMessage(field.name)"
                            @update:modelValue="(val: any) => handleFieldChange(field.name, val)"
                            :disabled="isSubmitting"
                            :class="field.style?.containerClass || props.config?.style?.fieldClass"
                            :style="{ 'grid-column': `span ${field.cols || props.config?.colsPerRow || 12}` }" />
                    </div>
                </template>
                <div v-else class="p-4 text-center text-gray-500">No hay campos definidos para este formulario.</div>
            </div>

            <div v-if="props.config" class="flex justify-end gap-3 pt-5 mt-8 border-t border-gray-200 form-actions"
                :class="props.config.style?.actionsClass">
                <Button v-if="props.config?.cancelButtonText !== null" type="button"
                    :label="props.config?.cancelButtonText || 'Cancelar'" @click="onCancel"
                    :class="getButtonClass('secondary', props.config?.style?.cancelButtonClass)"
                    :disabled="isSubmitting" raised />

                <Button v-if="props.config?.submitButtonText !== null" type="submit"
                    :label="props.config?.submitButtonText || 'Guardar'" icon="pi pi-check"
                    :class="getButtonClass('primary', props.config?.style?.submitButtonClass)" :loading="isSubmitting"
                    :disabled="isSubmitting" raised />
            </div>
        </form>
    </div>

    <div v-else class="flex items-center justify-center p-8 text-gray-500">Cargando configuración del formulario o no
        hay
        campos/pestañas definidos...</div>
</template>

<style scoped>
.action-button {
    min-width: 100px;
}

/* Estilos de campo y errores */
.form-field-wrapper {
    @apply mb-4;
}

/* Estilos responsivos para campos */
.form-row {
    grid-template-columns: repeat(var(--cols, 12), minmax(0, 1fr));
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr !important;
    }

    :deep(.p-tabview-nav) {
        justify-content: flex-start;
    }
}

:deep(.p-inputtext),
:deep(.p-dropdown),
:deep(.p-calendar .p-inputtext),
:deep(.p-inputtextarea),
:deep(.p-inputnumber-input),
:deep(.p-inputmask),
:deep(.p-multiselect),
:deep(.p-cascadeselect),
:deep(.p-editor .p-editor-content),
:deep(.p-fileupload-basic .p-button) {
    @apply w-full flex-grow;
}

:deep(.p-inputnumber),
:deep(.p-colorpicker-panel),
:deep(.p-editor-container) {
    @apply w-full;
}

:deep(.p-inputtext),
:deep(.p-dropdown:not(.p-togglebutton):not(.p-selectbutton)),
:deep(.p-calendar .p-inputtext),
:deep(.p-inputtextarea),
:deep(.p-inputnumber-input),
:deep(.p-inputmask),
:deep(.p-multiselect.p-focus),
:deep(.p-cascadeselect.p-focus) {
    @apply p-2 border border-gray-300 rounded-md;
}

:deep(.p-inputtext:hover),
:deep(.p-dropdown:not(.p-disabled):hover) {
    @apply border-blue-500;
}

:deep(.p-inputtext:focus),
:deep(.p-dropdown.p-focus),
:deep(.p-calendar .p-inputtext:focus),
:deep(.p-inputtextarea:focus),
:deep(.p-inputnumber-input:focus),
:deep(.p-inputmask:focus),
:deep(.p-multiselect.p-focus),
:deep(.p-cascadeselect.p-focus) {
    @apply ring-2 ring-blue-200 border-blue-500 outline-none shadow-none;
}

:deep(.p-invalid) {
    @apply border-red-400 ring-red-200;
}

:deep(.p-inputtext.p-invalid),
:deep(.p-dropdown.p-invalid),
:deep(.p-calendar .p-inputtext.p-invalid),
:deep(.p-inputtextarea.p-invalid),
:deep(.p-inputnumber-input.p-invalid),
:deep(.p-inputmask.p-invalid),
:deep(.p-multiselect.p-invalid),
:deep(.p-cascadeselect.p-invalid) {
    @apply border-red-400;
}

.p-error {
    @apply text-red-600;
}

:deep(.p-float-label label) {
    @apply text-gray-600;
}

:deep(.p-float-label input:focus ~ label),
:deep(.p-float-label .p-filled ~ label) {
    @apply text-blue-500;
}

:deep(.p-float-label .p-invalid ~ label) {
    @apply text-red-600;
}

:deep(.p-togglebutton .p-button) {
    @apply w-auto;
}

:deep(.p-checkbox),
:deep(.p-radiobutton) {
    @apply mr-2;
}

:deep(.p-rating) {
    @apply inline-flex;
}

:deep(.p-colorpicker .p-colorpicker-preview) {
    @apply w-full;
}

:deep(.p-fileupload-basic) {
    @apply w-full;
}

:deep(.form-field-wrapper .p-inputtext),
:deep(.form-field-wrapper .p-dropdown),
:deep(.form-field-wrapper .p-calendar .p-inputtext),
:deep(.form-field-wrapper .p-inputtextarea),
:deep(.form-field-wrapper .p-inputnumber .p-inputnumber-input),
:deep(.form-field-wrapper .p-inputmask),
:deep(.form-field-wrapper .p-multiselect),
:deep(.form-field-wrapper .p-cascadeselect),
:deep(.form-field-wrapper .p-editor .p-editor-content),
:deep(.form-field-wrapper .p-fileupload-basic .p-button),
:deep(.form-field-wrapper .p-colorpicker .p-colorpicker-preview) {
    @apply w-full;
}

:deep(.form-field-wrapper .p-inputnumber),
:deep(.form-field-wrapper .p-colorpicker),
:deep(.form-field-wrapper .p-editor-container),
:deep(.form-field-wrapper .p-fileupload-basic) {
    @apply w-full;
}

/* Estilos específicos de PrimeVue Tabs */
:deep(.p-tabview-nav) {
    /* Barra de navegación de las pestañas */
}

:deep(.p-tabview-header) {
    /* Cada cabecera de pestaña (el li) */
}

/* Estilos para el contenido DENTRO de la cabecera de Tab (el a) */
:deep(.p-tabview .p-tabview-nav .p-tabview-header .p-tabview-nav-link) {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    /* Espacio entre texto e icono */
}

:deep(.p-tabview-selected .p-tabview-header-action) {
    /* Cabecera de la pestaña activa (en algunas versiones) */
}

:deep(.p-tabview-panels) {
    /* Contenedor de los paneles */
}

:deep(.p-tabview-panel) {
    /* Cada panel de contenido */
}
</style>