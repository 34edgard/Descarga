import { RouteRecordRaw } from 'vue-router';

const schoolYearRoutes: RouteRecordRaw[] = [
    {
        path: '/school_years',
        name: 'school_year_list',
        component: () => import('@/views/pages/school_year/Index.vue'),
        meta: { title: 'SchoolYears' }
    },
    {
        path: '/school_years/create',
        name: 'create_school_year',
        component: () => import('@/views/pages/school_year/Create.vue'),
        meta: { title: 'Crear SchoolYear' }
    },
    {
        path: '/school_years/edit/:id',
        name: 'edit_school_year',
        component: () => import('@/views/pages/school_year/Edit.vue'),
        meta: { title: 'Editar SchoolYear' },
        props: true
    },
    {
        path: '/school_years/show/:id',
        name: 'show_school_year',
        component: () => import('@/views/pages/school_year/Show.vue'),
        meta: { title: 'Detalles de SchoolYear' },
        props: true
    }
];

export default schoolYearRoutes;