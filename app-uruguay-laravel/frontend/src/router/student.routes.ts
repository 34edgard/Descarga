import { RouteRecordRaw } from 'vue-router';

const studentRoutes: RouteRecordRaw[] = [
    {
        path: '/students',
        name: 'student_list',
        component: () => import('@/views/pages/student/Index.vue'),
        meta: { title: 'Students' }
    },
    {
        path: '/students/create',
        name: 'create_student',
        component: () => import('@/views/pages/student/Create.vue'),
        meta: { title: 'Crear Student' }
    },
    {
        path: '/students/edit/:id',
        name: 'edit_student',
        component: () => import('@/views/pages/student/Edit.vue'),
        meta: { title: 'Editar Student' },
        props: true
    },
    {
        path: '/students/show/:id',
        name: 'show_student',
        component: () => import('@/views/pages/student/Show.vue'),
        meta: { title: 'Detalles de Student' },
        props: true
    }
];

export default studentRoutes;