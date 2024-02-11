import ApplicationLogo from "@/Components/ApplicationLogo";
import LoginHeader from "@/Components/LoginHeader";
import SizedBox from "@/Components/SizedBox";
import { Head } from "@inertiajs/react";

export default function WelcomePage({name, message}){
    return (
        <div style={{height:"100%", width:"100%"}}>
            <div style={{position:"absolute", "top":"45%", "left":"50%", transform:"translate(-50%, -50%)"}}>
                <Head title="Avaleht" />
                <LoginHeader topMargin="16px" pageName="Pranglimine" description="Tere tulemast!"/>
                <br />
                <div>
                    <a href="/dashboard">Alusta</a>
                </div>
                <br /><br />
                <p style={{fontSize:"0.75rem"}}>10.02.2024.32</p>
            </div>
        </div>
    )
}