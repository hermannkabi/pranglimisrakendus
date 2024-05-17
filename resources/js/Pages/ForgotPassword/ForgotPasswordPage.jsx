import SizedBox from "@/Components/SizedBox";
import { Head } from "@inertiajs/react";
import "/public/css/auth_layout.css";
import LoginHeader from "@/Components/LoginHeader";
import InfoBanner from "@/Components/InfoBanner";


export default function ForgotPasswordPage({auth, token, errors}){

    const formChildrenStyle = {width:"100%", boxSizing:"border-box", height:"56px", margin:"8px auto"};

    return <>
        <Head title="Uus parool" />
        <SizedBox height={36} />

        <div className="auth-container">
            <LoginHeader pageName={"Uus parool"} />

            <form method="post" action={route("password.update")} autoComplete="off" className="login-container auth-main-content">
                {Object.keys(errors).length > 0 && <InfoBanner text={errors[Object.keys(errors)[0]] == "This password reset token is invalid." ? "Link on aegunud. Palun alusta parooli muutmise protsessi uuesti!" : errors[Object.keys(errors)[0]]} />}
                
                
                <input type="hidden" name="_token" value={window.csrfToken}  />

                <input type="hidden" name="token" value={token} />
                <input type="hidden" name="email" value={(new URLSearchParams(window.location.search).get("email"))}  />

                <input style={formChildrenStyle} autoComplete="new-password" required type="password" name="password" placeholder="Sisesta uus parool" />
                <input style={formChildrenStyle} autoComplete="new-password" required type="password" name="password_confirmation" placeholder="Kinnita uus parool" />
                
                <button style={{width:"100%"}} type="submit">Muuda parooli</button>
            </form>

        </div>

    </>;
}