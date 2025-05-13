import React from "react";
import Dashboard from "../layout/Dashboard";
import CreateCompanyForm from "../components/companies/CreateCompanyForm";

export default function CreateCompany() {
    return (
        <div>
            <Dashboard PageContent={CreateCompanyForm} />
        </div>
    );
}
