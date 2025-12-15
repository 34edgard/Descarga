import { RouteRecordRaw } from 'vue-router';
import { useAuthStore } from '@/store/authStore';
import { redirectIfAuthenticated } from '@/router/middlewares';

const authRoutes: RouteRecordRaw[] = [
    {
        path: '/login',
        name: 'login',
        component: () => import('@/views/pages/auth/Login.vue'),
        meta: { middleware: [redirectIfAuthenticated] }
    },
    {
        path: '/logout',
        name: 'logout',
        component: () => '<div>Logout</div>',
        beforeEnter: async (to, from, next) => {
            const store = useAuthStore();
            await store.logout();
            next({ name: 'login' });
        }
    }
];

export default authRoutes;
