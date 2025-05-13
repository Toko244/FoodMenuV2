import React from "react";
import "../../styles/base/button.css";

export default function Button({
    onClick,
    title,
    type,
    className,
    width,
    loading,
}) {
    return (
        <button
            onClick={onClick}
            type={type}
            className={`button ${className}`}
            style={{ width: width }}
            disabled={loading}
        >
            {loading ? (
                <div className="spinner-container">
                    <div className="loading-spinner"></div>
                </div>
            ) : (
                <>
                    <span>{title}</span>
                </>
            )}
        </button>
    );
}
