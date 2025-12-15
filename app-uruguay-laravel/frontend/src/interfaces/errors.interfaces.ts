export interface ValidationError extends Error {
    rawErrors: Record<string, string[]>;
    messages: string[];
}

export class AuthenticationError extends Error {
    constructor(
        message: string,
        public errors?: Record<string, string[]>
    ) {
        super(message);
        this.name = 'AuthenticationError';
    }
}

export class ConnectionError extends Error {
    constructor(message: string) {
        super(message);
        this.name = 'ConnectionError';
    }
}

export class ValidationError extends Error {
    constructor(
        message: string,
        public details: {
            rawErrors: Record<string, string[]>;
            messages: string[];
        }
    ) {
        super(message);
        this.name = 'ValidationError';
    }
}
