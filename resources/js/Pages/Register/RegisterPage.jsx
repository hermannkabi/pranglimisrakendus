import LoginHeader from "@/Components/LoginHeader";
import PasswordInput from "@/Components/PasswordInput";
import { Head } from "@inertiajs/react";
import "/public/css/register.css";
import SizedBox from "@/Components/SizedBox";
import LoadingSpinner from "@/Components/LoadingSpinner";
import { useState } from "react";

export default function RegisterPage(){

    const [loading, setLoading] = useState(false);


    function handleRegister(e){
        // Prevent reload
        e.preventDefault();

        // Show loading animation
        setLoading(true);

        //After 3 seconds, remove loading effect

        setTimeout(() => {
            setLoading(false);
        }, 3000);
    }

    return (
        <>
            <Head title="Loo konto" />
            <LoginHeader pageName="Loo konto" />
            <form  className="register-container">
                <div style={{display:"flex", justifyContent:"space-between", width:"100%"}}>
                    <input className="row-input" style={{flex:1, marginLeft:"0"}} type="text" placeholder="Eesnimi" />
                    <input className="row-input" style={{flex:1, marginRight:"0"}} type="text" placeholder="Perenimi" /><br />
                </div>
                <input type="text" placeholder="E-posti aadress" /><br />
                <input type="text" placeholder="Kooli pääsukood" /><br />
                <PasswordInput divstyle={{width:"100%"}} placeholder="Parool" /><br />
                <PasswordInput divstyle={{width:"100%"}} placeholder="Korda parooli" />
                <SizedBox height="16px" />
                <button onClick={handleRegister} type="submit">{loading && <LoadingSpinner />} Loo konto</button>
            </form>


        </>
    )
}