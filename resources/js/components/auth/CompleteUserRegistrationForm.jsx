import React, { useState } from "react";
import { Formik, Form } from "formik";
import * as Yup from "yup";
import Button from "../button";
import Input from "../input";
import { useTranslation } from "react-i18next";
import { completeUserRegistration } from "../../services/services";
import Message from "../message";
import { useNavigate } from "react-router-dom";
import PhoneInput from "../phoneInput";

export default function CompleteRegistrationForm({ token, email }) {
    const { t: authT } = useTranslation("auth");
    const [error, setError] = useState("");
    const [loading, setLoading] = useState(false);
    const [successMessage, setSuccessMessage] = useState("");
    const [status, setStatus] = useState("");
    const navigate = useNavigate();

    const initialValues = {
        email: email || "",
        name: "",
        surname: "",
        phone: "",
        password: "",
        confirmPassword: "",
    };

    const validationSchema = Yup.object({
        name: Yup.string()
            .max(50, authT("validationMessage.nameTooLong"))
            .required(authT("validationMessage.requiredName")),
        surname: Yup.string()
            .max(50, authT("validationMessage.surnameTooLong"))
            .required(authT("validationMessage.requiredSurname")),
        phone: Yup.string().required(
            authT("validationMessage.requiredPhoneNumber")
        ),
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

    const handleSubmit = (values, { setSubmitting }) => {
        const { name, surname, phone, password, confirmPassword } = values;
        setLoading(true);

        return completeUserRegistration({
            name,
            surname,
            phone,
            password,
            confirmPassword,
            token,
        })
            .then((response) => {
                const { message } = response.data;
                const { status } = response;
                setSuccessMessage(message);
                setStatus(status);
                setError("");
                setTimeout(() => {
                    navigate("/login");
                }, 10000);
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
            initialValues={initialValues}
            validationSchema={validationSchema}
            onSubmit={handleSubmit}
        >
            {({ isSubmitting, setFieldValue }) => (
                <>
                    {successMessage ? (
                        <Message message={successMessage} status={status} />
                    ) : (
                        <Form className="w-full" noValidate>
                            <div className="flex items-center flex-col gap-y-2 mb-2">
                                <div className="text-center">
                                    <h1 className="text-gray-900 font-bold mb-2 text-2xl">
                                        {authT("registration.title")}
                                    </h1>
                                </div>
                            </div>

                            {token && (
                                <>
                                    <Input
                                        name="email"
                                        placeholder={authT("inputField.email")}
                                        type="email"
                                        disabled
                                    />
                                    <Input
                                        name="name"
                                        placeholder={authT("inputField.name")}
                                        type="text"
                                    />
                                    <Input
                                        name="surname"
                                        placeholder={authT(
                                            "inputField.surname"
                                        )}
                                        type="text"
                                    />
                                    <div className="mt-2">
                                        <PhoneInput
                                            name="phone"
                                            onChange={(value) =>
                                                setFieldValue("phone", value)
                                            }
                                        />
                                    </div>
                                    <Input
                                        name="password"
                                        placeholder={authT(
                                            "inputField.password"
                                        )}
                                        type="password"
                                    />
                                    <Input
                                        name="confirmPassword"
                                        placeholder={authT(
                                            "inputField.confirmPassword"
                                        )}
                                        type="password"
                                    />
                                </>
                            )}

                            {error && (
                                <div className="text-red-500 text-xs mt-1">
                                    {error}
                                </div>
                            )}

                            <div className="flex gap-3 justify-center mt-3">
                                <Button
                                    title={authT("button.signUp")}
                                    type="submit"
                                    className="bg-blue-400 text-white"
                                    width="100%"
                                    loading={loading || isSubmitting}
                                />
                            </div>
                        </Form>
                    )}
                </>
            )}
        </Formik>
    );
}
