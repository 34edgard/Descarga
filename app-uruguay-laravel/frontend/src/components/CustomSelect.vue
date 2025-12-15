<template>
    <div class="relative w-full" ref="selectContainer">
        <!-- Control del Selector -->
        <div class="flex items-center justify-between p-2 bg-white border rounded" :class="{
            'border-blue-500 ring-1 ring-blue-500': isOpen,
            'border-gray-300 dark:border-white': !isOpen,
            'border-red-500': !!errorMessage,
            'bg-gray-100 cursor-not-allowed': disabled,
            'cursor-pointer': !disabled,
            'dark:bg-black dark:text-gray-400': !disabled && !isOpen,
            'dark:hover:bg-gray-700': !disabled && !isOpen
        }" @click="!disabled && toggleDropdown()">
            <!-- Valores seleccionados (múltiple) -->
            <div v-if="multiple" class="flex flex-wrap flex-1 gap-1">
                <span v-for="(value, index) in selectedOptions" :key="index"
                    class="inline-flex items-center px-2 py-1 text-xs rounded-full" :class="{
                        'text-blue-800 bg-blue-100 dark:bg-blue-900 dark:text-blue-200': true
                    }">
                    {{ getOptionLabel(value) }}
                    <button type="button"
                        class="ml-1 text-blue-500 hover:text-blue-700 dark:text-blue-300 dark:hover:text-blue-100"
                        @click.stop="removeSelected(value)">
                        ×
                    </button>
                </span>
                <span v-if="selectedOptions.length === 0" class="text-gray-400 dark:text-gray-500">
                    {{ placeholder }}
                </span>
            </div>
            <!-- Valor seleccionado (simple) -->
            <div v-else class="flex-1 truncate">
                {{ displayValue || placeholder }}
            </div>
            <!-- Indicadores -->
            <div class="flex items-center ml-2">
                <span class="text-gray-500 dark:text-gray-400" v-if="loading">
                    <svg class="w-4 h-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </span>
                <span v-else class="transition-transform duration-200 transform" :class="{ 'rotate-180': isOpen }">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </span>
            </div>
        </div>
        <!-- Dropdown con Opciones -->
        <div v-if="isOpen && !disabled"
            class="absolute z-50 w-full mt-1 overflow-hidden bg-white border border-gray-300 rounded-md shadow-lg"
            :class="{
                'dark:bg-black dark:border-gray-700': true
            }">
            <!-- Buscador -->
            <div v-if="searchable" class="p-2 border-b border-gray-200 dark:border-gray-700">
                <input ref="searchInput" v-model="searchQuery" type="text"
                    class="w-full p-2 text-sm border rounded focus:outline-none focus:ring-1 focus:ring-blue-500"
                    :placeholder="searchPlaceholder" @click.stop @keydown.enter="handleAddNewOption" />
            </div>
            <!-- Lista de Opciones -->
            <div class="overflow-y-auto max-h-60">
                <!-- Opciones paginadas -->
                <div v-for="(option, index) in paginatedOptions" :key="index"
                    class="p-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700" :class="{
                        'bg-blue-50 text-blue-800 dark:bg-blue-900 dark:text-blue-200': isOptionSelected(option),
                        'hidden': !multiple && selectedOption && option.value === selectedOption.value
                    }" @click="selectOption(option)">
                    <span class="text-gray-700 dark:text-gray-400">{{ option.label }}</span>
                </div>
                <!-- Mensaje cuando no hay resultados -->
                <div v-if="filteredOptions.length === 0" class="p-2 text-sm italic text-gray-500 dark:text-gray-400">
                    No se encontraron resultados
                </div>
                <!-- Controles de paginación -->
                <div v-if="showPagination"
                    class="flex items-center justify-between p-2 border-t border-gray-200 dark:border-gray-700">
                    <button type="button"
                        class="px-2 py-1 text-sm rounded disabled:opacity-50 dark:text-gray-400 dark:bg-gray-700 dark:hover:bg-gray-600"
                        :disabled="currentPage === 1" @click.stop="prevPage">
                        &larr; Anterior
                    </button>
                    <span class="text-sm text-gray-600 dark:text-gray-400">
                        Página {{ currentPage }} de {{ totalPages }}
                    </span>
                    <button type="button"
                        class="px-2 py-1 text-sm rounded disabled:opacity-50 dark:text-gray-400 dark:bg-gray-700 dark:hover:bg-gray-600"
                        :disabled="currentPage === totalPages" @click.stop="nextPage">
                        Siguiente &rarr;
                    </button>
                </div>
                <!-- Botón para agregar nueva opción -->
                <div v-if="allowAddNew"
                    class="flex items-center justify-center p-2 text-blue-600 border-t cursor-pointer bg-gray-50 hover:bg-gray-100 dark:bg-gray-700 dark:text-blue-400 dark:hover:bg-gray-600"
                    @click="openAddNewModal">
                    <span class="mr-1">+</span> {{ addNewText }}
                </div>
            </div>
        </div>
        <!-- Modal para agregar nueva opción -->
        <div v-if="showAddNewModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="w-full max-w-md p-6 bg-white rounded-lg dark:bg-black dark:text-gray-400">
                <h3 class="mb-4 text-lg font-medium">{{ addNewModalTitle }}</h3>
                <input v-model="newOptionLabel" type="text"
                    class="w-full p-2 mb-4 border rounded focus:outline-none focus:ring-1 focus:ring-blue-500"
                    :placeholder="addNewPlaceholder" @keydown.enter="addNewOption" />
                <div class="flex justify-end space-x-2">
                    <button
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-400 dark:hover:bg-gray-600"
                        @click="closeAddNewModal">
                        Cancelar
                    </button>
                    <button
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-800"
                        @click="addNewOption">
                        Agregar
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>


