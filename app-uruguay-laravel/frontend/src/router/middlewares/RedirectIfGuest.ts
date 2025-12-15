import { useAuthStore } from '@/store/authStore';

export function redirectIfGuest({ next, to }) {
    const store = useAuthStore();
    const isAuthenticated = store.isAuthenticated;
    if (!isAuthenticated) {
        return next({ name: 'login' });
    }
    return next();
}
