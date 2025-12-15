<template>
    <div class="mb-6 rounded-xl">
        <!-- Items List -->
        <div class="space-y-4">
            <div v-for="(item, index) in safeModelValue" :key="`item-${index}-${item.id || 'new'}`" class="p-5 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-600">
                <!-- Item Header -->
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-2">
                        <span class="font-medium text-primary-600 dark:text-primary-400"> {{ fieldConfig.label }} #{{ index + 1 }} </span>
                        <!-- Badge para indicar si es existente o nuevo -->
                        <span v-if="item._isExisting" class="px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full dark:bg-green-900 dark:text-green-300"> Existente </span>
                        <span v-else-if="item._isNew" class="px-2 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300"> Nuevo </span>
                    </div>
                    <button type="button" v-if="safeModelValue.length > minItems" @click="removeItem(index)" class="text-red-500 transition-colors hover:text-red-700 dark:hover:text-red-400" :title="removeButtonText">
                        <i class="text-sm pi pi-trash"></i>
                    </button>
                </div>

                <!-- Search Section - Solo si está configurado -->
                <div v-if="hasSearchConfig && isSearchableItem(item, index)" class="mb-4">
                    <div class="relative">
                        <input
                            v-model="searchQueries[index]"
                            @input="handleSearch(index)"
                            :placeholder="getSearchPlaceholder()"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                            :disabled="item._isExisting || isSearching[index]"
                        />
                        <div v-if="isSearching[index]" class="absolute right-3 top-2.5">
                            <ProgressSpinner class="w-5 h-5" />
                        </div>
                    </div>

                    <!-- Search Results -->
                    <div v-if="searchResults[index]?.length > 0" class="mt-2 overflow-y-auto bg-white border border-gray-200 rounded-lg max-h-40 dark:bg-gray-700 dark:border-gray-600">
                        <div
                            v-for="result in searchResults[index]"
                            :key="result[searchConfig.resultKey]"
                            @click="selectExistingItem(index, result)"
                            class="px-4 py-2 border-b border-gray-100 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-600 dark:border-gray-600 last:border-b-0"
                        >
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ getDisplayText(result, searchConfig.displayField) }}
                            </div>
                            <div v-if="searchConfig.secondaryField" class="text-xs text-gray-500 dark:text-gray-400">
                                {{ getDisplayText(result, searchConfig.secondaryField) }}
                            </div>
                            <div v-if="searchConfig.relatedField && result[searchConfig.relatedField]?.length > 0" class="mt-1 text-xs text-blue-600 dark:text-blue-400">
                                {{ searchConfig.relatedFieldLabel }}: {{ result[searchConfig.relatedField].join(', ') }}
                            </div>
                        </div>
                    </div>

                    <!-- No results message -->
                    <div
                        v-else-if="searchQueries[index] && !isSearching[index] && hasSearched[index]"
                        class="p-3 mt-2 text-sm text-green-600 border border-green-200 rounded-lg bg-green-50 dark:bg-green-900/20 dark:border-green-800 dark:text-green-400"
                    >
                        <i class="mr-2 pi pi-check-circle"></i>
                        {{ searchConfig.noResultsMessage || 'No se encontraron registros existentes' }}
                    </div>
                </div>

                <!-- Fields Grid - Dinámico según cols con cálculo automático -->
                <div :class="getGridClasses()">
                    <div v-for="subField in fieldConfig.config?.dynamicList?.fields || []" :key="`${subField.name}-${index}`" :class="getFieldColumnClass(subField)">
                        <DynamicField
                            :field-config="{
                                ...subField,
                                name: `${fieldConfig.name}.${index}.${subField.name}`,
                                labelType: 'float',
                                disabled: item._isExisting && isReadOnlyField(subField.name)
                            }"
                            :modelValue="item[subField.name]"
                            @update:modelValue="(val) => updateItem(index, subField.name, val)"
                            @blur="() => validateField(index, subField)"
                            :ref="(el) => setFieldRef(el, index, subField.name)"
                        />
                    </div>
                </div>

                <!-- Validation Errors Display -->
                <div v-if="itemErrors[index] && Object.keys(itemErrors[index]).length > 0" class="mt-3 p-3 border border-red-200 rounded-lg bg-red-50 dark:bg-red-900/20 dark:border-red-800">
                    <div class="text-sm text-red-700 dark:text-red-300">
                        <i class="mr-2 pi pi-exclamation-triangle"></i>
                        <span class="font-medium">Errores de validación:</span>
                        <ul class="mt-1 ml-4 list-disc">
                            <li v-for="(error, fieldName) in itemErrors[index]" :key="fieldName">{{ getFieldLabel(fieldName) }}: {{ error }}</li>
                        </ul>
                    </div>
                </div>

                <!-- Readonly info for existing items -->
                <div v-if="item._isExisting && searchConfig?.existingItemMessage" class="p-3 mt-3 border border-blue-200 rounded-lg bg-blue-50 dark:bg-blue-900/20 dark:border-blue-800">
                    <div class="text-sm text-blue-700 dark:text-blue-300">
                        <i class="mr-2 pi pi-info-circle"></i>
                        {{ searchConfig.existingItemMessage }}
                    </div>
                </div>
            </div>

            <!-- Add Button -->
            <div class="flex items-center justify-end">
                <button
                    type="button"
                    @click="validateBeforeAdd"
                    :disabled="isMaxItemsReached || isAdding"
                    class="flex items-center mt-2 transition-colors text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 disabled:opacity-50 disabled:cursor-not-allowed"
                    :class="{ 'opacity-50 cursor-not-allowed': isAdding }"
                >
                    <i class="mr-2 pi pi-plus"></i>
                    {{ addButtonText }}
                    <ProgressSpinner v-if="isAdding" class="w-4 h-4 ml-2" />
                </button>
            </div>

            <!-- Global validation message -->
            <div v-if="validationMessage" class="p-3 border border-orange-200 rounded-lg bg-orange-50 dark:bg-orange-900/20 dark:border-orange-800">
                <div class="text-sm text-orange-700 dark:text-orange-300">
                    <i class="mr-2 pi pi-exclamation-triangle"></i>
                    {{ validationMessage }}
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, ref, reactive, nextTick, inject, watch } from 'vue';
import type { FormField } from './types2';
import DynamicField from './DynamicField2.vue';
import { debounce } from 'lodash-es';
import axios from '@/plugins/axios';
import { ProgressSpinner } from 'primevue';

