import React from "react";
import { useField } from "formik";

export default function Input({ type, placeholder, noMargin, ...props }) {
    const [field, meta] = useField(props);

    return (
        <div>
            <input
                type={type || "text"}
                {...field}
                {...props}
                placeholder={placeholder}
                autoComplete="off"
                className={`appearance-none border border-solid border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ${
                    noMargin ? "" : "mt-2"
                } ${meta.touched && meta.error ? "border-red-500" : ""}`}
            />
            {meta.touched && meta.error ? (
                <p className="text-red-500 text-xs mt-1">{meta.error}</p>
            ) : null}
        </div>
    );
}
