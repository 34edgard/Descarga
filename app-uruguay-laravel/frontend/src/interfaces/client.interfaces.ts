import { City } from "./city.interfaces";
import { ClientType } from "./client_type.interfaces";
import { Club } from "./club.interfaces";
import { Contact } from "./contact.interfaces";
import { ContactType } from "./contact_type.interfaces";
import { Department } from "./department.interfaces";
import { Motorcycle } from "./motorcycle.interfaces";

export interface Client {
    id: number;
    name: string;
    description: string;
    club_id: number;
    club: Club;
    contact_type_id: number;
    contact_type: ContactType;
    contacts: Contact[];
    motorcycles: Motorcycle[];
    client_type_id: number;
    client_type: ClientType;
    department_id: number;
    department: Department;
    city_id: number;
    city: City;
    photo_url?: string;
    created_at: string;
    updated_at: string;
    extra_attributes?: Record<string, any>;
}

export interface ClientFormData extends Omit<Client, 'id' | 'created_at' | 'updated_at' | 'extra_attributes'> {
    id?: number;
    photo?: File | null;
    extra_attributes?: Record<string, any>;
}

export interface ClientResponse {
    data: Client | Client[];
    message?: string;
    success: boolean;
}