interface ListItem {
    [key: string]: any;
    id?: number;
    _isExisting?: boolean;
    _isNew?: boolean;
    _originalData?: any;
}

interface SearchResult {
    [key: string]: any;
}

interface SearchConfig {
    endpoint: string;
    searchFields: string[];
    displayField: string;
    secondaryField?: string;
    relatedField?: string;
    relatedFieldLabel?: string;
    resultKey: string;
    existingItemMessage?: string;
    noResultsMessage?: string;
    minQueryLength?: number;
    debounceTime?: number;
}

const props = defineProps<{
    fieldConfig: FormField;
    modelValue?: ListItem[];
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: ListItem[]): void;
    (e: 'validation-error', errors: Record<string, string>): void;
}>();

// Obtener token de autenticación desde el contexto si está disponible
const authToken = inject('authToken', '');

// Refs and reactive data
const fieldRefs = reactive<Record<string, any>>({});
const isAdding = ref(false);
const searchQueries = reactive<Record<number, string>>({});
const searchResults = reactive<Record<number, SearchResult[]>>({});
const isSearching = reactive<Record<number, boolean>>({});
const hasSearched = reactive<Record<number, boolean>>({});
const itemErrors = reactive<Record<number, Record<string, string>>>({});
const validationMessage = ref('');

// Computed properties
const minItems = computed(() => props.fieldConfig.config?.dynamicList?.minItems || 0);
const maxItems = computed(() => props.fieldConfig.config?.dynamicList?.maxItems || Infinity);
const addButtonText = computed(() => props.fieldConfig.config?.dynamicList?.addButtonText || 'Agregar item');
const removeButtonText = computed(() => props.fieldConfig.config?.dynamicList?.removeButtonText || 'Eliminar');

