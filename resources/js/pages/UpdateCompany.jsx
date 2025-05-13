import React from "react";
import Dashboard from "../layout/Dashboard";
import UpdateCompanyForm from "../components/companies/UpdateCompanyForm";

export default function UpdateCompany() {
    return (
        <div>
            <Dashboard PageContent={UpdateCompanyForm} />
        </div>
    );
}
