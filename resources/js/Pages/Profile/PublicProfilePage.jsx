import GameTile from "@/Components/GameTile";
import InfoBanner from "@/Components/InfoBanner";
import Navbar from "@/Components/Navbar";
import SizedBox from "@/Components/SizedBox";
import StatisticsWidget from "@/Components/StatisticsWidget";
import { Head } from "@inertiajs/react";

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


    return <>
            <Head title="Kontovaade" />
            <Navbar title="Kontovaade"  user={auth.user} />

            <SizedBox height={36} />
            <h2>Kontovaade</h2>
            {auth.user.id == user.id && <section>
                <p>Oled enda avalikus vaates. Profiili haldamiseks vajuta <a href={route("profilePage")}>siia</a> </p>
            </section>} 
            <section>
                <div className="" style={{display:'flex', flexWrap:"wrap", justifyContent:"center"}}>
                    <div className="big-container" style={{marginTop:"8px"}}>
                        <SizedBox height={16} />
                        <img style={{height:"64px", userSelect:"none"}} className="profile-pic" src={user.profile_pic} alt={user.eesnimi + " " + user.perenimi} />
                        <SizedBox height={8} />
                        <h1 style={{marginTop:"4px", marginBottom:"0", textTransform:"capitalize"}}>{user.eesnimi ?? window.localStorage.getItem("first-name") ?? "Mari"} {user.perenimi ?? window.localStorage.getItem("last-name") ?? "Maasikas"}</h1>
                        <p style={{color:"grey", fontSize:"20px", marginTop:"0"}}>{user.email}</p>
                    </div> 

                    <div className="stat-container" style={{width:"90%"}}>
                       {user.role != "student" && <div style={profileTypeStyle}>
                            <p style={{color:'gray', marginBlock: "0"}}>KONTOTÜÜP</p>
                            <h3 style={{marginBlock:0}}>{user.role == "teacher" ? "Õpetaja" : user.role == "guest" ? "Külaline" : user.role == null ? "Tavakonto" : user.role}</h3>
                        </div>}

                        <div style={profileTypeStyle}>
                            <p style={{color:'gray', marginBlock: "0"}}>KOOL</p>
                            <h3 style={{marginBlock:0}}>Tallinna Reaalkool</h3>
                        </div>
                        
                        {user.role != "teacher" && user.role != "guest" && klass != null && <div style={profileTypeStyle}>
                            <p style={{color:'gray', marginBlock: "0"}}>KLASS</p>
                            <h3 style={{marginBlock:0, color: klass.klass_name == null ? "grey" : "inherit"}}>{user == null ? "140.a" : user.klass == "õpetaja" ? "Õpetajakonto" : klass.klass_name ?? "Pole lisatud"}</h3>
                        </div>}
                    </div> 
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
                {lastGames.length <= 0 && <p style={{color:"grey"}}>Kasutajal ei ole veel mänge</p> }
            </section>
    </>;
}