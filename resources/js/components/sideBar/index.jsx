import React from "react";
import HomeIcon from "../icons/Home";
import ArrowDownIcon from "../icons/ArrowDown";
import LayersIcon from "../icons/Layers";
import Box1Icon from "../icons/Box1";
import GiftIcon from "../icons/Gift";
import Box2Icon from "../icons/Box2";
import PlusIcon from "../icons/Plus";

export default function SideBar() {
    const menuItems = [
        { id: 1, title: "Dashboards", icon: HomeIcon },
        { id: 2, title: "Pages", icon: GiftIcon },
        { id: 3, title: "Apps", icon: LayersIcon },
        { id: 4, title: "Utilities", icon: Box1Icon },
        { id: 5, title: "Help", icon: Box2Icon },
    ];
    const labels = [
        { id: 1, title: "Google Ads" },
        { id: 2, title: "AirStroke App" },
        { id: 3, title: "Internal Tasks" },
        { id: 4, title: "Utilities" },
        { id: 5, title: "Fitness App" },
        { id: 6, title: "Oppo CRM" },
        { id: 7, title: "Finance Dispatch" },
    ];
    return (
        <div className="app-sidebar-wrapper pr-7 w-72 lg:static lg:left-auto lg:bg-transparent absolute left-5 bg-[#f0f0f0] md:left-5 md:bg-[#f0f0f0] animate-slideInFromLeft lg:animate-none h-full z-[10000]">
            <div className="first-sidebar-menu mb-5">
                {menuItems.map((item) => (
                    <div
                        key={item.id}
                        className="flex justify-between cursor-pointer items-center py-[.65rem] px-[1rem]"
                    >
                        <div className="flex gap-2 ">
                            {item.icon && React.createElement(item.icon)}
                            <span className="text-gray-800 text-md font-semibold">
                                {item.title}
                            </span>
                        </div>
                        <div className="icon">
                            <ArrowDownIcon />
                        </div>
                    </div>
                ))}
            </div>
            <div className="second-sidebar-menu">
                <div className="flex justify-between items-center py-[.65rem]">
                    <span className="uppercase text-gray-700 font-semibold text-sm">
                        labels
                    </span>
                    <div>
                        <PlusIcon />
                    </div>
                </div>
                <div className="border-b-2 mb-[.5rem]"></div>
                {labels.map((item) => (
                    <div
                        key={item.id}
                        className="flex justify-between cursor-pointer items-center py-[.65rem] px-[0.700rem]"
                    >
                        <div className="flex gap-2 items-center">
                            <div className="rounded-[100%] bg-blue-600 h-[10px] w-[10px]"></div>
                            <span className="text-gray-700 text-sm font-semibold">
                                {item.title}
                            </span>
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
}
