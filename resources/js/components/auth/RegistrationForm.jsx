import React, { useState, useEffect } from "react";
import { Formik, Form } from "formik";
import * as Yup from "yup";
import Button from "../button";
import Input from "../input";
import { Link } from "react-router-dom";
import { useTranslation } from "react-i18next";
import { registration } from "../../services/services";
import Message from "../message";
import RadioButtons from "../radioButtons";
import QuestionIcon from "../icons/QuestionCircle";
import Information from "../modals/Information";
import { getQueryParameters } from "../../helpers/routes";

export default function RegistrationForm() {
    const { t: authT } = useTranslation("auth");
    const queryParams = getQueryParameters();

    const [error, setError] = useState("");
    const [loading, setLoading] = useState(false);
    const [successMessage, setSuccessMessage] = useState("");
    const [status, setStatus] = useState("");
    const [options, setOptions] = useState("business");
    const [showModal, setShowModal] = useState(false);
    const [type, setType] = useState(queryParams.user_type);

    const handleShow = () => setShowModal(true);
    const handleClose = () => setShowModal(false);

    useEffect(() => {
        if (queryParams.referralId) {
            setShowModal(false);
            setOptions("business");
        } else {
            handleShow();
            if (type && type.toLowerCase() === "ambassador") {
                setOptions("Ambassador");
            } else if (
                queryParams.type &&
                queryParams.type.toLowerCase() === "ambassador"
            ) {
                setOptions("Ambassador");
            } else {
                setOptions("business");
            }
        }
    }, []);

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

        const userType = options === "business" ? "user" : options;
        return registration(values.email, userType, queryParams.referralId)
            .then((response) => {
                const { message } = response.data;
                const { status } = response;
                setSuccessMessage(message);
                setStatus(status);
                setError("");
            })
            .catch((error) => {
                if (error.response && error.response.data) {
                    const { message } = error.response.data;
                    const { status } = error.response;
                    setError(message);
                    setStatus(status);
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
                        <Message message={successMessage} status={status} />
                    ) : (
                        <Form className="w-full" noValidate>
                            <div className="flex items-center flex-col gap-y-2 mb-4">
                                <div className="text-center">
                                    <h1 className="text-gray-900 font-bold mb-2 text-2xl">
                                        {authT("registration.title")}
                                    </h1>
                                    <p className="text-gray-500">
                                        {authT("registration.description")}
                                    </p>
                                </div>
                            </div>
                            <div className="flex flex-row justify-between items-center">
                                <div className="radioButton-div">
                                    <RadioButtons
                                        data={["business", "ambassador"]}
                                        value={options}
                                        disabled={queryParams.referralId}
                                        onChange={(e) => setOptions(e)}
                                    />
                                </div>
                                <div
                                    onClick={handleShow}
                                    className="cursor-pointer"
                                >
                                    <QuestionIcon />
                                </div>
                                <Information
                                    showModal={showModal}
                                    handleClose={handleClose}
                                />
                            </div>
                            <Input
                                name="email"
                                placeholder={authT("inputField.email")}
                                type="email"
                            />

                            {error && (
                                <div className="text-red-500 text-sm mt-1">
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
