// Interface para representar un companyareas
export interface Companyareas {
    id: number;
    name: string;
    description: string;
}

// Interface para crear un nuevo companyareas
export interface CreateCompanyareasData {
    name: string;
    description: string;
}

// Interface para actualizar un companyareas existente
export interface UpdateCompanyareasData {
    name?: string; // Optional properties for partial updates
    description?: string;
}
