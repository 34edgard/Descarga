# ✨ Generador de Formularios Dinámicos en Vue 3 con PrimeVue y Zod ✨

![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)
![Tech Stack](https://img.shields.io/badge/Vue.js%203-4FC08D?style=flat-square&logo=vuedotjs&logoColor=white)
![Tech Stack](https://img.shields.io/badge/PrimeVue-10B981?style=flat-square&logo=primefaces&logoColor=white)
![Tech Stack](https://img.shields.io/badge/Zod-3E66FF?style=flat-square&logo=zod&logoColor=white)
![Tech Stack](https://img.shields.io/badge/TypeScript-3178C6?style=flat-square&logo=typescript&logoColor=white)

---

## 🚀 Introducción

Este proyecto presenta un componente reutilizable en Vue 3 (usando Composition API con `<script setup>`) que permite renderizar formularios complejos de manera dinámica a partir de un objeto de configuración simple. Utiliza los componentes de UI de [PrimeVue v4+](https://primevue.org/) y la librería de validación de schemas [Zod](https://zod.dev/) para un manejo robusto de la validación y los tipos.

A diferencia de soluciones como `vee-validate/useForm`, este generador gestiona el estado y la validación del formulario de forma **manual** utilizando `reactive` y `computed` de Vue, junto con la poderosa validación de schemas de Zod. Soporta tanto layouts de formulario planos como layouts organizados en pestañas (Tabs) con indicación visual de errores por sección.

## 🌟 Características Principales

- **Renderizado Dinámico:** Construye formularios completos a partir de un JSON o un objeto JavaScript.
- **Integración con PrimeVue:** Utiliza una amplia gama de componentes de PrimeVue para los campos de formulario.
- **Validación Robusta con Zod:** Define schemas de validación poderosos y seguros usando Zod directamente en la configuración del campo.
- **Manejo Manual del Estado y Validación:** Gestión transparente del estado del formulario (`formValues`) y los errores (`formErrors`) sin dependencias complejas de hooks de formulario.
- **Layout Flexible:** Soporta renderizado de formulario plano o dividido en pestañas (Tabs).
- **Layout Grid Configurable:** Define la estructura de columnas (grid) a nivel global del formulario, por pestaña, o por campo individual.
- **Indicación de Errores por Pestaña:** Muestra visualmente qué pestañas contienen campos con errores de validación.
- **Campos Condicionales (`dependsOn`):** Controla la visibilidad/estado de los campos basados en el valor de otros campos.
- **Callbacks `onChange`:** Ejecuta lógica personalizada cuando cambia el valor de un campo específico.
- **Tipado Fuerte:** Construido con TypeScript para una mayor seguridad y mantenibilidad.

## 🛠️ Requisitos Previos

Antes de usar este componente, asegúrate de tener configurado en tu proyecto de Vue 3:

- **Vue 3** (v3.x, Composition API)
- **PrimeVue** (v4+ recomendado, con componentes registrados globalmente o importados donde se usen)
- **PrimeIcons** (Para iconos de PrimeVue, necesario para el indicador de error en pestañas)
- **Zod** (`npm install zod`)
- **Tailwind CSS** u otro framework CSS/utilidades para Grid Layout (clases como `grid`, `gap-*`, `col-span-*`).
- **TypeScript** (Necesario para los archivos `.ts` y recomendado para los componentes `.vue`).

## Instalar

Copia los siguientes archivos a la estructura de tu proyecto. Una ubicación común podría ser `src/components/DynamicForm/`.

1.  `src/components/DynamicForm/types.ts`
2.  `src/components/DynamicForm/DynamicField.vue`
3.  `src/components/DynamicForm/DynamicForm.vue`
4.  Un archivo para definir tus configuraciones de campos, por ejemplo `src/configs/employeeFields.ts` o `src/components/DynamicForm/fieldsExample.ts`.

Instala las dependencias necesarias:

```bash
npm install primevue@^4.0.0 primeicons zod
# o con yarn
yarn add primevue@^4.0.0 primeicons zod
```

Asegúrate de que PrimeVue y PrimeIcons estén correctamente configurados en tu `main.ts` (o donde inicialices tu aplicación Vue):

```typescript
// main.ts
import { createApp } from 'vue';
import App from './App.vue';
import PrimeVue from 'primevue/config';
import 'primevue/resources/themes/lara-light-green/theme.css'; // Tu tema
import 'primeicons/primeicons.css'; // Iconos
// Importar y registrar componentes específicos si no los registras todos globalmente
// import InputText from 'primevue/inputtext';
// ... (otros componentes usados en DynamicField)
// Importar componentes de Tabs v4
import { Tabs, TabList, Tab, TabPanels, TabPanel } from 'primevue/tabs';
// Importar Button si lo usas
import Button from 'primevue/button';
// Importar y registrar v-tooltip si lo usas
import Tooltip from 'primevue/tooltip';

const app = createApp(App);

app.use(PrimeVue);

// Registro global de componentes y directivas si no lo haces via un plugin o por componente
app.component('InputText', InputText);
// ... registra todos los componentes de DynamicField que necesites
app.component('Tabs', Tabs);
app.component('TabList', TabList);
app.component('Tab', Tab);
app.component('TabPanels', TabPanels);
app.component('TabPanel', TabPanel);
app.component('Button', Button); // Si PrimeVue Button no está global por defecto

app.directive('tooltip', Tooltip); // Registra v-tooltip

app.mount('#app');
```

_(Nota: El registro de componentes puede variar dependiendo de tu setup de PrimeVue. Asegúrate de que todos los componentes usados en `DynamicField.vue` estén disponibles)_

## 🚀 Uso Rápido

1.  **Define tus campos** en un archivo de configuración (ej. `employeeFields.ts`) usando la estructura `FormTab[]` para pestañas o `FormField[]` para plano (ver sección de Configuración Detallada).
2.  **Importa `DynamicForm`** y tu configuración en el componente donde quieras renderizar el formulario.
3.  **Usa el componente** `<DynamicForm>` pasando tu objeto de configuración a la prop `:config` y manejando los eventos `@submit` y `@cancel`.

<!-- end list -->

```vue
<script setup lang="ts">
import { ref } from 'vue';
import DynamicForm from '@/components/DynamicForm/DynamicForm.vue'; // Ajusta la ruta
// Importa tu configuración de campos (ej: para modo pestañas)
import { formTabs } from '@/configs/employeeFields'; // Ajusta la ruta

// Opcional: Importa una configuración plana para probar ese modo
// import { flatFieldsExample } from '@/configs/employeeFields';

// Define tu objeto de configuración basado en los tipos FormConfig
const myFormConfig = ref({
    title: 'Mi Formulario de Ejemplo',
    description: 'Por favor, complete los datos',
    tabs: formTabs, // Usa la configuración con pestañas
    // Si quieres el modo plano, usa:
    // fields: flatFieldsExample, // Usa la configuración plana
    // tabs: undefined, // Asegúrate de que 'tabs' no exista o esté undefined si usas 'fields'

    colsPerRow: 12, // Grid base para los paneles/layout plano

    submitButtonText: 'Enviar Datos',
    cancelButtonText: 'Cancelar',

    // Define cómo manejar el envío del formulario
    onSubmit: async (formData) => {
        console.log('Formulario Enviado!', formData);
        // Aquí va tu lógica para enviar los datos (ej. a una API)
        alert('Datos enviados (simulado)! Revisa la consola.');
    },

    // Define cómo manejar la cancelación
    onCancel: () => {
        console.log('Formulario Cancelado');
        // Aquí va tu lógica para cancelar (ej. cerrar modal, redirigir)
        alert('Acción cancelada!');
    }

    // Opcional: Valores iniciales para editar
    // initialValues: { fieldName1: 'valor', fieldName2: 123 }

    // Opcional: Handlers before/after submit
    // beforeSubmit: (data) => { console.log('Antes de enviar', data); return data; },
    // afterSubmit: (result) => { console.log('Después de enviar', result); }
});

// Opcional: Alternar entre configuraciones (para demostración)
// const toggleLayout = () => {
//     if (myFormConfig.value.tabs) {
//         myFormConfig.value = { ...myFormConfig.value, tabs: undefined, fields: flatFieldsExample, title: 'Mi Formulario (Plano)' };
//     } else {
//         myFormConfig.value = { ...myFormConfig.value, fields: undefined, tabs: formTabs, title: 'Mi Formulario (Pestañas)' };
//     }
// };
</script>

<template>
    <div>
        <div class="card"><DynamicForm :config="myFormConfig.value" @submit="myFormConfig.value.onSubmit" @cancel="myFormConfig.value.onCancel" /></div>
    </div>
</template>

<style scoped>
/* Tus estilos aquí si es necesario */
.card {
    /* Estilos para el contenedor principal */
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    max-width: 800px; /* Ejemplo */
    margin: 20px auto;
}
</style>
```

## 🧩 Configuración Detallada

La funcionalidad del generador se controla mediante un objeto de configuración que debe ajustarse a la interfaz `FormConfig`. La configuración puede definir un formulario plano o un formulario con pestañas.

### `FormConfig` Interface

```typescript
interface FormConfig {
    title?: string; // Título principal del formulario
    description?: string; // Descripción principal del formulario
    tabs?: FormTab[]; // **Array de pestañas (prioritario)**. Si está presente, renderiza con pestañas.
    fields?: FormField[]; // **Array de campos planos**. Si no hay 'tabs' y sí hay 'fields', renderiza plano.
    submitButtonText?: string; // Texto del botón principal de submit ('Guardar' por defecto)
    submitAndStayText?: string; // Texto para un botón adicional 'Guardar y Seguir' (si se implementa el handler)
    cancelButtonText?: string; // Texto del botón cancelar ('Cancelar' por defecto). Establecer a null para ocultar.
    layout?: LayoutType; // Tipo de layout global ('grid' es común con este componente)
    colsPerRow?: number; // **Número de columnas por defecto** para el layout grid (para pestañas sin colsPerRow o para el layout plano). Base 12 común.
    size?: FieldSize; // Tamaño por defecto de los campos PrimeVue ('normal' por defecto)
    labelType?: LabelType; // Tipo de etiqueta por defecto ('normal' o 'float')
    style?: {
        // Clases CSS personalizadas para varias partes del formulario
        formClass?: string;
        titleClass?: string;
        descriptionClass?: string;
        rowClass?: string; // Clase para los div que agrupan campos por fila
        fieldClass?: string; // Clase para el contenedor de cada DynamicField
        actionsClass?: string;
        submitButtonClass?: string;
        cancelButtonClass?: string;
        submitAndStayButtonClass?: string;
    };
    onSubmit: (data: Record<string, any>) => Promise<void> | void; // **Función a ejecutar al enviar el formulario y pasar la validación.** Recibe los datos validados.
    onCancel?: () => void; // Función a ejecutar al hacer clic en el botón cancelar.
    redirectOnSubmit?: string; // Ruta a la que redirigir después de un submit exitoso (opcional, usa vue-router).
    beforeSubmit?: (data: Record<string, any>) => Record<string, any> | Promise<Record<string, any>>; // Función opcional para transformar/procesar datos antes de onSubmit.
    afterSubmit?: (result: { success: boolean; error?: any; action?: 'submit' | 'stay' }) => void; // Callback opcional después de intentar el submit.
    initialValues?: Record<string, any>; // Valores iniciales para precargar el formulario (útil para edición).
}
```

### `FormTab` Interface (Usada dentro de `FormConfig` si se usa modo pestañas)

```typescript
interface FormTab {
    name: string; // Identificador único para la pestaña (usado internamente, ej. para errores)
    label: string; // Título visible en la cabecera de la pestaña
    fields: FormField[]; // **Array de campos que pertenecen a ESTA pestaña.**
    colsPerRow?: number; // **Opcional: Número de columnas específico para el layout grid DENTRO de esta pestaña.** Si no se define, usa FormConfig.colsPerRow. Base 12 común.
    description?: string; // Descripción opcional específica de la pestaña.
}
```

### `FormField` Interface (Usada dentro de `FormTab.fields` o `FormConfig.fields`)

```typescript
interface FormField {
    name: string; // **Nombre único del campo.** Clave usada en formValues y formErrors.
    label: string; // Etiqueta visible del campo.
    type: FieldType; // **Tipo de componente PrimeVue a renderizar** (ver Tipos de Campo abajo).
    placeholder?: string; // Texto placeholder del input.
    required?: boolean; // **INDICADOR UI:** Muestra * si true. La validación requerida debe definirse en el schema Zod.
    disabled?: boolean; // Deshabilita el campo.
    readonly?: boolean; // Hace el campo de solo lectura.
    defaultValue?: any; // Valor por defecto si initialValues no lo sobreescribe.
    options?: FieldOption[]; // Opciones para 'select', 'radio', 'multiselect', 'cascade'.
    cols?: number; // **Número de columnas que ocupa ESTE campo** dentro de la fila/sección actual (layout grid). Si no se define, ocupa todas las columnas disponibles (ej. 12).
    size?: FieldSize; // Tamaño específico para este campo (sobreescribe el global).
    labelType?: LabelType; // Tipo de etiqueta específico para este campo (sobreescribe el global).
    style?: StyleConfig; // Clases CSS personalizadas específicas para este campo.
    inputGroup?: {
        // Configuración para InputGroup de PrimeVue (para type 'inputgroup')
        before?: InputGroupAddon[];
        after?: InputGroupAddon[];
    };
    config?: FieldConfig; // **Configuración específica para el componente PrimeVue del tipo 'type'.** (Ej. rows para textarea, currency para InputNumber, accept para FileUpload, etc.)
    dependsOn?: {
        // Configuración para mostrar/ocultar el campo basado en otro.
        field: string; // Nombre del campo del que depende.
        value: any; // Valor que debe tener el campo dependiente para que este campo se muestre.
        action: 'show' | 'hide' | 'enable' | 'disable'; // 'show' es lo más común para dependeOn.
    };
    validation?: (zod: typeof z) => ZodTypeAny; // **Función que retorna el schema Zod para ESTE campo.** (Ver Validación).
    onChange?: (value: any, formValues: Record<string, any>) => void; // Callback opcional ejecutado cuando el valor de este campo cambia. Recibe el nuevo valor y todos los valores del formulario.
}
```

### `FieldType` Enum (Usado en `FormField.type`)

Define los tipos de campo soportados, mapeados a componentes específicos de PrimeVue dentro de `DynamicField.vue`.

```typescript
type FieldType =
    | 'text'
    | 'number'
    | 'email'
    | 'password'
    | 'textarea'
    | 'select'
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
```

_(Nota: La lista de `FieldType` en `types.ts` puede ser más extensa que los ejemplos en `DynamicField.vue`. Asegúrate de que `DynamicField.vue` tenga `template`s para todos los tipos que listes aquí y que los componentes de PrimeVue correspondientes estén importados/disponibles.)_

## 📊 Tipos de Campo Disponibles

La columna `cols` en `FormField` define el ancho del campo dentro del layout grid de su fila/sección. Por ejemplo, en un grid de 12 columnas (`colsPerRow: 12`), un campo con `cols: 6` ocupará la mitad del ancho de la fila.

| `type`        | Componente PrimeVue Principal     | Notas                                                                                                                               |
| :------------ | :-------------------------------- | :---------------------------------------------------------------------------------------------------------------------------------- |
| `text`        | `InputText`                       | Campo de texto simple.                                                                                                              |
| `number`      | `InputNumber`                     | Entrada numérica con botones.                                                                                                       |
| `email`       | `InputText` (type="email")        | Entrada de email.                                                                                                                   |
| `password`    | `InputText` (type="password")     | Entrada de contraseña.                                                                                                              |
| `textarea`    | `Textarea`                        | Área de texto multi-línea. `config.rows` para filas, `autoResize`.                                                                  |
| `select`      | `Dropdown`                        | Selector de una sola opción. `options` es requerido.                                                                                |
| `checkbox`    | `Checkbox` / `InputSwitch`        | Checkbox (binario). Puede renderizarse como `InputSwitch` con `config.asSwitch`.                                                    |
| `radio`       | `RadioButton`                     | Botones de radio. `options` es requerido.                                                                                           |
| `date`        | `Calendar` (modo fecha)           | Selector de fecha. `config.dateFormat`.                                                                                             |
| `file`        | `FileUpload` (modo básico)        | Subida de archivos. `config.accept`, `config.multiple`, `config.maxSize`.                                                           |
| `toggle`      | `ToggleButton`                    | Botón de alternancia booleano. `config.onLabel`, `config.offLabel`.                                                                 |
| `multiselect` | `MultiSelect`                     | Selector de múltiples opciones. `options` es requerido.                                                                             |
| `color`       | `ColorPicker`                     | Selector de color. `config.formatColor`.                                                                                            |
| `range`       | `Slider`                          | Slider para seleccionar un valor dentro de un rango. `config.min`, `config.max`, `config.step`.                                     |
| `rating`      | `Rating`                          | Componente de calificación por estrellas. `config.stars`, `config.cancel`.                                                          |
| `switch`      | `InputSwitch`                     | Interruptor booleano.                                                                                                               |
| `time`        | `Calendar` (modo hora)            | Selector de hora. `config.hourFormat`.                                                                                              |
| `currency`    | `InputNumber` (modo moneda)       | Entrada numérica con formato de moneda. `config.currency`, `config.locale`, `config.minFractionDigits`, `config.maxFractionDigits`. |
| `phone`       | `InputMask` (máscara de teléfono) | Entrada con máscara predefinida para teléfono. `config.mask`.                                                                       |
| `url`         | `InputText` (type="url")          | Entrada de URL.                                                                                                                     |
| `richtext`    | `Editor`                          | Editor de texto enriquecido. `config.heightEditor`.                                                                                 |
| `cascade`     | `CascadeSelect`                   | Selector en cascada. `options` es requerido.                                                                                        |
| `inputgroup`  | `InputGroup` + `InputText`        | Campo de texto con addons (texto, iconos) antes o después. `inputGroup`.                                                            |
| `inputmask`   | `InputMask`                       | Entrada con máscara personalizada. `config.mask`, `config.slotChar`.                                                                |

## ✅ Validación con Zod

La validación se define directamente en el campo `validation` como una función que recibe el objeto `z` de Zod y debe retornar el schema Zod correspondiente para el valor de ese campo.

Cuando el formulario se envía (`handleSubmit`) o un campo cambia (`handleFieldChange` si implementas validación on-change), se ejecuta `schema.safeParseAsync(value)` (o `formSchema.safeParseAsync(formValues)` para el formulario completo). Los errores resultantes (`result.error.issues`) se mapean al objeto reactivo `formErrors`.

**Ejemplos:**

```typescript
// En tu archivo fields.ts
import { z, type ZodTypeAny } from 'zod';
import type { FormField } from '@/components/DynamicForm/types'; // Ajusta la ruta

const myFields: FormField[] = [
    {
        name: 'username',
        label: 'Nombre de Usuario',
        type: 'text',
        required: true, // UI indicator
        validation: (
            zod: typeof z
        ): ZodTypeAny => // Zod schema function
            zod
                .string()
                .min(1, { message: 'El nombre de usuario es requerido.' }) // Required validation
                .min(3, { message: 'Debe tener al menos 3 caracteres.' })
                .max(20, { message: 'No puede exceder los 20 caracteres.' })
    },
    {
        name: 'age',
        label: 'Edad',
        type: 'number',
        required: false, // Not required
        validation: (
            zod: typeof z
        ): ZodTypeAny => // Zod schema
            zod.coerce
                .number({ invalid_type_error: 'Debe ser un número.' }) // Coerce input to number
                .min(18, { message: 'Debe ser mayor de 18 años.' })
                .max(99, { message: 'Debe ser menor de 100 años.' })
                .optional() // Allows undefined
                .nullable() // Allows null
    },
    {
        name: 'email',
        label: 'Email',
        type: 'email',
        required: true,
        validation: (zod: typeof z): ZodTypeAny => zod.string().email({ message: 'Formato de email inválido.' }).nonempty({ message: 'El email es requerido.' }) // Zod schema // Asserts non-empty string
    },
    {
        name: 'password',
        label: 'Contraseña',
        type: 'password',
        required: true,
        validation: (
            zod: typeof z
        ): ZodTypeAny => // Zod schema con refinamiento
            zod
                .string()
                .min(8, { message: 'La contraseña debe tener al menos 8 caracteres.' })
                .refine((val) => /[A-Z]/.test(val), { message: 'Debe contener al menos una mayúscula.' })
                .refine((val) => /[0-9]/.test(val), { message: 'Debe contener al menos un número.' })
    }
    // ... otros campos
];

// Exporta tus campos planos o agrupados en pestañas
// export const flatFields = myFields;
// export const formTabs = [{ name: 'tab1', label: 'Datos', fields: myFields }];
```

## ⚙️ Funcionalidades Avanzadas

### Campos Condicionales (`dependsOn`)

Puedes hacer que un campo aparezca u oculte basado en el valor de otro campo utilizando la propiedad `dependsOn`:

```typescript
{
    name: 'reason_inactive',
    label: 'Razón de Inactividad',
    type: 'textarea',
    required: false,
    // ... otras props ...
    dependsOn: {
        field: 'employment_status', // El campo del que depende
        value: 'inactivo', // El valor que debe tener 'employment_status'
        action: 'show', // La acción: 'show' (mostrar) u 'hide' (ocultar)
        // Nota: 'enable' / 'disable' no están implementados en el DynamicField actual,
        // solo 'show' / 'hide'.
    },
    validation: (zod, formValues) => {
        // Zod validation can also be conditional based on other fields
        return zod.string().optional().nullable()
            .refine((val, ctx) => {
                // Accede al valor del campo dependiente desde el contexto de Zod
                const status = (ctx.parent.data as any).employment_status;
                if (status === 'inactivo' && !val) {
                    ctx.addIssue({ code: zod.ZodIssueCode.custom, message: 'Debe especificar la razón.' });
                    return false;
                }
                return true;
            }, { message: 'La razón es requerida si el estado es Inactivo.' }); // Fallback message
    }
}
```

**Importante:** Para que `dependsOn` funcione, el componente `DynamicField.vue` necesita acceso a _todos_ los `formValues`. Esto se logra inyectando `formValues` usando `provide/inject` en `DynamicForm.vue` y `DynamicField.vue`.

### Callback `onChange`

Puedes definir una función `onChange` en un campo que se ejecutará cada vez que su valor cambie. Recibe el nuevo valor del campo y el objeto `formValues` completo:

```typescript
{
    name: 'price',
    label: 'Precio',
    type: 'number',
    // ...
    onChange: (newValue, allFormValues) => {
        console.log(`El precio ha cambiado a: ${newValue}`);
        // Ejemplo: Actualizar otro campo basado en este cambio
        // allFormValues.total = (newValue || 0) * (allFormValues.quantity || 0);
    }
}
```

**Nota:** Si `onChange` modifica otros campos, es posible que desees llamar `validateField('nombre_del_otro_campo')` después de modificarlos si la validación on-change está activada en tu lógica.

## 💅 Estilizado

El componente se basa en clases CSS para el layout grid y estilos de PrimeVue. Se asume que Tailwind CSS (o un sistema de grid similar) está configurado en tu proyecto para las clases como `grid`, `gap-*`, `col-span-*`. Las clases de PrimeVue (`p-inputtext`, `p-button`, `p-error`, etc.) proporcionan el estilo de los componentes individuales.

Puedes personalizar el estilo proporcionando clases CSS en la propiedad `style` de `FormConfig` (para estilos globales del formulario, botones, filas, contenedores de campo) o en la propiedad `style` de `FormField` (para sobreescribir estilos a nivel de campo individual).

Dentro de `DynamicField.vue`, se utilizan `:deep()` selectors para aplicar estilos a elementos internos de los componentes PrimeVue.

## 🚨 Indicación de Errores

- Los mensajes de error de Zod se muestran debajo de cada campo inválido.
- En el **modo pestañas**, si una pestaña contiene uno o más campos con errores, se mostrará un icono de alerta (`pi pi-exclamation-circle`) junto al título de la pestaña. Al hacer clic en el botón de submit con errores, el formulario intentará navegar a la primera pestaña que contenga errores.

## 🤝 Contribución

Si encuentras un error o tienes una sugerencia de mejora, ¡siéntete libre de abrir un "issue" o enviar un "pull request"\!

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Consulta el archivo [https://www.google.com/search?q=LICENSE](https://www.google.com/search?q=LICENSE) para más detalles.

---

Creado por Lcdo. Rod Rodriguez, rodriguezrod@gmail.com

```

```
