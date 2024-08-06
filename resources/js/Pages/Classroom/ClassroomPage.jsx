import Navbar from "@/Components/Navbar";
import SizedBox from "@/Components/SizedBox";
import StatisticsWidget from "@/Components/StatisticsWidget";
import LeaderboardRow from "@/Components/LeaderboardRow";
import { Head } from "@inertiajs/react";
import { useState } from "react";
import HorizontalInfoBanner from "@/Components/HorizontalInfoBanner";
import Layout from "@/Components/2024SummerRedesign/Layout";
import StatisticsTile from "@/Components/2024SummerRedesign/StatisticsTile";
import TwoRowTextButton from "@/Components/2024SummerRedesign/TwoRowTextButton";

export default function ClassroomPage({leaderboard, teacher, auth, className, stats, isTeacher, uuid}){

    const [copyText, setCopyText] = useState("Klassiga ühinemise link");

    function copyToClipboard(){
        navigator.clipboard.writeText(window.location.origin + "/classroom/" + uuid + "/join");
        setCopyText("Link kopeeritud!");

        setTimeout(() => {
            setCopyText("Klassiga ühinemise link");
        }, 2000);
    }

    console.log(leaderboard);

    return <>
        <Layout title="Minu klass">
            <div className="four-stat-row">
                <StatisticsTile stat={stats.gamesToday} label={"Mängu täna"} oneLabel={"Mäng täna"} icon={"schedule"} />
                <StatisticsTile stat={stats.totalGameCount} label={"Mängu kokku"} oneLabel={"Mäng kokku"} icon={"sports_esports"} />
                <StatisticsTile stat={stats.totalPointsCount} label={"XP kokku"} oneLabel={"1 XP klassi peale??"} icon={"trophy"} compactNumber={true} />
                <StatisticsTile stat={stats.studentsCount} label={"Õpilast"} oneLabel={"Õpilane"} icon={"groups"} />
            </div>
            <SizedBox height="16px" />
            <div className="two-column-layout">
                <div className="section">
                    <TwoRowTextButton upperText="Edetabel" lowerText={className} showArrow={false} />
                    <SizedBox height="16px" />
                    {leaderboard.length > 0 && <div className="podium" style={{display:"flex", flexDirection:"row", justifyContent:"stretch", alignItems:'end', gap:"8px"}}>
                        {leaderboard.length > 1 && <div style={{flex:"1", textAlign:"center"}}> 
                            <span style={{fontSize:"24px"}}>{leaderboard[1].place}</span>
                            <a style={{all:"unset"}} href={"/profile/"+leaderboard[1].user.id}>
                                <div className="section clickable">
                                    <i style={{fontSize:"32px", color:"#9F9F9F"}} className="material-icons-outlined">workspace_premium</i>
                                    <SizedBox height="32px" />
                                    <p style={{textTransform:"capitalize", marginBlock:"4px", display:"inline-flex", alignItems:'center', gap:"4px"}}>{leaderboard[1].user.eesnimi} {leaderboard[1].user.perenimi} {leaderboard[1].playedToday && <i title="Täna Reaalerit kasutanud" style={{color:"#F3AF71"}} className="material-icons-outlined">local_fire_department</i> } </p>
                                </div>
                            </a>
                        </div>}
                        <div style={{flex:"1", textAlign:"center",}}>
                            <span style={{fontWeight:"bold", fontSize:"28px"}}>{leaderboard[0].place}</span>
                            <a style={{all:"unset"}} href={"/profile/"+leaderboard[0].user.id}>
                                <div className="section clickable">
                                    <i style={{fontSize:"50px", color:"#F1C93C"}} className="material-icons-outlined">trophy</i>
                                    <SizedBox height="8px" />
                                    {leaderboard[0].playedToday && <><i title="Täna Reaalerit kasutanud" style={{color:"#F3AF71"}} className="material-icons-outlined">local_fire_department</i> <br /> </> }
                                    <span style={{textTransform:"capitalize", marginBlock:"4px", fontWeight:"bold", fontWeight:"28px"}}>{leaderboard[0].user.eesnimi}</span> <br />
                                    <span style={{textTransform:"capitalize", marginTop:"0", marginBottom:"4px", color:"var(--grey-color)"}}>{leaderboard[0].user.perenimi}</span>
                                </div>
                            </a>
                        </div>
                        {leaderboard.length > 2 && <div style={{flex:"1", textAlign:"center"}}>
                            <span style={{fontSize:"24px"}}>{leaderboard[2].place}</span>
                            <a style={{all:"unset"}} href={"/profile/"+leaderboard[2].user.id}>
                                <div className="section clickable">
                                    <i style={{fontSize:"32px", color:"#B78D65"}} className="material-icons-outlined">workspace_premium</i>
                                    <SizedBox height="12px" />
                                    <p style={{textTransform:"capitalize", marginBlock:"4px", display:"inline-flex", alignItems:'center', gap:"4px"}}>{leaderboard[2].user.eesnimi} {leaderboard[2].user.perenimi} {leaderboard[2].playedToday && <i title="Täna Reaalerit kasutanud" style={{color:"#F3AF71"}} className="material-icons-outlined">local_fire_department</i> } </p>
                                </div>
                            </a>
                        </div> }
                    </div>}
                    <SizedBox height="16px" />
                    {leaderboard.length > 3 && leaderboard.slice(3).map((e, index)=><LeaderboardRow playedToday={e.playedToday} place={e.place} key={e.user.id} index={index} player={auth.user.id == e.user.id} user={e.user} points={e.xp} /> )}
                </div>

                {/* Teine tulp */}
                {teacher != null && <div>
                    <div onClick={()=>window.location.href = "/profile/"+teacher.id} className="section clickable" style={{padding:"8px 16px", display:"flex", flexDirection:"row", justifyContent:"space-between", alignItems:"center"}}>
                        <div>
                            <SizedBox height="8px" />
                            <div>
                                <i style={{fontSize:"32px"}} className="material-icons-outlined">school</i>
                                <p style={{marginTop:"4px"}}>Õpetaja</p>
                            </div>
                            <p style={{textTransform:"capitalize", marginBottom:"8px", fontWeight:"bold", color:"var(--lightgrey-color)", fontSize:"24px"}}>{teacher.eesnimi} {teacher.perenimi}</p>
                        </div>

                        <img src={teacher.profile_pic} style={{height:"75px", aspectRatio:"1", borderRadius:"50%", objectFit:"cover"}} />
                    </div>
                    <SizedBox height="8px" />
                    {!isTeacher && <div style={{display:"grid", gridTemplateColumns:"repeat(2, 1fr)", gap:"16px"}}>
                        <div onClick={copyToClipboard} className="section clickable" style={{padding:"16px", display:"flex", justifyContent:"start", alignItems:"center", marginBlock:"0"}}>
                            <div>
                                <i style={{fontSize:"32px"}} className="material-icons-outlined">link</i>
                                <p style={{marginTop:"8px", marginBottom:"0"}}>{copyText}</p>
                            </div>
                        </div>
                        <div onClick={()=>window.location.href = "/classroom/"+uuid+"/edit"} className="section clickable" style={{padding:"16px", display:"flex", justifyContent:"start", alignItems:"center", marginBlock:"0"}}>
                            <div>
                                <i style={{fontSize:"32px"}} className="material-icons-outlined">edit</i>
                                <p style={{marginTop:"8px", marginBottom:"0"}}>Muuda klassi</p>
                            </div>
                        </div>
                    </div>}

                </div>}
            </div>
        </Layout>
    </>;
}