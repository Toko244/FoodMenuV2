import React, { useState, useEffect } from "react";
import Authorization from "../layout/Authorization";
import RegistrationForm from "../components/auth/RegistrationForm";
import { getQueryParameters } from "../helpers/routes";

export default function Registration() {
    const queryParams = getQueryParameters();
    const [referralId, setReferralId] = useState(queryParams.referralId);

    useEffect(() => {
        const saveReferralIdToLocalStorage = (referralId) => {
            const currentDate = new Date();
            const expirationDate = new Date(currentDate);
            expirationDate.setDate(expirationDate.getDate() + 3);

            const referralData = {
                referralId,
            };

            localStorage.setItem("referralData", JSON.stringify(referralData));
        };

        const referralData = JSON.parse(localStorage.getItem("referralData"));
        const newReferralId = queryParams.referralId;

        if (
            newReferralId &&
            (!referralData || referralData.referralId !== newReferralId)
        ) {
            saveReferralIdToLocalStorage(newReferralId);
            setReferralId(newReferralId);
        } else if (referralData) {
            if (referralData.expiresAt > Date.now()) {
                setReferralId(referralData.referralId);
            } else {
                localStorage.removeItem("referralData");
                setReferralId(null);
            }
        }
    }, [queryParams.referralId]);

    return (
        <>
            <Authorization
                FormComponent={RegistrationForm}
                referralId={referralId}
            />
        </>
    );
}
