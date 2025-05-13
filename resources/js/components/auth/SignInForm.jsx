import React, { useState } from "react";
import { Formik, Form } from "formik";
import * as Yup from "yup";
import Button from "../button";
import Input from "../input";
import { Link } from "react-router-dom";
import { login } from "../../services/services";
import { useNavigate } from "react-router-dom";
import { useTranslation } from "react-i18next";

export default function SignInForm() {
    const initialValues = { email: "", password: "" };
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState("");
    const nav = useNavigate();
    const { t: authT } = useTranslation("auth");

    const validationSchema = Yup.object({
        email: Yup.string()
            .email(authT("validationMessage.invalidEmailAddress"))
            .required(authT("validationMessage.requiredEmailAddress"))
            .matches(
                /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
                authT("validationMessage.invalidEmailAddress")
            ),
        password: Yup.string().required(
            authT("validationMessage.requiredPassword")
        ),
    });

    const handleSubmit = (values, { setSubmitting }) => {
        setLoading(true);

        return login(values)
            .then((response) => {
                const { access_token, user } = response.data;
                localStorage.setItem("accessToken", access_token);
                localStorage.setItem("user", JSON.stringify(user));
                nav("/dashboard");
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
            <Form className="w-full" noValidate>
                <div className="flex items-center flex-col mb-2">
                    <div className="text-center">
                        <h1 className="text-gray-900 font-bold mb-2 text-2xl">
                            {authT("signInPage.title")}
                        </h1>
                        <p className="text-gray-500">
                            {authT("signInPage.description")}
                        </p>
                    </div>
                </div>

                <Input
                    name="email"
                    type="email"
                    placeholder={authT("inputField.email")}
                />

                <Input
                    name="password"
                    type="password"
                    placeholder={authT("inputField.password")}
                />
                {error && (
                    <div className="text-red-500 text-sm mt-1">{error}</div>
                )}
                <div className="flex flex-col gap-5">
                    <div className="flex flex-row-reverse mt-2 text-blue-500 text-sm cursor-pointer">
                        <Link to="/forgot-password">
                            <p>{authT("forgotPassword.title")}</p>
                        </Link>
                    </div>

                    <div className="flex gap-3">
                        <Button
                            title={authT("button.auth")}
                            className="bg-blue-400 text-white rounded-md"
                            width="100%"
                            type="submit"
                            loading={loading}
                        />
                    </div>
                    <div className="text-sm flex items-center gap-1 justify-center">
                        <span className="text-gray-400 ">
                            {authT("notAMember.title")}
                        </span>
                        <Link to="/registration">
                            <div className="text-blue-500 cursor-pointer">
                                {authT("notAMember.register")}
                            </div>
                        </Link>
                    </div>
                </div>
            </Form>
        </Formik>
    );
}
