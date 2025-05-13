import React from "react";
import ProfileContent from "../components/profile";
import Dashboard from "../layout/Dashboard";

export default function Profile() {
    return (
        <>
            <Dashboard PageContent={ProfileContent} />
        </>
    );
}
