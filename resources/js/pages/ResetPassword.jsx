import React, { useState, useEffect } from "react";
import ResetPasswordForm from "../components/auth/ResetPasswordForm";
import Authorization from "../layout/Authorization";
import { checkRegistrationToken } from "../services/services";
import { getQueryParameters } from "../helpers/routes";

export default function ResetPassword() {
    const queryParams = getQueryParameters();

    const [error, setError] = useState(false);
    const [email, setEmail] = useState("");
    const [token, setToken] = useState(queryParams.token);

    useEffect(() => {
        // For testing purposes
        const handleCheckRegistrationToken = async () => {
            try {
                const response = await checkRegistrationToken({ token });
                setEmail(response?.data?.email);
            } catch (error) {
                if (error.response && error.response.data) {
                    alert("some loading");
                }
            }
        };

        setEmail(queryParams.email || "");
        setToken(queryParams.token || "");
        setError(queryParams.error && queryParams.error.length > 0);

        if (token) handleCheckRegistrationToken();
    }, []);

    return (
        <>
            {error ? (
                <div>Error: {error}</div>
            ) : (
                <Authorization
                    FormComponent={ResetPasswordForm}
                    email={email}
                    token={token}
                />
            )}
        </>
    );
}
