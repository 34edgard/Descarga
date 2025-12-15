import { RouteRecordRaw } from 'vue-router';

const dashboardRoutes: RouteRecordRaw[] = [
    {
        path: '/dashboard',
        name: 'dashboard',
        component: () => import('@/views/Dashboard.vue')
    }
];

export default dashboardRoutes;
