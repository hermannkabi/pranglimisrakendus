import LoginHeader from "@/Components/LoginHeader";
import { Head } from "@inertiajs/react";
import "/public/css/auth_layout.css";
import PasswordInput from "@/Components/PasswordInput";
import SizedBox from "@/Components/SizedBox";
import HorizontalRule from "@/Components/HorizontalRule";
import { useEffect, useRef, useState } from "react";
import LoadingSpinner from "@/Components/LoadingSpinner";
import InfoBanner from "@/Components/InfoBanner";
import PasswordWidget from "@/Components/2024SummerRedesign/PasswordWidget";
import BigButton from "@/Components/2024SummerRedesign/BigButton";
import Chip from "@/Components/2024SummerRedesign/Chip";

export default function NewLoginPage({message, errors}){

    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(errors);
    const form = useRef(null);

    function handleSubmit(e){
        form.current && form.current.requestSubmit();  

        // Show loading animation
        setLoading(true);
    }

    const formChildrenStyle = {width:"100%", boxSizing:"border-box", height:"56px", margin:"8px auto"};

    const googleLogo = "/assets/google_logo.png";

    const windowWidth = window.innerWidth;

    function requestNewPassword(){
        
        if(!$("#email").val()){
            window.location.href = route("forgotPassword");
            return;
        }

        $.post(route("password.store"), {
            "_token":window.csrfToken,
            "email":$("#email").val(),
        }).done(function (data){
            console.log("Tehtud!");
            setError({"success":"Sinu e-posti aadressile on saadetud kiri parooli muutmiseks"});
        }).fail(function (data){
            console.log(data);
            if(data.responseJSON.message == "Please wait before retrying."){
                setError({"error":"Liiga palju päringuid, palun oota mõni minut!"})
            }else{
                setError(data.responseJSON)
            }
        }); 
    }

    return (
        <>
            <Head title="Logi sisse" />
            <div className="auth-container">
                <LoginHeader pageName={"Tere tulemast Reaalerisse!"} description="Jätkamiseks palun logi sisse" />

                <div className="auth-main-content">
                    <SizedBox height={12} />
                    <form ref={form} style={{width:"min(100%, 600px)", margin:"auto", textAlign:'start'}} method="post" action={route("authenticate")} className="login-container">
                        {Object.keys(error).length > 0 && <InfoBanner text={error[Object.keys(error)[0]]} />}
                        <div onClick={()=>window.location.href = route('google.redirect')} className="section clickable" style={{padding:"16px", display:"flex", justifyContent:"start", alignItems:"center", marginBlock:"0", background:"linear-gradient(-120deg, #4285f430, #34a85330, #fbbc0530, #ea433530)"}}>
                            <div style={{display:"flex", flexDirection:"column", justifyContent:"start", alignItems:"start", marginBlock:"8px"}}>
                                <img style={{height:"24px"}} src={googleLogo} />
                                <p style={{marginTop:"8px", marginBottom:"0"}}>Sisene Google'i kontoga</p>
                            </div>
                        </div>
                        {/* <HorizontalRule /> */}
                        <HorizontalRule />

                        <input type="hidden" name="_token" value={window.csrfToken} />
                        <PasswordWidget inputName={"email"} autoComplete="email" text={"E-posti aadress"} isPassword={false} type={"email"} icon={"email"} />
                        <SizedBox height={12} />
                        <PasswordWidget inputName={"password"} autoComplete="password" text={"Parool"} />
                        <BigButton onClick={handleSubmit} title={"Logi sisse"} subtitle={loading ? <LoadingSpinner /> : "Reaaler"} />
                        <SizedBox height={8} />
                        <div style={{textAlign:"left"}}>
                            <a style={{all:"unset", cursor:"pointer"}} href={route("forgotPassword")}><Chip icon={"lock_reset"} label={"Unustasin salasõna"} /></a>
                            <a style={{all:"unset", cursor:"pointer"}} href={route("register")}><Chip icon={"person_add"} label={"Loo konto"} /></a>
                            <Chip icon={"supervisor_account"} label={"Sisene külalisena"} onClick={()=>window.location.href = route("authenticateGuest")} />
                        </div>
                    </form>
                    <SizedBox height={12} />
                </div>

            </div>
            
        </>
    )
}