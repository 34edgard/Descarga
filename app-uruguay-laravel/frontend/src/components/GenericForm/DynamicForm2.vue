<script setup lang="ts">
import { ref, reactive, computed, watch, provide, shallowRef, onMounted, nextTick, onBeforeUnmount } from 'vue';
import { useRouter } from 'vue-router';
import { z } from 'zod';
import type { FormConfig, FormField, FormErrors, FieldType, FilePreviewItem, FormStep } from './types2';
import axios from '@/plugins/axios';
import { FORM_VALUES_KEY, SELECT_OPTIONS_CACHE_KEY, THEME_KEY } from '@/utils/injection-keys';

// Estado para rastrear si los componentes están cargados
const componentsLoaded = ref(false);

// Componentes reactivos con shallowRef
const DynamicFieldComponent = shallowRef<any>(null);
const StepsComponent = shallowRef<any>(null);
const ProgressBarComponent = shallowRef<any>(null);
const ProgressSpinnerComponent = shallowRef<any>(null);
const ToastComponent = shallowRef<any>(null);
const FilePreviewComponent = shallowRef<any>(null);

// Props con tipado completo
const props = withDefaults(
    defineProps<{
        config?: FormConfig;
        initialValues?: Record<string, any>;
        theme?: 'light' | 'dark' | 'custom';
        apiBaseUrl?: string;
        mode?: 'create' | 'edit';
    }>(),
    {
        theme: 'light',
        mode: 'create'
    }
);

// Emits con tipado completo
const emit = defineEmits<{
    (e: 'submit', data: Record<string, any>): void;
    (e: 'submit-and-stay', data: Record<string, any>): void;
    (e: 'cancel'): void;
    (e: 'update:values', data: Record<string, any>): void;
    (e: 'step-change', currentStep: number): void;
}>();

// Estado del formulario
const router = useRouter();
const formValues = reactive<Record<string, any>>({});
const formErrors = reactive<FormErrors>({});
// Estado de caché para selectores
const selectOptionsCache = reactive<Record<string, any[]>>({});

// Nuevo estado para rastrear qué selectores ya fueron cargados
const selectorsLoaded = reactive<Record<string, boolean>>({});

// Computed para determinar si estamos en modo editar
const isEditMode = computed(() => {
    return props.mode === 'edit' || (props.config?.initialValues && Object.keys(props.config.initialValues).length > 0);
});

// Proveer las dependencias con una clave única
provide(FORM_VALUES_KEY, {
    formValues,
    updateValue: (key: string, value: any) => {
        formValues[key] = value;
    }
});

const isSubmitting = ref(false);
const activeStep = ref(0);
const loadingFields = reactive<Record<string, boolean>>({});
const initialized = ref(false);
const toast = ref<InstanceType<typeof ToastComponent.value>>();

// Cache y temas
const optionsCache = reactive<Record<string, any[]>>({});
const filePreviews = reactive<Record<string, FilePreviewItem[]>>({});

onMounted(async () => {
    try {
        const [{ default: DynamicField }, { default: Steps }, { default: ProgressBar }, { default: ProgressSpinner }, { default: Toast }, { default: FilePreview }] = await Promise.all([
            import('./DynamicField2.vue'),
            import('primevue/steps'),
            import('primevue/progressbar'),
            import('primevue/progressspinner'),
            import('primevue/toast'),
            import('./FilePreview.vue')
        ]);

        DynamicFieldComponent.value = DynamicField;
        StepsComponent.value = Steps;
        ProgressBarComponent.value = ProgressBar;
        ProgressSpinnerComponent.value = ProgressSpinner;
        ToastComponent.value = Toast;
        FilePreviewComponent.value = FilePreview;

        componentsLoaded.value = true;
        await initializeForm();
    } catch (error) {
        console.error('Error loading components:', error);
    }
});

provide(SELECT_OPTIONS_CACHE_KEY, selectOptionsCache);
provide(THEME_KEY, props.theme || props.config?.theme?.type || 'light');

// Computed properties
/*const themeClasses = computed(() => {
    const theme = props.theme || props.config?.theme?.type || 'light';
    return {
        light: 'bg-white text-gray-800',
        dark: 'bg-black-900 text-white',
        custom: props.config?.theme?.classes || ''
    }[theme];
});*/