// Search configuration
const searchConfig = computed((): SearchConfig | null => {
    const config = props.fieldConfig.config?.dynamicList?.searchConfig;
    if (!config) return null;

    return {
        endpoint: config.endpoint,
        searchFields: config.searchFields || ['name'],
        displayField: config.displayField || 'name',
        secondaryField: config.secondaryField,
        relatedField: config.relatedField,
        relatedFieldLabel: config.relatedFieldLabel || 'Relacionado con',
        resultKey: config.resultKey || 'id',
        existingItemMessage: config.existingItemMessage,
        noResultsMessage: config.noResultsMessage,
        minQueryLength: config.minQueryLength || 2,
        debounceTime: config.debounceTime || 300
    };
});

const hasSearchConfig = computed(() => !!searchConfig.value);

const readOnlyFields = computed(() => props.fieldConfig.config?.dynamicList?.readOnlyFields || []);

const safeModelValue = computed<ListItem[]>(() => {
    const value = Array.isArray(props.modelValue) ? props.modelValue : [];
    return value.length === 0 ? [createNewItem()] : value;
});

const isMaxItemsReached = computed(() => safeModelValue.value.length >= maxItems.value);

// Grid and column management
const getGridClasses = () => {
    const fields = props.fieldConfig.config?.dynamicList?.fields || [];
    const totalCols = fields.reduce((sum, field) => sum + (field.cols || 12), 0);

    // Si el total de columnas es mayor a 12, usar grid automático
    if (totalCols > 12) {
        return 'grid gap-4 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3';
    }

    return 'grid grid-cols-12 gap-4';
};

const getFieldColumnClass = (field: FormField) => {
    const totalCols = (props.fieldConfig.config?.dynamicList?.fields || []).reduce((sum, f) => sum + (f.cols || 12), 0);

    if (totalCols > 12) {
        // Grid automático - cada campo ocupa su espacio natural
        return 'w-full';
    }

    // Grid de 12 columnas tradicional
    const cols = field.cols || 12;
    return `col-span-${Math.min(cols, 12)}`;
};

const getFieldLabel = (fieldName: string): string => {
    const field = props.fieldConfig.config?.dynamicList?.fields?.find((f) => f.name === fieldName);
    return field?.label || fieldName;
};

// Helper functions
const createNewItem = (): ListItem => {
    const newItem: ListItem = {
        _isNew: true,
        _isExisting: false
    };

    props.fieldConfig.config?.dynamicList?.fields?.forEach((field) => {
        newItem[field.name] = field.defaultValue ?? (field.type === 'number' ? 0 : '');
    });

    return newItem;
};

const isSearchableItem = (item: ListItem, index: number): boolean => {
    return !item._isExisting && !item.id;
};

const isReadOnlyField = (fieldName: string): boolean => {
    return readOnlyFields.value.includes(fieldName);
};

const getSearchPlaceholder = (): string => {
    if (!searchConfig.value) return 'Buscar...';
    return `Buscar por ${searchConfig.value.searchFields.join(' o ')}...`;
};

const getDisplayText = (item: any, field: string): string => {
    return field.split('.').reduce((obj, key) => obj?.[key], item) || '';
};

const setFieldRef = (el: any, index: number, fieldName: string) => {
    if (el) {
        fieldRefs[`${index}-${fieldName}`] = el;
    }
};

// Search functionality
const searchItems = async (query: string): Promise<SearchResult[]> => {
    if (!query.trim() || !searchConfig.value) return [];

    if (query.length < searchConfig.value.minQueryLength) return [];

    try {
        const headers: any = {
            'Content-Type': 'application/json',
            Accept: 'application/json'
        };

        if (authToken) {
            headers['Authorization'] = `Bearer ${authToken}`;
        }

        const response = await axios.get(searchConfig.value.endpoint, {
            params: { q: query },
            headers,
            timeout: 10000 // 10 seconds timeout
        });

        return response.data.data || response.data || [];
    } catch (error) {
        console.error('Error searching items:', error);
        return [];
    }
};

