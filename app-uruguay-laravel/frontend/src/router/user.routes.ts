import { RouteRecordRaw } from 'vue-router';

const userRoutes: RouteRecordRaw[] = [
    {
        path: '/users',
        name: 'user_list',
        component: () => import('@/views/pages/user/Index.vue'),
        meta: { title: 'Users' }
    },
    {
        path: '/users/create',
        name: 'create_user',
        component: () => import('@/views/pages/user/Create.vue'),
        meta: { title: 'Crear User' }
    },
    {
        path: '/users/edit/:id',
        name: 'edit_user',
        component: () => import('@/views/pages/user/Edit.vue'),
        meta: { title: 'Editar User' },
        props: true
    },
    {
        path: '/users/show/:id',
        name: 'show_user',
        component: () => import('@/views/pages/user/Show.vue'),
        meta: { title: 'Detalles de User' },
        props: true
    }
];

export default userRoutes;