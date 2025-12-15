import { RouteRecordRaw } from 'vue-router';

const classroomRoutes: RouteRecordRaw[] = [
    {
        path: '/classrooms',
        name: 'classroom_list',
        component: () => import('@/views/pages/classroom/Index.vue'),
        meta: { title: 'Classrooms' }
    },
    {
        path: '/classrooms/create',
        name: 'create_classroom',
        component: () => import('@/views/pages/classroom/Create.vue'),
        meta: { title: 'Crear Classroom' }
    },
    {
        path: '/classrooms/edit/:id',
        name: 'edit_classroom',
        component: () => import('@/views/pages/classroom/Edit.vue'),
        meta: { title: 'Editar Classroom' },
        props: true
    },
    {
        path: '/classrooms/show/:id',
        name: 'show_classroom',
        component: () => import('@/views/pages/classroom/Show.vue'),
        meta: { title: 'Detalles de Classroom' },
        props: true
    }
];

export default classroomRoutes;