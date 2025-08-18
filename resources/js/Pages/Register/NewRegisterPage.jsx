import LoginHeader from "@/Components/LoginHeader";
import PasswordInput from "@/Components/PasswordInput";
import { Head } from "@inertiajs/react";
import "/public/css/register.css";
import "/public/css/auth_layout.css";
import { useState } from "react";
import InfoBanner from "@/Components/InfoBanner";
import HorizontalRule from "@/Components/HorizontalRule";
import BigButton from "@/Components/2024SummerRedesign/BigButton";
import Chip from "@/Components/2024SummerRedesign/Chip";
import PasswordWidget from "@/Components/2024SummerRedesign/PasswordWidget";
import SizedBox from "@/Components/SizedBox";

export default function NewRegisterPage({message, errors}){

    const pages = ["account-type-container", "name-email-container", "password-container"]

    const [loading, setLoading] = useState(false);
    const [errorMessages, setErrorMessages] = useState(message);

    const [currentPage, setCurrentPage] = useState(0);
    
    const [isTeacher, setIsTeacher] = useState(false);

    var force = false;

    function trySendData(){

        // Safe to assume non-null values here since the browser should take care of validating that thanks to 'required'
        // Similarly, the email address is probably correctly formatted (though, should check organisation)

        var email = $("input[name='email']").val();
        var pwd = $("input[name='password']").val();
        var pwd2 = $("input[name='password_confirmation']").val();

        var numberMatch = /\d/;

        var capitalMatch = /[A-ZÄÕÖÜ]/;


        if(!(email.trim().endsWith("real.edu.ee"))){

            setErrorMessages("Palun kasuta konto loomiseks Reaalkooli e-posti aadressi");
            setLoading(false);

            navigateToNext(null, false, 1);
            return;
        }

        if(pwd.length < 8 || !(numberMatch.test(pwd)) || !(capitalMatch.test(pwd))){
            setErrorMessages("Parool peab olema vähemalt 8 tähemärki ja sisaldama vähemalt ühte numbrit ja suurtähte");
            setLoading(false);

            navigateToNext(null, false, 2);

            return;
        }

        if(pwd != pwd2){
            setErrorMessages("Paroolid ei kattu");
            setLoading(false);

            navigateToNext(null, false, 2);

            return;
        }

        
        
        force = true;
        setLoading(false);
        $.post(route("store"), {
            "_token":window.csrfToken,
            "eesnimi":$("input[name='eesnimi']").val(),
            "perenimi":$("input[name='perenimi']").val(),
            "email":email,
            // "klass": isTeacher ? "õpetaja" : $("input[name='klass']").val(),
            "password":pwd,
            "password_confirmation":pwd2,
        }).done(function (data){
            window.location.href = route("dashboard");
        }).fail(function (data){
            console.log(data);
            setErrorMessages(Object.values(data.responseJSON.errors));
        });
    }


    function nameToEmail(name){
        return name.toLowerCase().replaceAll("-", "").replaceAll(" ", "").replaceAll("õ", "o").replaceAll("ä", "a").replaceAll("ö", "o").replaceAll("ü", "u").replaceAll("š", "s").replaceAll("ž", "z");
    }

    function generateEmail(){
        const host = "@real.edu.ee";

        var email = "";

        if($("#name").val().length > 0 && $("#famname").val().length > 0){
            var emailName = nameToEmail($("#name").val());

            var emailFamName = nameToEmail($("#famname").val());

            email = emailName + "." + emailFamName + host;
        }

        return email;
    }

    function navigateToNext(e, back=false, force=null){
        if(e != null){
            e.preventDefault();
        }

        if(force != null){
            showPage(pages[force]);
            setCurrentPage(force);
            return;
        }

        var maxPage = pages.length - 1;

        if(currentPage + 1 <= maxPage || back){
            setCurrentPage(currentPage + (back ? -1 : 1));
            showPage(pages[currentPage + (back ? -1 : 1)]);
        }

    }

    function showPage(classToShow){
        $(".register-step").hide();
        $("."+classToShow).fadeIn(200);
    }

    function submitForm(e){
        e.preventDefault();
        trySendData();
    }

    function setTeacherStatus(status){
        setIsTeacher(status);

        navigateToNext(null);
    }

    const googleLogo = "/assets/google_logo.png";


    return (
        <>
            <Head title="Loo konto" />

            <div className="auth-container">

                <LoginHeader pageName="Tere tulemast Reaalerisse!" description={"Reaaleri kasutamiseks palun loo konto"} />
                
                <div className="auth-main-content">
                    <div style={{width:"min(100%, 600px)", margin:"auto", textAlign:'start'}}>
                        <SizedBox height={12} />
                        
                        {errorMessages && <InfoBanner text={errorMessages}/>}
                        <div className="register-step account-type-container">
                            <div onClick={()=>window.location.href = route('google.redirect')} className="section clickable" style={{padding:"16px", display:"flex", justifyContent:"start", alignItems:"center", marginBlock:"0", background:"linear-gradient(-120deg, #4285f430, #34a85330, #fbbc0530, #ea433530)"}}>
                                <div style={{display:"flex", flexDirection:"column", justifyContent:"start", alignItems:"start", marginBlock:"8px"}}>
                                    <img style={{height:"24px"}} src={googleLogo} />
                                    <p style={{marginTop:"8px", marginBottom:"0"}}>Jätka Google'i kontoga</p>
                                </div>
                            </div>
                            <HorizontalRule />
                            
                            <BigButton onClick={()=>setTeacherStatus(false)} title={"Õpilase konto"} subtitle={"Loo konto"} />
                            <BigButton secondary={true} onClick={()=>setTeacherStatus(true)} title={"Õpetaja konto"} subtitle={"Loo konto"} />
                            <Chip icon={"supervisor_account"} label={"Sisene külalisena"} onClick={()=>window.location.href = route("authenticateGuest")} />                        
                        </div>

                        <div className="register-step name-email-container" hidden>
                            <form className="register-container" onSubmit={navigateToNext}>
                                <PasswordWidget required={true} inputName={"eesnimi"} text={"Eesnimi"} isPassword={false} type={"name"} icon={"person"} />
                                <SizedBox height={12} />
                                <PasswordWidget required={true} inputName={"perenimi"} text={"Perekonnanimi"} isPassword={false} type={"name"} icon={"signature"} />
                                <SizedBox height={12} />
                                <PasswordWidget required={true} inputName={"email"} text={"E-posti aadress"} isPassword={false} type={"email"} icon={"email"} />
                                <BigButton onClick={navigateToNext} title={"Edasi"} subtitle={"Loo konto"} />
                                <Chip icon={"arrow_back"} label={"Tagasi"} onClick={()=>navigateToNext(null, true)} />                        
                            </form>
                        </div>

                        <div className="register-step password-container" hidden>
                            <form className="register-container">
                                <PasswordWidget inputName={"password"} text={"Parool"} />
                                <SizedBox height={12} />
                                <PasswordWidget inputName={"password_confirmation"} text={"Korda parooli"} />

                                <BigButton onClick={submitForm} title={"Lõpeta konto loomine"} subtitle={"Loo konto"} />
                                <Chip icon={"arrow_back"} label={"Tagasi"} onClick={()=>navigateToNext(null, true)} />                        

                            </form>
                        </div>
                    </div>
                    <SizedBox height={12} /> 
                </div>

            </div>

        </>
    )
}