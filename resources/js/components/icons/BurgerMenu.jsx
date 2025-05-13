import React from "react";
import "../../styles/base/burgerMenu.css";

export default function BurgerMenu({ onClick }) {
    return (
        <div className="row">
            <input
                type="checkbox"
                id="hamburger"
                className="hamburger-checkbox"
                onClick={onClick}
            />
            <label htmlFor="hamburger" className="hamburger">
                <span className="line"></span>
                <span className="line"></span>
                <span className="line"></span>
            </label>
        </div>
    );
}
