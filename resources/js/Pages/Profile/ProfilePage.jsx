import Navbar from "@/Components/Navbar";
import { Head } from "@inertiajs/react";
import "/public/css/profile.css";
import "/public/css/game_end.css";
import SizedBox from "@/Components/SizedBox";
import RadioChoice from "@/Components/RadioChoice";
import { useEffect, useState } from "react";
import NumberChoice from "@/Components/NumberChoice";
import ColorPicker from "@/Components/ColorPicker";
import { pickFile } from 'js-pick-file';
import ProfileAction from "@/Components/ProfileAction";


export default function ProfilePage({auth, className}){

    const [lightTheme, setLightTheme] = useState(window.localStorage.getItem("app-theme") != "dark");
    const [primaryColor, setPrimaryColor] = useState(window.localStorage.getItem("app-primary-color") == null || window.localStorage.getItem("app-primary-color").length <= 0 ? "default" : window.localStorage.getItem("app-primary-color"));
    const [timerVisible, setTimerVisible] = useState(window.localStorage.getItem("timer-visibility") != "hidden");
    const [countGameMode, setCountGameMode] = useState(window.localStorage.getItem("game-mode") != "speed");
    const [pointsAnimation, setPointsAnimation] = useState(window.localStorage.getItem("points-animation") != "off");
    const [flipKeyboard, setFlipKeyboard] = useState(window.localStorage.getItem("flip-keyboard") == "true");


    const [imageUploadErrors, setImageUploadErrors] = useState(null);

    

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

    function logout(){
        window.location.href = route("logout");
    }

    function changePrimaryColor(color){
        setPrimaryColor(color);
    }

    async function uploadFile() {
        var options = {
            accept: '.jpg, .jpeg, .png, .gif',
            multiple: false,
        }
    
        const filePromise = pickFile(options);

        const file = (await filePromise)[0];

        var data = new FormData();

        data.append("image", file);
        data.append("_token", window.csrfToken);

        $.ajax({
            url:route("changeProfilePicture"),
            type:"post",
            data: data,
            contentType: false,
            processData:false,
        }).done(function (data){
            console.log("Tehtud!");
            setImageUploadErrors(null);
            window.location.reload();
        }).fail(function (data){
            console.log(data);
            setImageUploadErrors(data.responseJSON);
        });

    }

    return (
        <>
            <Head title="Minu konto" />
            <Navbar title="Profiil & seaded"  user={auth.user} />

            <SizedBox height={36} />
            <h2>Minu konto</h2>
            
            {false && <section style={{backgroundColor:"rgb(var(--section-color),  var(--section-transparency))", borderRadius:"var(--primary-btn-border-radius)", padding:"8px", marginBlock:"8px"}}>
                <p style={{color:"rgb(var(--primary-color))"}}><span translate="no">ⓘ</span> Tagasiside küsitlus asub <a href="https://docs.google.com/forms/d/e/1FAIpQLSc9gNf1wVw7GemStNCxaXL7jXjlghtnlti9u3aNjfqS6pnYog/viewform?vc=0&c=0&w=1&flr=0">siin</a></p>
            </section>}

            {imageUploadErrors != null && <section style={{backgroundColor:"rgb(var(--section-color),  var(--section-transparency))", borderRadius:"var(--primary-btn-border-radius)", padding:"8px", marginBlock:"8px"}}>
                <p style={{color:"rgb(var(--primary-color))"}}><span translate="no">ⓘ</span>{imageUploadErrors[Object.keys(imageUploadErrors)[0]]}</p>
            </section>}
            
            <section>
                <div className="" style={{display:'flex', flexWrap:"wrap", justifyContent:"center", alignItems:"center", gap:"16px"}}>
                   {/* Selle osa saaks lihtsasti teha eraldi komponendiks (sisse annad kasutaja) */}
                    <div style={{overflow:"hidden"}}>
                        <SizedBox height={32} />
                        <div  className="profile-widget" style={{display:"flex", flexDirection:"row", gap:"16px", alignItems:"center"}}>
                            <div style={{position:"relative", display:"inline", height:"fit-content"}} onClick={auth.user.role == "guest" ? null : uploadFile}>
                                <img style={{height:"64px", userSelect:"none"}} className="profile-pic" src={auth.user.profile_pic} alt={auth.user.eesnimi + " " + auth.user.perenimi} />
                                {auth.user.role != "guest" && <span style={{cursor:"pointer", position:"absolute", bottom:"0", right:"0", backgroundColor:"rgb(var(--primary-color), 0.9)", color:"white", borderRadius:"50%", padding:"4px", fontSize:"12px"}} className="material-icons">edit</span>}
                            </div>
                            <div className="name-email" style={{textAlign:"start"}}>
                                <div style={{}}><h1 translate="no" style={{marginTop:"4px", marginBottom:"0", textTransform:"capitalize", display:"inline", verticalAlign:"middle"}}>{auth.user == null ? window.localStorage.getItem("first-name") ?? "Mari" : auth.user.eesnimi ?? window.localStorage.getItem("first-name") ?? "Mari"} {auth.user == null ? window.localStorage.getItem("last-name") ?? "Maasikas" : auth.user.perenimi ?? window.localStorage.getItem("last-name") ?? "Maasikas"} </h1> {auth.user.role != "student" && <span style={{backgroundColor:"rgb(var(--primary-color))", borderRadius:"4px", color:"white", fontSize:"12px", padding:"2px 4px", fontWeight:"normal", marginTop:"6px"}}>{auth.user == null ? "Õpilane" : auth.user.role == "teacher" ? "Õpetaja" : auth.user.role == "guest" ? "Külaline" : auth.user.role == null ? "Tavakonto" : auth.user.role}</span>}</div>
                                {auth.user.email.length > 0 && <p translate="no" style={{marginBottom:"0", color:"grey", fontSize:"20px", marginTop:"0"}}>{auth.user == null ? "mari.maasikas@real.edu.ee" : auth.user.email}</p>}
                            </div>
                        </div>
                    </div>
                    
                    <div style={{overflow:"hidden", display:"grid", gridTemplateColumns:"repeat(2, 1fr)", marginTop:"36px"}} className="actions-container">
                        <ProfileAction icon="public" label="Kuva avalik profiil" link={"/profile/"+auth.user.id} />
                        {auth.user.role == "teacher" && <ProfileAction icon="school" label="Loo uus klass" link={route("newClass")} />}
                        {auth.user.role != "teacher" &&<ProfileAction icon="school" label={auth.user.klass == null ? "Liitu klassiga" : className} smallLabel={auth.user.klass == null ? null : "Muuda"} link={route("classJoin")} />}
                        <ProfileAction disabled={true} icon="lock" label="Muuda parooli" />
                        <ProfileAction onClick={logout} icon="logout" label={auth.user.role == "guest" ? "Välju külalisvaatest" : "Logi välja"} red={true} />
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