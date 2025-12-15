import { RouteRecordRaw } from 'vue-router';

const gendersRoutes: RouteRecordRaw[] = [
    {
        path: '/genders',
        name: 'genders_list',
        component: () => import('@/views/pages/genders/Index.vue'),
        meta: { title: 'Genderss' }
    },
    {
        path: '/genders/create',
        name: 'create_genders',
        component: () => import('@/views/pages/genders/Create.vue'),
        meta: { title: 'Crear Genders' }
    },
    {
        path: '/genders/edit/:id',
        name: 'edit_genders',
        component: () => import('@/views/pages/genders/Edit.vue'),
        meta: { title: 'Editar Genders' },
        props: true
    },
    {
        path: '/genders/show/:id',
        name: 'show_genders',
        component: () => import('@/views/pages/genders/Show.vue'),
        meta: { title: 'Detalles de Genders' },
        props: true
    }
];

export default gendersRoutes;