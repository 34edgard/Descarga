import { AuthError, RequeredErrors } from '@/interfaces';

export class AuthenticationError extends Error implements AuthError {
    constructor(message: string, errors?: RequeredErrors) {
        super(message);
        this.errors = this.getErrors(errors, message);
        this.name = 'AuthenticationError';
    }
    private getErrors(errors: RequeredErrors, message: string) {
        if (!errors) {
            return [message];
        }
        let arr = [];
        for (let key in errors) {
            arr.push(...errors[key]);
        }
        return arr;
    }
    errors?: string[] = [];
}
export class ConnectionError extends Error {
    constructor(message: string) {
        super(message);
        this.name = 'ConnectionError';
    }
}
