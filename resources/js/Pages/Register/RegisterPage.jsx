import LoginHeader from "@/Components/LoginHeader";
import PasswordInput from "@/Components/PasswordInput";
import { Head } from "@inertiajs/react";
import "/public/css/register.css";
import SizedBox from "@/Components/SizedBox";
import LoadingSpinner from "@/Components/LoadingSpinner";
import { useState } from "react";

export default function RegisterPage({message}){

    const [loading, setLoading] = useState(false);
    const [errorMessage, setErrorMessage] = useState(message);

    return (
        <>

            <Head title="Loo konto" />
            <LoginHeader pageName="Loo konto" />
            
            <div className="container">
                {errorMessage && <div style={{backgroundColor:"rgb(0,0,0, 0.05)", borderRadius:"16px", padding:"8px", marginBlock:"8px"}}>
                    <p style={{color:"rgb(var(--primary-color))"}}>ⓘ {errorMessage}</p>
                </div>}
                <form method="post" action={route("register")}  className="register-container">
                    <input type="hidden" name="_token" value={window.csrfToken} />
                    <div className="register-row">
                        <input name="name" className="row-input" style={{flex:1, marginLeft:"0"}} type="text" placeholder="Eesnimi" required/>
                        <input name="famname" className="row-input" style={{flex:1, marginRight:"0"}} type="text" placeholder="Perenimi" required/><br />
                    </div>
                    <input name="email" type="email" placeholder="E-posti aadress" required/><br />
                    <input name="class" type="text" placeholder="Klass (nt 140.a)" required/><br />
                    <PasswordInput name="pwd" divstyle={{width:"100%"}} placeholder="Parool" required/><br />
                    <PasswordInput name="pwdrepeat" divstyle={{width:"100%"}} placeholder="Korda parooli" required/>
                    <SizedBox height="16px" />
                    <button name="registration" type="submit">{loading && <LoadingSpinner />} Loo konto</button>
                </form>
            </div>
        </>
    )
}