<script setup lang="ts">
import { ref, computed, watch, onMounted, nextTick, onBeforeUnmount } from 'vue'

interface SelectOption {
    label: string
    value: any
}

const props = defineProps({
    modelValue: {
        type: [String, Number, Boolean, Object, Array] as any,
        default: null
    },
    options: {
        type: Array as () => SelectOption[],
        default: () => []
    },
    placeholder: {
        type: String,
        default: 'Seleccione una opción'
    },
    searchable: {
        type: Boolean,
        default: false
    },
    searchPlaceholder: {
        type: String,
        default: 'Buscar...'
    },
    multiple: {
        type: Boolean,
        default: false
    },
    allowAddNew: {
        type: Boolean,
        default: false
    },
    addNewText: {
        type: String,
        default: 'Agregar nueva opción'
    },
    addNewModalTitle: {
        type: String,
        default: 'Agregar nueva opción'
    },
    addNewPlaceholder: {
        type: String,
        default: 'Ingrese el nombre de la nueva opción'
    },
    loading: {
        type: Boolean,
        default: false
    },
    errorMessage: {
        type: String,
        default: ''
    },
    disabled: {
        type: Boolean,
        default: false
    },
    itemsPerPage: {
        type: Number,
        default: 10
    }
})

const emit = defineEmits({
    'update:modelValue': (value: any) => true,
    'add-new-option': (option: SelectOption) => true,
    'open': () => true
})

// Refs
const isOpen = ref(false)
const searchQuery = ref('')
const selectContainer = ref<HTMLElement | null>(null)
const searchInput = ref<HTMLInputElement | null>(null)
const showAddNewModal = ref(false)
const newOptionLabel = ref('')
const currentPage = ref(1)

// Computed
const selectedOption = computed(() => {
    if (props.multiple) return null
    return props.options.find(opt => opt.value === props.modelValue)
})

const selectedOptions = computed(() => {
    if (!props.multiple) return []
    if (!Array.isArray(props.modelValue)) return []
    return props.modelValue
})

