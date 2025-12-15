import { RouteRecordRaw } from 'vue-router';

const teacherRoutes: RouteRecordRaw[] = [
    {
        path: '/teachers',
        name: 'teacher_list',
        component: () => import('@/views/pages/teacher/Index.vue'),
        meta: { title: 'Teachers' }
    },
    {
        path: '/teachers/create',
        name: 'create_teacher',
        component: () => import('@/views/pages/teacher/Create.vue'),
        meta: { title: 'Crear Teacher' }
    },
    {
        path: '/teachers/edit/:id',
        name: 'edit_teacher',
        component: () => import('@/views/pages/teacher/Edit.vue'),
        meta: { title: 'Editar Teacher' },
        props: true
    },
    {
        path: '/teachers/show/:id',
        name: 'show_teacher',
        component: () => import('@/views/pages/teacher/Show.vue'),
        meta: { title: 'Detalles de Teacher' },
        props: true
    }
];

export default teacherRoutes;