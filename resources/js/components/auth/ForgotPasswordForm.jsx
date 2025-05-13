import React, { useState } from "react";
import { Formik, Form } from "formik";
import * as Yup from "yup";
import Button from "../button";
import Input from "../input";
import { Link } from "react-router-dom";
import { useTranslation } from "react-i18next";
import { forgotPassword } from "../../services/services";
import Message from "../message";

export default function ForgotPasswordForm() {
    const { t: authT } = useTranslation("auth");
    const [error, setError] = useState("");
    const [loading, setLoading] = useState(false);
    const [successMessage, setSuccessMessage] = useState("");

    const validationSchema = Yup.object({
        email: Yup.string()
            .email(authT("validationMessage.invalidEmailAddress"))
            .required(authT("validationMessage.requiredEmailAddress"))
            .matches(
                /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
                authT("validationMessage.invalidEmailAddress")
            ),
    });

    const handleSubmit = (values, { setSubmitting }) => {
        setLoading(true);

        return forgotPassword(values)
            .then((response) => {
                const { status } = response.data;
                setSuccessMessage(status);
                setError("");
            })
            .catch((error) => {
                if (error.response && error.response.data) {
                    const { message } = error.response.data;
                    setError(message);
                }
            })
            .finally(() => {
                setLoading(false);
                setSubmitting(false);
            });
    };

    return (
        <Formik
            initialValues={{ email: "" }}
            validationSchema={validationSchema}
            onSubmit={handleSubmit}
        >
            {({ isSubmitting }) => (
                <>
                    {successMessage ? (
                        <Message message={successMessage} />
                    ) : (
                        <Form className="w-full" noValidate>
                            <div className="flex items-center flex-col gap-y-2 mb-2">
                                <div className="text-center">
                                    <h1 className="text-gray-900 font-bold mb-2 text-2xl">
                                        {authT("forgotPassword.title")}
                                    </h1>
                                    <p className="text-gray-500">
                                        {authT("forgotPassword.description")}
                                    </p>
                                </div>
                            </div>

                            <Input
                                name="email"
                                placeholder={authT("inputField.email")}
                                type="email"
                            />
                            {error && (
                                <div className="text-red-500 text-sm">
                                    {error}
                                </div>
                            )}

                            <div className="flex gap-3 justify-center mt-3">
                                <Button
                                    title={authT("button.submit")}
                                    type="submit"
                                    className="bg-blue-400 text-white"
                                    loading={loading || isSubmitting}
                                />
                                <Link to="/login">
                                    <Button
                                        title={authT("button.back")}
                                        className="text-blue-400"
                                    />
                                </Link>
                            </div>
                        </Form>
                    )}
                </>
            )}
        </Formik>
    );
}
