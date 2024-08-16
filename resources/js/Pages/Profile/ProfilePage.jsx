import "/public/css/game_end.css";
import "/public/css/dashboard.css";
import SizedBox from "@/Components/SizedBox";
import { useEffect, useState } from "react";
import ColorPicker from "@/Components/ColorPicker";
import { pickFile } from 'js-pick-file';
import Layout from "@/Components/2024SummerRedesign/Layout";
import TwoRowTextButton from "@/Components/2024SummerRedesign/TwoRowTextButton";
import InfoBanner from "@/Components/InfoBanner";
import TimeSelector from "@/Components/2024SummerRedesign/TimeSelector";

export default function ProfilePage({auth, className}){

    const [lightTheme, setLightTheme] = useState(window.localStorage.getItem("app-theme") != "dark");
    const [primaryColor, setPrimaryColor] = useState(window.localStorage.getItem("app-primary-color") == null || window.localStorage.getItem("app-primary-color").length <= 0 ? "default" : window.localStorage.getItem("app-primary-color"));
    const [timerVisible, setTimerVisible] = useState(window.localStorage.getItem("timer-visibility") != "hidden");
    const [pointsAnimation, setPointsAnimation] = useState(window.localStorage.getItem("points-animation") != "off");
    const [flipKeyboard, setFlipKeyboard] = useState(window.localStorage.getItem("flip-keyboard") == "true");
    const [defaultTime, setDefaultTime] = useState(window.localStorage.getItem("default-time") ?? "0.5");


    const [imageUploadErrors, setImageUploadErrors] = useState(null);

    

    useEffect(()=>{
        saveSettings();
    }, [primaryColor, lightTheme, timerVisible, pointsAnimation, flipKeyboard, defaultTime]);

    function saveSettings(){
        var isLightTheme = window.localStorage.getItem("app-theme") != "dark";
        var currentPrimaryColor = window.localStorage.getItem("app-primary-color") ?? "default";
        var isTimerVisible = window.localStorage.getItem("timer-visibility") != "hidden";
        var isPointsAnimation = window.localStorage.getItem("points-animation") != "off";
        var isFlipKeyboard = window.localStorage.getItem("flip-keyboard") == "true";



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

        // Flipped keyboard
        if(isFlipKeyboard != flipKeyboard){
            changedSomething = true;
            window.localStorage.setItem("flip-keyboard", flipKeyboard ? "true" : "false");
        }



        // Default time
        if(defaultTime != null){
            if(defaultTime.toString() != window.localStorage.getItem("default-time")){
                changedSomething = true;
            }
            window.localStorage.setItem("default-time", defaultTime.toString());
        }

        if(changedSomething){
            $("#save-btn .save-icon").show();
            $("#save-btn .text").text("");

            setTimeout(() => {
                $("#save-btn .save-icon").hide();   
                $("#save-btn .text").text("Salvesta seaded");
            }, 1500);

            //window.location.href = route("dashboard");
            $.post(route("settingsAdd"), {
                "_token":window.csrfToken,
                'settings':'{"color":"'+(primaryColor == "default" ? "default" : document.documentElement.style.getPropertyValue('--primary-color'))+'", "theme":"'+document.documentElement.getAttribute("data-theme")+'", "timer-visibility":"'+(timerVisible ? "visible" : "hidden")+'", "points-animation":"'+(pointsAnimation ? "on" : "off")+'", "default-time":"'+(defaultTime)+'", "flip-keyboard":"'+(flipKeyboard ? "true" : "false")+'"}',
            }).done(function (data){
                console.log("Tehtud!");
            }).fail(function (data){
                console.log(data);
                setImageUploadErrors({"error":"Midagi on valesti. Sinu eelistusi ei pruugita praegu kontole salvestada. Selles seadmes töötavad siiski sinu valitud seaded!"})
                window.scrollTo({ top: 0, behavior: 'smooth' });
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
            setImageUploadErrors(null);
            window.location.reload();
        }).fail(function (data){
            console.log(data);
            setImageUploadErrors(data.responseJSON);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

    }

    function sendPwdResetLink(){
        $.post(route("password.store"), {
            "_token":window.csrfToken,
            "email":auth.user.email,
        }).done(function (data){
            console.log("Tehtud!");
            setImageUploadErrors({"success":"Sinu e-posti aadressile on saadetud kiri parooli muutmiseks"});
        }).fail(function (data){
            console.log(data);
            if(data.responseJSON.message == "Please wait before retrying."){
                setImageUploadErrors({"error":"Liiga palju päringuid, palun oota mõni minut!"})
            }else{
                setImageUploadErrors(data.responseJSON)
            }
        });
    }


    function verifyEmail(){
        $.post(route("verification.resend"), {
            "_token":window.csrfToken,
        }).done(function (data){
            console.log("Tehtud!");
            setImageUploadErrors({"success":"Link e-posti aadressi kinnitamiseks on saadetud meilile!"});
        }).fail(function (data){
            console.log(data);
        });
    }

    const roles = {
        "teacher":"Õpetaja",
        "guest":"Külaline",
        "valimised-admin":"Rebased (admin)",
        "valimised-vip":"Rebased (VIP)",
    };

    return <>
        <Layout title="Profiil & seaded">
            {imageUploadErrors != null && <div className="section" style={{marginBottom:"16px"}}>
                <InfoBanner type={Object.keys(imageUploadErrors)[0]} text={imageUploadErrors[Object.keys(imageUploadErrors)[0]]} />
            </div>}
            <div className="class-grid">
                <div className="section" style={{position:"relative"}}>
                    
                    <div style={{position:"absolute", right:"24px", top:"24px",}}>
                        <div style={{position:"relative", display:"inline", height:"fit-content"}} onClick={auth.user.role == "guest" ? null : uploadFile}>
                            <img src={auth.user.profile_pic} style={{position:"relative", borderRadius:"50%", aspectRatio:'1', height:"100px", objectFit:"cover"}}/>
                            {auth.user.role != "guest" && <span translate="no" style={{cursor:"pointer", position:"absolute", bottom:"4px", left:"12px", backgroundColor:"rgb(var(--primary-color), 0.9)", color:"white", borderRadius:"50%", padding:"4px", fontSize:"12px"}} className="material-icons">edit</span>}
                        </div>
                    </div>
                    <TwoRowTextButton showArrow={false} capitalizeUpper={true} capitalizeLower={true} upperText={auth.user.eesnimi} lowerText={auth.user.perenimi} />
                    {auth.user.role != "student" && <span style={{backgroundColor:"rgb(var(--primary-color))", borderRadius:"4px", color:"white", fontSize:"16px", padding:"4px 6px", fontWeight:"normal", marginTop:"0", marginInline:"8px"}}>{roles[auth.user.role] ?? auth.user.role ?? "Tavakonto"}</span>}
                    <SizedBox height="32px" />
                    {auth.user.role != "teacher" && <div className="section clickable" style={{position:"relative", margin:"8px", display:"inline-flex", gap:"8px", flexDirection:"row", alignItems:"center"}}>
                        {className != null && <div style={{display:"flex", alignItems:'center', gap:"8px"}}>
                            <div>
                                <h2 style={{color:"rgb(var(--primary-color))", fontSize:"48px", marginBlock:"0"}}>{className}</h2>
                                <SizedBox height="8px" />
                                <p style={{color:"var(--grey-color)", marginBlock:"0"}}>Muuda</p>
                            </div>
                            <i translate="no" style={{fontSize:"48px", color:"var(--lightgrey-color)"}} className="material-icons">arrow_forward_ios</i>
                        </div>}
                        {className == null && <TwoRowTextButton upperText="Liitu klassiga" lowerText="Vali klass" />}
                    
                        <a href={route("classJoin")} style={{all:"unset", position:"absolute", top:"0", left:"0", height:"100%", width:"100%"}}></a>
                    </div>}
                    {auth.user.role == "teacher" && <div className="section clickable" style={{display:"inline-flex", position:"relative"}}>
                        <TwoRowTextButton upperText="Loo uus klass" lowerText="Uus klass" />

                        <a href={route("newClass")} style={{all:"unset", position:"absolute", top:"0", left:"0", height:"100%", width:"100%"}}></a>
                    </div> }

                    <SizedBox height="16px" />
                    <p style={{position:"absolute", bottom:"16px", right:"16px", textAlign:"end", display:((window.innerWidth > 1000 && window.innerWidth < 1300) || window.innerWidth < 600 ? "block" : 'flex'), flexDirection:"row-reverse", alignItems:'center', marginBlock:"0", color:"var(--grey-color)"}}>{auth.user.email_verified_at == null && auth.user.role != "guest" && <a style={{display:(window.innerWidth > 1000 && window.innerWidth < 1300) || window.innerWidth < 600 ? "block" : "inherit"}} onClick={verifyEmail} alone="">(Kinnita)</a>} {auth.user.email}</p>
                </div>
                <div disabled={!auth.user.email_verified_at} onClick={sendPwdResetLink} className="section clickable" style={{padding:"16px", display:"flex", justifyContent:"start", alignItems:"center"}}>
                    <div>
                        <i translate="no" style={{fontSize:"32px"}} className="material-icons-outlined">lock</i>
                        <p style={{marginTop:"8px", marginBottom:"0"}}>Muuda parooli</p>
                    </div>
                </div>
                <div onClick={logout} className="section clickable red" style={{padding:"16px", display:"flex", justifyContent:"start", alignItems:"center"}}>
                    <div style={{color:"var(--red-color)",}}>
                        <i translate="no" style={{fontSize:"32px"}} className="material-icons-outlined">logout</i>
                        <p style={{marginTop:"8px", marginBottom:"0"}}>{auth.user.role == "guest" ? "Lahku külalisvaatest" : "Logi välja"}</p>
                    </div>
                </div>
                <div className="section clickable" style={{padding:"16px", position:'relative'}}>
                    <i translate="no" className="material-icons" style={{fontSize:"32px", marginBottom:"0", marginLeft:"8px"}}>language</i>
                    <TwoRowTextButton upperText="Avalik profiil" lowerText="Vaata, kuidas teised sind näevad" showArrow={false} />

                    <a href={"/profile/"+auth.user.id} style={{all:"unset", position:"absolute", top:"0", left:"0", height:"100%", width:"100%"}}></a>
                </div>
            </div>
            <SizedBox height="16px" />

            <div className="two-column-layout">
                <div onClick={()=>setLightTheme(lightTheme => !lightTheme)} className="section clickable" style={{display:"flex", justifyContent:"space-between", alignItems:'center'}}>
                    <TwoRowTextButton showArrow={false} upperText="Rakenduse teema" lowerText={lightTheme ? "Hele teema" : "Tume teema"} />

                    <i translate="no" style={{fontSize:"50px", marginRight:"16px"}} className="material-icons-outlined">{lightTheme ? "light_mode" : "brightness_2"}</i>
                </div>
                <div className="section" style={{display:"flex", justifyContent:"space-between", alignItems:'center'}}>
                    <TwoRowTextButton showArrow={false} upperText="Peamine värv" lowerText="Muuda" />
                    <div style={{display:"grid", gridTemplateColumns:"repeat(5, 1fr)", gap:"4px", marginRight:"8px"}}>
                            <ColorPicker color={"default"} currentColor={primaryColor} onChange={(color)=>changePrimaryColor(color)} />
                            <ColorPicker color={"64, 103, 158"} currentColor={primaryColor} onChange={(color)=>changePrimaryColor(color)} />
                            <ColorPicker color={"231, 136, 149"} currentColor={primaryColor} onChange={(color)=>changePrimaryColor(color)} />
                            <ColorPicker color={"82, 34, 88"} currentColor={primaryColor} onChange={(color)=>changePrimaryColor(color)} />
                            <ColorPicker color={"121, 160, 208"} currentColor={primaryColor} onChange={(color)=>changePrimaryColor(color)} />

                            <ColorPicker color={"125, 10, 10"} currentColor={primaryColor} onChange={(color)=>changePrimaryColor(color)} />
                            <ColorPicker color={"186, 186, 106"} currentColor={primaryColor} onChange={(color)=>changePrimaryColor(color)} />
                            <ColorPicker color={"90, 92, 60"} currentColor={primaryColor} onChange={(color)=>changePrimaryColor(color)} />
                            <ColorPicker color={"133, 199, 195"} currentColor={primaryColor} onChange={(color)=>changePrimaryColor(color)} />
                            <ColorPicker color={"164, 190, 123"} currentColor={primaryColor} onChange={(color)=>changePrimaryColor(color)} />
                    </div>
                </div>

                <div onClick={()=>setTimerVisible(timerVisible => !timerVisible)} className="section clickable" style={{display:"flex", justifyContent:"space-between", alignItems:'center'}}>
                    <TwoRowTextButton showArrow={false} upperText="Taimer" lowerText={timerVisible ? "Taimer nähtav" : "Taimer peidetud"} />

                    <i translate="no" style={{fontSize:"50px", marginRight:"16px"}} className="material-icons-outlined">{timerVisible ? "timer" : "timer_off"}</i>
                </div>
                <div onClick={()=>setPointsAnimation(pointsAnimation => !pointsAnimation)} className="section clickable" style={{display:"flex", justifyContent:"space-between", alignItems:'center'}}>
                    <TwoRowTextButton showArrow={false} upperText="Punktianimatsioon" lowerText={pointsAnimation ? "Näita" : "Peida"} />

                    <i translate="no" style={{fontSize:"50px", marginRight:"16px"}} className="material-icons-outlined">{pointsAnimation ? "visibility" : "visibility_off"}</i>
                </div>

                <div onClick={()=>setFlipKeyboard(flipKeyboard => !flipKeyboard)} className="section clickable" style={{display:"flex", justifyContent:"space-between", alignItems:'center'}}>
                    <TwoRowTextButton showArrow={false} upperText="Klaviatuur" lowerText={flipKeyboard ? "Ümberpööratud" : "Tavaline"} />

                    <i translate="no" style={{fontSize:"50px", marginRight:"16px"}} className="material-icons-outlined">{"dialpad"}</i>
                </div>

                <div className="section" style={{display:"flex", justifyContent:"space-between", alignItems:'center'}}>
                    <TwoRowTextButton showArrow={false} upperText="Vaikimisi aeg" lowerText="Muuda" />
                    <TimeSelector time={defaultTime} onIncrease={()=>setDefaultTime(defaultTime => parseFloat(defaultTime) >= 9.5 ? 10 : parseFloat(defaultTime) + 0.5)} onDecrease={()=>setDefaultTime(defaultTime => parseFloat(defaultTime) < 0.5 ? 0 : parseFloat(defaultTime) - 0.5)} />
                </div>
            </div>
            <SizedBox height="16px" />
            <div style={{display:"flex", justifyContent:"space-between", alignContent:'end', color:"var(--grey-color)"}}>
                <a alone="" style={{fontWeight:"bold", color:"var(--grey-color)"}} href={route("changelog")}>Uuenduste ajalugu</a>
                <span>© 2024</span>
            </div>
        </Layout>
    </>;
}