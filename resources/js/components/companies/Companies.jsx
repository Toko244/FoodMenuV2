import React, { useEffect } from "react";
import { useCompanyStore } from "../../store/companyStore";
import TrashIcon from "../icons/Trash";
import EditIcon from "../icons/Edit";
import { useNavigate } from "react-router-dom";

const tableTitles = [
    { id: 1, title: "Logo" },
    { id: 2, title: "Name" },
    { id: 3, title: "Email" },
    { id: 4, title: "Phone" },
    { id: 5, title: "State" },
    { id: 6, title: "City" },
    { id: 7, title: "Actions" },
];

export default function Companies() {
    const { companies, fetchCompanies, removeCompany } = useCompanyStore(
        (state) => ({
            companies: state.companies,
            fetchCompanies: state.fetchCompanies,
            removeCompany: state.removeCompany,
        })
    );

    const navigate = useNavigate();

    useEffect(() => {
        fetchCompanies();
    }, [fetchCompanies]);

    const handleDeleteCompany = async (companyId) => {
        try {
            await removeCompany(companyId);
            await fetchCompanies();
        } catch (error) {
            console.error("Error deleting company:", error);
        }
    };

    const handleUpdateCompany = (companyId) => {
        navigate(`/dashboard/companies/${companyId}`);
    };

    if (!Array.isArray(companies)) {
        return <div>Loading...</div>;
    }

    return (
        <div className="overflow-x-scroll">
            <table className="min-w-full divide-y divide-gray-200">
                <thead className="bg-gray-50">
                    <tr className="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        {tableTitles.map((column) => (
                            <th key={column.id} className="px-6 py-3 text-left">
                                {column.title}
                            </th>
                        ))}
                    </tr>
                </thead>
                <tbody className="bg-white divide-y divide-gray-200">
                    {companies.map((company) => (
                        <tr key={company.id} className="hover:bg-gray-50">
                            <td className="px-6 py-4 whitespace-nowrap">
                                {company.logo_url ? (
                                    <img
                                        src={company.logo_url}
                                        alt={company.translation.name}
                                        className="h-9 w-9 rounded-full"
                                    />
                                ) : (
                                    <span>No Logo</span>
                                )}
                            </td>
                            <td className="px-6 py-4 whitespace-nowrap">
                                {company.translation.name}
                            </td>
                            <td className="px-6 py-4 whitespace-nowrap">
                                {company.email}
                            </td>
                            <td className="px-6 py-4 whitespace-nowrap">
                                {company.phone}
                            </td>
                            <td className="px-6 py-4 whitespace-nowrap">
                                {company.state}
                            </td>
                            <td className="px-6 py-4 whitespace-nowrap">
                                {company.city}
                            </td>
                            <td className="px-6 py-5 flex gap-4 items-center">
                                <button
                                    onClick={() =>
                                        handleUpdateCompany(company.id)
                                    }
                                >
                                    <EditIcon />
                                </button>
                                <button
                                    onClick={() =>
                                        handleDeleteCompany(company.id)
                                    }
                                >
                                    <TrashIcon />
                                </button>
                            </td>
                        </tr>
                    ))}
                </tbody>
            </table>
        </div>
    );
}
