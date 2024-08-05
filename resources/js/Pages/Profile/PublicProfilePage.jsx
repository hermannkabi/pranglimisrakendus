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
import Layout from "@/Components/2024SummerRedesign/Layout";
import InfoBanner from "@/Components/InfoBanner";
import StatisticsTile from "@/Components/2024SummerRedesign/StatisticsTile";
import TwoRowTextButton from "@/Components/2024SummerRedesign/TwoRowTextButton";


export default function PublicProfilePage({auth, user, klass, stats, lastGames}){

    // useEffect(()=>{
    //     var style = document.querySelector('.hero').style;
    //     style.setProperty('--background', user.profile_pic == null ? "" : "url("+user.profile_pic+")");
    
    // }, []);

    return <>
        <Layout title="Avalik profiil">
            {auth.user.id == user.id && <div className="section" style={{marginBottom:'16px'}}>
                <InfoBanner text={"Oled enda profiili avalikus vaates. Selliselt saavad sind vaadata sinu "+(auth.user.role == "teacher" ? "õpilased" : "õpetaja ja klassikaaslased")+". Profiili muutmiseks mine profiilivaatesse"} />
            </div>} 
            {stats.total_training_count > 0 && <div className="four-stat-row" style={{marginBottom:"16px"}}>
                <StatisticsTile iconColor="#F3AF71" disabled={user.streak_active == 0} stat={user.streak ?? "-"} label={"Järjestikust päeva"} oneLabel={"Järjestikune päev"} icon={"local_fire_department"} />
                <StatisticsTile stat={stats.total_training_count ?? "0"} label={"Mängu"} oneLabel={"Mäng"} icon={"sports_esports"} />
                <StatisticsTile stat={(stats.accuracy ?? "0") + "%"} label={"Vastamistäpsus"}icon={"percent"} />
                <StatisticsTile stat={stats.points ?? "0"} label={"Punkti kokku"} oneLabel={"Punkt kokku"} icon={"trophy"} compactNumber={true} />
            </div>}

            <div className="two-column-layout">
                <div>
                    <div className="section" style={{position:"relative"}}>
                        <div style={{position:"absolute", right:"24px", top:"24px",}}>
                            <img src={user.profile_pic} style={{borderRadius:"50%", aspectRatio:'1', height:"100px", objectFit:"cover"}}/>
                        </div>
                        <TwoRowTextButton showArrow={false} capitalizeUpper={true} capitalizeLower={true} upperText={user.eesnimi} lowerText={user.perenimi} />
                        <SizedBox height="150px" />
                        <p style={{position:"absolute", bottom:"16px", right:"16px", display:"flex", alignItems:'center', marginBlock:"0", color:"var(--grey-color)"}}>Reaaleris alates {(new Date(user.created_at)).toLocaleString("et-EE", {month:"2-digit", day:"2-digit", year:"numeric"}).split(",")[0]}</p>
                    </div>
                    <SizedBox height="8px" />
                    {lastGames.map((e, ind)=><GameTile data={e} key={ind} />)}
                    {lastGames.length <= 0 && <div className="section">
                        <InfoBanner text={user.eesnimi + " ei ole veel mängida jõudnud. Vaata veidi aja pärast uuesti!"} />
                    </div> }
                </div>

                <div>
                    <div style={{display:"grid", gridTemplateColumns:"repeat(2, 1fr)", gap:"16px"}}> 
                        <div className="section class-stat" style={{padding:"16px"}}>
                            <div className="stat-desc">
                                <i className="material-icons-outlined">school</i>
                                <p style={{marginTop:"4px"}}>Klass</p>
                            </div>
                            <p style={{marginBottom:"8px", fontWeight:"bold", color:"var(--lightgrey-color)", fontSize:"24px"}}>{klass == null ? "Klassi pole" : klass.klass_name}</p>
                        </div>

                        <div className="section class-stat" style={{padding:"16px"}}>
                            <div className="stat-desc">
                                <i className="material-icons-outlined">apartment</i>
                                <p style={{marginTop:"4px"}}>Kool</p>
                            </div>
                            <p style={{display:"flex", alignItems:"center", gap:"8px", marginBottom:"8px", fontWeight:"bold", color:"var(--lightgrey-color)", fontSize:"24px"}}> <img style={{borderRadius:"50%", objectFit:"cover", height:"24px", aspectRatio:"1", margin:"0"}} src="https://reaaler.real.edu.ee/assets/logo.png" alt="" /> Tallinna Reaalkool</p>
                        </div>
                    </div>

                    {lastGames.length > 0 && <div onClick={()=>window.location.href = "/game/history/"+user.id} className="section clickable">
                        <TwoRowTextButton upperText="Mängude ajalugu" lowerText="Vaata kõiki" />
                    </div>}
                </div>
            </div>
        </Layout>
    </>;
}