const steps = computed<FormStep[]>(() => {
    if (!props.config) return [];
    return (
        props.config.steps ||
        props.config.tabs?.map((tab) => ({
            label: tab.label,
            description: tab.description,
            fields: tab.fields,
            colsPerRow: tab.colsPerRow || props.config?.colsPerRow || 12
        })) ||
        []
    );
});

// CurrentFields para manejar tanto steps como fields planos
const currentFields = computed(() => {
    if (!props.config) return [];

    // Si hay steps, usar los campos del step actual
    if (steps.value.length > 0) {
        return steps.value[activeStep.value]?.fields || [];
    }

    // Si no hay steps, usar todos los campos del config
    return props.config.fields || [];
});

const allFields = computed(() => {
    if (!props.config) return [];

    // Si hay steps, obtener todos los campos de todos los steps
    if (steps.value.length > 0) {
        return steps.value.flatMap((step) => step.fields);
    }

    // Si no hay steps, usar los campos directos del config
    return props.config.fields || [];
});

// Calcula el progreso
const progressValue = computed(() => {
    if (steps.value.length === 0) return 100;

    const currentStepFields = steps.value[activeStep.value]?.fields || [];
    const completedFields = currentStepFields.filter((field) => {
        if (!field.name || !field.required) return true;
        return formValues[field.name] !== undefined && formValues[field.name] !== '';
    }).length;

    const stepProgress = currentStepFields.length > 0 ? (completedFields / currentStepFields.length) * 100 : 100;

    const totalProgress = ((activeStep.value + stepProgress / 100) / steps.value.length) * 100;

    return Math.round(totalProgress);
});

/*const formSchema = computed(() => {
    const shape: Record<string, any> = {};
    allFields.value.forEach(field => {
        if (field.name && field.validation) {
            try {
                shape[field.name] = field.validation(z, formValues);
            } catch (e) {
                console.error(`Error creating schema for field ${field.name}:`, e);
                shape[field.name] = z.any();
            }
        }
    });
    return z.object(shape);
});*/

// Función mejorada para obtener valor por defecto
const getDefaultValueForType = (type?: FieldType): any => {
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
        case 'checklist':
        case 'file':
        case 'dynamic-list':
            return [];
        case 'select':
            return null;
        default:
            return '';
    }
};

// Función para cargar opciones de selectores solo en modo editar
const preloadSelectorsForEditMode = async () => {
    if (!isEditMode.value) return;

    const selectFields = allFields.value.filter((field) => (field.type === 'select' || field.type === 'multiselect') && field.config?.endpoint && !selectorsLoaded[field.name]);

    // Cargar todas las opciones en paralelo para mejor rendimiento
    const loadPromises = selectFields.map(async (field) => {
        try {
            const options = await fetchOptionsFromBackend(field);
            updateFieldOptions(field.name, options);
            selectorsLoaded[field.name] = true;
            console.log(`Preloaded options for ${field.name} in edit mode:`, options);
        } catch (error) {
            console.error(`Error preloading options for ${field.name}:`, error);
        }
    });

    await Promise.all(loadPromises);
};

