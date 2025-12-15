// import { useAppStore } from '@/store/AppStore';
// import { NavigationGuardNext as Next, RouteLocationNormalizedGeneric as To, RouteLocationNormalizedLoadedGeneric as From } from 'vue-router';

export function exampleMiddleware({ to, from, next }) {
    // const store = useAppStore()
    // const isAuthenticated = store.authenticated
    return next();
}
