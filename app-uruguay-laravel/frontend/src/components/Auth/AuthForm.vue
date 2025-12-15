<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/store/authStore';
import ValidationErrors from '@/components/ValidationErrors.vue';

const authStore = useAuthStore();
const router = useRouter();
const form = ref({
    email: '',
    password: '',
    remember: false
});

const handleSubmit = async () => {
    try {
        await authStore.login(form.value);
        router.push({ name: 'dashboard' });
    } catch (error) {}
};
</script>

<template>
    <FloatingConfigurator />
    <Toast position="top-right" />
    <div class="bg-surface-50 dark:bg-surface-950 flex items-center justify-center min-h-screen min-w-[100vw] overflow-hidden">
        <div class="flex flex-col items-center justify-center">
            <div style="border-radius: 56px; padding: 0.3rem; background: linear-gradient(180deg, var(--primary-color) 10%, rgba(33, 150, 243, 0) 30%)">
                <div class="w-full px-8 py-20 bg-surface-0 dark:bg-surface-900 sm:px-20" style="border-radius: 53px">
                    <div class="mb-8 text-center">
                        <div class="mb-4 text-3xl font-medium text-surface-900 dark:text-surface-0">Bienvenidos a la APP Uruguay</div>
                        <span class="font-medium text-muted-color">Ingrese sus credenciales para continuar</span>
                    </div>

                    <div>
                        <form @submit.prevent="handleSubmit">
                            <div class="mb-6">
                                <label for="email" class="block mb-2 text-xl font-medium text-surface-900 dark:text-surface-0">Correo Electrónico</label>
                                <InputText id="email" type="email" required placeholder="Ingrese el Correo Electrónico" class="w-full md:w-[30rem]" v-model="form.email" />
                                <ValidationErrors field="email" />
                            </div>

                            <div class="mb-6">
                                <label for="password" class="block mb-2 text-xl font-medium text-surface-900 dark:text-surface-0">Contraseña</label>
                                <Password id="password" v-model="form.password" placeholder="Ingrese la Contraseña" :toggleMask="true" class="w-full" inputClass="w-full" :feedback="false" :inputStyle="{ width: '100%' }">
                                    <template #header>
                                        <ValidationErrors field="password" />
                                    </template>
                                </Password>
                            </div>

                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center">
                                    <Checkbox id="remember" v-model="form.remember" :binary="true" />
                                    <label for="remember" class="ml-2 text-surface-700 dark:text-surface-300">Recordarme</label>
                                </div>
                                <router-link to="/login" class="text-primary-500 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300"> ¿Olvidó su contraseña? </router-link>
                            </div>

                            <Button type="submit" label="Ingresar" class="w-full" :disabled="authStore.isLoading" :loading="authStore.isLoading" />
                        </form>

                        <!-- <div class="mt-6 text-center">
                            <span class="text-surface-600 dark:text-surface-300">¿No tienes una cuenta? </span>
                            <router-link to="/register" class="font-medium text-primary-500 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300"> Regístrate </router-link>
                        </div>-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.p-password {
    width: 100%;
}

.error-message {
    color: var(--red-500);
    font-size: 0.875rem;
    margin-top: 0.25rem;
    display: block;
}

.p-invalid {
    border-color: var(--red-500) !important;
}

.p-inputtext:enabled:hover.p-invalid {
    border-color: var(--red-600) !important;
}

.p-inputtext:enabled:focus.p-invalid {
    box-shadow: 0 0 0 0.2rem rgba(244, 67, 54, 0.25);
    border-color: var(--red-700) !important;
}
</style>
