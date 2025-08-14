import BigButton from "@/Components/2024SummerRedesign/BigButton";
import Layout from "@/Components/2024SummerRedesign/Layout";
import StatisticsTile from "@/Components/2024SummerRedesign/StatisticsTile";
import TwoRowTextButton from "@/Components/2024SummerRedesign/TwoRowTextButton";
import VerticalStatTile from "@/Components/2024SummerRedesign/VerticalStatTile";
import GameTile from "@/Components/GameTile";
import InfoBanner from "@/Components/InfoBanner";
import SizedBox from "@/Components/SizedBox";

export default function CompetitionProfilePage({auth, user, competition, stats, games}){

    function averageTime(timeInSeconds){
        var minutes = Math.floor(timeInSeconds / 60);
        var seconds = timeInSeconds - 60*minutes;

        minutes = minutes <= 9 ? "0"+minutes.toString() : minutes.toString();
        seconds = seconds <= 9 ? "0"+seconds.toString() : seconds.toString();

        return minutes + ":" + seconds;
    }

    function formatDateTime(datetimeStr) {
        const date = new Date(datetimeStr.replace(/-/g, "/"));
        const [datePart, timePart] = date.toLocaleString("et-EE", {
            year: "numeric",
            month: "2-digit",
            day: "2-digit",
            hour: "2-digit",
            minute: "2-digit",
        }).split(", ");
        
        return `${datePart}`;
    }

    console.log(stats.user_rank);
    

    return <>
        <Layout auth={auth} title={"Võistluse profiil"}>
            <div className="four-stat-row">
                <StatisticsTile stat={stats.competitions_count ?? "0"} label={"Võistlusmängu"} oneLabel={"Võistlusmäng"} icon={"joystick"} />
                <StatisticsTile stat={stats.avg_accuracy + "%"} label={"Vastamistäpsus"} icon={"percent"} />
                <StatisticsTile stat={averageTime(stats.avg_time)} label={"Keskmine aeg"} icon={"hourglass_bottom"} />
                <StatisticsTile stat={stats.points_total ?? "0"} label={"Võistluspunkti"} oneLabel={"Võistluspunkt"} icon={"trophy"} compactNumber={true} />
            </div>

            <div className="two-column-layout">
                <div>
                    {games.length <= 0 && <InfoBanner text={user.eesnimi + " ei ole veel sellel võistlusel mängida jõudnud"} /> }
                    {games.length > 0 && games.map(e=><GameTile data={e} key={e.game_id} showPoints={true} />) }
                </div>

                <div>
                    <a style={{all:"unset", cursor:"pointer"}} href={"/profile/"+user.id}>
                        <div className="section clickable" style={{position:"relative", backgroundImage:"url("+user.profile_pic+")", backgroundRepeat:"no-repeat", backgroundSize:"cover", backgroundBlendMode:"soft-light", backgroundPosition:"center"}}>
                            <div style={{position:"absolute", right:"24px", top:"24px",}}>
                                <img src={user.profile_pic} style={{borderRadius:"50%", aspectRatio:'1', height:"100px", objectFit:"cover"}}/>
                            </div>
                            <TwoRowTextButton showArrow={false} capitalizeUpper={true} capitalizeLower={true} upperText={user.eesnimi} lowerText={user.perenimi} />
                            <h2 style={{color:"rgb(var(--primary-color))", fontSize:"56px", marginBlock:"24px 0", marginInline:"8px"}}>{stats.user_rank + "."} <span style={{color:"var(--grey-color)", marginBlock:"0", fontSize:"24px", fontWeight:"normal"}}>koht</span></h2>
                            <SizedBox height="100px" />
                            <p style={{position:"absolute", bottom:"16px", right:"16px", display:"flex", alignItems:'center', marginBlock:"0", color:"var(--grey-color)"}}>Reaaleris alates {(new Date(user.created_at)).toLocaleString("et-EE", {month:"2-digit", day:"2-digit", year:"numeric"}).split(",")[0]}</p>
                        </div>
                    </a>
                    <VerticalStatTile padding="16px" marginBlock={0} icon="info" text="Võistluse info" customValue={true} value={
                        <div style={{color:"var(--grey-color)"}}>

                            <p><b>{competition.name}</b></p>

                            <p>{competition.description ?? "Kirjeldust pole lisatud"}</p>

                            <p><b>Kestus:</b> {formatDateTime(competition.dt_start)}–{formatDateTime(competition.dt_end)}</p>
                            {competition.attempt_count == 0 && <p>Sellel võistlusel oli lubatud piiramatu arv katseid. Arvesse läks kõige parem tulemus.</p> }
                            {competition.attempt_count != 0 && <p>Sellel võistlusel oli lubatud {competition.attempt_count} katse{competition.attempt_count == 1 ? "" : "t"}. Arvesse läheb kõigi mängude punktide summa.</p> }

                        </div>
                    } />

                    <BigButton title={"Võistluse üldvaatesse"} subtitle={competition.name} onClick={()=>window.location.href = "/competition/"+competition.competition_id+"/view"} />
                </div>
            </div>
        </Layout>
    </>;
}