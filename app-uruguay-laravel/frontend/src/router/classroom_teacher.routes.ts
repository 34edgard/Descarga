import { RouteRecordRaw } from 'vue-router';

const classroomTeacherRoutes: RouteRecordRaw[] = [
    {
        path: '/classroom_teachers',
        name: 'classroom_teacher_list',
        component: () => import('@/views/pages/classroom_teacher/Index.vue'),
        meta: { title: 'ClassroomTeachers' }
    },
    {
        path: '/classroom_teachers/create',
        name: 'create_classroom_teacher',
        component: () => import('@/views/pages/classroom_teacher/Create.vue'),
        meta: { title: 'Crear ClassroomTeacher' }
    },
    {
        path: '/classroom_teachers/edit/:id',
        name: 'edit_classroom_teacher',
        component: () => import('@/views/pages/classroom_teacher/Edit.vue'),
        meta: { title: 'Editar ClassroomTeacher' },
        props: true
    },
    {
        path: '/classroom_teachers/show/:id',
        name: 'show_classroom_teacher',
        component: () => import('@/views/pages/classroom_teacher/Show.vue'),
        meta: { title: 'Detalles de ClassroomTeacher' },
        props: true
    }
];

export default classroomTeacherRoutes;