import ApplicationLogo from "@/Components/ApplicationLogo";
import SizedBox from "@/Components/SizedBox";
import { Head } from "@inertiajs/react";
import "/public/css/welcome.css";
import "/public/css/404.css";
import { useEffect, useState } from "react";
import Chip from "@/Components/2024SummerRedesign/Chip";
import WelcomeTile from "@/Components/2024SummerRedesign/WelcomeTile";


export default function WelcomePage({auth, users, games, points, message}){

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
        
        moveShadow();
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

    function moveShadow(){
        document.querySelectorAll('.section').forEach(section => {
            const shadow = section.querySelector('.shadow');

            if(!shadow) return;

            section.addEventListener('mousemove', (e) => {
            const rect = section.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            shadow.style.left = `${x - shadow.offsetWidth / 2}px`;
            shadow.style.top = `${y - shadow.offsetHeight / 2}px`;
            });

            section.addEventListener('mouseleave', () => {
            shadow.style.left = '-9999px'; // hide shadow when leaving
            });
        });
    }

    const data = {
        "Uus tase peastarvutamises":[
            {icon: "functions", title: "Palju mängutüüpe", message: "Igaüks leiab Reaalerist sobiva mängu, mida harjutada ja seeläbi matemaatikas areneda. Oleme lisanud mänge, mida saavad nautida kõik lapsed alates koolieelikutest gümnasistideni välja."},
            {icon: "local_fire_department", title: "Mänguline õpe", message: "Reaaler muudab õppimise lõbusaks, kasutades erinevaid mängustamismeetodeid, näiteks klassisisesed edetabelid, lõbusad võistlused ja streak-süsteem."},
            {icon: "devices", title: "Mõnus igas seadmes", message: "Reaaleri moodne kasutajaliides on täpselt läbi mõeldud ja testitud, tagamaks sujuva ja ilusa kogemuse nii arvutis kui mobiiliekraanil."},
            {icon: "tune", title: "Täpselt sinu moodi", message: "Reaalerit saab kohandada täpselt selliseks, nagu tahad. Lisaks rakenduse teema vahetamisele saad valida ka endale meelepärase põhivärvi ja palju muud, mida kasutatakse kogu rakenduse vältel."},
        ],
        "Kooli jaoks mõeldud":[
            {icon: "verified",title: "Loodud koostöös õpetajatega", message: "Reaaleri funktsionaalsus ja võimalused on välja töötatud tihedas koostöös Tallinna Reaalkooli matemaatikaõpetajatega, et tagada võimalikult õpisõbralik, aga samas lõbus kogemus kõigile."},
            {icon: "school", title: "Täpselt nagu koolis!", message: "Õpetajakonto võimaldab luua klasse, millega õpilased ühineda saavad. Klassi juures saab igal ajal muuta selle nime ja turvalisuse tagamiseks parooli, vajadusel saab kutsumata külalised klassist hõlpsasti välja visata."},
            {icon: "show_chart", title: "Jälgi klassi arengut", message: "Õpetajana saad klassisiseselt jälgida nii üldiseid andmeid kogu klassi kohta, aga ka õpilaste tulemustest reaalajas loodud edetabelit. Samuti saad kiire ülevaate, kes on täna juba Reaalerit kasutanud."},
            {icon: "person", title: "Iga õpilane loeb!", message: "Õpetaja saab ligipääsu iga õpilase statistikale ja kõikidele mängukordadele. Nii saab ta veenduda, et keegi ei jääks matemaatika põneval retkel teistest maha."},
        ],
        "Tehnoloogiline tipptase":[
            {icon: "lan", title: "Paindlik koodibaas", message: "Reaaler on loodud uute ja vanade veebitehnoloogiate kombinatsioonina. See tagab ühest küljest testitud ja töökorras vundamendi ja teisalt võimaldab kasutada ka uusimaid veebiarenduse võtteid." },
            {icon: "bolt", title: "Valminud välearendust rakendades", message: "Esimesest päevast on Reaalerit arendatud, pidades kinni välearenduse põhimõtetest - pidevalt on kohtutud õpetajatega, viidud läbi teste õpilastega ja kasutatud muuhulgas paarisprogrammeerimist. Selle tõttu on Reaaleri kood ka tavalisest läbimõeldum." },
            {icon: "speed", title: "Ülikiire kasutajaliides", message: "Peastarvutamine käib kiiruse peale, mistõttu on oluline, et rakendus suudaks võimalikult kiiresti liidest uuendada ja uus tehe kuvada. Reaaleris kasutatud ülipopulaarne React raamistik just seda võimaldabki" },
            {icon: "design_services", title: "Käsitööna valminud välimus", message: "Reaaleri kasutajaliideses ei kasutata ühtegi valmiskomponenti, kõik on loodud spetsiaalselt Reaaleri jaoks. Seepärast saavadki kõik elemendid ekraanil ühtseks tervikuks sulanduda, ühtlasi teistest veebilehtedest selgelt eristudes." }
        ]
    };

    

    return (
        <div>
            <Head title="Tere tulemast!" />
            <div style={{marginLeft:"auto"}} className="text-button small" onClick={()=>setIsDarkTheme(e=>!e)}>
                <i className="material-icons-outlined">{!isDarkTheme ? "light_mode" : "brightness_2"}</i>
                <span>{!isDarkTheme ? "Hele" : "Tume"} teema</span>
            </div>
            <div className="welcome-main">
                
                <div className="auth-links">
                    {message && <div className="section" style={{display:"flex", alignItems:"center", gap:"8px", padding:"16px 24px", borderRadius:"30px"}}>
                        <i translate="no" className="material-icons-outlined">info</i> <span>{message}</span>
                    </div>}
                    {!message && <div></div>}
                    {!auth.user && <div style={{display:"flex", flexDirection:"row", justifyContent:"end", alignItems:"center"}}>
                        <a style={{all:"unset", cursor:"pointer"}} href={route("register")}> 
                            <div className="text-button simple">
                                <span>Loo konto</span>
                            </div>
                        </a>
                        
                        <a style={{all:"unset", cursor:"pointer"}} href={route("login")}>
                            <div className="text-button">
                                <i className="material-icons-outlined">account_circle</i>
                                <span>Logi sisse</span>
                            </div>
                        </a>
                    </div>}
                    {auth.user && <div style={{display:"flex", justifyContent:"end", alignItems:"center"}}>
                        <a style={{all:"unset", cursor:"pointer"}} href={route("dashboard")}>
                            <div className="text-button">
                                <img style={{width:"30px", height:"30px", borderRadius:"50px", objectFit:"cover", margin:"0"}} src={auth.user.profile_pic} alt="" />
                                <SizedBox width={2} /><span>{auth.user.eesnimi}</span>
                            </div>
                        </a>
                    </div>}
                </div>

                <div className="section title-screen" style={{position:"relative"}}>
                    <div className="shadow" style={{position:"absolute", height:"0", width:"0", boxShadow:"0 0 500px 100px #D9D9D925"}}></div>
                    <div className="two-column-welcome">
                        <div className="text-container">
                            <h1 className="main-title" style={{color:"var(--text-color)"}} >Reaaler muudab <br />matemaatika <span className="shine" style={{userSelect:"none"}} onClick={()=>setClickEaster2((e)=>e+1)}>säravaks<img alt="Täht" className="sparkle " src="/assets/homepage/sparkle.png" /><img alt="Täht" className="sparkle " src="/assets/homepage/sparkle.png" /><img alt="Täht" className="sparkle " src="/assets/homepage/sparkle.png" /></span></h1>
                            <p>Harjuta peastarvutamist, arene koos klassikaaslastega ja võta mõõtu lõbusatest võistlustest</p>
                            <Chip onClick={()=>window.location.href = "#start"} active={true} classNames={"onboarding-btn"} icon={"waving_hand"} label="Alusta kohe" />
                        </div>

                        <div className="img-container">
                            <ApplicationLogo size={window.innerWidth > 850 ? 300 : 200} style={{pointerEvents:"none"}} />
                        </div>
                    </div>
                </div>
                <div className="stats-section">
                    <div className="stat-tile section" style={{position:"relative"}}>
                        <div className="shadow" style={{position:"absolute", height:"0", width:"0", boxShadow:"0 0 500px 100px #D9D9D925"}}></div>
                        <p className="stat">{users}</p>
                        <p className="type">Kasutajat</p>
                    </div>
                    <div className="stat-tile section" style={{position:"relative"}}>
                        <div className="shadow" style={{position:"absolute", height:"0", width:"0", boxShadow:"0 0 500px 100px #D9D9D925"}}></div>
                        <p className="stat">{games}</p>
                        <p className="type">Mängu mängitud</p>
                    </div>
                    <div className="stat-tile section" style={{position:"relative"}}>
                        <div className="shadow" style={{position:"absolute", height:"0", width:"0", boxShadow:"0 0 500px 100px #D9D9D925"}}></div>
                        <p className="stat">{Intl.NumberFormat('en', { notation: 'compact' }).format(points).replace(".", ",")}</p>
                        <p className="type">Punkti kokku</p>
                    </div>
                </div>

                {Object.keys(data).map(title => <div key={title} style={{marginBottom:"16px", position:"relative"}} className="about-section section">
                    <div className="shadow" style={{position:"absolute", height:"0", width:"0", boxShadow:"0 0 500px 100px #D9D9D925"}}></div>
                    <h2>{title}</h2>
                    <SizedBox height={32} />

                    <div className="about-tiles">
                        {data[title].map(feature => <WelcomeTile key={feature.title} {...feature} /> )}
                    </div>
                </div>)}

                <div style={{position:"relative"}} className="section onboarding-screen" id="start">
                    <div className="shadow" style={{position:"absolute", height:"0", width:"0", boxShadow:"0 0 500px 100px #D9D9D925"}}></div>
                    <div className="two-column-welcome">
                        <div className="text-container">
                            <h1 className="main-title" style={{color:"var(--text-color)"}} >Hakkame pihta?</h1>
                            <p>Hetkel on Reaaler mõeldud vaid Tallinna  Reaalkooli õpilastele ja õpetajatele. Aga ka teised ei pea veel pead  norgu laskma – põhiline arvutamise funktsionaalsus on läbi külaliskonto  saadaval kõigile.</p>
                            
                            {auth.user == null && <div>
                                <Chip icon={"person_add"} active={true} onClick={()=>window.location.href = route("register")} label={"Loo konto"}  />
                                <Chip icon={"supervisor_account"} onClick={()=>window.location.href = route("authenticateGuest")} label={"Sisene külalisena"}  />
                            </div>}
                            {auth.user != null && <div>
                                <Chip icon={"door_open"} active={true} onClick={()=>window.location.href = route("dashboard")} label={"Sisene Reaalerisse"}  />
                            </div>}
                        </div>

                        <div className="img-container">
                            <i style={{fontSize:(window.innerWidth > 850 ? "150px" : "80px")}} className="material-icons-outlined">check_circle</i>
                        </div>
                    </div>
                </div>

                <SizedBox height="100px" />

                <div style={{color:"var(--grey-color)", textAlign:"start"}}>
                    <p style={{marginBottom:"8px"}}>Reaaler &copy; 2024-{(new Date()).getFullYear()}</p>
                    <p style={{marginTop:"0"}}>Reaaleri arendajad on Hermann Käbi ja Jarl Justus Hellat</p>
                </div>
                <div id="easteregg"></div>
            </div>
        </div>
    );
}