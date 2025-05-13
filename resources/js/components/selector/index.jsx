import React from "react";
import Select from "react-select";
import { useField } from "formik";
import { useTranslation } from "react-i18next";
import "../../styles/base/selector.css";

export default function Selector({ options, onChange, ...props }) {
    const [field, meta] = useField(props);
    const { t: authT } = useTranslation("auth");

    return (
        <div>
            <Select
                options={options}
                onChange={onChange}
                placeholder={authT("inputField.selector")}
                theme={(theme) => ({
                    ...theme,
                    colors: {
                        ...theme.colors,
                        neutral50: "#9ba3af",
                    },
                })}
                className="z-[10000]"
                styles={{
                    control: (provided, state) => ({
                        ...provided,
                        outline: "none",
                        border:
                            meta.touched && meta.error
                                ? "1px solid #e53e3e"
                                : "",
                        "&:hover": {
                            border:
                                meta.touched && meta.error
                                    ? "1px solid #e53e3e"
                                    : "",
                        },
                    }),
                }}
            />
            {meta.touched && meta.error ? (
                <p className="text-red-500 text-xs italic mt-1">{meta.error}</p>
            ) : null}
        </div>
    );
}