// InitializeForm para manejar ambos modos
const initializeForm = async () => {
    if (!props.config) return;

    try {
        console.log('Initializing form...', {
            hasSteps: steps.value.length > 0,
            hasDirectFields: !!props.config.fields,
            isEditMode: isEditMode.value,
            initialValues: props.config.initialValues
        });

        // Limpiar estado anterior
        resetFormState();

        // Inicializar todos los campos
        allFields.value.forEach((field) => {
            if (!field.name) return;

            console.log(`Initializing field: ${field.name}, type: ${field.type}`);

            // Inicializar campos dynamic-list como array vacío
            if (field.type === 'dynamic-list') {
                formValues[field.name] = props.config.initialValues?.[field.name]?.length ? [...props.config.initialValues[field.name]] : [{}];
                return;
            }

            if (field.type === 'checklist' && field.config?.endpoint) {
                // En modo editar, cargar inmediatamente
                if (isEditMode.value) {
                    axios.get(`${field.config.endpoint}/${field.config.relationName}`).then((response) => {
                        field.options = response.data;
                    });
                }
                formValues[field.name] = props.config.initialValues?.[field.name] || [];
            }

            // Manejar archivos existentes
            if (field.type === 'file' && props.config.initialValues?.[field.name]) {
                handleInitialFiles(field.name, props.config.initialValues[field.name]);
            }

            // Establecer valor inicial
            const initialValue = props.config.initialValues?.[field.name];
            if (initialValue !== undefined) {
                formValues[field.name] = initialValue;
                console.log(`Set initial value for ${field.name}:`, initialValue);
            } else {
                // Solo establecer valor por defecto si no hay valor inicial
                const defaultValue = field.type === 'select' ? null : (field.defaultValue ?? getDefaultValueForType(field.type));
                formValues[field.name] = defaultValue;
                console.log(`Set default value for ${field.name}:`, defaultValue);
            }
        });

        // Pre-cargar selectores solo en modo editar
        await preloadSelectorsForEditMode();

        initialized.value = true;
        console.log('Form initialized successfully. Final values:', formValues);
        console.log('Mode:', isEditMode.value ? 'edit' : 'create');
    } catch (error) {
        console.error('Error initializing form:', error);
        toast.value?.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to initialize form',
            life: 5000
        });
    }
};

// Función para limpiar estado del formulario
const resetFormState = () => {
    // Limpiar valores del formulario manteniendo la reactividad
    Object.keys(formValues).forEach((key) => {
        delete formValues[key];
    });

    // Limpiar errores del formulario
    Object.keys(formErrors).forEach((key) => {
        delete formErrors[key];
    });

    // Limpiar estado de selectores cargados
    Object.keys(selectorsLoaded).forEach((key) => {
        delete selectorsLoaded[key];
    });

    // Resetear step activo
    activeStep.value = 0;

    console.log('Form state reset');
};

onBeforeUnmount(() => {
    // Limpiar todas las URLs de objetos creadas
    Object.values(filePreviews).forEach((previews) => {
        previews.forEach((preview) => {
            URL.revokeObjectURL(preview.url);
        });
    });
});

const handleFileUpload = (fieldName: string, event: { files: File[] }) => {
    const field = allFields.value.find((f) => f.name === fieldName);
    if (!field) return;

    if (!field.config?.multiple) {
        filePreviews[fieldName] = [];
    }

    const files = event.files;
    formValues[fieldName] = field.config?.multiple ? files : files[0];

    filePreviews[fieldName] = [
        ...(filePreviews[fieldName] || []),
        ...files.map((file) => ({
            url: URL.createObjectURL(file),
            file,
            name: file.name,
            type: file.type
        }))
    ];

    validateField(fieldName);
};

const removeFilePreview = (fieldName: string, index: number) => {
    if (!filePreviews[fieldName]) return;

    URL.revokeObjectURL(filePreviews[fieldName][index].url);
    filePreviews[fieldName].splice(index, 1);

    const field = allFields.value.find((f) => f.name === fieldName);
    if (!field) return;

    if (filePreviews[fieldName].length === 0) {
        formValues[fieldName] = field.config?.multiple ? [] : null;
    } else {
        formValues[fieldName] = field.config?.multiple ? filePreviews[fieldName].map((item) => item.file) : filePreviews[fieldName][0].file;
    }

    validateField(fieldName);
};

