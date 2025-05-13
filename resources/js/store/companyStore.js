import { create } from "zustand";
import {
    getCompanies,
    createCompany,
    updateCompany,
    deleteCompany,
} from "../services/services";

export const useCompanyStore = create((set) => ({
    selectedLanguage: "georgian",
    data: {
        english: {},
        georgian: {},
    },
    updateLanguage: (e) => {
        console.log(e);
        set(() => ({ selectedLanguage: e }));
    },
    companies: [],
    fetchCompanies: async (params) => {
        const response = await getCompanies(params);
        set({ companies: response.data.companies.data });
    },
    createCompany: async (data) => {
        const response = await createCompany(data);
        set((state) => ({ companies: [...state.companies, response.data] }));
    },
    editCompany: async (companyData) => {
        const response = await updateCompany(companyData);
        set((state) => ({
            companies: state.companies.map((company) =>
                company.id === companyData.id ? response.data : company
            ),
        }));
    },
    removeCompany: async (companyId) => {
        await deleteCompany(companyId);
        set((state) => ({
            companies: state.companies.filter(
                (company) => company.id !== companyId
            ),
        }));
    },
}));
