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
    
    

    var force = false;

    function trySendData(e){

        // Safe to assume non-null values here since the browser should take care of validating that thanks to 'required'
        // Similarly, the email address is probably correctly formatted (though, should check organisation)

        var email = $("input[name='email']").val();
        var pwd = $("input[name='pwd']").val();
        var pwd2 = $("input[name='pwdrepeat']").val();

        var numberMatch = /\d/;

        var capitalMatch = /[A-ZÄÕÖÜ]/;


        if(!(email.trim().endsWith("real.edu.ee"))){

            setErrorMessage("Palun kasuta konto loomiseks Reaalkooli e-posti aadressi");

            setLoading(false);
            return;
        }

        if(pwd.length < 8 || !(numberMatch.test(pwd)) || !(capitalMatch.test(pwd))){
            setErrorMessage("Parool peab olema vähemalt 8 tähemärki ja sisaldama vähemalt ühte numbrit ja suurtähte");
            setLoading(false);
            return;
        }

        if(pwd != pwd2){
            setErrorMessage("Paroolid ei kattu");
            setLoading(false);
            return;
        }

        
        
        force = true;
        setLoading(false);
        $(".register-container").submit();
    }


    $(".register-container").submit(function (e){
        setLoading(true);
        if(!force){
            e.preventDefault();
            trySendData(e);
        }
    });

    return (
        <>

            <Head title="Loo konto" />
            <LoginHeader pageName="Loo konto" />
            
            <div className="container">
                {errorMessage && <div style={{backgroundColor:"rgb(0,0,0, 0.05)", borderRadius:"16px", padding:"8px", marginBlock:"8px"}}>
                    <p style={{color:"rgb(var(--primary-color))"}}>ⓘ {errorMessage}</p>
                </div>}
                <form method="post" action={route("registerPost")}  className="register-container">
                    <input type="hidden" name="_token" value={window.csrfToken} />
                    <div className="register-row">
                        <input name="name" className="row-input" style={{flex:1, marginLeft:"0"}} type="text" placeholder="Eesnimi" required/>
                        <input name="famname" className="row-input" style={{flex:1, marginRight:"0"}} type="text" placeholder="Perenimi" required/><br />
                    </div>
                    <input name="email" type="email" placeholder="E-posti aadress" required/><br />
                    <input pattern="\d{2,3}\.[^\d]" title="Klass lennu numbriga (nt 140.a)" name="class" type="text" placeholder="Klass (nt 140.a)" required/><br />
                    <PasswordInput name="pwd" divstyle={{width:"100%"}} placeholder="Parool" required/><br />
                    <PasswordInput name="pwdrepeat" divstyle={{width:"100%"}} placeholder="Korda parooli" required/>
                    <SizedBox height="16px" />
                    <button name="registration" type="submit">{loading && <LoadingSpinner />} Loo konto</button>
                </form>
            </div>
        </>
    )
}