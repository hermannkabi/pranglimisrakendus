import Layout from "@/Components/2024SummerRedesign/Layout";
import LeaderboardPodiumTile from "@/Components/2024SummerRedesign/LeaderboardPodiumTile";
import StatisticsTile from "@/Components/2024SummerRedesign/StatisticsTile";
import TwoRowTextButton from "@/Components/2024SummerRedesign/TwoRowTextButton";
import VerticalStatTile from "@/Components/2024SummerRedesign/VerticalStatTile";
import InfoBanner from "@/Components/InfoBanner";
import SizedBox from "@/Components/SizedBox";
import LeaderboardRow from "@/Components/LeaderboardRow";
import Chip from "@/Components/2024SummerRedesign/Chip";
import BigButton from "@/Components/2024SummerRedesign/BigButton";


export default function CompetitionPage({auth, competition, leaderboard, participants, totalGames, maxScore, gamesToday, attemptsLeft}){

    function formatDateTime(datetimeStr) {
        const date = new Date(datetimeStr.replace(/-/g, "/"));
        const [datePart, timePart] = date.toLocaleString("et-EE", {
            year: "numeric",
            month: "2-digit",
            day: "2-digit",
            hour: "2-digit",
            minute: "2-digit",
        }).split(", ");
        
        return `${datePart} kell ${timePart.replace(":", ".")}`;
    }
    
    const gameNames = {
        "lihtsustamine":"Murru taandamine",
        "murruTaandamine":"Murru taandamine",
        "jaguvus":"Jaguvusseadused"
    };

    var formattedName = (gameName) => gameName == null ? "Tundmatu" : gameName in gameNames ? gameNames[gameName] : gameName.substring(0, 1).toUpperCase() + gameName.substring(1);
    
    
    function leaveCompetition(){
        if(confirm("Kas oled kindel, et soovid end võistluselt eemaldada? Kui tegu ei ole avaliku võistlusega, pead uuesti liitumiseks võtma ühendust administraatoriga")){
            $.post("/competition/"+competition.competition_id+"/remove/self", {
                "_token":window.csrfToken,
            }).done(function (data){
                window.location.href = route("dashboard");
            }).fail(function (data){
                console.log(data);
            });
        }
    }

    function joinCompetition(){
        $.post("/competition/"+competition.competition_id+"/join", {
            "_token":window.csrfToken,
        }).done(function (data){
            window.location.reload();
        }).fail(function (data){
            console.log(data);
        });
    } 

    function removeParticipant(id){
        if(confirm("Kas oled kindel, et tahad selle osaleja võistluselt eemaldada?")){
            $.post("/competition/"+competition.competition_id+"/participants/remove", {
                "_token":window.csrfToken,
                "participant":id,
            }).done(function (data){
                window.location.reload();
            }).fail(function (data){
                console.log(data);
            });
        }
    }

    return <Layout title={competition.name} auth={auth}>
                <div className="four-stat-row">
                    <StatisticsTile stat={gamesToday} label={"Mängu täna"} oneLabel={"Mäng täna"} icon={"nest_clock_farsight_analog"} />
                    <StatisticsTile stat={totalGames} label={"Mängu kokku"} oneLabel={"Mäng kokku"} icon={"sports_esports"} />
                    <StatisticsTile stat={maxScore} label={"Parim tulemus"} icon={"trophy"} compactNumber={true} />
                    <StatisticsTile stat={participants.length} label={"Võistlejat"} oneLabel={"Võistleja"} icon={"groups"} />
                </div>
                <SizedBox height="8px" />
                <div className="two-column-layout">
                    <div>
                        {Date.now() < (new Date(competition.dt_end.replace(/-/g, "/"))) && <div className="section">
                            <TwoRowTextButton showArrow={false} upperText={"Võistlejad"} lowerText={competition.name} />
                            {participants.map((e, index)=> <LeaderboardRow onRemove={auth.user.role.split(",").includes("admin") ? ()=>removeParticipant(e.id) : null} points={e.id==auth.user.id ? leaderboard.filter((i)=>i.user.id==auth.user.id)[0].total_score : null} place={index + 1} key={e.id} index={index} player={auth.user.id == e.id} user={e} /> )}
                            {participants.length == 0 && <InfoBanner text="Siin ei ole hetkel kedagi..." /> }
                        </div>}
                        {Date.now() >= (new Date(competition.dt_end.replace(/-/g, "/"))) && <><div className="section">
                            <TwoRowTextButton upperText="Tulemused" lowerText={competition.name} showArrow={false} />
                            <SizedBox height="16px" />
                            {leaderboard.length > 0 && <div className="podium" style={{display:"flex", flexDirection:"row", justifyContent:"stretch", alignItems:'end', gap:"8px"}}>
                                {leaderboard.length > 1 && <LeaderboardPodiumTile auth={auth} e={{place:leaderboard[1].rank_label, xp:leaderboard[1].total_score ?? 0, user:leaderboard[1].user, playedToday:false, }} />}
                                <LeaderboardPodiumTile auth={auth} e={{place:leaderboard[0].rank_label, xp:leaderboard[0].total_score ?? 0, user:leaderboard[0].user, playedToday:false, }} firstPlace={true} />
                                {leaderboard.length > 2 && <LeaderboardPodiumTile auth={auth} e={{place:leaderboard[2].rank_label, xp:leaderboard[2].total_score ?? 0, user:leaderboard[2].user, playedToday:false, }} />}
                            </div>}
                            <SizedBox height="16px" />
                        </div>
                        {leaderboard.length > 3 && leaderboard.slice(3).map((e, index)=><LeaderboardRow place={e.rank_label} key={e.user.id} index={index} player={auth.user.id == e.user.id} user={e.user} points={e.total_score ?? 0} /> )}
                        {leaderboard.length <= 0 && <InfoBanner text={"Siin võistlusel ei ole (veel) kedagi."} />}</>}
                    </div>
    
                    {/* Teine tulp */}
                    <div>
                        <div className="section" style={{position:"relative", padding:"8px 16px", display:"flex", flexDirection:"row", justifyContent:"space-between", alignItems:"center"}}>
                            <VerticalStatTile padding="8px 0" marginBlock={0} icon="info" text="Võistluse info" customValue={true} value={
                                <div style={{color:"var(--grey-color)"}}>

                                    <p><b>{competition.name}</b></p>

                                    <p>{competition.description ?? "Kirjeldust pole lisatud"}</p>

                                    <p><b>Algusaeg:</b> {formatDateTime(competition.dt_start)}</p>
                                    <p><b>Lõpuaeg:</b> {formatDateTime(competition.dt_end)}</p>
                                    <p><b>Lubatud mängukordi:</b> {competition.attempt_count == 0 ? "Piiramatu" : competition.attempt_count} {!competition.active || competition.attempt_count == 0 ? "" : "(" + Math.max(0, attemptsLeft) + " mängukord"+(attemptsLeft == 1 ? "" : "a")+" jäänud)"}</p>
                                    <span><b>Mängutüübid: </b></span> {JSON.parse(competition.game_data)["mis"].split(",").map(e=><Chip key={e} label={formattedName(e)} />)}

                                </div>
                            } />
                        </div> 
                        {auth.user.role == "guest" && <InfoBanner text={"Külaliskontoga ei saa võistlustel osaleda. Palun loo võistlemiseks endale konto"} />}

                        {Date.now() < (new Date(competition.dt_end.replace(/-/g, "/"))) && attemptsLeft == 0 && <InfoBanner text={"Oled kõik lubatud mängukorrad sel võistlusel ära kasutanud. Nüüd jääb vaid üle tulemusi oodata!"} />}
                        {Date.now() < (new Date(competition.dt_start.replace(/-/g, "/"))) && <InfoBanner text={"See võistlus pole veel alanud. Tule siia tagasi " + formatDateTime(competition.dt_start)} />}
                        {competition.active && participants.filter((e)=>e.id==auth.user.id).length > 0 && <div className="two-button-layout">
                            <div onClick={leaveCompetition} className="section clickable" style={{padding:"16px", display:"flex", justifyContent:"start", alignItems:"center", marginBlock:"8px"}}>
                                <div>
                                    <i translate="no" style={{fontSize:"32px"}} className="material-icons-outlined">door_open</i>
                                    <p style={{marginTop:"8px", marginBottom:"0"}}>Lahku võistluselt</p>
                                </div>
                            </div>
                            <BigButton disabled={!(participants.filter((e)=>e.id==auth.user.id).length > 0 && attemptsLeft != 0 && competition.active)} onClick={()=>window.location.href = "/preview/competition/"+competition.competition_id} title={"Alusta võistlemist!"} subtitle={"Liigu eelvaatesse"}/>
                        </div>}

                        {auth.user.role != "guest" && participants.filter((e)=>e.id==auth.user.id).length == 0 && Date.now() < (new Date(competition.dt_end.replace(/-/g, "/"))) && <BigButton onClick={joinCompetition} title="Liitu võistlusega" subtitle={competition.name} />}

                        {auth.user.role.split(",").includes("admin") && <div className="two-button-layout">
                            <div onClick={()=>window.location.href = "/competition/"+competition.competition_id+"/participants/add"} className="section clickable" style={{padding:"16px", display:"flex", justifyContent:"start", alignItems:"center", marginBlock:"8px"}}>
                                <div>
                                    <i translate="no" style={{fontSize:"32px"}} className="material-icons-outlined">person</i>
                                    <p style={{marginTop:"8px", marginBottom:"0"}}>Lisa võistlejaid</p>
                                </div>
                            </div>

                            <div onClick={()=>window.location.href = "/competition/"+competition.competition_id+"/edit"} className="section clickable" style={{padding:"16px", display:"flex", justifyContent:"start", alignItems:"center", marginBlock:"8px"}}>
                                <div>
                                    <i translate="no" style={{fontSize:"32px"}} className="material-icons-outlined">edit</i>
                                    <p style={{marginTop:"8px", marginBottom:"0"}}>Muuda võistlust</p>
                                </div>
                            </div>

                        </div>}
                    </div>
                </div>
            </Layout>
}