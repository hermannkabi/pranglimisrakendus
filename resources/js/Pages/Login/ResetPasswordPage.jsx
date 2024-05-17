import SizedBox from "@/Components/SizedBox";
import { Head } from "@inertiajs/react";
import "/public/css/auth_layout.css";
import LoginHeader from "@/Components/LoginHeader";
import InfoBanner from "@/Components/InfoBanner";
import { useState } from "react";

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
        <SizedBox height={36} />

        <div className="auth-container">
            <LoginHeader pageName={"Unustasin parooli"} />

            <form onSubmit={submitForm} method="post" action={route("password.store")} autoComplete="off" className="login-container auth-main-content">
                {Object.keys(errors).length > 0 && <InfoBanner text={errors[Object.keys(errors)[0]] == "Please wait before retrying." ?  "Palun oota pisut, enne kui uuesti proovid!" : errors[Object.keys(errors)[0]] == "We can't find a user with that email address." ? "Sellise e-posti aadressiga kasutajat ei leitud!" : errors[Object.keys(errors)[0]]} />}
                
                
                <input type="hidden" name="_token" value={window.csrfToken}  />

                <input style={formChildrenStyle} type="email" name="email" placeholder="E-posti aadress" />
                
                <button style={{width:"100%"}} type="submit">Muuda parooli</button>
            </form>

        </div>
    </>;    
}