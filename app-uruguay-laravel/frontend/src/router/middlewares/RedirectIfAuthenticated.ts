import { useAuthStore } from '@/store/authStore';

export function redirectIfAuthenticated({ next }) {
    const store = useAuthStore();
    const isAuthenticated = store.isAuthenticated;
    if (isAuthenticated) {
        return next({ name: 'dashboard' });
    }
    return next();
}
