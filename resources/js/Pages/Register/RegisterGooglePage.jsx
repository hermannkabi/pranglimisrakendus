import LoginHeader from "@/Components/LoginHeader";
import SizedBox from "@/Components/SizedBox";
import { Head } from "@inertiajs/react";
import { useState } from "react";
import "/public/css/register.css";
import "/public/css/auth_layout.css";
import InfoBanner from "@/Components/InfoBanner";



export default function RegisterGooglePage({errors}){
    const [loading, setLoading] = useState(false);

    var urlParams = new URLSearchParams(window.location.search);

    const name = urlParams.get("name");
    const email = urlParams.get("email");
    const googleId = urlParams.get("googleId");


    $("#name").val(name.substring(0, name.lastIndexOf(" ")));
    $("#famname").val(name.substring(name.lastIndexOf(" ")+1));

    console.log(errors);

    return <>
        <Head title="Jätka Google'ga" />

        <div className="auth-container">
            <LoginHeader pageName="Jätka Google'ga" googleLogo={true} />
            <div className="auth-main-content">
                {Object.values(errors).map((e)=><InfoBanner key={e} text={e} />)}
                <form method="post" action={route("storeGoogle")}  className="register-container">
                    <input type="hidden" name="_token" value={window.csrfToken} />
                    <input style={{display:'none'}} name="googleid" value={googleId} />

                    <div className="register-row">
                        <input id="name" name="eesnimi" className="row-input" style={{flex:1, marginLeft:"0"}} type="text" placeholder="Eesnimi" required/>
                        <input id="famname" name="perenimi" className="row-input" style={{flex:1, marginRight:"0"}} type="text" placeholder="Perenimi" required/><br />
                    </div>
                    <input className="field-disabled" onChange={null} defaultValue={email} id="email" name="email" type="email" placeholder="E-posti aadress" required readOnly={true}/><br />
                    <input minLength="4" maxLength="5" pattern="\d{2,3}\.[^\d]" title="Mis lennus sa õpid? (nt 140.a)" name="klass" type="text" placeholder="Lend (nt 140.a)" required/><br />
                    <SizedBox height="16px" />
                    <button name="registration" type="submit">{loading && <LoadingSpinner />} Loo konto</button>
                </form>
            </div>
        </div>
        
    </>;
}