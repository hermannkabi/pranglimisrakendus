import Navbar from "@/Components/Navbar";
import { Head } from "@inertiajs/react";
import "/public/css/profile.css";
import SizedBox from "@/Components/SizedBox";
import NumberInput from "@/Components/NumberInput";


export default function ProfilePage(){

    function saveSettings(){
        var appTheme = $("#app-theme").val();
        var timerVisibility = $("#timer-visibility").val();
        var defaultTime = $("#default-time").val();

        // If any of the settings was changed
        // Used to show/not show the done icon
        var changedSomething = false;


        // App theme
        if(appTheme != null){
            if(appTheme != window.localStorage.getItem("app-theme")){
                changedSomething = true;
            }
            window.localStorage.setItem("app-theme", appTheme);
            document.documentElement.setAttribute('data-theme', appTheme);
        }


        // Timer visibility
        if(timerVisibility != null){
            if(timerVisibility != window.localStorage.getItem("timer-visibility")){
                changedSomething = true;
            }
            window.localStorage.setItem("timer-visibility", timerVisibility);
        }

        // Default time
        if(defaultTime != null){
            if(defaultTime != window.localStorage.getItem("default-time")){
                changedSomething = true;
            }
            window.localStorage.setItem("default-time", defaultTime);
        }

        if(changedSomething){
            $("#save-btn .save-icon").show();
            $("#save-btn .text").text("");

            setTimeout(() => {
                $("#save-btn .save-icon").hide();   
                $("#save-btn .text").text("Salvesta seaded");
            }, 1500);
        }
    }

    return (
        <>
            <Head title="Profiil" />
            <Navbar title="Profiil & seaded" />

            <SizedBox height={36} />
            <h2>Minu konto</h2>

            <section>
                <div className="header-container">
                    <h3 className="section-header">Seaded</h3>
                </div>
                <div className="padding-container settings-padding" style={{display:'flex', flexWrap:"wrap"}}>
                    <select style={{width:"100%"}} name="app-theme" id="app-theme" defaultValue={window.localStorage.getItem("app-theme") ?? "default"}>
                        <option value="default" selected disabled id="default">Rakenduse teema</option>
                        <option value="light">Hele teema</option>
                        <option value="dark">Tume teema</option>
                    </select>
                    <select style={{width:"100%"}} name="timer-visibility" id="timer-visibility" defaultValue={window.localStorage.getItem("timer-visibility") ?? "default"}> 
                        <option value="default" selected disabled id="default">Taimeri nähtavus</option>
                        <option value="visible">Taimer nähtav</option>
                        <option value="hidden">Taimer peidetud</option>
                    </select>
                    <select style={{width:"100%"}} name="game-type" id="game-type">
                        <option selected disabled id="default">Vaikimisi mängurežiim</option>
                        <option value="speed">Kiiruspõhine</option>
                        <option value="count">Tehete arvu põhine</option>
                    </select>
                    <NumberInput placeholder="Vaikimisi aeg (min)" id="default-time" defaultValue={window.localStorage.getItem("default-time") ?? ""} />
                    <SizedBox height="32px" />
                    <button style={{flex:'1', marginInline:"4px"}} onClick={saveSettings} id="save-btn"><span style={{display:"none"}} className="material-icons save-icon">done</span><span className="text">Salvesta seaded</span></button>
                </div>
            </section>
            <section>
                <div className="header-container">
                    <h3 className="section-header">Profiil</h3>
                </div>
                <div className="" style={{display:'flex', flexWrap:"wrap"}}>
                    <div className="mobile-block" style={{display:"flex", justifyContent:"stretch", width:"100%", gap:"8px"}}>
                        <input type="text" placeholder="Eesnimi" value="Mari" disabled/>
                        <input type="text" placeholder="Perenimi" value="Maasikas" disabled/>
                    </div>
                    <input type="text" placeholder="E-posti aadress" value="mari.maasikas@koolikool.edu.ee" disabled/>
                    <div style={{display:"flex", justifyContent:"stretch", width:"100%", gap:"8px"}}>
                        <input style={{flex:"5"}} type="text" placeholder="Kooli nimi" value="Kooli Põhikool" disabled/>
                        <input type="text" placeholder="Klass" value="140.a" style={{minWidth:'100px'}} disabled/>
                    </div>
                    <div className="mobile-block" style={{display:"flex", justifyContent:"stretch", width:"100%", gap:"8px"}}>
                        <button style={{flex:"1", width:'100%', marginLeft:"0px"}}>Muuda parooli</button>
                        <button darkred="true" style={{flex:"1", width:'100%', marginRight:"8px"}} secondary="true" onClick={()=>window.location.href=route("login")}>Logi välja</button>
                    </div>
                    <a alone="" style={{color:"rgb(var(--darkred-color))", marginInline:"auto", marginBlock:"16px"}}>Kustuta konto</a>
                </div>
            </section>
            

            <div className="container">
                <div className="profile">
                    
                </div>

                <div className="settings">
                    
                </div>
            </div>
        </>
    );
}