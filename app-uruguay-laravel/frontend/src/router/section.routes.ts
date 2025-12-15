import { RouteRecordRaw } from 'vue-router';

const sectionRoutes: RouteRecordRaw[] = [
    {
        path: '/sections',
        name: 'section_list',
        component: () => import('@/views/pages/section/Index.vue'),
        meta: { title: 'Sections' }
    },
    {
        path: '/sections/create',
        name: 'create_section',
        component: () => import('@/views/pages/section/Create.vue'),
        meta: { title: 'Crear Section' }
    },
    {
        path: '/sections/edit/:id',
        name: 'edit_section',
        component: () => import('@/views/pages/section/Edit.vue'),
        meta: { title: 'Editar Section' },
        props: true
    },
    {
        path: '/sections/show/:id',
        name: 'show_section',
        component: () => import('@/views/pages/section/Show.vue'),
        meta: { title: 'Detalles de Section' },
        props: true
    }
];

export default sectionRoutes;