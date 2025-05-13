export const getQueryParameters = () => {
    const searchParams = new URLSearchParams(window.location.search);
    const params = {};
    for (const [key, value] of searchParams) {
        params[key] = value;
    }
    return params;
};
