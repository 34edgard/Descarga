import { Genders } from './genders.interfaces';
import { SelectOptions } from '@/types/Index';
import { MaritalStatuses } from './marital_statuses.interfaces';

export interface OptionsSelectEmployee {
    maritalStatusOptions?: SelectOptions[];
    gendersOptions?: SelectOptions[];
    contractTypeOptions?: SelectOptions[];
    companyDepartmentOptions?: SelectOptions[];
    profileOptions?: SelectOptions[];
    bankOptions?: SelectOptions[];
    paymentClasseOptions?: SelectOptions[];
    salaryTypeOptions?: SelectOptions[];
    storeOptions?: SelectOptions[];
}

export interface Employee {
    id: number;
    store_id: number;
    enabled: boolean;
    id_number: string;
    name: string;
    phone: string;
    birthday: Date | string;
    email: string;
    corporate_phone: string;
    corporate_email: string;
    gender_id: number;
    address: string;
    marital_status_id: number;
    bank_id: number;
    bank_account: string;
    salary: number;
    ajustment: number;
    waranty: number;
    payment_class_id: number;
    salary_type_id: number;
    employee_profile_id: number;
    personal_references?: string;
    vacation_days?: number;
    is_trial_period?: boolean;
    insurance_start_date?: Date | string;
    contract_type_id: number;
    extra_attributes?: Record<string, any>;

    // Relaciones (pueden ser opcionales dependiendo del caso de uso)
    marital_status?: MaritalStatuses;
    bank?: Bank;
    payment_class?: PaymentClass;
    salary_type?: SalaryType;
    employee_profile?: EmployeeProfile;
    contract_type?: ContractType;
    family_members?: FamilyMember[];
    goals?: Goal[];
    seller_groups?: SellerGroup[];
    admission_dates?: EmployeeAdmissionDate[];
    trial_periods?: EmployeeTrialPeriod[];
    income_checks?: IncomeCheck[];
    genders?: Genders;
}

// Tablas de atributos
export interface FamilyMemberType {
    id: number;
    name: string;
}

export interface FamilyMember {
    id: number;
    family_members_type_id: number;
    id_number: string;
    first_name: string;
    last_name: string;
    phone: string;
    birthday: Date | string;
    address: string;
    emergency_contact: boolean;

    // Relaciones
    type?: FamilyMemberType;
}

export interface Goal {
    id: number;
    amount: number;
    balance: number;
    total: number;
}

export interface Bank {
    id: number;
    name: string;
    code: string;
}

export interface PaymentClass {
    id: number;
    name: string;
    code: string;
}

export interface SalaryType {
    id: number;
    name: string;
    code: string;
}

export interface EmployeeProfile {
    id: number;
    name: string;
    description?: string;
    company_area_id: number;

    // Relaciones
    company_area?: CompanyArea;
}

export interface CompanyArea {
    id: number;
    name: string;
    description?: string;
    company_department_id: number;

    // Relaciones
    company_department?: CompanyDepartment;
}

export interface CompanyDepartment {
    id: number;
    name: string;
    description?: string;
}

export interface CommissionCondition {
    id: number;
    name: string;
    condition: string;
}

export interface ResignationCheck {
    id: number;
    name: string;
    description?: string;
}

export interface IncomeCheck {
    id: number;
    name: string;
    description?: string;
}

export interface SellerGroupType {
    id: number;
    store_id: number;
    name: string;
    description?: string;
}

export interface SellerGroup {
    id: number;
    store_id: number;
    name: string;
    description?: string;
    commission_condition_id: number;
    seller_group_type_id: number;

    // Relaciones
    commission_condition?: CommissionCondition;
    seller_group_type?: SellerGroupType;
}

export interface ContractType {
    id: number;
    name: string;
    description?: string;
}

// Tablas many-to-many (generalmente representan relaciones)
export interface EmployeeFamilyMember {
    id: number;
    employee_id: number;
    family_member_id: number;

    // Relaciones
    employee?: Employee;
    family_member?: FamilyMember;
}

export interface EmployeeGoal {
    id: number;
    employee_id: number;
    goal_id: number;

    // Relaciones
    employee?: Employee;
    goal?: Goal;
}

export interface EmployeeResignationCheck {
    id: number;
    employee_id: number;
    resignation_check_id: number;

    // Relaciones
    employee?: Employee;
    resignation_check?: ResignationCheck;
}

export interface EmployeeIncomeCheck {
    id: number;
    employee_id: number;
    income_check_id: number;

    // Relaciones
    employee?: Employee;
    income_check?: IncomeCheck;
}

export interface EmployeeUser {
    id: number;
    user_id: number;
    employee_id: number;
}

export interface EmployeeSellerGroup {
    id: number;
    employee_id: number;
    seller_group_id: number;

    // Relaciones
    employee?: Employee;
    seller_group?: SellerGroup;
}

// Tablas de operaciones
export interface EmployeeAdmissionDate {
    id: number;
    employee_id: number;
    admission_date: Date | string;
    end_date?: Date | string;
    end_reason?: string;

    // Relaciones
    employee?: Employee;
}

export interface EmployeeTrialPeriod {
    id: number;
    employee_id: number;
    start_date: Date | string;
    end_date: Date | string;

    // Relaciones
    employee?: Employee;
}

// Interfaces para formularios y requests
export interface EmployeeFormData extends Omit<Employee, 'id' | 'extra_attributes'> {
    id?: number;
    photo?: File | null;
    documents?: File[] | null;
    extra_attributes?: Record<string, any>;
}

// Interfaces para responses de API
export interface ApiResponse<T> {
    data: T;
    message: string;
    success: boolean;
}

export interface PaginatedResponse<T> {
    data: T[];
    current_page: number;
    per_page: number;
    total: number;
    last_page: number;
}
