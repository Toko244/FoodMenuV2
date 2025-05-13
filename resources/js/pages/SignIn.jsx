import React from "react";
import SignIn from "../components/auth/SignInForm";
import Authorization from "../layout/Authorization";

export default function Login() {
    return (
        <>
            <Authorization FormComponent={SignIn} />
        </>
    );
}
