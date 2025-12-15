export type AuthData = {
    user: AuthUser | null
}
export interface AuthState {
    auth: AuthData | null
    authenticated: Boolean
    errors: string[] | null
    current: AuthCurrent | null
}
export type AuthProps = {
    auth: AuthData
}
export type AuthCredentials = {
    email: string
    password: string
}

export interface AuthError extends Error {
    errors?: string[]
}

export type RequeredErrors = {
    email?: string[]
    password?: string[]
}

export type AuthCurrent = {
    store: AuthStore
    in_process_change: {
        check: boolean
        store_id: number | null
        tenant_id: string | null
    }
}

export type MenuItem = {
    label?: string;
    icon?: string;
    command?: () => void;
    items?: MenuItem[];
};

export type AuthUser = {
    id: number
    name: string
    email: string
    roles: Role[]
    permissions: any[]
    stores: AuthStore[]
}

export type Role = {
    id?: number
    name?: string
    pivot?: RolePivot
}

export type RolePivot = {
    model_type?: string
    model_id?: number
    role_id?: number
}

export type AuthStore = {
    id: number
    name: string
    address?: string | null
    phone?: string | null
    email?: string | null
    website?: string | null
    logo?: string | null
    extra_attributes?: any | null
    pivot?: StorePivot
    tenant_id?: string
}

export type StorePivot = {
    user_id?: number
    store_id?: number
}