// Función para manejar carga condicional
const fetchOptionsFromBackend = async (field: FormField, forceLoad: boolean = false): Promise<any[]> => {
    if (!field.config?.endpoint) return [];

    const cacheKey = `${field.config.endpoint}_${JSON.stringify(field.config.params)}`;

    // Si ya está en caché y no es una carga forzada, devolver del caché
    if (optionsCache[cacheKey] && !forceLoad) {
        return optionsCache[cacheKey];
    }

    // En modo crear, solo cargar si es una carga forzada (onClick)
    if (!isEditMode.value && !forceLoad) {
        return [];
    }

    loadingFields[field.name] = true;

    try {
        const params = field.config.params ? Object.fromEntries(Object.entries(field.config.params).map(([key, value]) => [key, typeof value === 'function' ? value(formValues) : value])) : {};

        const response = await axios.get(`${props.apiBaseUrl || ''}${field.config.endpoint}`, { params });
        const apiData = response.data.data || response.data;

        if (!Array.isArray(apiData)) {
            throw new Error('La respuesta del API no es un array válido');
        }

        const options = apiData.map((item: any) => ({
            label: item[field.config?.optionLabel || 'name'],
            value: item[field.config?.optionValue || 'id']
        }));

        console.log(`Options loaded for ${field.name} (${isEditMode.value ? 'edit' : 'create'} mode):`, options);

        optionsCache[cacheKey] = options;
        selectorsLoaded[field.name] = true;
        return options;
    } catch (error) {
        console.error(`Error fetching options for ${field.name}:`, error);
        toast.value?.add({
            severity: 'error',
            summary: 'Error',
            detail: `Failed to load options for ${field.label}`,
            life: 3000
        });
        return [];
    } finally {
        loadingFields[field.name] = false;
    }
};

// Función para manejar la carga onClick (solo en modo crear)
/*const handleSelectClick = async (fieldName: string) => {
    const field = allFields.value.find(f => f.name === fieldName);
    if (!field || selectorsLoaded[fieldName] || isEditMode.value) return;

    console.log(`Loading options for ${fieldName} on click (create mode)`);
    const options = await fetchOptionsFromBackend(field, true);
    updateFieldOptions(fieldName, options);
};*/

const updateFieldOptions = (fieldName: string, options: any[]) => {
    if (!props.config) return;
    const updateOptions = (fields: FormField[]) => {
        const fieldIndex = fields.findIndex((f) => f.name === fieldName);
        if (fieldIndex !== -1) {
            const newField = {
                ...fields[fieldIndex],
                options: [...options]
            };
            fields.splice(fieldIndex, 1, newField);
        }
    };
    if (props.config.steps) {
        props.config.steps.forEach((step) => updateOptions(step.fields));
    } else if (props.config.tabs) {
        props.config.tabs.forEach((tab) => updateOptions(tab.fields));
    } else if (props.config.fields) {
        updateOptions(props.config.fields);
    }
};

const updateDependentFields = async (fieldName: string) => {
    const dependentFields = allFields.value.filter((f) => f.dependsOn?.field === fieldName);
    await Promise.all(
        dependentFields.map(async (field) => {
            formValues[field.name] = field.defaultValue ?? getDefaultValueForType(field.type);
            delete formErrors[field.name];

            // Resetear estado de carga para campos dependientes
            selectorsLoaded[field.name] = false;

            if (field.config?.endpoint) {
                // En modo editar, cargar inmediatamente las opciones
                // En modo crear, esperar al onClick
                if (isEditMode.value) {
                    const options = await fetchOptionsFromBackend(field, true);
                    updateFieldOptions(field.name, options);
                }
            }
            await validateField(field.name);
        })
    );
};

const validateField = async (fieldName: string): Promise<boolean> => {
    const field = allFields.value.find((f) => f.name === fieldName);
    if (!field?.validation) {
        delete formErrors[fieldName];
        return true;
    }
    try {
        const schema = field.validation(z, formValues);
        const result = await schema.safeParseAsync(formValues[fieldName]);
        if (result.success) {
            delete formErrors[fieldName];
            return true;
        } else {
            formErrors[fieldName] = result.error.errors.map((err) => err.message).join(', ');
            return false;
        }
    } catch (error) {
        console.error(`Error validating ${fieldName}:`, error);
        formErrors[fieldName] = 'Validation error';
        return false;
    }
};

const validateCurrentStep = async (): Promise<boolean> => {
    const fieldsToValidate = currentFields.value.filter((f) => f.name && f.required);
    const results = await Promise.all(fieldsToValidate.map((f) => validateField(f.name)));
    return results.every(Boolean);
};

const validateForm = async (): Promise<boolean> => {
    const requiredFields = allFields.value.filter((f) => f.name && f.required);
    const results = await Promise.all(requiredFields.map((f) => validateField(f.name)));
    return results.every(Boolean);
};

