import React, { useState, useEffect } from "react";
import { PhoneInput } from "react-international-phone";
import { useField } from "formik";
import "react-international-phone/style.css";
import "../../styles/base/phoneInput.css";

export default function Phone({ onChange, ...props }) {
    const [phone, setPhone] = useState("");
    const [field, meta] = useField(props);

    useEffect(() => {
        const container = document.querySelector(
            ".react-international-phone-country-selector"
        );
        if (container) {
            if (meta.touched && meta.error) {
                container.classList.add("error");
            } else {
                container.classList.remove("error");
            }
        }
    }, [meta.touched, meta.error]);

    return (
        <div>
            <PhoneInput
                defaultCountry="ge"
                value={phone}
                onChange={onChange}
                inputStyle={{
                    border:
                        meta.touched && meta.error ? "1px solid #e53e3e" : "",
                }}
            />
            {meta.touched && meta.error ? (
                <p className="text-red-500 text-xs italic mt-1">{meta.error}</p>
            ) : null}
        </div>
    );
}
