<script setup lang="ts">
import { computed, inject, ref, watch } from 'vue';
// Importa todos los componentes de PrimeVue necesarios
/*import InputText from 'primevue/inputtext';
import Textarea from 'primevue/textarea';
import Dropdown from 'primevue/dropdown';
import Calendar from 'primevue/calendar';
import RadioButton from 'primevue/radiobutton';
import InputNumber from 'primevue/inputnumber';
import InputMask from 'primevue/inputmask';
import InputSwitch from 'primevue/inputswitch';
import MultiSelect from 'primevue/multiselect';
import ColorPicker from 'primevue/colorpicker';
import Slider from 'primevue/slider';
import Rating from 'primevue/rating';
import Editor from 'primevue/editor';
import CascadeSelect from 'primevue/cascadeselect';
import InputGroup from 'primevue/inputgroup';
import InputGroupAddon from 'primevue/inputgroupaddon';
import FileUpload from 'primevue/fileupload';
import FloatLabel from 'primevue/floatlabel';
import ToggleButton from 'primevue/togglebutton';
import Checkbox from 'primevue/checkbox';*/

import type { FormField, LabelType, FieldSize } from './types';

const props = defineProps<{
    fieldConfig: FormField;
    labelType: LabelType | undefined | null;
    modelValue: any;
    errorMessage?: string | string[] | undefined;
    disabled?: boolean;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: any): void;
}>();

// Inyecta formValues para la lógica de dependsOn
const formValues = inject<Record<string, any>>('formValues', {});

const shouldShowField = computed(() => {
    if (!props.fieldConfig.dependsOn) return true;
    const dependentValue = formValues ? formValues[props.fieldConfig.dependsOn.field] : undefined;
    return dependentValue === props.fieldConfig.dependsOn.value;
});

const fieldClasses = computed(() => {
    const sizeClasses: Record<FieldSize, string> = {
        small: 'p-inputtext-sm',
        normal: '',
        large: 'p-inputtext-lg'
    };

    return [
        props.fieldConfig.style?.inputClass,
        sizeClasses[props.fieldConfig.size || 'normal'],
        { 'p-invalid': !!props.errorMessage }
    ];
});

const handleInput = (value: any) => {
    if (props.fieldConfig.type === 'date') {
        // No usar este handler para date, usar handleDateInput
        return;
    }
    emit('update:modelValue', value);
};

const handleFileUpload = (event: any) => {
    emit('update:modelValue', event.files);
};

const firstErrorMessage = computed(() => {
    if (Array.isArray(props.errorMessage)) {
        return props.errorMessage[0];
    }
    return props.errorMessage;
});

const suggestions = ref<any[]>([]);

watch(
    () => props.modelValue,
    (val) => {
        if (props.fieldConfig.type === 'autocomplete' && val != null) {
            const optionValue = props.fieldConfig.config?.optionValue || 'value';
            const optionLabel = props.fieldConfig.config?.optionLabel || 'label';
            let obj: any = null;
            if (typeof val === 'object') {
                obj = { ...val };
                obj[optionValue] = obj[optionValue] ?? obj.id ?? obj.value;
                obj[optionLabel] = obj[optionLabel] ?? obj.name ?? obj.label;
            } else {
                let found = null;
                if (Array.isArray(props.fieldConfig.options)) {
                    found = props.fieldConfig.options.find(opt => opt[optionValue] === val);
                }
                if (!found) {
                    found = suggestions.value.find(opt => opt[optionValue] === val);
                }
                if (found) {
                    obj = { ...found };
                } else {
                    obj = { [optionValue]: val, [optionLabel]: String(val) };
                }
            }
            if (obj && !suggestions.value.some(opt => opt[optionValue] === obj[optionValue])) {
                suggestions.value.push({
                    [optionLabel]: obj[optionLabel],
                    [optionValue]: obj[optionValue]
                });
            }
        }
    },
    { immediate: true }
);