const nextStep = async () => {
    if (!(await validateCurrentStep())) {
        toast.value?.add({
            severity: 'warn',
            summary: 'Validaciones',
            detail: 'Por favor corrija los errores antes de continuar.',
            life: 3000
        });
        return;
    }
    if (activeStep.value < steps.value.length - 1) {
        activeStep.value++;
        emit('step-change', activeStep.value);
    }
};

const prevStep = () => {
    if (activeStep.value > 0) {
        activeStep.value--;
        emit('step-change', activeStep.value);
    }
};

// Función para formatear fechas para Laravel
const formatDateForBackend = (dateValue: Date | string | null): string | null => {
    if (!dateValue) return null;

    try {
        const dateObj = dateValue instanceof Date ? dateValue : new Date(dateValue);

        if (isNaN(dateObj.getTime())) {
            console.warn('Fecha inválida:', dateValue);
            return null;
        }

        const year = dateObj.getFullYear();
        const month = String(dateObj.getMonth() + 1).padStart(2, '0');
        const day = String(dateObj.getDate()).padStart(2, '0');

        return `${year}-${month}-${day}`;
    } catch (error) {
        console.error('Error formateando fecha:', error);
        return null;
    }
};

// Función para preparar los datos antes de enviar
const prepareFormData = (formData: Record<string, any>): Record<string, any> => {
    const preparedData: Record<string, any> = {};

    Object.keys(formData).forEach((key) => {
        const field = allFields.value.find((f) => f.name === key);

        if (field?.type === 'date') {
            preparedData[key] = formatDateForBackend(formData[key]);
        } else {
            preparedData[key] = formData[key];
        }

        if (Array.isArray(formData[key])) {
            console.log('Preparando datos para array', formData[key]);

            preparedData[key] = formData[key].map((item: any) => {
                const preparedItem: Record<string, any> = {};
                Object.keys(item).forEach((itemKey) => {
                    if (itemKey.includes('date') || itemKey.includes('birthday')) {
                        preparedItem[itemKey] = formatDateForBackend(item[itemKey]);
                    } else {
                        preparedItem[itemKey] = item[itemKey];
                    }
                });
                return preparedItem;
            });
        }
    });

    return preparedData;
};

const handleSubmit = async () => {
    isSubmitting.value = true;
    try {
        if (!(await validateForm())) {
            toast.value?.add({
                severity: 'error',
                summary: 'Validation Error',
                detail: 'Por favor, corrija todos las validaciones.',
                life: 3000
            });
            return;
        }

        const preparedData = prepareFormData(formValues);

        const dataToSubmit = props.config?.beforeSubmit ? await props.config.beforeSubmit(preparedData) : { ...preparedData };
        if (props.config?.onSubmit) {
            await props.config.onSubmit(dataToSubmit);
        } else {
            emit('submit', dataToSubmit);
        }
        if (props.config?.redirectOnSubmit) {
            router.push(props.config.redirectOnSubmit);
        }
        props.config?.afterSubmit?.({ success: true, action: 'submit' });
    } catch (error: any) {
        console.log('Error formulario', error);

        if (error.response?.status === 422) {
            const errorFields = Object.keys(error.response.data.errors);

            const errorStep = steps.value.findIndex((step) => step.fields.some((field) => errorFields.includes(field.name || '')));

            if (errorStep >= 0) {
                activeStep.value = errorStep;
                await nextTick();
                scrollToFirstError();
            }

            Object.entries(error.response.data.errors).forEach(([field, messages]) => {
                if (Array.isArray(messages)) {
                    formErrors[field] = messages.join(', ');
                } else if (typeof messages === 'string') {
                    formErrors[field] = messages;
                }
            });
            toast.value?.add({
                severity: 'error',
                summary: 'Validation Error',
                detail: 'Por favor corrija los errores resaltados',
                life: 5000
            });
        } else {
            toast.value?.add({
                severity: 'error',
                summary: 'Error',
                detail: error.message || 'An error occurred',
                life: 5000
            });
        }
        props.config?.afterSubmit?.({
            success: false,
            error,
            action: 'submit'
        });
    } finally {
        isSubmitting.value = false;
    }
};

