import SizedBox from "@/Components/SizedBox";
import LeaderboardRow from "@/Components/LeaderboardRow";
import { useState } from "react";
import Layout from "@/Components/2024SummerRedesign/Layout";
import StatisticsTile from "@/Components/2024SummerRedesign/StatisticsTile";
import TwoRowTextButton from "@/Components/2024SummerRedesign/TwoRowTextButton";
import LeaderboardPodiumTile from "@/Components/2024SummerRedesign/LeaderboardPodiumTile";
import VerticalStatTile from "@/Components/2024SummerRedesign/VerticalStatTile";
import InfoBanner from "@/Components/InfoBanner";

export default function ClassroomPage({leaderboard, teacher, auth, className, stats, isTeacher, uuid}){

    const [copyText, setCopyText] = useState("Klassiga ühinemise link");

    function copyToClipboard(){
        navigator.clipboard.writeText(window.location.origin + "/classroom/" + uuid + "/join");
        setCopyText("Link kopeeritud!");

        setTimeout(() => {
            setCopyText("Klassiga ühinemise link");
        }, 2000);
    }

    return <>
        <Layout title="Minu klass" auth={auth}>
            <div className="four-stat-row">
                <StatisticsTile stat={stats.gamesToday} label={"Mängu täna"} oneLabel={"Mäng täna"} icon={"schedule"} />
                <StatisticsTile stat={stats.totalGameCount} label={"Mängu kokku"} oneLabel={"Mäng kokku"} icon={"sports_esports"} />
                <StatisticsTile stat={stats.totalPointsCount} label={"XP kokku"} oneLabel={"1 XP klassi peale??"} icon={"trophy"} compactNumber={true} />
                <StatisticsTile stat={stats.studentsCount} label={"Õpilast"} oneLabel={"Õpilane"} icon={"groups"} />
            </div>
            <SizedBox height="8px" />
            <div className="two-column-layout">
                <div>
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
                    {leaderboard.length > 3 && leaderboard.slice(3).map((e, index)=><LeaderboardRow playedToday={e.playedToday} place={e.place} key={e.user.id} index={index} player={auth.user.id == e.user.id} user={e.user} points={e.xp} /> )}
                    {leaderboard.length <= 0 && <InfoBanner text={"Siin klassis ei ole (veel) kedagi. Kutsu õpilasi klassi, jagades neile klassi nime ja parooli või saates neile klassiga ühinemise link."} />}
                </div>

                {/* Teine tulp */}
                {teacher != null && <div>
                    <div className="section clickable" style={{position:"relative", padding:"8px 16px", display:"flex", flexDirection:"row", justifyContent:"space-between", alignItems:"center"}}>
                        <VerticalStatTile padding="8px 0" marginBlock={0} capitalize={true} icon="school" text="Õpetaja" value={teacher.eesnimi + " " + teacher.perenimi} />
                        <img src={teacher.profile_pic} style={{height:"75px", aspectRatio:"1", borderRadius:"50%", objectFit:"cover"}} />

                        <a href={"/profile/"+teacher.id} style={{all:"unset", position:"absolute", top:"0", left:"0", height:"100%", width:"100%"}}></a>
                    </div>
                    <SizedBox height="8px" />
                    {isTeacher && <div style={{display:"grid", gridTemplateColumns:"repeat(2, 1fr)", gap:"16px"}}>
                        <div onClick={copyToClipboard} className="section clickable" style={{padding:"16px", display:"flex", justifyContent:"start", alignItems:"center", marginBlock:"0"}}>
                            <div>
                                <i translate="no" style={{fontSize:"32px"}} className="material-icons-outlined">link</i>
                                <p style={{marginTop:"8px", marginBottom:"0"}}>{copyText}</p>
                            </div>
                        </div>
                        <div onClick={()=>window.location.href = "/classroom/"+uuid+"/edit"} className="section clickable" style={{padding:"16px", display:"flex", justifyContent:"start", alignItems:"center", marginBlock:"0"}}>
                            <div>
                                <i translate="no" style={{fontSize:"32px"}} className="material-icons-outlined">edit</i>
                                <p style={{marginTop:"8px", marginBottom:"0"}}>Muuda klassi</p>
                            </div>
                        </div>
                    </div>}

                </div>}
            </div>
        </Layout>
    </>;
}