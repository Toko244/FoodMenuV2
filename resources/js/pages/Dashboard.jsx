import React from "react";
import Dashboard from "../layout/Dashboard";
import DashboardContent from "../components/dashboard";

export default function DashboardPage() {
    return (
        <div>
            <Dashboard PageContent={DashboardContent} />
        </div>
    );
}