const groupFieldsIntoRows = (fields: FormField[], colsPerRow: number): FormField[][] => {
    const rows: FormField[][] = [];
    let currentRow: FormField[] = [];
    let currentCols = 0;
    fields.forEach((field) => {
        const fieldCols = field.cols && field.cols > 0 && field.cols <= colsPerRow ? field.cols : colsPerRow;
        if (currentCols + fieldCols > colsPerRow) {
            if (currentRow.length > 0) {
                rows.push([...currentRow]);
            }
            currentRow = [];
            currentCols = 0;
        }
        currentRow.push(field);
        currentCols += fieldCols;
        if (fieldCols === colsPerRow) {
            if (currentRow.length > 0) {
                rows.push([...currentRow]);
            }
            currentRow = [];
            currentCols = 0;
        }
    });
    if (currentRow.length > 0) {
        rows.push([...currentRow]);
    }
    return rows;
};

// Watcher de config para detectar cambios estructurales
watch(
    () => props.config,
    async (newConfig, oldConfig) => {
        if (!newConfig) return;

        // Detectar si cambió la estructura (de tabs/steps a plano o viceversa)
        const oldHasSteps = oldConfig?.steps?.length > 0 || oldConfig?.tabs?.length > 0;
        const newHasSteps = newConfig.steps?.length > 0 || newConfig.tabs?.length > 0;

        const structureChanged = oldHasSteps !== newHasSteps;

        console.log('Config changed:', {
            structureChanged,
            oldHasSteps,
            newHasSteps,
            reinitializing: true
        });

        // Siempre reinicializar cuando cambia el config
        await initializeForm();
    },
    { deep: true }
);

// Watcher de initialValues
watch(
    () => props.config?.initialValues,
    async (newData, oldData) => {
        if (!newData || !props.config) return;

        console.log('Initial values changed:', newData);

        // Reinicializar completamente el formulario
        await initializeForm();

        await nextTick(() => {
            // Actualizar campos dependientes después de la inicialización
            allFields.value.forEach((field) => {
                if (field.dependsOn) {
                    updateDependentFields(field.dependsOn.field);
                }
            });
        });
    },
    { deep: true }
);

const resetForm = () => {
    resetFormState();
};

watch(
    formValues,
    async (newValues, oldValues) => {
        const changedFields = Object.keys(newValues).filter((key) => newValues[key] !== oldValues[key]);
        if (changedFields.length > 0) {
            await Promise.all(changedFields.map((fieldName) => updateDependentFields(fieldName)));
            emit('update:values', { ...formValues });
        }
    },
    { deep: true }
);

const handleInitialFiles = (fieldName: string, files: File | File[]) => {
    if (!Array.isArray(files)) {
        files = [files];
    }

    filePreviews[fieldName] = files.map((file) => ({
        url: file instanceof File ? URL.createObjectURL(file) : file,
        file,
        name: file.name,
        type: file.type
    }));
};

const handleStepClick = async (event: { originalEvent: Event; index: number }) => {
    if (event.index < activeStep.value) {
        const isValid = await validateCurrentStep();
        if (isValid) {
            activeStep.value = event.index;
            emit('step-change', activeStep.value);
        }
    } else if (event.index > activeStep.value) {
        const isValid = await validateCurrentStep();
        if (isValid) {
            activeStep.value = event.index;
            emit('step-change', activeStep.value);
        } else {
            scrollToFirstError();
        }
    }
};

const scrollToFirstError = () => {
    nextTick(() => {
        const firstError = document.querySelector('.p-invalid');
        if (firstError) {
            firstError.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });

            firstError.classList.add('error-highlight');
            setTimeout(() => {
                firstError.classList.remove('error-highlight');
            }, 2000);
        }
    });
};
</script>

