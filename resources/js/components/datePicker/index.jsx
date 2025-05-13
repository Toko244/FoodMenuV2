import React from "react";
import { DatePicker } from "antd";
import { useField } from "formik";
import "../../styles/base/datePicker.css";

export default function DatePickerComponent({
    onChange,
    placeholder,
    ...props
}) {
    const handleChange = (_, dateString) => {
        onChange(dateString);
    };
    const [field, meta] = useField(props);

    return (
        <div>
            <DatePicker
                onChange={handleChange}
                style={{
                    width: "100%",
                    padding: "0.5rem 0.75rem",
                    borderRadius: "0.25rem",
                    ...(meta.touched && meta.error
                        ? { border: "1px solid #e53e3e" }
                        : {}),
                }}
                className="custom-datepicker"
                placeholder={placeholder}
            />
            {meta.touched && meta.error ? (
                <p className="text-red-500 text-xs italic mt-1">{meta.error}</p>
            ) : null}
        </div>
    );
}
