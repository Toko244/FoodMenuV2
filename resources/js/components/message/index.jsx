import React from "react";
import { Link } from "react-router-dom";
import SuccessIcon from "../icons/Success";
import ErrorIcon from "../icons/Error";
import { useTranslation } from "react-i18next";

const Message = ({ message, status }) => {
    const { t: authT } = useTranslation("auth");

    const handleColorAndIconByStatus = () => {
        if (status === 200) {
            return {
                color: "text-green-500",
                component: SuccessIcon,
            };
        } else {
            return {
                color: "text-red-500",
                component: ErrorIcon,
            };
        }
    };

    const { color, component: Icon } = handleColorAndIconByStatus();

    return (
        <div>
            <div className="flex justify-center items-center">
                <Icon />
            </div>
            <h2
                className={`text-center lg:text-2xl font-bold my-1 ${color} md:text-xl sm:text-base`}
            >
                {message}
            </h2>
            <div className="flex justify-center w-100 lg:text-base md:text-base sm:text-sm">
                <Link to="/login">
                    <span className="text-blue-500">
                        {authT("backToSignIn.title")}
                    </span>
                </Link>
            </div>
        </div>
    );
};

export default Message;
