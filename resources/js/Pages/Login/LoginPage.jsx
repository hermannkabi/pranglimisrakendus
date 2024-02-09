import LoginHeader from "@/Components/LoginHeader";
import { Head } from "@inertiajs/react";
import "/public/css/login.css"
import PasswordInput from "@/Components/PasswordInput";
import SizedBox from "@/Components/SizedBox";
import HorizontalRule from "@/Components/HorizontalRule";
import { useState } from "react";
import LoadingSpinner from "@/Components/LoadingSpinner";

export default function LoginPage({message, errors}){

    const [loading, setLoading] = useState(false);

    function handleSubmit(e){

        if($("#email").val().length <= 0){
            e.preventDefault();
            return;
        }

        // Show loading animation
        setLoading(true);
    }

    const formChildrenStyle = {width:"100%", boxSizing:"border-box", height:"56px", margin:"8px auto"};

    const googleLogo = "https://upload.wikimedia.org/wikipedia/commons/thumb/c/c1/Google_%22G%22_logo.svg/1024px-Google_%22G%22_logo.svg.png";

    return (
        <>
            <Head title="Logi sisse" />
            <LoginHeader pageName={"Logi sisse"} />
            <br />
            <form method="post" action={route("authenticate")} className="login-container">
                {Object.keys(errors).length > 0 && <div style={{backgroundColor:"rgb(var(--section-color),  var(--section-transparency))", borderRadius:"var(--primary-btn-border-radius)", padding:"8px", marginBlock:"8px"}}>
                    <p style={{color:"rgb(var(--primary-color))"}}>ⓘ {errors.email}</p>
                </div>}

                <button style={formChildrenStyle} type="button" secondary="true" onClick={()=>window.location.href =  route('google.redirect') }><img src={googleLogo} /> Google</button>
                {/* <HorizontalRule /> */}
                <HorizontalRule />

                <input type="hidden" name="_token" value={window.csrfToken} />
                <input style={formChildrenStyle} id="email" name="email" type="email" placeholder="E-posti aadress"/>
                <br />
                <PasswordInput name="password" divstyle={{width:"100%"}} style={formChildrenStyle} placeholder="Parool" />
                <SizedBox height="16px" />
                <button type="submit" onClick={handleSubmit} style={formChildrenStyle}>{loading && <LoadingSpinner />} Logi sisse</button>
                <a href={route("register")} alone="true" style={{textAlign:"right", display:"block", fontSize:"18px"}}>Loo konto</a>
            </form>
        </>
    )
}