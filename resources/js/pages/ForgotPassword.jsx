import React from "react";
import Authorization from "../layout/Authorization";
import ForgotPasswordForm from "../components/auth/ForgotPasswordForm";

export default function ForgotPassword() {
    return (
        <>
            <Authorization FormComponent={ForgotPasswordForm} />
        </>
    );
}
