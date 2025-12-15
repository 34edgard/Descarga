import { City } from "./city.interfaces";
import { ContactType } from "./contact_type.interfaces";
import { Country } from "./country.interfaces";
import { Department } from "./department.interfaces";

export interface Contact {
    id: number;
    contact_type_id: number;
    contact_type: ContactType;
    city_id: number;
    city: City;
    department_id: number;
    department: Department;
    contry_id: number;
    contry: Country;
    name: string;
    id_number: string;
    vat_number: string;
    phone: string;
    email: string;
    address: string;
    created_at?: string;
    updated_at?: string;
}

export interface ContactFormData extends Omit<Contact, 'id' | 'created_at' | 'updated_at'> {
    id?: number;
    photo?: File | null;
}

export interface ContactResponse {
    data: Contact | Contact[];
    message?: string;
    success: boolean;
}