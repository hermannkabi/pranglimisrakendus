import GameTile from "@/Components/GameTile";
import HorizontalInfoBanner from "@/Components/HorizontalInfoBanner";
import Navbar from "@/Components/Navbar";
import ProfileAction from "@/Components/ProfileAction";
import SizedBox from "@/Components/SizedBox";
import StatisticsWidget from "@/Components/StatisticsWidget";
import { Head } from "@inertiajs/react";
import "/public/css/profile.css";
import { useEffect } from "react";


export default function PublicProfilePage({auth, user, klass, stats, lastGames}){

    console.log(lastGames);
    console.log(stats);

    const profileTypeStyle = {
        display:"flex",
        flexDirection:"row",
        justifyContent:"space-between",
        alignItems:"baseline",
        marginBlock:"32px",
    };


    useEffect(()=>{
        var style = document.querySelector('.hero').style;
        style.setProperty('--background', user.profile_pic == null ? "" : "url("+user.profile_pic+")");
    
    }, []);

    return <>
            <Head title="Kontovaade" />
            <Navbar title="Kontovaade"  user={auth.user} />

            <SizedBox height={36} />
            <h2>Kontovaade</h2>
            {auth.user.id == user.id && <section>
                <HorizontalInfoBanner text={"Oled enda profiili avalikus vaates. Selliselt saavad sind vaadata sinu "+(auth.user.role == "teacher" ? "õpilased" : "õpetaja ja klassikaaslased")+". Profiili muutmiseks mine profiilivaatesse"} link={route("profilePage")} />
            </section>} 

            <section className="hero">
                <SizedBox height={32} />
                <div className="" style={{display:'flex', flexWrap:"wrap", justifyContent:"center", alignItems:"center", gap:"16px"}}>
                    <div style={{overflow:"hidden"}}>
                        <div  className="profile-widget" style={{display:"flex", flexDirection:"row", gap:"16px", alignItems:"center"}}>
                            <img style={{height:"64px", userSelect:"none"}} className="profile-pic" src={user.profile_pic} alt={user.eesnimi + " " + user.perenimi} />
                            <div className="name-email" style={{textAlign:"start"}}>
                                <div style={{}}><h1 translate="no" style={{marginTop:"4px", marginBottom:"0", textTransform:"capitalize", display:"inline", verticalAlign:"middle"}}>{user.eesnimi} {user.perenimi} </h1> {user.role != "student" && <span style={{backgroundColor:"rgb(var(--primary-color))", borderRadius:"4px", color:"white", fontSize:"12px", padding:"2px 4px", fontWeight:"normal", marginTop:"6px"}}>{user == null ? "Õpilane" : user.role == "teacher" ? "Õpetaja" : user.role == "guest" ? "Külaline" : user.role == null ? "Tavakonto" : user.role}</span>}</div>
                                {user.email.length > 0 && <p translate="no" style={{marginBottom:"0", color:"grey", fontSize:"20px", marginTop:"0"}}>{user == null ? "mari.maasikas@real.edu.ee" : user.email}</p>}
                            </div>
                        </div>
                    </div>

                    {user.role != "teacher" && <div style={{display:"grid", gridTemplateColumns:"repeat(2, 1fr)", marginTop:"36px"}} className="actions-container">
                        <ProfileAction icon="apartment" label="Tallinna Reaalkool" smallLabel="Kool" />
                        <ProfileAction icon="school" label={klass == null ? "Klassi pole" : klass.klass_name} smallLabel="Klass" />
                    </div>}
                        
                    {user.role == "teacher" && <div style={{gridTemplateColumns:"1fr"}} className="actions-container">
                        <ProfileAction icon="apartment" label="Tallinna Reaalkool" smallLabel="Kool" />
                    </div>}
                </div>
            </section>

            <section>
                <div className="header-container">
                    <h3 className="section-header">Statistika</h3>
                </div>

                <div className="stats-container">
                    <StatisticsWidget stat={stats.total_training_count ?? totalTrainingCount} desc={"Mängu"} oneDesc={"Mäng"} />
                    <StatisticsWidget stat={(stats.accuracy ??(parseInt(window.localStorage.getItem("total-percentage") ?? "0")/parseInt(window.localStorage.getItem("total-training-count") ?? "1")).toFixed(0)) + "%"} desc="Vastamistäpsus" />
                    {/* <StatisticsWidget stat={stats.last_active ?? "-"} desc="Viimati aktiivne" /> */}
                    <StatisticsWidget stat={stats.streak ?? "-"} desc="Järjestikust päeva" oneDesc="Järjestikune päev" />
                    <StatisticsWidget stat={stats.points ?? window.localStorage.getItem("total-points") ?? "0"} desc="Punkti" oneDesc={"Punkt"} />
                </div>
            </section>

            <section>
                <div className="header-container">
                    <h3 className="section-header">Viimased mängud</h3>
                </div>

                {lastGames.map((e, ind)=><GameTile data={e} key={ind} />)}
                {lastGames.length <= 0 && <HorizontalInfoBanner text={user.role == "teacher" ? "Õpetajal on kiire - ta ei ole veel jõudnud arvutamisega tegeleda 😊" : "Kasutajal ei ole veel mänge"} />}
            </section>
    </>;
}