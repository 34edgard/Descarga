import { defineStore } from 'pinia';
import { AuthState, AuthProps, AuthCredentials } from '@/interfaces';
import { login, logout } from '@/services/UseAuthService';
import router from '@/router/index';
import { AuthenticationError } from '@/errors';

export const useAppStore = defineStore('app', {
    state: (): AuthState => ({
        auth: null,
        authenticated: false,
        errors: [],
        current: {
            store: null,
            in_process_change: {
                check: false,
                store_id: null,
                tenant_id: null
            }
        }
    }),
    getters: {
        isAuthenticated: (state: AuthState) => {
            setTimeout(() => {
                state.errors = [];
            }, 30000);
            const isAuth = !!state.authenticated && !!state.auth?.user;
            return isAuth;
        },
        getUser: (state: AuthState) => {
            return state?.auth?.user;
        },
        getErrors: (state: AuthState) => {
            setTimeout(() => {
                state.errors = [];
            }, 30000);
            return state.errors.length > 0 ? state.errors : false;
        }
    },
    actions: {
        async login(credentials: AuthCredentials) {
            try {
                this.errors = [];
                const props: AuthProps = await login(credentials);
                this.auth = props.auth;
                this.authenticated = true;
                router.push({ name: 'dashboard' });
            } catch (error) {
                if (error instanceof AuthenticationError) {
                    this.errors.push(...error.errors);
                } else {
                    this.errors.push(error.message);
                }
                this.logout();
            }
        },
        async logout() {
            try {
                await logout();
                this.clear();
            } catch (error) {
                this.clear();
            }
        },
        clear() {
            this.auth = null;
            this.authenticated = false;
            router.push({ name: 'login' });
        }
    },
    persist: {
        storage: localStorage
    }
});
