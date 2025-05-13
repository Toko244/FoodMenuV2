import React from "react";

export default function Loading() {
    return (
        <div className="flex justify-center items-center h-screen">
            <div className="border-4 border-solid border-opacity-10 border-gray-100 border-t-gray-700 rounded-full w-12 h-12 animate-spin"></div>
        </div>
    );
}
