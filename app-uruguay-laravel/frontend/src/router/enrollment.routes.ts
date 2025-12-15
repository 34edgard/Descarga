import { RouteRecordRaw } from 'vue-router';

const enrollmentRoutes: RouteRecordRaw[] = [
    {
        path: '/enrollments',
        name: 'enrollment_list',
        component: () => import('@/views/pages/enrollment/Index.vue'),
        meta: { title: 'Enrollments' }
    },
    {
        path: '/enrollments/create',
        name: 'create_enrollment',
        component: () => import('@/views/pages/enrollment/Create.vue'),
        meta: { title: 'Crear Enrollment' }
    },
    {
        path: '/enrollments/edit/:id',
        name: 'edit_enrollment',
        component: () => import('@/views/pages/enrollment/Edit.vue'),
        meta: { title: 'Editar Enrollment' },
        props: true
    },
    {
        path: '/enrollments/show/:id',
        name: 'show_enrollment',
        component: () => import('@/views/pages/enrollment/Show.vue'),
        meta: { title: 'Detalles de Enrollment' },
        props: true
    }
];

export default enrollmentRoutes;