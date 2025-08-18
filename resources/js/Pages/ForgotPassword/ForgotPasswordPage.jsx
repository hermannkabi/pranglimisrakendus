import SizedBox from "@/Components/SizedBox";
import { Head } from "@inertiajs/react";
import "/public/css/auth_layout.css";
import LoginHeader from "@/Components/LoginHeader";
import InfoBanner from "@/Components/InfoBanner";
import PasswordWidget from "@/Components/2024SummerRedesign/PasswordWidget";
import BigButton from "@/Components/2024SummerRedesign/BigButton";


export default function ForgotPasswordPage({auth, token, errors}){

    const formChildrenStyle = {width:"100%", boxSizing:"border-box", height:"56px", margin:"8px auto"};

    function submitForm(e){
        e.preventDefault();
        var url = $("form").attr('action'),
        data = $("form").serialize();
        $.ajax({
            url: url,
            type: 'post',
            data: data,
            success: function(){
               // Whatever you want to do after the form is successfully submitted
               window.location.href = route("login");
           },
           error: function (req){
            console.log(JSON.parse(req.responseText));
            setErrors(JSON.parse(req.responseText));
           }
       });
    }

    return <>
        <Head title="Uus parool" />

        <div className="auth-container">
            <LoginHeader pageName={"Loo uus parool"} description="Loo endale uus parool, millega Reaalerisse sisse logida" />

            <div className="auth-main-content">
                <SizedBox height={12} />
                <form style={{width:"min(100%, 600px)", margin:"auto", textAlign:'start'}} method="post" action={route("password.update")} autoComplete="off" className="login-container">
                    {Object.keys(errors).length > 0 && <InfoBanner text={errors[Object.keys(errors)[0]] == "This password reset token is invalid." ? "Link on aegunud. Palun alusta parooli muutmise protsessi uuesti!" : errors[Object.keys(errors)[0]]} />}
                    
                    
                    <input type="hidden" name="_token" value={window.csrfToken}  />

                    <input type="hidden" name="token" value={token} />
                    <input type="hidden" name="email" value={(new URLSearchParams(window.location.search).get("email"))}  />

                    <PasswordWidget inputName={"password"} text={"Sisesta uus parool"} />
                    <SizedBox height={12} />
                    <PasswordWidget inputName={"password_confirmation"} text={"Kinnita uus parool"} />

                    <BigButton onClick={submitForm} title={"Muuda parooli"} subtitle={"Kinnita parooli muutmine"} />
                </form>
                <SizedBox height={12} />
            </div>
            

        </div>

    </>;
}