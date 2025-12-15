const { SUB_ROUTE_CENTRAL_PREFIX = '/api', ENVAIRONMENT = 'local' } = import.meta.env;

export const getEnv = () => {
    // const hostname = window.location.hostname;
    const hostname = window.location.host;

    const protocol = ENVAIRONMENT === 'local' ? 'http' : 'https';

    const prefix = SUB_ROUTE_CENTRAL_PREFIX;

    const baseUrl = `${protocol}://${hostname}${prefix}`;

    return {
        prefix: prefix,
        baseUrl: baseUrl
    };
};
