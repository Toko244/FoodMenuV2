import React, { useState } from "react";
import Header from "./Header";
import SideBar from "../components/sideBar";

export default function Dashboard({ PageContent, ...props }) {
    const [isSidebarVisible, setIsSidebarVisible] = useState(false);

    const toggleSidebar = () => {
        setIsSidebarVisible(!isSidebarVisible);
    };

    return (
        <div>
            <div>
                <Header toggleSidebar={toggleSidebar} />
                <div className="flex justify-between px-[30px] ">
                    <div
                        className={`lg:block ${
                            isSidebarVisible ? "" : "hidden"
                        }`}
                    >
                        <SideBar />
                    </div>

                    <div
                        className={`w-[100%] bg-white p-[30px] rounded-md border border-solid border-[#DBDFE9]`}
                    >
                        <PageContent {...props} />
                    </div>
                </div>
            </div>
        </div>
    );
}
