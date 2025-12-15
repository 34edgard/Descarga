import { NavigationGuardNext as Next, RouteLocationNormalizedGeneric as To, RouteLocationNormalizedLoadedGeneric as From } from 'vue-router';

export type MiddlewareContext = { // <--- context
    to: To,
    from: From,
    next: Next | NextPipeline,
}

export type MiddlewareParams = { // <--- params
    name?: string
    path?: string
}

export type NextPipeline = (
    params?: MiddlewareParams
) => Next | NextPipeline | void

export type MiddlewareType = (
    context?: MiddlewareContext
) => NextPipeline | void


