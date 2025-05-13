import React from "react";

const RadioButtons = ({ data, value, disabled, onChange }) => {
    const Button = ({ item }) => {
        const isSelected = value === item;
        return (
            <button
                className={`${
                    isSelected
                        ? "bg-blue-400 hover:bg-blue-500"
                        : "bg-gray-400 hover:bg-gray-500"
                } text-white capitalize focus:shadow-outline px-2 rounded-sm`}
                onClick={() => onChange(item.toLowerCase())}
                disabled={disabled}
                type="button"
            >
                <span className="text-sm"> {item}</span>
            </button>
        );
    };
    return (
        <div className="flex items-center gap-4">
            {data.map((item, index) => (
                <Button item={item} key={index} />
            ))}
        </div>
    );
};

export default RadioButtons;