const search = async (event: { query?: string } = {}) => {
    const query = event.query ?? '';
    console.log('AutoComplete search triggered. Query:', query);
    if (props.fieldConfig.config?.remoteSearch && typeof props.fieldConfig.config.remoteSearch === 'function') {
        const result = await props.fieldConfig.config.remoteSearch(query);
        console.log('remoteSearch result:', result);
        suggestions.value = result || [];
    } else if (Array.isArray(props.fieldConfig.options)) {
        const q = query.toLowerCase();
        suggestions.value = props.fieldConfig.options.filter((option: any) => {
            const label = option[props.fieldConfig.config?.optionLabel || 'name'] || '';
            return label.toLowerCase().includes(q);
        });
    } else {
        suggestions.value = [];
    }
};

function getSelectedOptionObject(value: any, options: any[], fieldConfig: any) {
    if (value == null) return null;
    const optionValue = fieldConfig.config?.optionValue || 'value';
    const optionLabel = fieldConfig.config?.optionLabel || 'label';
    if (typeof value === 'object') {
        // Si ya tiene label, úsalo tal cual
        if (value[optionLabel]) return value;
        // Si tiene name, mapea a label
        if (value.name) return { ...value, [optionLabel]: value.name };
        // Si tiene id, mapea a value
        if (value.id) return { ...value, [optionValue]: value.id };
        // Si tiene value, úsalo
        if (value[optionValue]) return value;
    }
    // Si es primitivo, busca primero en fieldConfig.options, luego en suggestions
    let found = null;
    if (Array.isArray(fieldConfig.options)) {
        found = fieldConfig.options.find(opt => opt[optionValue] === value);
    }
    if (!found) {
        found = options.find(opt => opt[optionValue] === value);
    }
    if (found) return found;
    return { [optionValue]: value, [optionLabel]: String(value) };
}
// --- FECHA: Manejo seguro para emitir siempre string 'YYYY-MM-DD' ---
const dateValue = ref<Date | null>(null);

watch(
    () => props.modelValue,
    (val) => {
        if (
            props.fieldConfig.type === 'date'
        ) {
            if (typeof val === 'string' && /^\d{4}-\d{2}-\d{2}$/.test(val)) {
                // Solo para el UI, convierte string a Date
                const [yyyy, mm, dd] = val.split('-');
                dateValue.value = new Date(Number(yyyy), Number(mm) - 1, Number(dd));
            } else if (val instanceof Date) {
                dateValue.value = val;
            } else {
                dateValue.value = null;
            }
        }
    },
    { immediate: true }
);

const handleDateInput = (value: Date | null) => {
    if (value instanceof Date && !isNaN(value.getTime())) {
        const yyyy = value.getFullYear();
        const mm = String(value.getMonth() + 1).padStart(2, '0');
        const dd = String(value.getDate()).padStart(2, '0');
        emit('update:modelValue', `${yyyy}-${mm}-${dd}`);
    } else {
        emit('update:modelValue', null);
    }
};
</script>