const debouncedSearch = debounce(async (index: number, query: string) => {
    if (!query.trim() || !searchConfig.value) {
        searchResults[index] = [];
        hasSearched[index] = false;
        return;
    }

    if (query.length < searchConfig.value.minQueryLength) {
        searchResults[index] = [];
        hasSearched[index] = false;
        return;
    }

    isSearching[index] = true;
    try {
        const results = await searchItems(query);
        searchResults[index] = results;
        hasSearched[index] = true;
    } catch (error) {
        console.error('Search error:', error);
        searchResults[index] = [];
        hasSearched[index] = true;
    } finally {
        isSearching[index] = false;
    }
}, searchConfig.value?.debounceTime || 300);

const handleSearch = (index: number) => {
    const query = searchQueries[index];
    debouncedSearch(index, query);
};

const selectExistingItem = (index: number, selectedItem: SearchResult) => {
    if (!searchConfig.value) return;

    const newItems = [...safeModelValue.value];

    // Marcar como existente y preservar datos originales
    newItems[index] = {
        ...selectedItem,
        _isExisting: true,
        _isNew: false,
        _originalData: { ...selectedItem }
    };

    // Limpiar búsqueda y errores
    searchQueries[index] = '';
    searchResults[index] = [];
    hasSearched[index] = false;
    delete itemErrors[index];

    emit('update:modelValue', newItems);
};

// Enhanced validation functions
const validateField = async (index: number, field: FormField): Promise<boolean> => {
    if (!field.name) return true;

    const fieldKey = `${index}-${field.name}`;
    const fieldRef = fieldRefs[fieldKey];
    const fieldValue = safeModelValue.value[index]?.[field.name];

    // Clear previous error
    if (itemErrors[index]) {
        delete itemErrors[index][field.name];
        if (Object.keys(itemErrors[index]).length === 0) {
            delete itemErrors[index];
        }
    }

    // Required field validation
    if (field.required) {
        const isEmpty = fieldValue === null || fieldValue === undefined || fieldValue === '' || (Array.isArray(fieldValue) && fieldValue.length === 0);

        if (isEmpty) {
            if (!itemErrors[index]) itemErrors[index] = {};
            itemErrors[index][field.name] = 'Este campo es requerido';
            return false;
        }
    }

    // Custom validation from field config
    if (field.validation && typeof field.validation === 'function') {
        try {
            const zodSchema = field.validation(await import('zod'), safeModelValue.value[index]);
            const result = zodSchema.safeParse(fieldValue);

            if (!result.success) {
                if (!itemErrors[index]) itemErrors[index] = {};
                itemErrors[index][field.name] = result.error.errors[0]?.message || 'Valor inválido';
                return false;
            }
        } catch (error) {
            console.error('Validation error:', error);
        }
    }

    // Delegate to field component validation if available
    if (fieldRef && typeof fieldRef.validateField === 'function') {
        try {
            const isValid = await fieldRef.validateField();
            if (!isValid) {
                if (!itemErrors[index]) itemErrors[index] = {};
                itemErrors[index][field.name] = 'Valor inválido';
                return false;
            }
        } catch (error) {
            console.error('Field validation error:', error);
            return false;
        }
    }

    return true;
};

const validateCurrentItem = async (index: number): Promise<boolean> => {
    const fields = props.fieldConfig.config?.dynamicList?.fields || [];
    const validationPromises = fields.map((field) => validateField(index, field));
    const results = await Promise.all(validationPromises);

    return results.every(Boolean);
};

const validateBeforeAdd = async () => {
    if (isMaxItemsReached.value) {
        validationMessage.value = `Máximo ${maxItems.value} items permitidos`;
        return;
    }

    isAdding.value = true;
    validationMessage.value = '';

    try {
        // Validar todos los items existentes
        const validationPromises = safeModelValue.value.map((_, index) => validateCurrentItem(index));
        const results = await Promise.all(validationPromises);

        const hasErrors = results.some((result) => !result);

        if (hasErrors) {
            validationMessage.value = 'Complete todos los campos requeridos antes de agregar otro item';
            emit('validation-error', {
                message: validationMessage.value
            });
            return;
        }

        addItem();
    } finally {
        isAdding.value = false;
    }
};

