import React from "react";
import CloseIcon from "../icons/Close";
import "../../styles/base/modal.css";
import Warning from "../icons/Warning";
import Info from "../icons/InfoCircle";

const Modal = ({ isModalOpen, children, onClose, type }) => {
    if (!isModalOpen) {
        return null;
    }

    const handleBackgroundClick = (e) => {
        if (e.target.classList.contains("modal")) {
            onClose();
        }
    };

    const iconTypes = {
        warning: Warning,
        info: Info,
    };

    const Icon = iconTypes[type] || null;

    return (
        <section
            className="modal z-50 fixed inset-0 overflow-y-auto"
            onClick={handleBackgroundClick}
        >
            <article className="modal-content relative bg-white mx-auto my-8 p-6 rounded-md text-black">
                <div className="flex justify-between items-center">
                    {Icon && <Icon />}
                    <div onClick={onClose} className="cursor-pointer">
                        <CloseIcon />
                    </div>
                </div>

                <div>{children}</div>
            </article>
        </section>
    );
};

export default Modal;
