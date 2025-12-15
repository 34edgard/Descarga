export interface ClassroomTeacher {
    id: number;
    uuid: string;
    name: string;
    created_at?: string;
    updated_at?: string;
    extra_attributes?: Record<string, any>;
}

export interface ClassroomTeacherFormData extends Omit<ClassroomTeacher, 'id' | 'created_at' | 'updated_at' | 'extra_attributes'> {
    id?: number;
    photo?: File | null;
    extra_attributes?: Record<string, any>;
}

export interface ClassroomTeacherResponse {
    data: ClassroomTeacher | ClassroomTeacher[];
    message?: string;
    success: boolean;
}