const displayValue = computed(() => {
    return selectedOption.value?.label
})

const filteredOptions = computed(() => {
    let options = [...props.options]

    // Filtrar por búsqueda
    if (props.searchable && searchQuery.value) {
        options = options.filter(opt =>
            opt.label.toLowerCase().includes(searchQuery.value.toLowerCase()))
    }

    return options
})

const paginatedOptions = computed(() => {
    const start = (currentPage.value - 1) * props.itemsPerPage
    const end = start + props.itemsPerPage
    return filteredOptions.value.slice(start, end)
})

const totalPages = computed(() => {
    return Math.ceil(filteredOptions.value.length / props.itemsPerPage)
})

const showPagination = computed(() => {
    return filteredOptions.value.length > props.itemsPerPage
})

// Methods
const handleAddNewOption = () => {
    if (props.allowAddNew && searchQuery.value.trim()) {
        const newOption = {
            label: searchQuery.value.trim(),
            value: searchQuery.value.trim().toLowerCase().replace(/\s+/g, '-')
        }
        emit('add-new-option', newOption)
        searchQuery.value = ''
    }
}

const getOptionLabel = (value: any) => {
    const option = props.options.find(opt => opt.value === value)
    return option?.label || value
}

const toggleDropdown = async () => {
    isOpen.value = !isOpen.value

    if (isOpen.value) {
        emit('open')
        await nextTick()
        if (props.searchable && searchInput.value) {
            searchInput.value.focus()
        }
    } else {
        searchQuery.value = ''
        currentPage.value = 1
    }
}

const selectOption = (option: SelectOption) => {
    if (props.multiple) {
        const currentValue = Array.isArray(props.modelValue) ? [...props.modelValue] : []
        const index = currentValue.indexOf(option.value)

        if (index === -1) {
            currentValue.push(option.value)
        } else {
            currentValue.splice(index, 1)
        }

        emit('update:modelValue', currentValue)
    } else {
        emit('update:modelValue', option.value)
        isOpen.value = false
        searchQuery.value = ''
    }
}

const removeSelected = (value: any) => {
    if (!props.multiple) return

    const currentValue = Array.isArray(props.modelValue) ? [...props.modelValue] : []
    const index = currentValue.indexOf(value)

    if (index !== -1) {
        currentValue.splice(index, 1)
        emit('update:modelValue', currentValue)
    }
}

const isOptionSelected = (option: SelectOption) => {
    if (props.multiple) {
        return Array.isArray(props.modelValue) && props.modelValue.includes(option.value)
    } else {
        return props.modelValue === option.value
    }
}

const prevPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--
    }
}

const nextPage = () => {
    if (currentPage.value < totalPages.value) {
        currentPage.value++
    }
}

const openAddNewModal = () => {
    showAddNewModal.value = true
    isOpen.value = false
}

const closeAddNewModal = () => {
    showAddNewModal.value = false
    newOptionLabel.value = ''
}

const addNewOption = () => {
    if (!newOptionLabel.value.trim()) return

    const newOption = {
        label: newOptionLabel.value.trim(),
        value: newOptionLabel.value.trim().toLowerCase().replace(/\s+/g, '-')
    }

    emit('add-new-option', newOption)

    if (props.multiple) {
        const currentValue = Array.isArray(props.modelValue) ? [...props.modelValue] : []
        currentValue.push(newOption.value)
        emit('update:modelValue', currentValue)
    } else {
        emit('update:modelValue', newOption.value)
    }

    closeAddNewModal()
}

const handleClickOutside = (event: MouseEvent) => {
    if (selectContainer.value && !selectContainer.value.contains(event.target as Node)) {
        isOpen.value = false
    }
}

// Watchers
watch(searchQuery, () => {
    currentPage.value = 1
})

// Lifecycle hooks
onMounted(() => {
    document.addEventListener('click', handleClickOutside)
})

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside)
})
</script>

<style scoped>
button {
    cursor: pointer;
}
</style>