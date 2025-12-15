import { RouteRecordRaw } from 'vue-router';

const representativeRoutes: RouteRecordRaw[] = [
    {
        path: '/representatives',
        name: 'representative_list',
        component: () => import('@/views/pages/representative/Index.vue'),
        meta: { title: 'Representatives' }
    },
    {
        path: '/representatives/create',
        name: 'create_representative',
        component: () => import('@/views/pages/representative/Create.vue'),
        meta: { title: 'Crear Representative' }
    },
    {
        path: '/representatives/edit/:id',
        name: 'edit_representative',
        component: () => import('@/views/pages/representative/Edit.vue'),
        meta: { title: 'Editar Representative' },
        props: true
    },
    {
        path: '/representatives/show/:id',
        name: 'show_representative',
        component: () => import('@/views/pages/representative/Show.vue'),
        meta: { title: 'Detalles de Representative' },
        props: true
    }
];

export default representativeRoutes;