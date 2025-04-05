import ApplicationLogo from "@/Components/ApplicationLogo";
import SizedBox from "@/Components/SizedBox";
import { Head } from "@inertiajs/react";
import "/public/css/welcome.css";
import "/public/css/404.css";
import { useEffect } from "react";


export default function TeaduskonkurssPage({auth, users, games, points}){

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


    const quotes = [
        {"text":"Mulle meeldib Reaaler, sest seal on mõnus arvutada", "author":"Lisete"},
        {"text":"Reaaler on tore ja mõnus, lõbus ja hariv turvaline keskkond", "author":"Gisela"},
        {"text":"Mulle meeldib Reaaler, sest seal ma saan harjutada enda arvutamist mitmetes moodides", "author":"Mariet"},
        {"text":"Mulle meeldib Reaaler, sest seal võib iga hetk harjutada arvutamist mõnusalt ja turvaliselt", "author":"Mila"},
        {"text":"Mulle meeldib Reaaler, sest seal saab igasuguseid tasemeid valida ja erinevaid numbreid", "author":"Mairon"},
        {"text":"Mulle meeldib Reaaler, sest seal saab valida raskusastmeid lihtsatest raskemateks", "author":"Tambet"},
        {"text":"Mulle meeldib Reaaler, sest seal on palju erinevaid tasemeid", "author":"Kärt"},
        {"text":"Mulle meeldib Reaaler, sest seal on nii palju erinevaid arvutamisviise", "author":"Sander"},
        {"text":"Mulle meeldib Reaaler, sest seal on väga hea kujundus ja väga mõnus õppida, seal on ka palju toredaid tasemeid", "author":"Henrik"},
        {"text":"Mulle meeldib Reaaler, sest sellega on lihtne arvutada ja seda on lihtne kasutada", "author":"Helena"},
        {"text":"Mulle meeldib Reaaler, sest seal on hea harjutada matat", "author":"Emil"},
        {"text":"Mulle meeldib Reaaler, kuna see on õpetlik ja seal saad ise valida kuidas harjutada", "author":"Anette"},
        {"text":"Mulle meeldib Reaaler, sest seal on palju erinevaid arvutamisviise ja saad valida taseme", "author":"Simona"},
        {"text":"Mulle meeldib Reaaler, kuna seal saab rooma numbritega arvutada", "author":"Bruno"},
        {"text":"Mulle meeldib Reaaler, kuna seal saab harjutada kiirust ja täpsust", "author":"Andreas"},
        {"text":"Mulle meeldib Reaaler, sest ma näen, mitmendal kohal olen", "author":"Oliver"},
        {"text":"Mulle meeldib Reaaler, sest see on lõbus", "author":"Karl"},
        {"text":"Mulle meeldib Reaaler, sest see on Reaalkooli enda arvutusprogramm", "author":"Ralf"},
        {"text":"Mulle meeldib Reaaler, sest seal saab valida taseme, mida tahad", "author":"Marta"},
        {"text":"Mulle meeldib Reaaler, sest seal saab valida taseme, mis on sulle paras", "author":"Eliise"},
        {"text":"Mulle meeldib Reaaler, sest seal saab harjutada arvutamist", "author":"Hugo-Martin"},
        {"text":"Mulle meeldib Reaaler, sest seal saab võistelda teistega", "author":"Hugo-Laur"},
        {"text":"Mulle meeldib Reaaler, sest seal saab arvutada erinevat moodi, aga seal võiks olla otsing", "author":"Miranda"},
        {"text":"Mulle meeldib Reaaler, sest seal saab rooma numbritega arvutada", "author":"Rene"},
        {"text":"Mulle meeldib Reaaler, sest seal saab panna peaaegu igaks tasemeks ja numbriks, mis sa tahad", "author":"Randel"},
        {"text":"Mulle meeldib Reaaler, sest selle kujundus on äge ja seal on punktid", "author":"Simon"},
        {"text":"Mulle meeldib Reaaler, sest seal saab vaadata varasemaid tehtud mänge ja saab näha enda vigu", "author":"Keitly"},
    ];

    useEffect(()=>{
        setInterval(() => {
            $(".teadus-quote").fadeOut(1000);

            var coordsY = (window.innerHeight-200) * Math.random() + 50;
            var coordsX = Math.random() > 0.5 ? Math.random()*200 : 200*Math.random() + window.innerWidth-400;

            if(coordsY <= 75 || coordsY >= (window.innerHeight - 100)){
                var coordsX = (window.innerWidth-200) * Math.random();
            }

            var randomQuote = quotes[Math.round(Math.random()*quotes.length) - 1] ?? quotes[0];

            var newQuote = $("<div class='teadus-quote' style='top: "+coordsY+"px; left:"+coordsX+"px'><span>"+randomQuote.text.replaceAll("Reaaler", "<b>Reaaler</b>")+"</span> <span class='author'>- "+randomQuote.author+"</span></div>").hide().fadeIn(1000);

            $(".main-content").append(newQuote);
        }, 5000);
    }, []);

    return (
        <div style={{textAlign:"center"}}>
            <Head title="Tere tulemast!" />

            <div className="main-content" style={{textAlign:'center'}}>
                <ApplicationLogo size={150} />
                <div className="section" style={{display:"flex", alignItems:"center", gap:"8px", width:"min-content", margin:"auto", padding:"16px 24px", borderRadius:"30px"}}>
                    <span>4,7/5 </span><i translate="no" className="material-icons-outlined" style={{color:"gold"}}>star</i>
                </div>
                <h1 className="main-title" style={{color:"var(--text-color)"}} >Reaaler muudab <br />matemaatika <span className="shine" style={{userSelect:"none"}} onClick={()=>setClickEaster2((e)=>e+1)}>säravaks<img alt="Täht" className="sparkle " src="/assets/homepage/sparkle.png" /><img alt="Täht" className="sparkle " src="/assets/homepage/sparkle.png" /><img alt="Täht" className="sparkle " src="/assets/homepage/sparkle.png" /></span></h1>
                <div className="buttons">
                    <button className="onboarding-btn" onClick={()=>window.location.href = "/"}> <i className="material-icons no-anim">waving_hand</i> Proovi ise</button>
                    <br /><br />
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

            <SizedBox height="100px" />

            <div style={{color:"var(--grey-color)"}}>
                <p style={{marginBottom:"8px"}}>Reaaler &copy; 2024-2025</p>
                <p style={{marginTop:"0"}}>Reaaleri arendajad on Hermann Käbi ja Jarl Justus Hellat</p>
            </div>
            <div id="easteregg"></div>
        </div>
    );
}