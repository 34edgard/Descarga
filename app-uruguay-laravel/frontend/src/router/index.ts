import { createRouter, createWebHistory } from 'vue-router';
import AppLayout from '@/layout/AppLayout.vue';
import { middlewarePipeline, redirectIfGuest } from '@/router/middlewares';
import authRoutes from '@/router/auth.routes';
import dashboardRoutes from '@/router/dashboard.routes';
import GenderStatusesRoutes from '@/router/genders.routes';
import UserRoutes from '@/router/user.routes';
import LebelRoutes from '@/router/level.routes';
import RepresentativeRoutes from '@/router/representative.routes';
import School_yearRoutes from '@/router/school_year.routes';
import StudentRoutes from '@/router/student.routes';
import TeacherRoutes from '@/router/teacher.routes';
import ClassroomRoutes from '@/router/classroom.routes';
import SectionRoutes from '@/router/section.routes';
import Classroom_teacherRoutes from '@/router/classroom_teacher.routes';
import EnrollmentRoutes from '@/router/enrollment.routes';

const routes = [
    ...authRoutes,
    {
        path: '/',
        component: AppLayout,
        meta: { middleware: [redirectIfGuest] },
        children: [
            ...dashboardRoutes,
            ...GenderStatusesRoutes,
            ...UserRoutes,
            ...LebelRoutes,
            ...RepresentativeRoutes,
            ...School_yearRoutes,
            ...StudentRoutes,
            ...TeacherRoutes,
            ...ClassroomRoutes,
            ...SectionRoutes,
            ...Classroom_teacherRoutes,
            ...EnrollmentRoutes
        ]
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach(async (to, from, next) => {
    if (to.path === '/') {
        return next({ name: 'dashboard' });
    }

    if (!to.meta.middleware) {
        return next();
    }

    const middleware = to.meta.middleware;

    const context = { to, from, next };
    return middleware[0]({
        ...context,
        next: middlewarePipeline(context, middleware, 1)
    });
});

export default router;
