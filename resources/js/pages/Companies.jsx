import React from "react";
import Dashboard from "../layout/Dashboard";
import CompaniesForm from "../components/companies/Companies";

export default function Companies() {
    return (
        <div>
            <Dashboard PageContent={CompaniesForm} />
        </div>
    );
}
