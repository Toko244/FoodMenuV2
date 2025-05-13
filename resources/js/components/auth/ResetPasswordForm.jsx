import React, { useState } from "react";
import { Formik, Form } from "formik";
import * as Yup from "yup";
import Button from "../button";
import Input from "../input";
import { useTranslation } from "react-i18next";
import { resetPassword } from "../../services/services";
import { Link } from "react-router-dom";

export default function ResetPasswordForm({ token, email }) {
    const { t: authT } = useTranslation("auth");
    const [error, setError] = useState("");
    const [loading, setLoading] = useState(false);

    const validationSchema = Yup.object({
        password: Yup.string()
            .required(authT("validationMessage.requiredPassword"))
            .matches(
                /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/,
                authT("validationMessage.passwordRequirements")
            ),
        confirmPassword: Yup.string()
            .oneOf(
                [Yup.ref("password"), null],
                authT("validationMessage.passwordsMustMatch")
            )
            .required(authT("validationMessage.confirmPasswordRequired")),
    });
    // must change on promises
    const handleSubmit = async (values, { setSubmitting }) => {
        setLoading(true);

        try {
            const response = await resetPassword({
                token: token,
                email: email,
                password: values.password,
                confirmPassword: values.confirmPassword,
            });
        } catch (error) {
            if (error.response && error.response.data) {
                const { message } = error.response.data;
                setError(message);
            }
        } finally {
            setLoading(false);
            setSubmitting(false);
        }
    };
    return (
        <Formik
            initialValues={{ password: "", confirmPassword: "" }}
            validationSchema={validationSchema}
            onSubmit={handleSubmit}
        >
            {({ isSubmitting }) => (
                <>
                    <Form className="w-full" noValidate>
                        <div className="flex items-center flex-col gap-y-2 mb-2">
                            <div className="text-center">
                                {error && (
                                    <div className="text-red-500 text-sm">
                                        {error}
                                    </div>
                                )}
                                <h1 className="text-gray-900 font-bold mb-2 text-2xl">
                                    {authT("resetPassword.title")}
                                </h1>
                            </div>
                        </div>
                        <Input
                            name="email"
                            placeholder={authT("inputField.email")}
                            type="email"
                            value={email}
                            disabled
                        />
                        <Input
                            name="password"
                            placeholder={authT("inputField.password")}
                            type="password"
                        />
                        <Input
                            name="confirmPassword"
                            placeholder={authT("inputField.confirmPassword")}
                            type="password"
                        />

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
                </>
            )}
        </Formik>
    );
}
