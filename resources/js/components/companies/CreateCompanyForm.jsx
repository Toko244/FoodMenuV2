import React, { useState, useEffect } from "react";
import { Formik, Form } from "formik";
import * as Yup from "yup";
import { useCompanyStore } from "../../store/companyStore";
import { useTranslation } from "react-i18next";
import Input from "../input";
import Selector from "../selector";
import PhoneInput from "../phoneInput";
import Button from "../button";
import RadioButtons from "../radioButtons";
import { getFormData } from "../../services/services";
import Editor from "../editor";
import Checkbox from "../checkBox";
import Select2 from "../select2";

export default function CreateCompanyForm() {
    const language = useCompanyStore((state) => state.selectedLanguage);
    const updateLanguage = useCompanyStore((state) => state.updateLanguage);
    const { t: dashboardT } = useTranslation("dashboard");
    const [countries, setCountries] = useState([]);
    const [error, setError] = useState("");
    const [loading, setLoading] = useState(false);
    const [step, setStep] = useState(1);
    const [editorLoaded, setEditorLoaded] = useState(false);
    const [data, setData] = useState("");
    const [languages, setLanguages] = useState([]);
    const [selectedLanguages, setSelectedLanguages] = useState([]);
    const [defaultLanguage, setDefaultLanguage] = useState(null);

    useEffect(() => {
        const fetchData = async () => {
            try {
                const response = await getFormData({});
                const formData = response.data;

                const languageOptions = formData.languages.map((lang) => ({
                    value: lang.id,
                    label: lang.name,
                }));
                setLanguages(languageOptions);
            } catch (error) {
                console.error("Error fetching form data:", error);
            }
        };

        fetchData();
    }, []);

    const handleLanguagesChange = (selectedOptions) => {
        setSelectedLanguages(selectedOptions);
        if (
            !selectedOptions.some(
                (option) => option.value === defaultLanguage?.value
            )
        ) {
            setDefaultLanguage(null);
        }
    };

    const handleDefaultLanguageChange = (selectedOption) => {
        setDefaultLanguage(selectedOption);
    };

    const validationSchema = Yup.object({
        name: Yup.string()
            .max(50, dashboardT("validationMessage.nameTooLong"))
            .required(dashboardT("validationMessage.requiredName")),
        description: Yup.string()
            .max(50, dashboardT("validationMessage.nameTooLong"))
            .required(dashboardT("validationMessage.requiredName")),
        state: Yup.string().required(
            dashboardT("validationMessage.requiredCity")
        ),
        city: Yup.string().required(
            dashboardT("validationMessage.requiredCity")
        ),
        address: Yup.string().required(
            dashboardT("validationMessage.requiredAddress")
        ),
        email: Yup.string()
            .email(dashboardT("validationMessage.invalidEmailAddress"))
            .required(dashboardT("validationMessage.requiredEmailAddress"))
            .matches(
                /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
                dashboardT("validationMessage.invalidEmailAddress")
            ),
        phone: Yup.string()
            .matches(
                /^\+(?:[0-9] ?){6,14}[0-9]$/,
                dashboardT("validationMessage.phoneInvalid")
            )
            .required(dashboardT("validationMessage.requiredPhoneNumber")),
        country_id: Yup.string().required(
            dashboardT("validationMessage.requiredCountry")
        ),
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

    const handleNextStep = () => setStep(step + 1);
    const handlePreviousStep = () => setStep(step - 1);
    const handleSetStep = (step) => setStep(step);

    const initialValues = {
        name: "",
        description: "",
        state: "",
        city: "",
        address: "",
        email: "",
        phone: "",
        country_id: "",
        twitter: "",
        facebook: "",
        instagram: "",
        tiktok: "",
    };
    const handleSubmit = (values, { setSubmitting }) => {
        //logic for submitting
    };

    useEffect(() => {
        setEditorLoaded(true);
    }, []);

    return (
        <div className="flex lg:flex-row md:flex-col max-[768px]:flex-col">
            <div className="flex flex-col gap-y-7 p-6 min-w-72 cursor-pointer">
                <button onClick={() => handleSetStep(1)} className="flex gap-4">
                    <div
                        className={`w-10 h-10 text-lg text-white font-medium rounded-[.375rem] flex justify-center items-center ${
                            step === 1 ? "bg-blue-400" : "bg-gray-400"
                        }`}
                    >
                        <span>1</span>
                    </div>
                    <div className="flex flex-col items-start">
                        <span
                            className={`font-medium text-[.9375rem] ${
                                step === 1 ? "text-[#5d596c]" : "text-[#a5a3ae]"
                            }`}
                        >
                            {dashboardT(
                                "companies.form.companyParameters.title"
                            )}
                        </span>
                        <span className="font-normal text-[0.8125rem] text-[#a5a3ae] text-left">
                            {dashboardT(
                                "companies.form.companyParameters.description"
                            )}
                        </span>
                    </div>
                </button>
                <button onClick={() => handleSetStep(2)} className="flex gap-4">
                    <div
                        className={`w-10 h-10 text-lg text-white font-medium rounded-[.375rem] flex justify-center items-center ${
                            step === 2 ? "bg-blue-400" : "bg-gray-400"
                        }`}
                    >
                        <span>2</span>
                    </div>
                    <div className="flex flex-col items-start">
                        <span
                            className={`font-medium text-[.9375rem] ${
                                step === 2 ? "text-[#5d596c]" : "text-[#a5a3ae]"
                            }`}
                        >
                            {dashboardT("companies.form.companyDetails.title")}
                        </span>
                        <span className="font-normal text-[0.8125rem] text-[#a5a3ae] text-left">
                            {dashboardT(
                                "companies.form.companyDetails.description"
                            )}
                        </span>
                    </div>
                </button>
                <button onClick={() => handleSetStep(3)} className="flex gap-4">
                    <div
                        className={`w-10 h-10 text-lg text-white font-medium rounded-[.375rem] flex justify-center items-center ${
                            step === 3 ? "bg-blue-400" : "bg-gray-400"
                        }`}
                    >
                        <span>3</span>
                    </div>
                    <div className="flex flex-col items-start">
                        <span
                            className={`font-medium text-[.9375rem] ${
                                step === 3 ? "text-[#5d596c]" : "text-[#a5a3ae]"
                            }`}
                        >
                            {dashboardT("companies.form.socialLinks.title")}
                        </span>
                        <span className="font-normal text-[0.8125rem] text-[#a5a3ae] text-left">
                            {dashboardT(
                                "companies.form.socialLinks.description"
                            )}
                        </span>
                    </div>
                </button>
            </div>
            {/* form */}
            <div className="w-full p-6 bg-white shadow-md rounded-md">
                <Formik
                    initialValues={{
                        initialValues,
                    }}
                    validationSchema={validationSchema}
                    onSubmit={handleSubmit}
                >
                    {({ isSubmitting, setFieldValue }) => (
                        <Form className="w-full" noValidate>
                            {/* first step form */}
                            {step === 1 && (
                                <>
                                    <div className="mb-4">
                                        <h6 className="mb-0 text-[0.9375rem]">
                                            {dashboardT(
                                                "companies.form.companyParameters.title"
                                            )}
                                        </h6>
                                        <span className="mb-0 text-[0.8125rem]">
                                            {dashboardT(
                                                "companies.form.companyParameters.form.description"
                                            )}
                                        </span>
                                    </div>
                                    <div className="lg:grid lg:grid-cols-6 gap-2 md:flex md:flex-col">
                                        <div className="col-span-3">
                                            <label
                                                htmlFor="name"
                                                className="text-[0.8125rem] text-[#5d596c]"
                                            >
                                                {dashboardT("inputField.name")}
                                            </label>
                                            <Input
                                                name="name"
                                                placeholder={dashboardT(
                                                    "inputField.name"
                                                )}
                                                type="text"
                                                noMargin
                                                className="p-2 border border-gray-300 rounded "
                                            />
                                        </div>
                                        <div className="col-span-3">
                                            <label
                                                htmlFor="image"
                                                className="text-[0.8125rem] text-[#5d596c]"
                                            >
                                                {dashboardT("inputField.logo")}
                                            </label>
                                            <input
                                                type="file"
                                                name="image"
                                                accept="image/*"
                                                onChange={(event) => {
                                                    const file =
                                                        event.target.files[0];
                                                }}
                                                className="p-[0.2rem] border border-gray-300 rounded w-full"
                                            />
                                        </div>
                                        <div className="col-span-3">
                                            <label
                                                htmlFor="languages"
                                                className="text-[0.8125rem] text-[#5d596c]"
                                            >
                                                {dashboardT(
                                                    "inputField.languages"
                                                )}
                                            </label>
                                            <Select2
                                                name="languages"
                                                options={languages}
                                                defaultValue={selectedLanguages}
                                                onChange={handleLanguagesChange}
                                                isMulti
                                            />
                                        </div>
                                        <div className="col-span-3">
                                            <label
                                                htmlFor="defaultLanguage"
                                                className="text-[0.8125rem] text-[#5d596c]"
                                            >
                                                {dashboardT(
                                                    "inputField.defaultLanguage"
                                                )}
                                            </label>
                                            <Selector
                                                name="defaultLanguage"
                                                options={selectedLanguages}
                                                defaultValue={defaultLanguage}
                                                onChange={
                                                    handleDefaultLanguageChange
                                                }
                                            />
                                        </div>
                                    </div>

                                    <div className="flex justify-end mt-4">
                                        <Button
                                            title={dashboardT("button.next")}
                                            type="button"
                                            onClick={handleNextStep}
                                            className="bg-blue-500 text-white px-4 py-2 rounded"
                                        />
                                    </div>
                                </>
                            )}
                            {/* second step form */}
                            {step === 2 && (
                                <>
                                    <div className="mb-4">
                                        <h6 className="mb-0 text-[0.9375rem]">
                                            {dashboardT(
                                                "companies.form.companyDetails.title"
                                            )}
                                        </h6>
                                        <span className="mb-0 text-[0.8125rem]">
                                            {dashboardT(
                                                "companies.form.companyDetails.form.description"
                                            )}
                                        </span>
                                    </div>
                                    <div className="lg:grid lg:grid-cols-6 gap-2 md:flex md:flex-col">
                                        <div className="col-span-2">
                                            <label
                                                htmlFor="name"
                                                className="text-[0.8125rem] text-[#5d596c]"
                                            >
                                                {dashboardT("inputField.name")}
                                            </label>
                                            <Input
                                                name="name"
                                                placeholder={dashboardT(
                                                    "inputField.name"
                                                )}
                                                type="text"
                                                noMargin
                                                className="p-2 border border-gray-300 rounded "
                                            />
                                        </div>
                                        <div className="col-span-2">
                                            <label
                                                htmlFor="email"
                                                className="text-[0.8125rem] text-[#5d596c]"
                                            >
                                                {dashboardT("inputField.email")}
                                            </label>
                                            <Input
                                                name="email"
                                                placeholder={dashboardT(
                                                    "inputField.email"
                                                )}
                                                type="text"
                                                noMargin
                                                className="w-full p-2 border border-gray-300 rounded"
                                            />
                                        </div>
                                        <div className="col-span-2">
                                            <label
                                                htmlFor="phone"
                                                className="text-[0.8125rem] text-[#5d596c]"
                                            >
                                                {dashboardT("inputField.phone")}
                                            </label>
                                            <PhoneInput
                                                name="phone"
                                                onChange={(value) =>
                                                    setFieldValue(
                                                        "phone",
                                                        value
                                                    )
                                                }
                                            />
                                        </div>
                                        <div className="col-span-2">
                                            <label
                                                htmlFor="city"
                                                className="text-[0.8125rem] text-[#5d596c]"
                                            >
                                                {dashboardT("inputField.city")}
                                            </label>
                                            <Input
                                                name="city"
                                                placeholder={dashboardT(
                                                    "inputField.city"
                                                )}
                                                type="text"
                                                noMargin
                                                className="w-full p-2 border border-gray-300 rounded"
                                            />
                                        </div>
                                        <div className="col-span-2">
                                            <label
                                                htmlFor="address"
                                                className="text-[0.8125rem] text-[#5d596c]"
                                            >
                                                {dashboardT(
                                                    "inputField.address"
                                                )}
                                            </label>
                                            <Input
                                                name="address"
                                                placeholder={dashboardT(
                                                    "inputField.address"
                                                )}
                                                type="text"
                                                noMargin
                                                className="w-full p-2 border border-gray-300 rounded"
                                            />
                                        </div>

                                        <div className="col-span-2">
                                            <label
                                                htmlFor="state"
                                                className="text-[0.8125rem] text-[#5d596c]"
                                            >
                                                {dashboardT("inputField.state")}
                                            </label>
                                            <Input
                                                name="state"
                                                placeholder={dashboardT(
                                                    "inputField.state"
                                                )}
                                                type="text"
                                                noMargin
                                                className="w-full p-2 border border-gray-300 rounded"
                                            />
                                        </div>

                                        <div className="col-span-3">
                                            <label
                                                htmlFor="zipCode"
                                                className="text-[0.8125rem] text-[#5d596c]"
                                            >
                                                {dashboardT(
                                                    "inputField.zipCode"
                                                )}
                                            </label>
                                            <Input
                                                name="zipCode"
                                                placeholder={dashboardT(
                                                    "inputField.zipCode"
                                                )}
                                                type="text"
                                                noMargin
                                                className="w-full p-2 border border-gray-300 rounded"
                                            />
                                        </div>
                                        <div className="col-span-3">
                                            <label
                                                htmlFor="country"
                                                className="text-[0.8125rem] text-[#5d596c]"
                                            >
                                                {dashboardT(
                                                    "inputField.country"
                                                )}
                                            </label>
                                            <Selector
                                                name="country"
                                                options={countries.map(
                                                    (country) => ({
                                                        label: country.name,
                                                        value: country.id,
                                                    })
                                                )}
                                                onChange={({ value }) =>
                                                    setFieldValue(
                                                        "country",
                                                        value
                                                    )
                                                }
                                            />
                                        </div>
                                        <div className="col-span-6">
                                            <label
                                                htmlFor="description"
                                                className="text-[0.8125rem] text-[#5d596c]"
                                            >
                                                {dashboardT(
                                                    "inputField.description"
                                                )}
                                            </label>
                                            <Editor
                                                name="description"
                                                onChange={(data) => {
                                                    setData(data);
                                                    setFieldValue(
                                                        "description",
                                                        data
                                                    );
                                                }}
                                                editorLoaded={editorLoaded}
                                                value={data}
                                            />
                                        </div>
                                        <div className="max-[768px]:mt-4 ">
                                            <Checkbox
                                                label="ambassador"
                                                // checked="/"
                                                // onChange="/"
                                            />
                                        </div>
                                    </div>
                                    <div className="flex justify-between mt-4">
                                        <Button
                                            title={dashboardT(
                                                "button.previous"
                                            )}
                                            type="button"
                                            onClick={handlePreviousStep}
                                            className="bg-gray-400 text-white px-4 py-2 rounded"
                                        />

                                        <Button
                                            title={dashboardT("button.next")}
                                            type="button"
                                            onClick={handleNextStep}
                                            className="bg-blue-500 text-white px-4 py-2 rounded"
                                        />
                                    </div>
                                </>
                            )}
                            {/* third step form */}
                            {step === 3 && (
                                <>
                                    <div className="mb-4">
                                        <h6 className="mb-0 text-[0.9375rem]">
                                            {dashboardT(
                                                "companies.form.socialLinks.title"
                                            )}
                                        </h6>
                                        <span className="mb-0 text-[0.8125rem]">
                                            {dashboardT(
                                                "companies.form.socialLinks.form.description"
                                            )}
                                        </span>
                                    </div>
                                    <div className="lg:grid lg:grid-cols-2 gap-3 md:flex md:flex-col">
                                        <div>
                                            <label
                                                htmlFor="twitter"
                                                className="text-[0.8125rem] text-[#5d596c]"
                                            >
                                                Twitter
                                            </label>
                                            <Input
                                                name="twitter"
                                                placeholder="https://twitter.com/abc"
                                                type="text"
                                                noMargin
                                                className="w-full p-2 border border-gray-300 rounded"
                                            />
                                        </div>
                                        <div>
                                            <label
                                                htmlFor="facebook"
                                                className="text-[0.8125rem] text-[#5d596c]"
                                            >
                                                Facebook
                                            </label>
                                            <Input
                                                name="facebook"
                                                placeholder="https://facebook.com/abc"
                                                type="text"
                                                noMargin
                                                className="w-full p-2 border border-gray-300 rounded"
                                            />
                                        </div>
                                        <div>
                                            <label
                                                htmlFor="instagram"
                                                className="text-[0.8125rem] text-[#5d596c]"
                                            >
                                                Instagram
                                            </label>
                                            <Input
                                                name="instagram"
                                                placeholder="https://instagram.com/abc"
                                                type="text"
                                                noMargin
                                                className="w-full p-2 border border-gray-300 rounded"
                                            />
                                        </div>
                                        <div>
                                            <label
                                                htmlFor="tiktok"
                                                className="text-[0.8125rem] text-[#5d596c]"
                                            >
                                                TikTok
                                            </label>
                                            <Input
                                                name="tiktok"
                                                placeholder="https://twitter.com/abc"
                                                type="text"
                                                noMargin
                                                className="w-full p-2 border border-gray-300 rounded"
                                            />
                                        </div>
                                    </div>
                                    <div className="flex justify-between mt-4">
                                        <Button
                                            title={dashboardT(
                                                "button.previous"
                                            )}
                                            type="button"
                                            onClick={handlePreviousStep}
                                            className="bg-gray-400 text-white px-4 py-2 rounded"
                                        />
                                        <Button
                                            title={dashboardT("button.submit")}
                                            type="submit"
                                            className="bg-green-500 text-white px-4 py-2 rounded"
                                            loading={loading || isSubmitting}
                                        />
                                    </div>
                                </>
                            )}

                            {error && (
                                <div className="text-red-500 text-xs mt-1">
                                    {error}
                                </div>
                            )}
                        </Form>
                    )}
                </Formik>
            </div>
        </div>
    );
}
