import React, { useState, useEffect } from "react";
import { Link, useNavigate } from "react-router-dom";
import LayersIcon from "../components/icons/Layers";
import NotificationIcon from "../components/icons/Notification";
import UserIcon from "../components/icons/User";
import BurgerMenu from "../components/icons/BurgerMenu";
import LanguageController from "../components/languageSwitcher/LanguageController";
import LogoutIcon from "../components/icons/Logout";
import { logout } from "../services/services";

export default function Header({ toggleSidebar }) {
    const [isDropdownVisible, setIsDropdownVisible] = useState(false);
    const [user, setUser] = useState(null);
    const navigate = useNavigate();
    const [error, setError] = useState("");
    useEffect(() => {
        const storedUser = localStorage.getItem("user");
        if (storedUser) {
            setUser(JSON.parse(storedUser));
        }
    }, []);

    const handleMouseEnter = () => {
        setIsDropdownVisible(true);
    };

    const handleMouseLeave = () => {
        setIsDropdownVisible(false);
    };

    const handleSignOut = async () => {
        try {
            const data = { token: localStorage.getItem("accessToken") };
            await logout(data);
            localStorage.removeItem("user");
            localStorage.removeItem("accessToken");
            setUser(null);
            navigate("/login");
        } catch (error) {
            setError(error);
        }
    };
    const dropdownItems = [
        { id: 1, title: "My Profile", to: "/profile" },
        { id: 2, component: LanguageController, type: "switcherSlide" },
        { id: 3, title: "Sign Out", action: handleSignOut, icon: LogoutIcon },
    ];

    return (
        <div className="flex justify-between items-center p-[30px]">
            <div className="flex gap-3 items-center">
                <div className="cursor-pointer lg:hidden">
                    <BurgerMenu onClick={toggleSidebar} />
                </div>
                <Link to="/dashboard">
                    <img
                        src="https://preview.keenthemes.com/metronic8/demo39/assets/media/logos/demo39.svg"
                        alt="logo"
                    />
                </Link>
            </div>
            <div className="flex gap-4 items-center">
                <LayersIcon />
                <NotificationIcon />
                {user && (
                    <div
                        className="relative"
                        onMouseEnter={handleMouseEnter}
                        onMouseLeave={handleMouseLeave}
                    >
                        <div className="cursor-pointer">
                            <UserIcon />
                        </div>

                        <div
                            className={`absolute right-0 top-1 mt-2 w-[275px] bg-white border rounded-lg shadow-lg px-4 py-2 transition-opacity duration-300 ease-in-out z-[10000] ${
                                isDropdownVisible
                                    ? "opacity-100"
                                    : "opacity-0 pointer-events-none"
                            }`}
                        >
                            <div className="border-b px-4 py-2 mb-2">
                                <div className="text-gray-800 font-semibold">
                                    {user.name} {user.surname}
                                </div>
                                <div className="text-gray-600 text-sm">
                                    {user.email}
                                </div>
                            </div>

                            {dropdownItems.map((item) =>
                                item.action ? (
                                    <div
                                        key={item.id}
                                        onClick={item.action}
                                        className="text-gray-800 px-4 py-2 hover:bg-gray-100 hover:text-blue-500 rounded-[6px] cursor-pointer flex justify-between items-center"
                                    >
                                        {item.title}
                                        {item.icon &&
                                            React.createElement(item.icon)}
                                    </div>
                                ) : item.component ? (
                                    <div
                                        key={item.id}
                                        className="cursor-pointer"
                                    >
                                        <item.component type={item.type} />
                                    </div>
                                ) : (
                                    <Link
                                        key={item.id}
                                        to={item.to}
                                        className="block text-gray-800 px-4 py-2 hover:bg-gray-100 hover:text-blue-500 rounded-[6px]"
                                    >
                                        {item.title}
                                    </Link>
                                )
                            )}
                        </div>
                    </div>
                )}
            </div>
        </div>
    );
}
