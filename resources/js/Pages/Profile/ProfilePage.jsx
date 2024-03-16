import Navbar from "@/Components/Navbar";
import { Head } from "@inertiajs/react";
import "/public/css/profile.css";
import "/public/css/game_end.css";

import SizedBox from "@/Components/SizedBox";
import NumberInput from "@/Components/NumberInput";
import RadioChoice from "@/Components/RadioChoice";
import { useEffect, useState } from "react";
import NumberChoice from "@/Components/NumberChoice";
import ColorPicker from "@/Components/ColorPicker";


export default function ProfilePage({auth, className}){

    const [lightTheme, setLightTheme] = useState(window.localStorage.getItem("app-theme") != "dark");
    const [primaryColor, setPrimaryColor] = useState(window.localStorage.getItem("app-primary-color") == null || window.localStorage.getItem("app-primary-color").length <= 0 ? "default" : window.localStorage.getItem("app-primary-color"));
    const [timerVisible, setTimerVisible] = useState(window.localStorage.getItem("timer-visibility") != "hidden");
    const [countGameMode, setCountGameMode] = useState(window.localStorage.getItem("game-mode") != "speed");
    const [pointsAnimation, setPointsAnimation] = useState(window.localStorage.getItem("points-animation") != "off");
    const [flipKeyboard, setFlipKeyboard] = useState(window.localStorage.getItem("flip-keyboard") == "true");


    useEffect(()=>{
        saveSettings();
    }, [primaryColor, lightTheme, timerVisible, countGameMode, pointsAnimation, flipKeyboard]);

    function saveSettings(){
        var isLightTheme = window.localStorage.getItem("app-theme") != "dark";
        var currentPrimaryColor = window.localStorage.getItem("app-primary-color") ?? "default";
        var isTimerVisible = window.localStorage.getItem("timer-visibility") != "hidden";
        var isCountGameMode = window.localStorage.getItem("game-mode") != "speed";
        var isPointsAnimation = window.localStorage.getItem("points-animation") != "off";
        var isFlipKeyboard = window.localStorage.getItem("flip-keyboard") == "true";

        var defaultTime = $("#default-time-val").val();


        // If any of the settings was changed
        // Used to show/not show the done icon
        var changedSomething = false;


        // App theme
        if(lightTheme != isLightTheme){
            changedSomething = true;
            window.localStorage.setItem("app-theme", lightTheme ? "light" : "dark");
            document.documentElement.setAttribute('data-theme', lightTheme ? "light" : "dark");
        }

        // App primary color
        if(primaryColor != currentPrimaryColor){
            changedSomething = true;
            window.localStorage.setItem("app-primary-color", primaryColor);
            if(primaryColor != "default"){
                document.documentElement.style.setProperty('--primary-color', primaryColor);
            }else{
                document.documentElement.style.setProperty('--primary-color', lightTheme ? "53, 81, 80" : "70, 107, 106");

            }
        }

        // Timer visibility
        if(isTimerVisible != timerVisible){
            changedSomething = true;
            window.localStorage.setItem("timer-visibility", timerVisible ? "visible" : "hidden");
        }

        // Points animation
        if(isPointsAnimation != pointsAnimation){
            changedSomething = true;
            window.localStorage.setItem("points-animation", pointsAnimation ? "on" : "off");
        }

        // Game mode
        if(isCountGameMode != countGameMode){
            changedSomething = true;
            window.localStorage.setItem("game-mode", countGameMode ? "count" : "speed");
        }

        // Flipped keyboard
        if(isFlipKeyboard != flipKeyboard){
            changedSomething = true;
            window.localStorage.setItem("flip-keyboard", flipKeyboard ? "true" : "false");
        }



        // Default time
        if(defaultTime != null){
            if(defaultTime != window.localStorage.getItem("default-time")){
                changedSomething = true;
            }
            window.localStorage.setItem("default-time", defaultTime.length == 0 ? "0" : defaultTime);
        }

        if(changedSomething){
            $("#save-btn .save-icon").show();
            $("#save-btn .text").text("");

            setTimeout(() => {
                $("#save-btn .save-icon").hide();   
                $("#save-btn .text").text("Salvesta seaded");
            }, 1500);

            console.log("Saved");
            //window.location.href = route("dashboard");
            $.post(route("settingsAdd"), {
                "_token":window.csrfToken,
                'settings':'{"color":"'+(primaryColor == "default" ? "default" : document.documentElement.style.getPropertyValue('--primary-color'))+'", "theme":"'+document.documentElement.getAttribute("data-theme")+'", "timer-visibility":"'+(timerVisible ? "visible" : "hidden")+'", "points-animation":"'+(pointsAnimation ? "on" : "off")+'", "default-time":"'+(defaultTime.length == 0 ? "0" : defaultTime)+'", "flip-keyboard":"'+(flipKeyboard ? "true" : "false")+'"}',
            }).done(function (data){
                console.log("Tehtud!");
            }).fail(function (data){
                console.log(data);
            });
        }
    }

    const profileTypeStyle = {
        display:"flex",
        flexDirection:"row",
        justifyContent:"space-between",
        alignItems:"baseline",
        marginBlock:"32px",
    };

    function logout(){
        window.location.href = route("logout");
    }

    function changePrimaryColor(color){
        setPrimaryColor(color);
    }

    return (
        <>
            <Head title="Minu konto" />
            <Navbar title="Profiil & seaded"  user={auth.user} />

            <SizedBox height={36} />
            <h2>Minu konto</h2>
            {/* <section>
                <div className="header-container">
                    <h3 className="section-header">Profiil</h3>
                </div>
                <div className="" style={{display:'flex', flexWrap:"wrap"}}>
                    <div className="mobile-block" style={{display:"flex", justifyContent:"stretch", width:"100%", gap:"8px"}}>
                        <input id="fname" type="text" placeholder="Eesnimi" defaultValue={auth.user.eesnimi ?? window.localStorage.getItem("first-name") ?? "Mari"} disabled />
                        <input id="lname" type="text" placeholder="Perenimi" defaultValue={auth.user.perenimi ?? window.localStorage.getItem("last-name") ?? "Maasikas"} disabled />
                    </div>
                    <input type="text" placeholder="E-posti aadress" value={auth.user.email} disabled/>
                    <div style={{display:"flex", justifyContent:"stretch", width:"100%", gap:"8px"}}>
                        <input style={{flex:"5"}} type="text" placeholder="Kooli nimi" value="Tallinna Reaalkool" disabled/>
                        <input type="text" placeholder="Klass" value={auth.user.klass} style={{minWidth:'100px'}} disabled/>
                    </div>
                    <div className="mobile-block" style={{display:"flex", justifyContent:"stretch", width:"100%", gap:"8px"}}>
                        <button style={{flex:"1", width:'100%', marginLeft:"0px"}}>Muuda parooli</button>
                        <button darkred="true" style={{flex:"1", width:'100%', marginRight:"8px"}} secondary="true" onClick={()=>window.location.href=route("logout")}>Logi välja</button>
                    </div>
                    <a alone="" style={{color:"rgb(var(--darkred-color))", marginInline:"auto", marginBlock:"16px"}}>Kustuta konto</a>
                </div>
            </section> */}
            {/* A new design for the profile page */}
            
            {false && <section style={{backgroundColor:"rgb(var(--section-color),  var(--section-transparency))", borderRadius:"var(--primary-btn-border-radius)", padding:"8px", marginBlock:"8px"}}>
                <p style={{color:"rgb(var(--primary-color))"}}><span translate="no">ⓘ</span> Tagasiside küsitlus asub <a href="https://docs.google.com/forms/d/e/1FAIpQLSc9gNf1wVw7GemStNCxaXL7jXjlghtnlti9u3aNjfqS6pnYog/viewform?vc=0&c=0&w=1&flr=0">siin</a></p>
            </section>}

            <section>
                
                <div className="" style={{display:'flex', flexWrap:"wrap", justifyContent:"center"}}>
                    <div className="big-container" style={{marginTop:"8px"}}>
                        <SizedBox height={16} />

                        <img style={{height:"64px", userSelect:"none"}} className="profile-pic" src={auth.user.profile_pic} alt={auth.user.eesnimi + " " + auth.user.perenimi} />
                        <SizedBox height={8} />
                        <h1 style={{marginTop:"4px", marginBottom:"0", textTransform:"capitalize"}}>{auth.user == null ? window.localStorage.getItem("first-name") ?? "Mari" : auth.user.eesnimi ?? window.localStorage.getItem("first-name") ?? "Mari"} {auth.user == null ? window.localStorage.getItem("last-name") ?? "Maasikas" : auth.user.perenimi ?? window.localStorage.getItem("last-name") ?? "Maasikas"}</h1>
                        <p style={{color:"grey", fontSize:"20px", marginTop:"0"}}>{auth.user == null ? "mari.maasikas@real.edu.ee" : auth.user.email}</p>
                    </div>                    

                    <div className="stat-container" style={{width:"90%"}}>
                       {auth.user.role != "student" && <div style={profileTypeStyle}>
                            <p style={{color:'gray', marginBlock: "0"}}>KONTOTÜÜP</p>
                            <h3 style={{marginBlock:0}}>{auth.user == null ? "Õpilane" : auth.user.role == "teacher" ? "Õpetaja" : auth.user.role == "guest" ? "Külaline" : auth.user.role == null ? "Tavakonto" : auth.user.role}</h3>
                        </div>}

                        <div style={profileTypeStyle}>
                            <p style={{color:'gray', marginBlock: "0"}}>KOOL</p>
                            <h3 style={{marginBlock:0}}>Tallinna Reaalkool</h3>
                        </div>
                        
                        <div style={profileTypeStyle}>
                            <p style={{color:'gray', marginBlock: "0"}}>KLASS</p>
                            {className == null && <button style={{marginRight:0}} onClick={()=>window.location.href = route("classJoin")}>Ühine klassiga</button> }
                            {className != null && <div style={{display:"flex", gap:"8px", flexDirection:"row", alignItems:"center"}} ><h3 style={{marginBlock:0, color: className == null ? "grey" : "inherit"}}>{auth.user == null ? "140.a" : auth.user.klass == "õpetaja" ? "Õpetajakonto" : className ?? "Pole lisatud"}</h3> <a alone="" href={route("classJoin")}><span className="material-icons no-anim" style={{cursor:"pointer", color:"rgb(var(--text-color))"}} translate="no">edit</span></a> </div> }
                        </div>
                    </div>
                    <div className="mobile-block" style={{display:"grid", gridTemplate:"1fr", width:"90%", gap:"8px", margin:'auto'}}>
                        <a alone="" style={{margin:'auto'}}>Muuda parooli</a>
                        <SizedBox height={8} />
                        <a translate="no" style={{display:"inline-flex", margin:'auto'}} alone="" red="" onClick={logout}>Logi välja <SizedBox width={8} /> <span className="material-icons">logout</span></a>
                        <SizedBox height={4} />
                    </div>
                </div>
            </section>
            <section>
                <div className="header-container">
                    <h3 className="section-header">Seaded</h3>
                </div>
                <div className="padding-container settings-padding" style={{display:'flex', flexWrap:"wrap"}}>
                    <div style={{width:"100%"}}>
                        <p style={{color:"grey"}}>Rakenduse teema</p>
                        <div className="app-theme-group" style={{width:'100%', display:"grid", gridTemplateColumns:"repeat(2, 1fr)", gap:"16px", marginBlock:"8px"}}>                       
                            <RadioChoice icon="light_mode" text="Hele teema" selected={lightTheme} onClick={()=>setLightTheme(true)} />
                            <RadioChoice icon="dark_mode" text="Tume teema" selected={!lightTheme} onClick={()=>setLightTheme(false)} />
                        </div>
                    </div>

                    <div style={{width:"100%"}}>
                        <p style={{color:"grey"}}>Peamine värv</p>
                        <div className="color-picker-container">
                            <ColorPicker color={"default"} currentColor={primaryColor} onChange={(color)=>changePrimaryColor(color)} />
                            <ColorPicker color={"64, 103, 158"} currentColor={primaryColor} onChange={(color)=>changePrimaryColor(color)} />
                            <ColorPicker color={"231, 136, 149"} currentColor={primaryColor} onChange={(color)=>changePrimaryColor(color)} />
                            <ColorPicker color={"142, 122, 181"} currentColor={primaryColor} onChange={(color)=>changePrimaryColor(color)} />
                            <ColorPicker color={"255, 164, 71"} currentColor={primaryColor} onChange={(color)=>changePrimaryColor(color)} />

                            <ColorPicker color={"208, 72, 72"} currentColor={primaryColor} onChange={(color)=>changePrimaryColor(color)} />
                            <ColorPicker color={"186, 186, 106"} currentColor={primaryColor} onChange={(color)=>changePrimaryColor(color)} />
                            <ColorPicker color={"173, 139, 115"} currentColor={primaryColor} onChange={(color)=>changePrimaryColor(color)} />
                            <ColorPicker color={"48, 227, 202"} currentColor={primaryColor} onChange={(color)=>changePrimaryColor(color)} />
                            <ColorPicker color={"164, 190, 123"} currentColor={primaryColor} onChange={(color)=>changePrimaryColor(color)} />

                        </div>

                    </div>

                    <div style={{width:"100%"}}>
                        <p style={{color:"grey"}}>Taimeri nähtavus</p>
                        <div className="app-theme-group" style={{width:'100%', display:"grid", gridTemplateColumns:"repeat(2, 1fr)", gap:"16px", marginBlock:"8px"}}>
                            <RadioChoice icon="timer" text="Näita" selected={timerVisible} onClick={()=>setTimerVisible(true)} />
                            <RadioChoice icon="timer_off" text="Peida" selected={!timerVisible} onClick={()=>setTimerVisible(false)} />
                        </div>
                    </div>

                    <div style={{width:"100%"}}>
                        <p style={{color:"grey"}}>Punktianimatsioon</p>
                        <div className="app-theme-group" style={{width:'100%', display:"grid", gridTemplateColumns:"repeat(2, 1fr)", gap:"16px", marginBlock:"8px"}}>
                            <RadioChoice icon="visibility" text="Näita" selected={pointsAnimation} onClick={()=>setPointsAnimation(true)} />
                            <RadioChoice icon="visibility_off" text="Peida" selected={!pointsAnimation} onClick={()=>setPointsAnimation(false)} />
                        </div>
                    </div>

                    <div style={{width:"100%"}}>
                        <p style={{color:"grey"}}>Klaviatuur</p>
                        <div className="app-theme-group" style={{width:'100%', display:"grid", gridTemplateColumns:"repeat(2, 1fr)", gap:"16px", marginBlock:"8px"}}>
                            <RadioChoice icon="dialpad" text="Tavaline" selected={!flipKeyboard} onClick={()=>setFlipKeyboard(false)} />
                            <RadioChoice icon="dialpad" text="Ümberpööratud" selected={flipKeyboard} onClick={()=>setFlipKeyboard(true)} />
                        </div>
                    </div>

                    {/* <div style={{width:"100%"}}>
                        <p style={{color:"grey"}}>Vaikimisi mängurežiim</p>
                        <div className="app-theme-group" style={{width:'100%', display:"grid", gridTemplateColumns:"repeat(2, 1fr)", gap:"16px", marginBlock:"8px"}}>
                            <RadioChoice icon="tag" text="Tehete arvu põhine" selected={countGameMode} onClick={()=>setCountGameMode(true)} />
                            <RadioChoice icon="speed" text="Kiiruspõhine" selected={!countGameMode} onClick={()=>setCountGameMode(false)} />
                        </div>
                    </div> */}

                    <div style={{width:"100%"}}>
                        <p style={{color:"grey"}}>Vaikimisi aeg</p>
                        <NumberChoice onChange={saveSettings} id="default-time-val" defaultValue={window.localStorage.getItem("default-time") == null ? null : parseFloat(window.localStorage.getItem("default-time"))} />
                    </div>
                    
                    {/* <button style={{flex:'1', marginInline:"4px", marginTop:"32px"}} onClick={saveSettings} id="save-btn"><span style={{display:"none"}} className="material-icons save-icon">done</span><span className="text">Salvesta seaded</span></button> */}
                </div>
            </section>
            <SizedBox height={48} />
            <a alone="" href={route("changelog")}><i className="material-icons no-anim">update</i>&nbsp; Uuenduste ajalugu</a>
            <SizedBox height={32} />

        </>
    );
}