<template>
    <Transition name="fade-slide" mode="out-in">
        <div v-if="initialized && config" class="dynamic-form-container dark:text-white" key="form-loaded">
            <component :is="ToastComponent" ref="toast" />

            <!-- Debug info (remover en producción) -->
            <div v-if="false" class="p-2 mb-4 text-sm rounded debug-info">
                <p><strong>Mode:</strong> {{ isEditMode ? 'Edit' : 'Create' }}</p>
                <p><strong>Initial Values:</strong> {{ Object.keys(props.config.initialValues || {}).length > 0 ? 'Present' : 'None' }}</p>
                <p><strong>Selectors Loaded:</strong> {{ Object.keys(selectorsLoaded).filter((k) => selectorsLoaded[k]).length }}</p>
            </div>
            <!-- /Debug info -->

            <!-- Encabezado del formulario con animación -->
            <Transition name="fade">
                <div v-if="config.title || config.description" class="form-header">
                    <h2 v-if="config.title" class="form-title">{{ config.title }}</h2>
                    <p v-if="config.description" class="form-description">{{ config.description }}</p>
                </div>
            </Transition>

            <!-- Progress bar del formulario con animación -->
            <div v-if="steps.length > 1" class="progress-bar-container">
                <ProgressBar :value="progressValue" :showValue="false" class="progress-bar" :class="{ 'progress-complete': progressValue === 100 }" />
                <span class="progress-text">{{ progressValue }}% completado</span>
            </div>

            <!-- Steps Navigation con animación -->
            <Transition name="slide-down">
                <div v-if="steps.length > 1 && StepsComponent" class="mb-10">
                    <component :is="StepsComponent" :model="steps" :activeIndex="activeStep" @step-click="handleStepClick" />
                </div>
            </Transition>

            <!-- Formulario con animación de campos -->
            <TransitionGroup name="list" tag="form" @submit.prevent="handleSubmit" novalidate class="form-content">
                <!-- Current Step Fields -->
                <template v-for="(row, rowIndex) in groupFieldsIntoRows(currentFields, steps[activeStep]?.colsPerRow || config.colsPerRow || 12)" :key="`row-${rowIndex}`">
                    <div
                        class="grid gap-4 md:gap-6 form-row"
                        :style="{
                            gridTemplateColumns: `repeat(${steps[activeStep]?.colsPerRow || config.colsPerRow || 12}, minmax(0, 1fr))`,
                            maxWidth: '1200px',
                            margin: '0 auto'
                        }"
                    >
                        <template v-for="field in row" :key="field.name">
                            <div :style="{ gridColumn: `span ${field.cols || 12}` }">
                                <!-- Mostrar previsualización de archivos con animación -->
                                <TransitionGroup name="list" tag="div">
                                    <div v-if="field.type === 'file' && filePreviews[field.name]?.length" class="file-previews-container" key="file-previews">
                                        <component
                                            :is="FilePreviewComponent"
                                            v-for="(preview, idx) in filePreviews[field.name]"
                                            :key="`preview-${idx}`"
                                            :url="preview.url"
                                            :name="preview.name"
                                            :type="preview.type"
                                            @remove="removeFilePreview(field.name, idx)"
                                        />
                                    </div>
                                </TransitionGroup>

                                <Transition name="fade" mode="out-in">
                                    <component
                                        v-if="DynamicFieldComponent && field.name"
                                        :is="DynamicFieldComponent"
                                        :field-config="field"
                                        :modelValue="formValues[field.name]"
                                        :error-message="formErrors[field.name]"
                                        :disabled="isSubmitting || field.disabled"
                                        @update:modelValue="
                                            (val: any) => {
                                                formValues[field.name] = val;
                                                validateField(field.name);
                                                field.onChange?.(val, formValues);
                                            }
                                        "
                                        @file-upload="handleFileUpload(field.name, $event)"
                                    />
                                </Transition>
                            </div>
                        </template>
                    </div>
                </template>

                <!-- Navigation Buttons con efectos hover -->
                <div class="flex justify-between pt-5 mt-8 border-t border-gray-200" key="form-actions">
                    <div>
                        <Button v-if="activeStep > 0" type="button" label="Anterior" icon="pi pi-arrow-left" class="p-button-secondary button-transition" @click="prevStep" :disabled="isSubmitting" />
                    </div>
                    <div class="flex gap-3">
                        <Button v-if="config.cancelButtonText !== null" type="button" :label="config.cancelButtonText || 'Cancel'" class="p-button-secondary button-transition" @click="() => router.go(-1)" :disabled="isSubmitting" />
                        <Button v-if="activeStep < steps.length - 1" type="button" label="Siguiente" icon="pi pi-arrow-right" iconPos="right" class="button-transition" @click="nextStep" :disabled="isSubmitting" />
                        <Button v-if="activeStep === steps.length - 1 || steps.length === 0" type="submit" :label="config.submitButtonText || 'Submit'" icon="pi pi-check" class="button-transition" :loading="isSubmitting" />
                        <Button
                            v-if="config.submitAndStayText && (activeStep === steps.length - 1 || steps.length === 0)"
                            type="button"
                            :label="config.submitAndStayText"
                            icon="pi pi-check"
                            class="p-button-success button-transition"
                            :loading="isSubmitting"
                            @click="emit('submit-and-stay', formValues)"
                        />
                    </div>
                </div>
            </TransitionGroup>
        </div>

        <!-- Loading State con animación -->
        <div v-else class="flex items-center justify-center p-8" key="form-loading">
            <ProgressSpinner />
        </div>
    </Transition>
