import React from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faChevronDown, faChevronUp } from "@fortawesome/free-solid-svg-icons";
import LanguageIcon from "./LanguageIcon";

const SwitcherDropdown = ({
    currentLanguage,
    languages,
    changeLanguage,
    isOpen,
    toggleDropdown,
    dropdownRef,
}) => {
    return (
        <div className="relative inline-block" ref={dropdownRef}>
            <button
                onClick={toggleDropdown}
                value={currentLanguage}
                className="text-black px-3 py-2 flex items-center gap-1"
            >
                <LanguageIcon className={`icon-language-${currentLanguage}`} />
                <span className="hover:text-blue-600 text-[15px]">
                    {languages[currentLanguage]}
                </span>
                <FontAwesomeIcon
                    icon={isOpen ? faChevronUp : faChevronDown}
                    className="text-[12px]"
                />
            </button>

            {isOpen && (
                <div className="absolute top-full mt-1 bg-white rounded-md shadow-lg">
                    {Object.keys(languages).map((langCode) => (
                        <button
                            key={langCode}
                            onClick={() => changeLanguage(langCode)}
                            className="w-full px-4 py-2 hover:bg-gray-100 flex items-center gap-2"
                        >
                            <LanguageIcon
                                className={`icon-language-${langCode}`}
                            />
                            <span className="text-[13px]">
                                {languages[langCode]}
                            </span>
                        </button>
                    ))}
                </div>
            )}
        </div>
    );
};

export default SwitcherDropdown;
