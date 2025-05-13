import api from "./api";

const baseURL = import.meta.env.VITE_API_BASE_URL;

export const login = (data) => {
    return api.post(`${baseURL}/login`, { ...data });
};

export const logout = (data) => {
    return api.post(`${baseURL}/logout`, { ...data });
};

export const forgotPassword = (data) => {
    return api.post(`${baseURL}/forgot-password`, { ...data });
};

export const resetPassword = ({ token, email, password, confirmPassword }) => {
    return api.post(`${baseURL}/reset-password`, {
        token: token,
        email: email,
        password: password,
        password_confirmation: confirmPassword,
    });
};

export const refreshAccessToken = (data) => {
    return api.post(`${baseURL}/auth/update-tokens`, { ...data });
};

export const registration = (email, user_type, ambassador_uuid) => {
    return api.post(`${baseURL}/registration`, {
        email: email,
        user_type: user_type,
        ambassador_uuid: ambassador_uuid,
    });
};

export const checkRegistrationToken = (token) => {
    return api.post(`${baseURL}/checkRegistrationToken`, { token });
};

export const completeUserRegistration = ({
    name,
    surname,
    phone,
    password,
    confirmPassword,
    token,
}) => {
    return api.post(`${baseURL}/complete-user-registration`, {
        name: name,
        surname: surname,
        phone: phone,
        password: password,
        password_confirmation: confirmPassword,
        token: token,
    });
};

export const completeAmbassadorRegistration = ({
    name,
    surname,
    dateOfBirth,
    personalNumber,
    phone,
    city,
    address,
    password,
    confirmPassword,
    country_id,
    token,
}) => {
    return api.post(`${baseURL}/complete-ambassador-registration`, {
        name: name,
        surname: surname,
        date_of_birth: dateOfBirth,
        personal_number: personalNumber,
        phone: phone,
        city: city,
        country_id: country_id,
        address: address,
        password: password,
        password_confirmation: confirmPassword,
        token: token,
    });
};

export const getFormData = ({ data }) => {
    return api.get(`${baseURL}/dashboard/companies/form-data`, {
        ...data,
    });
};

export const getCompanies = (data) => {
    return api.get(`${baseURL}/dashboard/companies`, { ...data });
};

export const updateCompany = (companyData) => {
    return api.put(`${baseURL}/dashboard/companies`, companyData);
};

export const deleteCompany = (companyId) => {
    return api.delete(`${baseURL}/dashboard/companies/${companyId}`);
};

export const createCompany = (data) => {
    return api.post(`${baseURL}/dashboard/companies`, { ...data });
};
