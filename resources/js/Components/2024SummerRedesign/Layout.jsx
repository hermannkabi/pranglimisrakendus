import SizedBox from "../SizedBox";
import Sidebar from "./Sidebar";
import { Head } from "@inertiajs/react";

export default function Layout({children, title}){
    return <>
        
        <Head title={title} />
        <div className="layout">
            <Sidebar title={title} />
            <div className="layout-main-content">
                <SizedBox height={16} />
                {children}
                <SizedBox height={16} />
            </div>
        </div>
    </>;
}