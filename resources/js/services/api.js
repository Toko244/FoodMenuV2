import { refreshAccessToken } from "../services/services";

const api = window.axios.create({
    baseURL: import.meta.env.VITE_API_BASE_URL,
});

api.interceptors.request.use(
    async (config) => {
        const accessToken = localStorage.getItem("accessToken");

        if (accessToken) {
            config.headers.Authorization = `Bearer ${accessToken}`;
        }

        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

api.interceptors.response.use(
    (response) => response,
    async (error) => {
        const refreshToken = localStorage.getItem("refreshToken");

        if (error.response && error.response.status === 401) {
            try {
                const newAccessToken = await refreshAccessToken({
                    refresh_token: refreshToken,
                });
                const { access_token, refresh_token } = newAccessToken.data;

                localStorage.setItem("accessToken", access_token);
                localStorage.setItem("refreshToken", refresh_token);

                error.config.headers[
                    "Authorization"
                ] = `Bearer ${access_token}`;

                return api(error.config);
            } catch (refreshError) {
                throw refreshError;
            }
        }
        return Promise.reject(error);
    }
);
export default api;
