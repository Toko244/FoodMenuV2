import React from "react";
import { createRoot } from "react-dom/client";
import { BrowserRouter as Router, Route, Routes } from "react-router-dom";

import { I18nextProvider } from "react-i18next";
import i18next from "i18next";
// languages
import auth_ka from "./locales/ka/auth.json";
import auth_en from "./locales/en/auth.json";
import dashboard_ka from "./locales/ka/dashboard.json";
import dashboard_en from "./locales/en/dashboard.json";
// pages
import SignIn from "./pages/SignIn";
import ForgotPassword from "./pages/ForgotPassword";
import Dashboard from "./pages/Dashboard";
import ResetPassword from "./pages/ResetPassword";
import Registration from "./pages/Registration";
import CompleteRegistration from "./pages/CompleteUserRegistration";
import CompleteAmbassadorRegistration from "./pages/CompleteAmbassadorRegistration";
import Profile from "./pages/Profile";
import Companies from "./pages/Companies";
import UpdateCompany from "./pages/UpdateCompany";
import CreateCompany from "./pages/CreateCompany";
const selectedLanguage = localStorage.getItem("language");

i18next.init({
    interpolation: { escapeValue: false },
    lng: selectedLanguage,
    fallbackLng: "ka",
    resources: {
        en: {
            auth: auth_en,
            dashboard: dashboard_en,
        },
        ka: {
            auth: auth_ka,
            dashboard: dashboard_ka,
        },
    },
});

const App = () => {
    return (
        <>
            <Router>
                <Routes>
                    <Route path="/login" element={<SignIn />} />
                    <Route
                        path="/forgot-password"
                        element={<ForgotPassword />}
                    />
                    <Route path="/registration" element={<Registration />} />
                    <Route
                        path="/complete-registration"
                        element={<CompleteRegistration />}
                    />
                    <Route
                        path="/complete-ambassador-registration"
                        element={<CompleteAmbassadorRegistration />}
                    />
                    <Route path="/reset-password" element={<ResetPassword />} />
                    <Route path="/dashboard" element={<Dashboard />} />
                    <Route path="/profile" element={<Profile />} />
                    <Route
                        path="/dashboard/companies"
                        element={<Companies />}
                    />
                    <Route
                        path="/dashboard/companies/:companyId"
                        element={<UpdateCompany />}
                    />

                    <Route
                        path="/dashboard/create-company"
                        element={<CreateCompany />}
                    />
                </Routes>
            </Router>
        </>
    );
};

if (document.getElementById("root")) {
    createRoot(document.getElementById("root")).render(
        <>
            <I18nextProvider i18n={i18next}>
                <App />
            </I18nextProvider>
        </>
    );
}
