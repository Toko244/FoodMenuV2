import React, { useState } from "react";
import LanguageIcon from "./LanguageIcon";

const SwitcherSlide = ({ currentLanguage, languages, changeLanguage }) => {
    const [isOpen, setIsOpen] = useState(false);

    const handleMouseEnter = () => {
        setIsOpen(true);
    };

    const handleMouseLeave = () => {
        setIsOpen(false);
    };

    return (
        <div
            className="relative"
            onMouseEnter={handleMouseEnter}
            onMouseLeave={handleMouseLeave}
        >
            <div className="flex items-center justify-between px-4 py-2 hover:bg-gray-100 hover:text-blue-500 rounded-[6px]">
                <span>Language</span>
                <div className="flex items-center rounded-md bg-gray-100 px-1 py-1 gap-1">
                    <span className="text-[13px]">
                        {languages[currentLanguage]}
                    </span>
                    <LanguageIcon
                        className={`icon-language-${currentLanguage}`}
                    />
                </div>
            </div>
            <div
                className={`absolute left-0 top-0 mt-2 transform -translate-x-full bg-white border rounded-lg shadow-lg px-3 py-2 transition-opacity duration-300 ease-in-out ${
                    isOpen ? "opacity-100" : "opacity-0 pointer-events-none"
                }`}
            >
                {Object.keys(languages).map((lang) => (
                    <div
                        key={lang}
                        onClick={() => changeLanguage(lang)}
                        className="py-[2px] text-gray-800"
                    >
                        <div
                            className={`flex items-center gap-2 px-4 py-1 rounded-[6px] ${
                                currentLanguage === lang
                                    ? "bg-gray-100 text-blue-500"
                                    : "hover:bg-gray-100 hover:text-blue-500"
                            }`}
                        >
                            <LanguageIcon className={`icon-language-${lang}`} />
                            <span className="text-[15px]">
                                {languages[lang]}
                            </span>
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
};

export default SwitcherSlide;
