import LoginHeader from "@/Components/LoginHeader";
import PasswordInput from "@/Components/PasswordInput";
import { Head } from "@inertiajs/react";
import "/public/css/register.css";
import "/public/css/auth_layout.css";
import LoadingSpinner from "@/Components/LoadingSpinner";
import { useState } from "react";
import InfoBanner from "@/Components/InfoBanner";
import BigGameButton from "@/Components/BigGameButton";

export default function NewRegisterPage({message, errors}){

    const pages = ["account-type-container", "name-email-container", "password-container"]

    const [loading, setLoading] = useState(false);
    const [errorMessages, setErrorMessages] = useState(message);

    const [currentPage, setCurrentPage] = useState(0);
    
    const urlParams = new URLSearchParams(window.location.search);
    const email = urlParams.get('email');
    const name = urlParams.get('name');

    const [isTeacher, setIsTeacher] = useState(false);

    if(email){
        $("#email").val(email);
    }

    if(name){
        $("#name").val(name.substring(0, name.lastIndexOf(" ")));
        $("#famname").val(name.substring(name.lastIndexOf(" ")));
    }

    

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

        $("#email").val(email);
    }


    // $(".register-container").submit(function (e){
    //     setLoading(true);
    //     if(!force){
    //         e.preventDefault();
    //         trySendData(e);
    //     }
    // });

    $("#name, #famname").on("input", generateEmail);

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


    return (
        <>

            <Head title="Loo konto" />

            <div className="auth-container">

                <LoginHeader pageName="Loo konto" />
                
                <div className="auth-main-content">
                    {errorMessages && <InfoBanner text={errorMessages}/>}
                    <div className="register-step account-type-container">
                        <p>Olen...</p>
                        <button onClick={()=>setTeacherStatus(false)}>Õpilane</button>
                        <button onClick={()=>setTeacherStatus(true)} secondary="">Õpetaja</button>
                    </div>

                    <div className="register-step name-email-container" hidden>
                        <form className="register-container" onSubmit={navigateToNext}>
                            <div className="register-row">
                                <input id="name" name="eesnimi" className="row-input" style={{flex:1, marginLeft:"0", textTransform:"capitalize"}} type="text" placeholder="Eesnimi" autoCapitalize="words" required/>
                                <input id="famname" name="perenimi" className="row-input" style={{flex:1, marginRight:"0", textTransform:"capitalize"}} type="text" placeholder="Perenimi" autoCapitalize="words" required/><br />
                            </div>
                            <input id="email" name="email" type="email" placeholder="E-posti aadress" required/><br />
                            {!isTeacher && <><input minLength="4" maxLength="5" pattern="\d{2,3}\.[^\d]" title="Mis lennus sa õpid? (nt 140.a)" name="klass" type="text" placeholder="Lend (nt 140.a)" required/><br /> </>}                       
                            <button type="submit" >Edasi</button>
                            <a style={{display:"block", textAlign:"left"}} alone="" onClick={()=>navigateToNext(null, true)}>Tagasi</a>
                        </form>
                    </div>

                    <div className="register-step password-container" hidden>
                        <form className="register-container" onSubmit={submitForm}>
                            <PasswordInput name="password" divstyle={{width:"100%"}} placeholder="Parool" required/><br />
                            <PasswordInput name="password_confirmation" divstyle={{width:"100%"}} placeholder="Korda parooli" required/>
                            <button type="submit">Loo konto</button>
                            <a style={{display:"block", textAlign:"left"}} alone="" onClick={()=>navigateToNext(null, true)}>Tagasi</a>
                        </form>
                    </div>
                </div>

            </div>

        </>
    )
}