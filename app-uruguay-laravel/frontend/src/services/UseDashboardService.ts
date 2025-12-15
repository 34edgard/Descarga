export const getMenuItems = () => {
    return [
        {
            label: 'Inscripción',
            items: [{ label: 'Gestión', icon: 'pi pi-fw pi-compass', to: { name: 'enrollment_list' } }]
        },
        {
            label: 'Gestión del Planter',
            icon: 'pi pi-fw pi-cogs',
            items: [
                {
                    label: 'Niveles',
                    icon: 'pi pi-fw pi-compass',
                    to: { name: 'level_list' }
                },
                {
                    label: 'Secciones',
                    icon: 'pi pi-fw pi-compass',
                    to: { name: 'section_list' }
                },
                {
                    label: 'Aulas',
                    icon: 'pi pi-fw pi-compass',
                    to: { name: 'classroom_list' }
                },
                {
                    label: 'Periodos',
                    icon: 'pi pi-fw pi-compass',
                    to: { name: 'school_year_list' }
                }
            ]
        },
        {
            label: 'Administración',
            icon: 'pi pi-fw pi-user',
            items: [
                {
                    label: 'Usuarios',
                    icon: 'pi pi-fw pi-users',
                    to: { name: 'user_list' }
                },
                {
                    label: 'Estudiantes',
                    icon: 'pi pi-fw pi-users',
                    to: { name: 'student_list' }
                },
                {
                    label: 'Docentes',
                    icon: 'pi pi-fw pi-users',
                    to: { name: 'teacher_list' }
                }
            ]
        },
        {
            label: 'Auth',
            icon: 'pi pi-fw pi-user',
            items: [
                {
                    label: 'Logout',
                    icon: 'pi pi-fw pi-sign-out',
                    to: { name: 'logout' }
                }
            ]
        }
    ];
};
