import LoginHeader from "@/Components/LoginHeader";
import { Head } from "@inertiajs/react";
import "/public/css/login.css"
import PasswordInput from "@/Components/PasswordInput";
import SizedBox from "@/Components/SizedBox";
import HorizontalRule from "@/Components/HorizontalRule";
import { useState } from "react";
import LoadingSpinner from "@/Components/LoadingSpinner";

export default function LoginPage({message}){

    const [loading, setLoading] = useState(false);

    function handleSubmit(e){
        // Show loading animation
        setLoading(true);
    }

    const formChildrenStyle = {width:"100%", boxSizing:"border-box", height:"56px", margin:"8px auto"};

    const googleLogo = "https://upload.wikimedia.org/wikipedia/commons/thumb/c/c1/Google_%22G%22_logo.svg/1024px-Google_%22G%22_logo.svg.png";
    const msLogo = "https://img.freepik.com/free-icon/microsoft_318-566086.jpg?q=10&h=200";


    return (
        <>
            <Head title="Logi sisse" />
            <LoginHeader pageName={"Logi sisse"} />
            <br />
            <form method="post" action={route("login")} className="login-container">
                <input type="hidden" name="_token" value={window.csrfToken} />
                {message}
                <input style={formChildrenStyle} name="email" type="email" placeholder="E-posti aadress"/>
                <br />
                <PasswordInput name="pwd" divstyle={{width:"100%"}} style={formChildrenStyle} placeholder="Parool" />
                <SizedBox height="16px" />
                <button type="submit" onClick={handleSubmit} style={formChildrenStyle}>{loading && <LoadingSpinner />} Logi sisse</button>
                <a href={route("register")} alone="true" style={{textAlign:"right", display:"block", fontSize:"18px"}}>Loo konto</a>
                <HorizontalRule />
                <div className="sso-btns">
                    <button type="button" secondary="true" onClick={()=>window.location.href =  route('google.redirect') }><img src={googleLogo} /> Google</button> <button type="button" style={{marginRight:"0"}} secondary="true"><img src={msLogo} /> Microsoft</button>
                </div>
            </form>
        </>
    )
}