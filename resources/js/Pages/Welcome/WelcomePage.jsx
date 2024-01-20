import ApplicationLogo from "@/Components/ApplicationLogo";
import LoginHeader from "@/Components/LoginHeader";
import SizedBox from "@/Components/SizedBox";
import { Head } from "@inertiajs/react";

export default function WelcomePage({name, message}){
    return (
        <div style={{height:"100%", width:"100%"}}>
            <div style={{position:"absolute", "top":"45%", "left":"50%", transform:"translate(-50%, -50%)"}}>
                <Head title="Avaleht" />
                <LoginHeader topMargin="16px" pageName="Pranglimisrakendus" description="Installatsioon on õnnestunud!!!"/>
                <br />
                <div>
                    <a href="/dashboard">Dashboard</a>
                    &nbsp;
                    <a href="/register">Register</a>
                </div>
                <br /><br />
                <p style={{fontSize:"0.75rem"}}>20.01.2024.1</p>
            </div>
        </div>
    )
}