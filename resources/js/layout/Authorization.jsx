import React from "react";
import Image from "../assets/images/agency.png";
import LogInFooter from "../components/auth/Footer";
import { useTranslation } from "react-i18next";

export default function Authorization({ FormComponent, ...props }) {
    const { t: authT } = useTranslation("auth");

    return (
        <div className="container px-4">
            <div className="flex lg:items-center h-screen flex-col md:flex-row lg:justify-around">
                <div className="flex lg:flex-col items-center sm:flex-row">
                    <div className="flex flex-col items-center">
                        <img
                            src={Image}
                            className="lg:w-72 mb-5 sm:mb-10 lg:mb-20 md:w-56 w-52"
                        />
                        <h1 className="text-gray-800 font-bold mb-5 text-3xl text-center sm:text-4xl md:text-[25px] lg:text-[40px]">
                            {authT("layout.title")}
                        </h1>
                        <p className="text-gray-600 font-medium text-sm text-center sm:text-base lg:text-lg lg:w-[600px] md:text-[14px] pb-5">
                            {authT("layout.description")}
                        </p>
                    </div>
                </div>

                <div className="flex flex-col justify-center">
                    <div className="flex flex-col rounded-md items-center justify-center md:w-[400px] lg:w-[540px] bg-white p-8 sm:p-8">
                        <div className="flex justify-center w-full flex-col">
                            <div className="flex items-center flex-col">
                                <FormComponent {...props} />
                            </div>
                            <LogInFooter />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