// CRUD operations
const addItem = () => {
    const newItem = createNewItem();
    const newItems = [...safeModelValue.value, newItem];
    emit('update:modelValue', newItems);

    // Inicializar búsqueda para el nuevo item
    nextTick(() => {
        const newIndex = newItems.length - 1;
        searchQueries[newIndex] = '';
        searchResults[newIndex] = [];
        hasSearched[newIndex] = false;
        isSearching[newIndex] = false;
    });
};

const removeItem = (index: number) => {
    if (safeModelValue.value.length <= minItems.value) return;

    const newItems = safeModelValue.value.filter((_, i) => i !== index);
    emit('update:modelValue', newItems);

    // Limpiar datos asociados
    delete searchQueries[index];
    delete searchResults[index];
    delete hasSearched[index];
    delete isSearching[index];
    delete itemErrors[index];

    // Reindexar datos restantes
    Object.keys(searchQueries).forEach((key) => {
        const idx = parseInt(key);
        if (idx > index) {
            searchQueries[idx - 1] = searchQueries[idx];
            delete searchQueries[idx];
        }
    });

    // Hacer lo mismo para otros objetos reactivos
    [searchResults, hasSearched, isSearching, itemErrors].forEach((obj) => {
        Object.keys(obj).forEach((key) => {
            const idx = parseInt(key);
            if (idx > index) {
                obj[idx - 1] = obj[idx];
                delete obj[idx];
            }
        });
    });
};

const updateItem = (index: number, fieldName: string, value: any) => {
    const newItems = [...safeModelValue.value];
    const currentItem = newItems[index];

    // Si es un item existente, solo permitir cambios en campos específicos
    if (currentItem._isExisting && isReadOnlyField(fieldName)) {
        return;
    }

    newItems[index] = { ...newItems[index], [fieldName]: value };

    // Si es un campo de búsqueda y es un item nuevo, actualizar la query de búsqueda
    if (!currentItem._isExisting && searchConfig.value?.searchFields.includes(fieldName)) {
        searchQueries[index] = value || '';
        if (value) {
            handleSearch(index);
        } else {
            searchResults[index] = [];
            hasSearched[index] = false;
        }
    }

    // Clear field error on change
    if (itemErrors[index]?.[fieldName]) {
        delete itemErrors[index][fieldName];
        if (Object.keys(itemErrors[index]).length === 0) {
            delete itemErrors[index];
        }
    }

    emit('update:modelValue', newItems);
};

// Watch for external validation clearing
watch(
    () => props.modelValue,
    () => {
        validationMessage.value = '';
    },
    { deep: true }
);

// Preparar datos para envío al backend
const prepareDataForBackend = () => {
    return safeModelValue.value.map((item) => {
        if (item._isExisting && searchConfig.value) {
            const cleanItem: any = {
                [`${searchConfig.value.resultKey}`]: item[searchConfig.value.resultKey],
                _action: 'link'
            };

            props.fieldConfig.config?.dynamicList?.fields?.forEach((field) => {
                if (!isReadOnlyField(field.name)) {
                    cleanItem[field.name] = item[field.name];
                }
            });

            return cleanItem;
        } else {
            const { _isNew, _isExisting, _originalData, ...cleanData } = item;
            return {
                ...cleanData,
                _action: 'create'
            };
        }
    });
};

// Exponer funciones para uso externo
defineExpose({
    prepareDataForBackend,
    validateAllItems: async () => {
        const validationPromises = safeModelValue.value.map((_, index) => validateCurrentItem(index));
        const results = await Promise.all(validationPromises);
        return results.every(Boolean);
    },
    clearValidation: () => {
        Object.keys(itemErrors).forEach((key) => delete itemErrors[key]);
        validationMessage.value = '';
    },
    getValidationErrors: () => ({ ...itemErrors }),
    hasValidationErrors: () => Object.keys(itemErrors).length > 0
});
</script>