</template>

<style scoped>
.progress-bar-container {
    margin-bottom: 2rem;
    position: relative;
    margin-top: 2.5rem;
}

.progress-bar {
    height: 0.5rem;
    border-radius: 0.25rem;
    transition: all 0.3s ease;
}

.progress-bar.progress-complete {
    background-color: var(--green-500);
}

.progress-text {
    position: absolute;
    right: 0;
    top: -1.5rem;
    font-size: 0.875rem;
    color: var(--text-color-secondary);
}

/* Estilos para modo oscuro */
.dark .progress-text {
    color: var(--gray-400);
}

/* Animación para cuando cambia el progreso */
.progress-bar::v-deep(.p-progressbar-value) {
    transition: width 0.5s ease;
}

/* Animaciones */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.slide-down-enter-active,
.slide-down-leave-active {
    transition: all 0.3s ease;
}

.slide-down-enter-from,
.slide-down-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}

.fade-slide-enter-active {
    transition: all 0.3s ease-out;
}

.fade-slide-leave-active {
    transition: all 0.3s ease-in;
}

.fade-slide-enter-from {
    opacity: 0;
    transform: translateX(20px);
}

.fade-slide-leave-to {
    opacity: 0;
    transform: translateX(-20px);
}

.list-move,
.list-enter-active,
.list-leave-active {
    transition: all 0.5s ease;
}

.list-enter-from,
.list-leave-to {
    opacity: 0;
    transform: translateY(30px);
}

.list-leave-active {
    position: absolute;
}

/* Efectos de hover y focus */
.button-transition {
    transition: all 0.2s ease;
}

.button-transition:hover {
    transform: translateY(-1px);
    box-shadow:
        0 4px 6px -1px rgba(0, 0, 0, 0.1),
        0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.button-transition:active {
    transform: translateY(0);
}

:deep(.p-focus) {
    transform: translateY(-2px);
    box-shadow:
        0 4px 6px -1px rgba(0, 0, 0, 0.1),
        0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

/* Estilos existentes mejorados */
.dynamic-form-container {
    @apply max-w-5xl mx-auto p-6;
    transition: all 0.3s ease;
}

.form-header {
    @apply pb-4 mb-6 border-b border-gray-200;
    transition: all 0.3s ease;
}

.form-title {
    @apply text-2xl font-semibold mb-2;
    transition: all 0.3s ease;
}

.form-description {
    @apply text-sm text-gray-600;
    transition: all 0.3s ease;
}

.form-row {
    @apply gap-4 md:gap-6 mb-6;
    transition: all 0.3s ease;
}

.file-previews-container {
    @apply flex flex-wrap gap-4 mt-2;
    transition: all 0.3s ease;
}

/* Modo oscuro */
.dark .form-header {
    @apply border-gray-700;
}

.dark .form-title {
    @apply text-white;
}

.dark .form-description {
    @apply text-gray-400;
}

/* Efecto para errores */
.error-highlight {
    animation: pulse-error 2s;
}

@keyframes pulse-error {
    0% {
        box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
    }

    70% {
        box-shadow: 0 0 0 10px rgba(239, 68, 68, 0);
    }

    100% {
        box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr !important;
    }

    .form-actions {
        flex-direction: column;
        gap: 1rem;
    }
}
</style>
