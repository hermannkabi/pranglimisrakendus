import GameTile from "@/Components/GameTile";
import HorizontalInfoBanner from "@/Components/HorizontalInfoBanner";
import Navbar from "@/Components/Navbar";
import ProfileAction from "@/Components/ProfileAction";
import SizedBox from "@/Components/SizedBox";
import StatisticsWidget from "@/Components/StatisticsWidget";
import { Head } from "@inertiajs/react";
import "/public/css/profile.css";
import { useEffect } from "react";
import ProfileWidget from "@/Components/ProfileWidget";


export default function PublicProfilePage({auth, user, klass, stats, lastGames}){

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
                <ProfileWidget user={user} auth={auth} isPublic={true} />
                {user.role != "teacher" && <div style={{display:"grid", gridTemplateColumns:"repeat(2, 1fr)", marginTop:"36px"}} className="actions-container">
                    <ProfileAction icon="apartment" label="Tallinna Reaalkool" smallLabel="Kool" />
                    <ProfileAction icon="school" label={klass == null ? "Klassi pole" : klass.klass_name} smallLabel="Klass" />
                </div>}
                    
                {user.role == "teacher" && <div className="actions-container school-container">
                    <ProfileAction icon="apartment" label="Tallinna Reaalkool" smallLabel="Kool" />
                </div>}
            </section>

            {stats.total_training_count > 0 && <section>
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
            </section>}

            <section>
                <div className="header-container">
                    <h3 className="section-header">Viimased mängud</h3>
                </div>

                {lastGames.map((e, ind)=><GameTile data={e} key={ind} />)}
                {lastGames.length <= 0 && <HorizontalInfoBanner text={user.role == "teacher" ? "Õpetaja "+(user.eesnimi)+" peab õpilaste kontrolltöid parandama - ta ei ole veel jõudnud arvutamisega tegeleda 😊" : "Kasutajal ei ole veel mänge"} />}
            
                {lastGames.length > 0 && <>
                    <SizedBox height={24} />
                    <a href={"/game/history/"+user.id} alone="" >Kõik mängud&nbsp;<span className="material-icons" translate="no">navigate_next</span></a>
                    <SizedBox height={8} />
                    
                </>}
            </section>
            
    </>;
}