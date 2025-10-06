import SizedBox from "@/Components/SizedBox";
import LeaderboardRow from "@/Components/LeaderboardRow";
import { useState } from "react";
import Layout from "@/Components/2024SummerRedesign/Layout";
import StatisticsTile from "@/Components/2024SummerRedesign/StatisticsTile";
import TwoRowTextButton from "@/Components/2024SummerRedesign/TwoRowTextButton";
import LeaderboardPodiumTile from "@/Components/2024SummerRedesign/LeaderboardPodiumTile";
import VerticalStatTile from "@/Components/2024SummerRedesign/VerticalStatTile";
import InfoBanner from "@/Components/InfoBanner";
import Chip from "@/Components/2024SummerRedesign/Chip";

export default function ClassroomPage({leaderboard, teacher, auth, className, stats, isTeacher, uuid}){

    const [copyText, setCopyText] = useState("Klassiga ühinemise link");
    const [orderBy, setOrderBy] = useState("leaderboard");

    function copyToClipboard(){
        navigator.clipboard.writeText(window.location.origin + "/classroom/" + uuid + "/join");
        setCopyText("Link kopeeritud!");

        setTimeout(() => {
            setCopyText("Klassiga ühinemise link");
        }, 2000);
    }

    return <>
        <Layout title={className} auth={auth}>
            <div className="four-stat-row">
                <StatisticsTile stat={stats.gamesToday} label={"Mängu täna"} oneLabel={"Mäng täna"} icon={"schedule"} />
                <StatisticsTile stat={stats.totalGameCount} label={"Mängu kokku"} oneLabel={"Mäng kokku"} icon={"sports_esports"} />
                <StatisticsTile stat={stats.totalPointsCount} label={"XP kokku"} oneLabel={"1 XP klassi peale??"} icon={"trophy"} compactNumber={true} />
                <StatisticsTile stat={stats.studentsCount} label={"Õpilast"} oneLabel={"Õpilane"} icon={"groups"} />
            </div>
            <SizedBox height="8px" />
            <div className="two-column-layout">
                <div>
                    {leaderboard.length > 0 && (auth.user.role.split(",").includes("admin") || auth.user.role.split(",").includes("teacher")) &&<VerticalStatTile icon="sort_by_alpha" text="Järjestusalus" customValue={true} value={<>
                        <div>
                            <Chip onClick={()=>setOrderBy("leaderboard")} active={orderBy == "leaderboard"} label={"Edetabel"} />
                            <Chip onClick={()=>setOrderBy("firstName")} active={orderBy == "firstName"} label={"Eesnime järgi"} />
                            <Chip onClick={()=>setOrderBy("lastName")} active={orderBy == "lastName"} label={"Perekonnanime järgi"} />
                        </div>
                    </>} />}
                    {orderBy == "leaderboard" && <>
                        <div className="section">
                            <TwoRowTextButton upperText="Edetabel" lowerText={className} showArrow={false} />
                            <SizedBox height="16px" />
                            {leaderboard.length > 0 && <div className="podium" style={{display:"flex", flexDirection:"row", justifyContent:"stretch", alignItems:'end', gap:"8px"}}>
                                {leaderboard.length > 1 && <LeaderboardPodiumTile auth={auth} e={leaderboard[1]} />}
                                <LeaderboardPodiumTile auth={auth} e={leaderboard[0]} firstPlace={true} />
                                {leaderboard.length > 2 && <LeaderboardPodiumTile auth={auth} e={leaderboard[2]} />}
                            </div>}
                            <SizedBox height="16px" />
                        </div>
                        {leaderboard.length > 3 && leaderboard.slice(3).map((e, index)=><LeaderboardRow auth={auth} playedToday={e.playedToday} place={e.place} key={e.user.id} index={index} player={auth.user.id == e.user.id} user={e.user} points={e.xp} /> )}
                        {leaderboard.length <= 0 && <InfoBanner text={"Siin klassis ei ole (veel) kedagi. Kutsu õpilasi klassi, jagades neile klassi nime ja parooli või saates neile klassiga ühinemise link."} />}
                    </>}
                    {(orderBy == "firstName" || orderBy == "lastName") && <>
                        {leaderboard.toSorted((a, b) => {
                        const nameA = (orderBy == "firstName" ? a.user.eesnimi : a.user.perenimi).toLowerCase(); 
                        const nameB = (orderBy == "firstName" ? b.user.eesnimi : b.user.perenimi).toLowerCase(); 
                        if (nameA < nameB) {
                            return -1;
                        }
                        if (nameA > nameB) {
                            return 1;
                        }
                        return 0;
                        }).map((e, index)=> <LeaderboardRow auth={auth} playedToday={e.playedToday} place={e.place} key={e.user.id + orderBy} index={index} player={auth.user.id == e.user.id} user={e.user} points={e.xp} />)}
                    </>}
                </div>

                {/* Teine tulp */}
                {teacher != null && <div>
                    <div className="section clickable" style={{position:"relative", padding:"8px 16px", display:"flex", flexDirection:"row", justifyContent:"space-between", alignItems:"center"}}>
                        <VerticalStatTile padding="8px 0" marginBlock={0} capitalize={true} icon="school" text="Õpetaja" value={teacher.eesnimi + " " + teacher.perenimi} />
                        <img src={teacher.profile_pic} style={{height:"75px", aspectRatio:"1", borderRadius:"50%", objectFit:"cover"}} />

                        <a href={"/profile/"+teacher.id} style={{all:"unset", position:"absolute", top:"0", left:"0", height:"100%", width:"100%"}}></a>
                    </div>
                    <SizedBox height="8px" />
                    {isTeacher && <><div style={{display:"grid", gridTemplateColumns:"repeat(2, 1fr)", gap:"16px"}}>
                        <div onClick={()=>window.location.href = "./share"} className="section clickable" style={{padding:"16px", display:"flex", justifyContent:"start", alignItems:"center", marginBlock:"0"}}>
                            <div>
                                <i translate="no" style={{fontSize:"32px"}} className="material-icons-outlined">share</i>
                                <p style={{marginTop:"8px", marginBottom:"0"}}>Jaga klassi</p>
                            </div>
                        </div>
                        <div onClick={()=>window.location.href = "/classroom/"+uuid+"/edit"} className="section clickable" style={{padding:"16px", display:"flex", justifyContent:"start", alignItems:"center", marginBlock:"0"}}>
                            <div>
                                <i translate="no" style={{fontSize:"32px"}} className="material-icons-outlined">edit</i>
                                <p style={{marginTop:"8px", marginBottom:"0"}}>Muuda klassi</p>
                            </div>
                        </div>
                    </div>
                    
                    <SizedBox height={16} />
                    {leaderboard.length > 0 && <a href="./export" alone="" style={{color:"var(--grey-color)"}}> <i translate="no" className="material-icons-outlined no-anim">export_notes</i>&nbsp; Ekspordi klassi andmed</a>}

                    </>
                    }

                </div>}
            </div>
        </Layout>
    </>;
}