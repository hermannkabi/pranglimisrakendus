import ApplicationLogo from "@/Components/ApplicationLogo";
import SizedBox from "@/Components/SizedBox";
import { Head } from "@inertiajs/react";
import "/public/css/welcome.css";
import "/public/css/404.css";
import { useEffect, useState } from "react";


export default function WelcomePage({auth, users, games, points}){

    const [clickEaster2, setClickEaster2] = useState(0);
    const [isDarkTheme, setIsDarkTheme] = useState(localStorage.getItem("app-theme") == "dark");

    function showPriscAnimation(){
        $("#priscilla").css("transform", "translate(0px, -250px)").css("visibility", "visible");

        setTimeout(() => {
            $("#priscilla").css("transform", "translate(-100px, 250px)");
            setTimeout(() => {
                $("#priscilla").css("visibility", "hidden");
            }, 1000);
        }, 2000);
    }

    useEffect(()=>{
        $("#easteregg").append('<img loading="lazy" id="priscilla" style="z-index: 1000; transition: transform 1000ms; visibility: hidden; position:fixed; rotate:20deg; height:300px; bottom: -300px; left: -100px" src="/assets/eastereggs/priskilla.png" />');
    }, []);

    useEffect(()=>{
        if(clickEaster2 == 5){
            setClickEaster2(0);
            showPriscAnimation();
        }
    }, [clickEaster2]);

    useEffect(()=>{
        $("html").attr("data-theme", isDarkTheme ? "dark" : "light");
        localStorage.setItem("app-theme", isDarkTheme ? "dark" : "light");
    }, [isDarkTheme]);

    

    setTimeout(() => {
        setTimeout(() => {

            setTimeout(() => {
                $(".sparkle:nth-of-type(1)").animate({"opacity": "1"}, {"duration":"200"});
            }, 400);
        
            setTimeout(() => {
                $(".sparkle:nth-of-type(3)").animate({"opacity": "1"}, {"duration":"300"});
            }, 500);
        
            setTimeout(() => {
                $(".sparkle:nth-of-type(2)").animate({"opacity": "1"}, {"duration":"400"});
                $("h1 .shine").addClass("extra");

            }, 550);
        }, 100);

    }, 350);

    return (
        <div style={{textAlign:"center"}}>
            <Head title="Tere tulemast!" />
            <div className="home-navbar" style={{margin:"16px 24px", paddingInline:"24px", borderRadius:"60px", background:"rgba(255, 255, 255, 0.14)"}}>
                <div className="title-div">
                    <ApplicationLogo onClick={()=>window.location.href="#"} height={50} />
                    <p className="title" style={{fontWeight:"bold", fontSize:"24px", color:"var(--primary-header-color)", marginLeft:"16px"}}>Reaaler</p>
                </div>

                <div style={{display:"flex", alignItems:'center', gap:"24px"}}>
                    {auth.user == null && <i translate="no" style={{cursor:"pointer"}} onClick={()=>setIsDarkTheme(e=>!e)} className="material-icons">{isDarkTheme ? "light_mode" : "brightness_2"}</i>}
                    
                    <a style={{display:"flex"}} href={route("login")}>{auth.user != null ? <img style={{height:"40px"}} src={auth.user.profile_pic} alt="" className="profile-pic" /> : "Logi sisse"}</a>
                </div>
            </div>
            <SizedBox height={36} />
            {/* <div className="section" style={{display:"flex", alignItems:"center", gap:"8px", width:"min(500px, 80%)", margin:"auto", padding:"16px 24px", borderRadius:"30px"}}>
                <i translate="no" className="material-icons-outlined">info</i> <span>Ilusat alanud kooliaastat! Reaaleri tiim soovib sulle lõbusat õppimist ja kiiret peastarvutamist!</span>
            </div> */}
            <div className="main-content" style={{textAlign:'center'}}>
                <ApplicationLogo size={150} />
                <h1 className="main-title" style={{color:"var(--text-color)"}} >Reaaler muudab <br />matemaatika <span className="shine" style={{userSelect:"none"}} onClick={()=>setClickEaster2((e)=>e+1)}>säravaks<img alt="Täht" className="sparkle " src="/assets/homepage/sparkle.png" /><img alt="Täht" className="sparkle " src="/assets/homepage/sparkle.png" /><img alt="Täht" className="sparkle " src="/assets/homepage/sparkle.png" /></span></h1>
                <div className="buttons">
                    <button className="onboarding-btn" onClick={()=>window.location.href = "#start"}> <i className="material-icons no-anim">waving_hand</i> Alusta kohe</button>
                    <br /><br />
                    <a style={{display:"flex", justifyContent:"center" }} alone="" href="#statistics"><span translate="no" className="no-anim material-icons">keyboard_arrow_down</span></a>
                </div>
            </div>

            <div id="statistics" className="section" style={{width:"max(300px, 60%)", margin:"auto", display:"flex", alignItems:"end", justifyContent:"space-around"}}>

                <div className="stat" style={{margin:"8px"}}>
                    <p style={{color:"var(--primary-header-color)", marginBlock:"0", fontSize:"60px", fontWeight:"bold"}}>{users}</p>
                    <p style={{marginBlock:"0", marginTop:"8px", color:"var(--grey-color)"}}>Kasutajat</p>
                </div>

                <div className="stat" style={{margin:"8px"}}>
                    <p style={{color:"var(--primary-header-color)", marginBlock:"0", fontSize:"60px", fontWeight:"bold"}}>{games}</p>
                    <p style={{marginBlock:"0", marginTop:"8px", color:"var(--grey-color)"}}>Mängitud mängu</p>
                </div>

                <div className="stat" style={{margin:"8px"}}>
                    <p style={{color:"var(--primary-header-color)", marginBlock:"0", fontSize:"60px", fontWeight:"bold"}}>{Intl.NumberFormat('en', { notation: 'compact' }).format(points).replace(".", ",")}+</p>
                    <p style={{marginBlock:"0", marginTop:"8px", color:"var(--grey-color)"}}>Punkti kokku</p>
                </div>
            </div>

            <SizedBox height="72px" />

            <h2 style={{marginInline:"8px"}}>Uus tase peastarvutamises</h2>
            <SizedBox height="36px" />
            
            <div className="features">

                <div className="feature feature-left">
                    <div className="section">
                        <i translate="no" className="material-icons">functions</i>
                    </div>
                    <div>
                        <h3>Palju mängutüüpe</h3>
                        <p>Igaüks leiab Reaalerist sobiva mängu, mida harjutada ja seeläbi matemaatikas areneda. Oleme lisanud mänge, mida saavad nautida kõik lapsed alates koolieelikutest gümnasistideni välja.</p>
                    </div>
                </div>

                <div className="feature feature-right">
                    <div className="section">
                        <i translate="no" className="material-icons-outlined">local_fire_department</i>
                    </div>
                    <div>
                        <h3>Mänguline õpe</h3>
                        <p>Reaaler muudab õppimise lõbusaks, kasutades erinevaid mängustamismeetodeid, näiteks klassisisesed edetabelid ja <i>streak</i>-süsteem.</p>
                    </div>
                </div>

                <div className="feature feature-left">
                    <div className="section">
                        <i translate="no" className="material-icons">groups</i>
                    </div>
                    <div>
                        <h3>Arene koos sõpradega</h3>
                        <p>Üksi õppimine on igav! Seetõttu oleme Reaalerisse lisanud võimaluse liituda klassiga, kus õpilane saab reaalajas ka teiste tulemusi näha. Kui kõik teised harjutavad, tekib motivatsioon ka ise veidi peastarvutamisega tegeleda!</p>
                    </div>
                </div>

                <div className="feature feature-right">
                    <div className="section">
                        <i translate="no" className="material-icons">devices</i>
                    </div>
                    <div>
                        <h3>Mugav igas seadmes</h3>
                        <p>Reaaleri moodne kasutajaliides on täpselt läbi mõeldud ja testitud, tagamaks sujuva ja ilusa kogemuse nii arvutis kui mobiiliekraanil.</p>
                    </div>
                </div>

                <div className="feature feature-left">
                    <div className="section">
                        <i translate="no" className="material-icons">tune</i>
                    </div>
                    <div>
                        <h3>Täpselt sinu moodi</h3>
                        <p>Reaalerit saab kohandada täpselt selliseks, nagu tahad. Lisaks rakenduse teema vahetamisele saad valida ka endale meelepärase põhivärvi, mida kasutatakse kogu rakenduse vältel.</p>
                    </div>
                </div>

            </div>

            <SizedBox height="72px" />

            <h2 style={{marginInline:"8px"}}>Kooli jaoks mõeldud</h2>
            <SizedBox height="36px" />

            <div className="features">

                <div className="feature feature-right">
                    <div className="section">
                        <i translate="no" className="material-icons-outlined">verified</i>
                    </div>
                    <div>
                        <h3>Loodud koostöös õpetajatega</h3>
                        <p>Reaaleri funktsionaalsus ja võimalused on välja töötatud tihedas koostöös Tallinna Reaalkooli matemaatikaõpetajatega, et tagada võimalikult õpisõbralik, aga samas lõbus kogemus kõigile.</p>
                    </div>
                </div>

                <div className="feature feature-left">
                    <div className="section">
                        <i translate="no" className="material-icons-outlined">school</i>
                    </div>
                    <div>
                        <h3>Täpselt nagu koolis!</h3>
                        <p>Õpetajakonto võimaldab luua klasse, millega õpilased ühineda saavad. Klassi juures saab igal ajal muuta selle nime ja turvalisuse tagamiseks parooli, vajadusel saab kutsumata külalised klassist hõlpsasti välja visata.</p>
                    </div>
                </div>

                <div className="feature feature-right">
                    <div className="section">
                        <i translate="no" className="material-icons-outlined">monitoring</i>
                    </div>
                    <div>
                        <h3>Jälgi klassi arengut</h3>
                        <p>Õpetajana saad klassisiseselt jälgida nii üldiseid andmeid kogu klassi kohta, aga ka õpilaste tulemustest reaalajas loodud edetabelit. Samuti saad kiire ülevaate, kes on juba täna Reaalerit kasutanud.</p>
                    </div>
                </div>

                <div className="feature feature-left">
                    <div className="section">
                        <i translate="no" className="material-icons-outlined">person</i>
                    </div>
                    <div>
                        <h3>Iga õpilane loeb!</h3>
                        <p>Õpetaja saab ligipääsu iga õpilase statistikale ja kõikidele mängukordadele. Nii saab ta veenduda, et keegi ei jääks matemaatika põneval retkel teistest maha.</p>
                    </div>
                </div>

                <div className="feature feature-right">
                    <div className="section">
                        <i translate="no" className="material-icons-outlined">lock</i>
                    </div>
                    <div>
                        <h3>Turvalisus tagatud</h3>
                        <p>Koolis õppivate laste ja noorte privaatsus peab Eestis kehtiva õigusruumi kohaselt olema eriti kaitstud. Õpilase Reaaleri konto avalikule vaatele pääsevad ligi vaid tema klassikaaslased ja õpetaja, kõigi teiste eest on andmed peidetud.</p>
                    </div>
                </div>
            </div>
            <SizedBox height="72px" />

            <h2 style={{marginInline:"8px"}}>Tehnoloogiline tipptase</h2>
            <SizedBox height="36px" />

            <div className="features">
                <div className="feature feature-left">
                    <div className="section">
                        <i translate="no" className="material-icons-outlined">lan</i>
                    </div>
                    <div>
                        <h3>Paindlik koodibaas</h3>
                        <p>Reaaler on loodud uute ja vanade veebitehnoloogiate kombinatsioonina. See tagab ühest küljest testitud ja töökorras vundamendi ja teisalt võimaldab kasutada ka uusimaid veebiarenduse võtteid.</p>
                    </div>
                </div>

                <div className="feature feature-right">
                    <div className="section">
                        <i translate="no" className="material-icons-outlined">bolt</i>
                    </div>
                    <div>
                        <h3>Valminud välearendust rakendades</h3>
                        <p>Esimesest päevast on Reaalerit arendatud, pidades kinni välearenduse põhimõtetest - pidevalt on kohtutud õpetajatega, viidud läbi teste õpilastega ja kasutatud muuhulgas paarisprogrammeerimist. Selle tõttu on Reaaleri kood ka tavalisest läbimõeldum.</p>
                    </div>
                </div>

                <div className="feature feature-left">
                    <div className="section">
                        <i translate="no" className="material-icons-outlined">speed</i>
                    </div>
                    <div>
                        <h3>Ülikiire kasutajaliides</h3>
                        <p>Peastarvutamine käib kiiruse peale, mistõttu on oluline, et rakendus suudaks võimalikult kiiresti liidest uuendada ja uus tehe kuvada. Reaaleris kasutatud ülipopulaarne React raamistik just seda võimaldabki</p>
                    </div>
                </div>

                <div className="feature feature-right">
                    <div className="section">
                        <i translate="no"  className="material-icons-outlined">design_services</i>
                    </div>
                    <div>
                        <h3>Käsitööna valminud välimus</h3>
                        <p>Reaaleri kasutajaliideses ei kasutata ühtegi valmiskomponenti, kõik on loodud spetsiaalselt Reaaleri jaoks. Seepärast saavadki kõik elemendid ekraanil ühtseks tervikuks sulanduda, ühtlasi teistest veebilehtedest selgelt eristudes.</p>
                    </div>
                </div>
            </div>

            <SizedBox height="144px" />
            <div id="start" className="section onboarding">
                <br />
                <i translate="no" style={{fontSize:"75px", color:"rgb(var(--primary-color))"}} className="material-icons-outlined">check_circle</i>
                <h2>Hakkame pihta?</h2>
                <p className="onboarding-text">Hetkel on Reaaler mõeldud vaid Tallinna Reaalkooli õpilastele ja õpetajatele. Aga ka teised ei pea veel pead norgu laskma - põhiline arvutamise funktsionaalsus on läbi külaliskonto saadaval kõigile.</p>
                <br />
                {auth.user == null && <div>
                    <button onClick={()=>window.location.href = route("register")}>Loo konto</button>
                    <button onClick={()=>window.location.href = route("authenticateGuest")} secondary="true">Sisene külalisena</button>
                </div>}
                {auth.user != null && <div>
                    <button onClick={()=>window.location.href = route("dashboard")}>Sisene Reaalerisse</button>
                </div>}
                <br /><br />
            </div>

            <SizedBox height="100px" />

            <div style={{color:"var(--grey-color)"}}>
                <p style={{marginBottom:"8px"}}>Reaaler &copy; 2024-2025</p>
                <p style={{marginTop:"0"}}>Reaaleri arendajad on Hermann Käbi ja Jarl Justus Hellat</p>
            </div>
            <div id="easteregg"></div>
        </div>
    );
}