import React, { useState, useEffect } from "react";
import { Formik, Form } from "formik";
import * as Yup from "yup";
import Button from "../button";
import Input from "../input";
import { useTranslation } from "react-i18next";
import {
    completeAmbassadorRegistration,
    getFormData,
} from "../../services/services";
import Message from "../message";
import { useNavigate } from "react-router-dom";
import Selector from "../selector";
import DatePicker from "../datePicker";
import PhoneInput from "../phoneInput";

export default function CompleteAmbassadorRegistrationForm({ token, email }) {
    const { t: authT } = useTranslation("auth");
    const [error, setError] = useState("");
    const [loading, setLoading] = useState(false);
    const [successMessage, setSuccessMessage] = useState("");
    const [status, setStatus] = useState("");
    const [countries, setCountries] = useState([]);
    const navigate = useNavigate();

    const initialValues = {
        email: email || "",
        name: "",
        surname: "",
        dateOfBirth: "",
        personalNumber: "",
        phone: "",
        city: "",
        address: "",
        country_id: null,
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
        phone: Yup.string()
            .matches(
                /^\+(?:[0-9] ?){6,14}[0-9]$/,
                authT("validationMessage.phoneInvalid")
            )
            .required(authT("validationMessage.requiredPhoneNumber")),
        address: Yup.string().required(
            authT("validationMessage.requiredAddress")
        ),
        country_id: Yup.string().required(
            authT("validationMessage.requiredCountry")
        ),
        dateOfBirth: Yup.string().required(
            authT("validationMessage.requiredDateOfBirth")
        ),
        city: Yup.string().required(authT("validationMessage.requiredCity")),
        personalNumber: Yup.string().required(
            authT("validationMessage.requiredPersonalNumber")
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
    const fetchCountries = () => {
        getFormData({})
            .then((response) => {
                setCountries(response.data.countries);
            })
            .catch((error) => {
                console.error("Error fetching countries:", error);
            });
    };
    useEffect(() => {
        fetchCountries();
    }, []);

    const handleSubmit = (values, { setSubmitting }) => {
        const {
            name,
            surname,
            dateOfBirth,
            personalNumber,
            phone,
            city,
            address,
            password,
            confirmPassword,
            country_id,
        } = values;

        setLoading(true);

        return completeAmbassadorRegistration({
            name,
            surname,
            dateOfBirth,
            personalNumber,
            phone,
            city,
            address,
            password,
            confirmPassword,
            country_id,
            token,
        })
            .then((response) => {
                setSuccessMessage(response.data.message);
                setStatus(response.status);
                setError("");

                setTimeout(() => {
                    navigate("/login");
                }, 10000);
            })
            .catch((error) => {
                if (error.response && error.response.data) {
                    setError(error.response.data.message);
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
                                        name="personalNumber"
                                        placeholder={authT(
                                            "inputField.personalNumber"
                                        )}
                                        type="text"
                                    />
                                    <div className="mt-2">
                                        <DatePicker
                                            name="dateOfBirth"
                                            onChange={(value) =>
                                                setFieldValue(
                                                    "dateOfBirth",
                                                    value
                                                )
                                            }
                                            placeholder={authT(
                                                "inputField.dateOfBirth"
                                            )}
                                        />
                                    </div>
                                    <Input
                                        name="address"
                                        placeholder={authT(
                                            "inputField.address"
                                        )}
                                        type="text"
                                    />
                                    <div className="mt-2">
                                        <Selector
                                            name="country_id"
                                            options={countries.map(
                                                (country) => ({
                                                    label: country.name,
                                                    value: country.id,
                                                })
                                            )}
                                            onChange={({ value }) =>
                                                setFieldValue(
                                                    "country_id",
                                                    value
                                                )
                                            }
                                        />
                                    </div>
                                    <Input
                                        name="city"
                                        placeholder={authT("inputField.city")}
                                        type="text"
                                    />
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
