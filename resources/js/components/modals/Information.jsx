/* eslint-disable react/prop-types */
import Modal from "./Modal";
import React from "react";
import { useTranslation } from "react-i18next";

export default function Registration({ showModal, handleClose }) {
    const { t: authT } = useTranslation("auth");

    return (
        <Modal isModalOpen={showModal} onClose={handleClose} type="info">
            <div className="flex items-center justify-center">
                <div className="py-4 px-4 text-center">
                    <h1 className="font-medium text-lg">
                        {authT("information.ambassador.title")}
                    </h1>
                    <span> {authT("information.ambassador.description")}</span>
                </div>
                <div className="border-l-2 border-[#5fa5f9] border-solid h-[280px]"></div>
                <div className="py-4 px-4 text-center">
                    <h1 className="font-medium text-lg">
                        {authT("information.business.title")}
                    </h1>
                    <span> {authT("information.business.description")}</span>
                </div>
            </div>
        </Modal>
    );
}