<template>
    <div v-if="shouldShowField" class="mb-4 card" :class="fieldConfig.style?.containerClass">
        <template
            v-if="labelType === 'float' && !['checkbox', 'radio', 'switch', 'rating', 'color', 'file', 'range', 'multiselect', 'cascade', 'inputgroup'].includes(fieldConfig.type)">
            <FloatLabel>
                <template v-if="['text', 'email', 'password', 'url'].includes(fieldConfig.type)">
                    <InputText :id="fieldConfig.name" :modelValue="modelValue" :type="fieldConfig.type"
                        :placeholder="fieldConfig.placeholder" :disabled="disabled || fieldConfig.disabled"
                        :class="fieldClasses" @update:modelValue="handleInput" />
                </template>
                <template v-else-if="fieldConfig.type === 'number' || fieldConfig.type === 'currency'">
                    <InputNumber :id="fieldConfig.name" :modelValue="modelValue"
                        :disabled="disabled || fieldConfig.disabled" :class="fieldClasses"
                        :mode="fieldConfig.type === 'currency' ? 'currency' : 'decimal'"
                        :currency="fieldConfig.config?.currency || 'USD'"
                        :locale="fieldConfig.config?.locale || 'en-US'"
                        :minFractionDigits="fieldConfig.config?.minFractionDigits"
                        :maxFractionDigits="fieldConfig.config?.maxFractionDigits" :min="fieldConfig.config?.min"
                        :max="fieldConfig.config?.max" @update:modelValue="handleInput" showButtons />
                </template>
                <template v-else-if="fieldConfig.type === 'select'">
                    <Dropdown :id="fieldConfig.name" :modelValue="modelValue" :options="fieldConfig.options"
                        :disabled="disabled || fieldConfig.disabled" :class="fieldClasses"
                        :optionLabel="fieldConfig.config?.optionLabel || 'label'"
                        :optionValue="fieldConfig.config?.optionValue || 'value'" :placeholder="fieldConfig.placeholder"
                        @update:modelValue="handleInput" />
                </template>
                <template v-else-if="fieldConfig.type === 'date'">
                    <Calendar :id="fieldConfig.name" :modelValue="dateValue"
                        :disabled="disabled || fieldConfig.disabled" :class="fieldClasses"
                        :dateFormat="fieldConfig.config?.dateFormat || 'dd/mm/yy'" :showIcon="true"
                        :placeholder="fieldConfig.placeholder" @update:modelValue="handleDateInput" />
                </template>
                <template v-else-if="fieldConfig.type === 'time'">
                    <Calendar :id="fieldConfig.name" :modelValue="modelValue"
                        :disabled="disabled || fieldConfig.disabled" :class="fieldClasses" timeOnly :showTime="true"
                        :hourFormat="fieldConfig.config?.hourFormat || '24'" :placeholder="fieldConfig.placeholder"
                        @update:modelValue="handleInput" />
                </template>
                <template v-else-if="fieldConfig.type === 'textarea'">
                    <Textarea :id="fieldConfig.name" :modelValue="modelValue" :placeholder="fieldConfig.placeholder"
                        :disabled="disabled || fieldConfig.disabled" :class="fieldClasses"
                        :rows="fieldConfig.config?.rows || 3" @update:modelValue="handleInput" autoResize />
                </template>
                <template v-else-if="fieldConfig.type === 'inputmask' || fieldConfig.type === 'phone'">
                    <InputMask :id="fieldConfig.name" :modelValue="modelValue"
                        :disabled="disabled || fieldConfig.disabled" :class="fieldClasses"
                        :mask="fieldConfig.config?.mask || (fieldConfig.type === 'phone' ? '(999) 999-9999' : '')"
                        :placeholder="fieldConfig.placeholder" :slotChar="fieldConfig.config?.slotChar || '_'"
                        :autoClear="fieldConfig.config?.autoClear ?? true" @update:modelValue="handleInput" />
                </template>
                <template v-else-if="fieldConfig.type === 'richtext'">
                    <Editor :id="fieldConfig.name" :modelValue="modelValue" :disabled="disabled || fieldConfig.disabled"
                        :class="fieldClasses" :editorStyle="{ height: fieldConfig.config?.heightEditor || '200px' }"
                        @update:modelValue="handleInput" />
                </template>
                <template v-else-if="fieldConfig.type === 'autocomplete'">
                    <AutoComplete
                        :id="fieldConfig.name"
                        :modelValue="getSelectedOptionObject(modelValue, suggestions, fieldConfig)"
                        :suggestions="suggestions"
                        :optionLabel="fieldConfig.config?.optionLabel || 'label'"
                        :optionValue="fieldConfig.config?.optionValue || 'value'"
                        :placeholder="fieldConfig.placeholder"
                        :disabled="disabled || fieldConfig.disabled"
                        :class="fieldClasses"
                        @update:modelValue="handleInput"
                        @complete="search"
                        :minLength="0"
                        dropdown
                    />
                </template>
                <label :for="fieldConfig.name">{{ fieldConfig.label }}</label>
            </FloatLabel>
        </template>

        <template v-else>
            <label
                v-if="fieldConfig.type !== 'checkbox' && fieldConfig.type !== 'switch' && fieldConfig.type !== 'toggle' && fieldConfig.type !== 'radio'"
                :for="fieldConfig.name" class="block mb-1 text-sm font-medium text-gray-700 dark:text-white"
                :class="fieldConfig.style?.labelClass">
                {{ fieldConfig.label }}
                <span v-if="fieldConfig.required" class="text-red-500">*</span>
            </label>

            <template v-if="['text', 'email', 'password', 'url'].includes(fieldConfig.type)">
                <InputText :id="fieldConfig.name" :modelValue="modelValue" :type="fieldConfig.type"
                    :placeholder="fieldConfig.placeholder" :disabled="disabled || fieldConfig.disabled"
                    :class="fieldClasses" @update:modelValue="handleInput" />
            </template>

            <template v-else-if="fieldConfig.type === 'textarea'">
                <Textarea :id="fieldConfig.name" :modelValue="modelValue" :placeholder="fieldConfig.placeholder"
                    :disabled="disabled || fieldConfig.disabled" :class="fieldClasses"
                    :rows="fieldConfig.config?.rows || 3" @update:modelValue="handleInput" autoResize />
            </template>

            <template v-else-if="fieldConfig.type === 'select'">
                <Dropdown :id="fieldConfig.name" :modelValue="modelValue" :options="fieldConfig.options"
                    :disabled="disabled || fieldConfig.disabled" :class="fieldClasses"
                    :optionLabel="fieldConfig.config?.optionLabel || 'label'"
                    :optionValue="fieldConfig.config?.optionValue || 'value'" :placeholder="fieldConfig.placeholder"
                    @update:modelValue="handleInput" />
            </template>

            <template v-else-if="fieldConfig.type === 'number' || fieldConfig.type === 'currency'">
                <InputNumber :id="fieldConfig.name" :modelValue="modelValue"
                    :disabled="disabled || fieldConfig.disabled" :class="fieldClasses"
                    :mode="fieldConfig.type === 'currency' ? 'currency' : 'decimal'"
                    :currency="fieldConfig.config?.currency || 'USD'" :locale="fieldConfig.config?.locale || 'en-US'"
                    :minFractionDigits="fieldConfig.config?.minFractionDigits"
                    :maxFractionDigits="fieldConfig.config?.maxFractionDigits" :min="fieldConfig.config?.min"
                    :max="fieldConfig.config?.max" @update:modelValue="handleInput" showButtons />
            </template>

            <template v-else-if="fieldConfig.type === 'date'">
                <Calendar :id="fieldConfig.name" :modelValue="dateValue" :disabled="disabled || fieldConfig.disabled"
                    :class="fieldClasses" :dateFormat="fieldConfig.config?.dateFormat || 'dd/mm/yy'" :showIcon="true"
                    :placeholder="fieldConfig.placeholder" @update:modelValue="handleDateInput" />
            </template>

            <template v-else-if="fieldConfig.type === 'time'">
                <Calendar :id="fieldConfig.name" :modelValue="modelValue" :disabled="disabled || fieldConfig.disabled"
                    :class="fieldClasses" timeOnly :showTime="true" :hourFormat="fieldConfig.config?.hourFormat || '24'"
                    :placeholder="fieldConfig.placeholder" @update:modelValue="handleInput" />
            </template>

            <template v-else-if="fieldConfig.type === 'radio' && fieldConfig.options">
                <label :for="fieldConfig.name" class="block mb-1 text-sm font-medium text-gray-700 dark:text-white"
                    :class="fieldConfig.style?.labelClass">
                    {{ fieldConfig.label }}
                    <span v-if="fieldConfig.required" class="text-red-500">*</span>
                </label>
                <div class="flex flex-wrap gap-4 mt-2">
                    <div v-for="option in fieldConfig.options" :key="option.value?.toString()"
                        class="flex items-center">
                        <RadioButton :inputId="`${fieldConfig.name}-${option.value}`" :name="fieldConfig.name"
                            :value="option.value" :modelValue="modelValue"
                            :disabled="disabled || fieldConfig.disabled || option.disabled"
                            :class="fieldConfig.style?.inputClass" @update:modelValue="handleInput" />
                        <label :for="`${fieldConfig.name}-${option.value}`" class="ml-2">
                            {{ option.label }}
                        </label>
                    </div>
                </div>
            </template>

            <template v-else-if="fieldConfig.type === 'checkbox'">
                <div class="flex items-center gap-2">
                    <InputSwitch v-if="fieldConfig.config?.asSwitch" :id="fieldConfig.name" :modelValue="modelValue"
                        :disabled="disabled || fieldConfig.disabled" @update:modelValue="handleInput" />
                    <Checkbox v-else :id="fieldConfig.name" :modelValue="modelValue"
                        :binary="fieldConfig.config?.binary ?? true" :disabled="disabled || fieldConfig.disabled"
                        @update:modelValue="handleInput" />
                    <label :for="fieldConfig.name" class="text-sm font-medium text-gray-700 dark:text-white"
                        :class="fieldConfig.style?.labelClass">
                        {{ fieldConfig.label }}
                        <span v-if="fieldConfig.required" class="text-red-500">*</span>
                    </label>
                </div>
            </template>

            <template v-else-if="fieldConfig.type === 'switch' || fieldConfig.type === 'toggle'">
                <div class="flex items-center gap-2">
                    <InputSwitch v-if="fieldConfig.type === 'switch'" :id="fieldConfig.name" :modelValue="modelValue"
                        :disabled="disabled || fieldConfig.disabled" @update:modelValue="handleInput" />
                    <ToggleButton v-else :id="fieldConfig.name" :modelValue="modelValue"
                        :disabled="disabled || fieldConfig.disabled" :onLabel="fieldConfig.config?.onLabel || 'Si'"
                        :offLabel="fieldConfig.config?.offLabel || 'No'" @update:modelValue="handleInput" />
                    <label :for="fieldConfig.name" class="text-sm font-medium text-gray-700 dark:text-white"
                        :class="fieldConfig.style?.labelClass">
                        {{ fieldConfig.label }}
                        <span v-if="fieldConfig.required" class="text-red-500">*</span>
                    </label>
                </div>
            </template>

            <template v-else-if="fieldConfig.type === 'color'">
                <ColorPicker :id="fieldConfig.name" :modelValue="modelValue"
                    :disabled="disabled || fieldConfig.disabled" :class="fieldClasses"
                    :format="fieldConfig.config?.formatColor || 'hex'" @update:modelValue="handleInput" />
            </template>

            <template v-else-if="fieldConfig.type === 'range'">
                <Slider :id="fieldConfig.name" :modelValue="modelValue" :disabled="disabled || fieldConfig.disabled"
                    :class="fieldClasses" :min="fieldConfig.config?.min || 0" :max="fieldConfig.config?.max || 100"
                    :step="fieldConfig.config?.step || 1" @update:modelValue="handleInput" />
            </template>

            <template v-else-if="fieldConfig.type === 'rating'">
                <Rating :id="fieldConfig.name" :modelValue="modelValue" :disabled="disabled || fieldConfig.disabled"
                    :class="fieldClasses" :stars="fieldConfig.config?.stars || 5"
                    :cancel="fieldConfig.config?.cancel ?? false" @update:modelValue="handleInput" />
            </template>

            <template v-else-if="fieldConfig.type === 'richtext'">
                <Editor :id="fieldConfig.name" :modelValue="modelValue" :disabled="disabled || fieldConfig.disabled"
                    :class="fieldClasses" :editorStyle="{ height: fieldConfig.config?.heightEditor || '200px' }"
                    @update:modelValue="handleInput" />
            </template>

            <template v-else-if="fieldConfig.type === 'cascade'">
                <CascadeSelect :id="fieldConfig.name" :modelValue="modelValue" :options="fieldConfig.options"
                    :disabled="disabled || fieldConfig.disabled" :class="fieldClasses"
                    :optionLabel="fieldConfig.config?.optionLabel || 'label'"
                    :optionValue="fieldConfig.config?.optionValue || 'value'"
                    :optionGroupLabel="fieldConfig.config?.optionGroupLabel"
                    :optionGroupChildren="fieldConfig.config?.optionGroupChildren"
                    :placeholder="fieldConfig.placeholder" @update:modelValue="handleInput" />
            </template>

            <template v-else-if="fieldConfig.type === 'inputgroup'">
                <InputGroup :class="fieldClasses">
                    <InputGroupAddon v-if="fieldConfig.inputGroup?.before">
                        <template v-for="(addon, index) in fieldConfig.inputGroup.before" :key="`before-${index}`">
                            <i v-if="addon.type === 'icon'" :class="addon.icon"></i>
                            <span v-else-if="addon.type === 'text'">{{ addon.content }}</span>
                        </template>
                    </InputGroupAddon>
                    <InputText :id="fieldConfig.name" :modelValue="modelValue" :placeholder="fieldConfig.placeholder"
                        :disabled="disabled || fieldConfig.disabled" @update:modelValue="handleInput" />
                    <InputGroupAddon v-if="fieldConfig.inputGroup?.after">
                        <template v-for="(addon, index) in fieldConfig.inputGroup.after" :key="`after-${index}`">
                            <i v-if="addon.type === 'icon'" :class="addon.icon"></i>
                            <span v-else-if="addon.type === 'text'">{{ addon.content }}</span>
                        </template>
                    </InputGroupAddon>
                </InputGroup>
            </template>

            <template v-else-if="fieldConfig.type === 'inputmask' || fieldConfig.type === 'phone'">
                <InputMask :id="fieldConfig.name" :modelValue="modelValue" :disabled="disabled || fieldConfig.disabled"
                    :class="fieldClasses"
                    :mask="fieldConfig.config?.mask || (fieldConfig.type === 'phone' ? '(999) 999-9999' : '')"
                    :placeholder="fieldConfig.placeholder" :slotChar="fieldConfig.config?.slotChar || '_'"
                    :autoClear="fieldConfig.config?.autoClear ?? true" @update:modelValue="handleInput" />
            </template>

            <template v-else-if="fieldConfig.type === 'file'">
                <FileUpload :id="fieldConfig.name" :multiple="fieldConfig.config?.multiple || false"
                    :accept="fieldConfig.config?.accept" :maxFileSize="fieldConfig.config?.maxSize || 1000000"
                    :chooseLabel="fieldConfig.config?.chooseLabelFileUpload || 'Seleccionar'"
                    :uploadLabel="fieldConfig.config?.uploadLabelFileUpload || 'Subir'"
                    :cancelLabel="fieldConfig.config?.cancelLabelFileUpload || 'Cancelar'"
                    :disabled="disabled || fieldConfig.disabled" @select="handleFileUpload" mode="basic"
                    :class="fieldClasses" />
            </template>

            <template v-else-if="fieldConfig.type === 'autocomplete'">
                <AutoComplete
                    :id="fieldConfig.name"
                    :modelValue="getSelectedOptionObject(modelValue, suggestions, fieldConfig)"
                    :suggestions="suggestions"
                    :optionLabel="fieldConfig.config?.optionLabel || 'label'"
                    :optionValue="fieldConfig.config?.optionValue || 'value'"
                    :placeholder="fieldConfig.placeholder"
                    :disabled="disabled || fieldConfig.disabled"
                    :class="fieldClasses"
                    @update:modelValue="handleInput"
                    @complete="search"
                    :minLength="0"
                    :forceSelection="false"
                    dropdown
                />
            </template>
        </template>

        <small v-if="firstErrorMessage" :id="`${fieldConfig.name}-error`" class="mt-1 text-xs p-error">
            {{ firstErrorMessage }}
        </small>
        <small v-if="fieldConfig.style?.helpText" :id="`${fieldConfig.name}-help`" class="mt-1 text-xs text-gray-500"
            :class="fieldConfig.style?.helpTextClass">
            {{ fieldConfig.style.helpText }}
        </small>
    </div>
</template>

<style scoped>
.form-field-wrapper {
    @apply mb-4;
}

/* Estilos de campo y errores */
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

:deep(.p-float-label input:focus~label),
:deep(.p-float-label .p-filled~label) {
    @apply text-blue-500;
}

:deep(.p-float-label .p-invalid~label) {
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
</style>