import React from "react";
import CheckIcon from "../icons/Check";

export default function Checkbox({ label, checked, onChange }) {
    return (
        <div className="inline-flex items-center gap-x-2">
            <label className="relative flex items-center rounded-full cursor-pointer">
                <input
                    id="ripple-off"
                    type="checkbox"
                    className="before:content[''] peer relative h-5 w-5 cursor-pointer appearance-none rounded-md border border-blue-gray-200 transition-all before:absolute before:top-2/4 before:left-2/4 before:block before:h-12 before:w-12 before:-translate-y-2/4 before:-translate-x-2/4 before:rounded-full before:bg-blue-gray-500 before:opacity-0 before:transition-opacity checked:border-gray-900 checked:bg-gray-900 checked:before:bg-gray-900"
                    checked={checked}
                    onChange={onChange}
                />
                <span className="absolute text-white transition-opacity opacity-0 pointer-events-none top-2/4 left-2/4 -translate-y-2/4 -translate-x-2/4 peer-checked:opacity-100">
                    <CheckIcon />
                </span>
            </label>
            <label className="mt-px font-light text-gray-700 cursor-pointer select-none">
                {label}
            </label>
        </div>
    );
}
