import LoginHeader from "@/Components/LoginHeader";
import { Head } from "@inertiajs/react";
import "/public/css/login.css";
import "/public/css/auth_layout.css";

import PasswordInput from "@/Components/PasswordInput";
import SizedBox from "@/Components/SizedBox";
import HorizontalRule from "@/Components/HorizontalRule";
import { useState } from "react";
import LoadingSpinner from "@/Components/LoadingSpinner";
import CheckboxTile from "@/Components/CheckboxTile";
import InfoBanner from "@/Components/InfoBanner";

export default function NewLoginPage({message, errors}){

    const [loading, setLoading] = useState(false);
    const [error, setError] = useState(errors);


    function handleSubmit(e){

        if($("#email").val().length <= 0){
            e.preventDefault();
            return;
        }

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
                <LoginHeader pageName={"Logi sisse"} />

                <form method="post" action={route("authenticate")} className="login-container auth-main-content">
                    {Object.keys(error).length > 0 && <InfoBanner text={error[Object.keys(error)[0]]} />}

                    <button style={formChildrenStyle} type="button" secondary="true" onClick={()=>window.location.href =  route('google.redirect') }><img src={googleLogo} /> Google</button>
                    {/* <HorizontalRule /> */}
                    <HorizontalRule />

                    <input type="hidden" name="_token" value={window.csrfToken} />
                    <input style={formChildrenStyle} id="email" name="email" type="email" placeholder="E-posti aadress" required />
                    <br />
                    <PasswordInput name="password" divstyle={{width:"100%"}} style={formChildrenStyle} placeholder="Parool" required />
                    <a onClick={requestNewPassword} style={{display:"block", textAlign:"right"}} alone="">Unustasin salasõna</a>
                    <button type="submit" onClick={handleSubmit} style={formChildrenStyle}>{loading && <LoadingSpinner />} Logi sisse</button>                    
                    
                    <SizedBox height={8} />
                    
                    <div className="section" style={{paddingInline:"16px"}}>
                        <p style={{fontWeight:"bold", fontSize: "18px", marginBottom:"8px"}}>Oled siin uus?</p>
                        <SizedBox height={8} />
                        <a href={route("register")} alone="true" style={{fontSize:"18px"}}>Loo konto</a>
                        <SizedBox height={4} />
                        <a href={route("authenticateGuest")} alone="true" style={{fontSize:"18px"}}>Sisene külalisena</a>
                        <SizedBox height={8} />
                    </div>

                </form>

            </div>
            
        </>
    )
}