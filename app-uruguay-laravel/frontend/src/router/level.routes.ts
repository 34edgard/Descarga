import { RouteRecordRaw } from 'vue-router';

const levelRoutes: RouteRecordRaw[] = [
    {
        path: '/levels',
        name: 'level_list',
        component: () => import('@/views/pages/level/Index.vue'),
        meta: { title: 'Levels' }
    },
    {
        path: '/levels/create',
        name: 'create_level',
        component: () => import('@/views/pages/level/Create.vue'),
        meta: { title: 'Crear Level' }
    },
    {
        path: '/levels/edit/:id',
        name: 'edit_level',
        component: () => import('@/views/pages/level/Edit.vue'),
        meta: { title: 'Editar Level' },
        props: true
    },
    {
        path: '/levels/show/:id',
        name: 'show_level',
        component: () => import('@/views/pages/level/Show.vue'),
        meta: { title: 'Detalles de Level' },
        props: true
    }
];

export default levelRoutes;