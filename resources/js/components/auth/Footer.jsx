import React from "react";
import LanguageSwitcherController from "../languageSwitcher/LanguageController";

export default function LogInFooter() {
    return (
        <div className="flex justify-between items-center mt-3">
            <LanguageSwitcherController type="switcherDropdown" />
            {/* <div className="flex gap-3 font-base font-semibold text-xs text-blue-500 cursor-pointer">
                <span>Terms</span>
                <span>Plans</span>
                <span>Contact Us</span>
            </div> */}
        </div>
    );
}
