import React, { useState, useEffect, useRef } from "react";
import { useTranslation } from "react-i18next";
import "../../styles/base/languagesIcon.css";
import SwitcherDropdown from "./SwitcherDropdown";
import SwitcherSlide from "./SwitcherSlide";

export default function LanguageSwitcherController({ type }) {
    const { i18n } = useTranslation("global");
    const [currentLanguage, setCurrentLanguage] = useState(() => {
        const savedLanguage = localStorage.getItem("language");
        return savedLanguage || "ka";
    });
    const [isDropdownOpen, setIsDropdownOpen] = useState(false);
    const dropdownRef = useRef(null);

    const languages = {
        ka: "Georgian",
        en: "English",
    };

    const changeLanguage = (newLanguage) => {
        setCurrentLanguage(newLanguage);
        i18n.changeLanguage(newLanguage);
        localStorage.setItem("language", newLanguage);
    };

    useEffect(() => {
        function handleClickOutside(event) {
            if (
                dropdownRef.current &&
                !dropdownRef.current.contains(event.target)
            ) {
                setIsDropdownOpen(false);
            }
        }

        document.addEventListener("mousedown", handleClickOutside);
        return () => {
            document.removeEventListener("mousedown", handleClickOutside);
        };
    }, []);

    const toggleDropdown = () => {
        setIsDropdownOpen((prev) => !prev);
    };

    if (type === "switcherDropdown") {
        return (
            <SwitcherDropdown
                currentLanguage={currentLanguage}
                languages={languages}
                changeLanguage={changeLanguage}
                isOpen={isDropdownOpen}
                toggleDropdown={toggleDropdown}
                dropdownRef={dropdownRef}
                type="switcherDropdown"
            />
        );
    } else if (type === "switcherSlide") {
        return (
            <SwitcherSlide
                currentLanguage={currentLanguage}
                languages={languages}
                changeLanguage={changeLanguage}
                isOpen={isDropdownOpen}
                toggleDropdown={toggleDropdown}
                type="switcherSlide"
            />
        );
    }
}
