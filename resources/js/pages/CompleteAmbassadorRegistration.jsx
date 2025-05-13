import React, { useState, useEffect } from "react";
import Authorization from "../layout/Authorization";
import CompleteAmbassadorRegistrationForm from "../components/auth/CompleteAmbassadorRegistrationForm";
import { checkRegistrationToken } from "../services/services";
import { getQueryParameters } from "../helpers/routes";
import Loading from "../components/loading";
import Message from "../components/message";
import { useNavigate } from "react-router-dom";

export default function CompleteAmbassadorRegistration() {
    const queryParams = getQueryParameters();
    const navigate = useNavigate();

    const [token, setToken] = useState(queryParams.token);
    const [email, setEmail] = useState("");
    const [showForm, setShowForm] = useState(false);
    const [loading, setLoading] = useState(false);
    const [status, setStatus] = useState("");
    const [errorMessage, setErrorMessage] = useState("");

    useEffect(() => {
        const handleCheckRegistrationToken = () => {
            setLoading(true);
            checkRegistrationToken({ token })
                .then((response) => {
                    setEmail(response.data.email);
                    setShowForm(true);
                    setLoading(false);
                })
                .catch((error) => {
                    setShowForm(false);
                    setLoading(false);
                    setStatus(error.response.status);
                    setErrorMessage(error.response.data.message);
                    setTimeout(() => {
                        navigate("/login");
                    }, 10000);
                });
        };

        if (token) handleCheckRegistrationToken();
    }, [token]);

    const renderForm = () => {
        if (showForm) {
            return (
                <CompleteAmbassadorRegistrationForm
                    token={token}
                    email={email}
                />
            );
        } else {
            return <Message message={errorMessage} status={status} />;
        }
    };

    return (
        <>
            {loading ? (
                <Loading />
            ) : (
                <Authorization FormComponent={renderForm} />
            )}
        </>
    );
}
