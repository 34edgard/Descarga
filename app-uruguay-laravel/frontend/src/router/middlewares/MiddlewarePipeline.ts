import { MiddlewareContext as Context, MiddlewareParams as Params } from '@/interfaces';

export function middlewarePipeline(context: Context, middleware: any, index: number) {
    const nextMiddleware = middleware[index];

    if (!nextMiddleware) {
        return context.next;
    }

    return (params?: Params) => {
        if (params) {
            return context.next(params);
        }

        nextMiddleware({
            ...context,
            next: middlewarePipeline(context, middleware, index + 1)
        });
    };
}
