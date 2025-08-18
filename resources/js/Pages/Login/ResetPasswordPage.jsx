import SizedBox from "@/Components/SizedBox";
import { Head } from "@inertiajs/react";
import "/public/css/auth_layout.css";
import LoginHeader from "@/Components/LoginHeader";
import InfoBanner from "@/Components/InfoBanner";
import { useState } from "react";
import PasswordWidget from "@/Components/2024SummerRedesign/PasswordWidget";
import BigButton from "@/Components/2024SummerRedesign/BigButton";

export default function ResetPasswordPage(){
    const formChildrenStyle = {width:"100%", boxSizing:"border-box", height:"56px", margin:"8px auto"};

    const [errors, setErrors] = useState("");

    function submitForm(e){
        e.preventDefault();
        var url = $("form").attr('action'),
        data = $("form").serialize();
        $.ajax({
            url: url,
            type: 'post',
            data: data,
            success: function(){
                setErrors({"message":"Kiri parooli muutmiseks on edukalt saadetud!"});

               // Whatever you want to do after the form is successfully submitted
           },
           error: function (req){
            console.log(JSON.parse(req.responseText));
            setErrors(JSON.parse(req.responseText));
           }
       });
    }


    return <>
        <Head title="Unustasin parooli" />

        <div className="auth-container">
            <LoginHeader pageName={"Unustasid parooli?"} description="Uue parooli loomiseks sisesta e-posti aadress ja järgi juhiseid" />

            <div className="auth-main-content">
               <SizedBox height={12} />
                <form style={{width:"min(100%, 600px)", margin:"auto", textAlign:'start'}} method="post" action={route("password.store")} autoComplete="off" className="login-container">
                    {Object.keys(errors).length > 0 && <InfoBanner text={errors[Object.keys(errors)[0]] == "Please wait before retrying." ?  "Palun oota pisut, enne kui uuesti proovid!" : errors[Object.keys(errors)[0]] == "We can't find a user with that email address." ? "Sellise e-posti aadressiga kasutajat ei leitud!" : errors[Object.keys(errors)[0]]} />}
                    
                    <input type="hidden" name="_token" value={window.csrfToken}  />
                    <PasswordWidget inputName={"email"} autoComplete="email" text={"E-posti aadress"} isPassword={false} type={"email"} icon={"email"} />
                    
                    <BigButton onClick={submitForm} title={"Muuda parooli"} subtitle={"Saada parooli lähtestamise kiri"} />
                </form>
                <SizedBox height={12} />
            </div>
        </div>
    </>;    
}