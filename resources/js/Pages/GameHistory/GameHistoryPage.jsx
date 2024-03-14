import GameTile from "@/Components/GameTile";
import Navbar from "@/Components/Navbar";
import NavigatePagesButton from "@/Components/NavigatePagesButton";
import SizedBox from "@/Components/SizedBox";
import StatisticsWidget from "@/Components/StatisticsWidget";
import { Head } from "@inertiajs/react";

export default function GameHistoryPage({auth, games, stats}){

    function averageTime(timeInSeconds){
        var minutes = Math.floor(timeInSeconds / 60);
        var seconds = timeInSeconds - 60*minutes;

        minutes = minutes <= 9 ? "0"+minutes.toString() : minutes.toString();
        seconds = seconds <= 9 ? "0"+seconds.toString() : seconds.toString();

        return minutes + ":" + seconds;
    }

    return <>
            <Head title="Mängude ajalugu" />
            <Navbar title="Mängude ajalugu" user={auth.user} />
            <SizedBox height={36} />

            <h2>Mängude ajalugu</h2>

            <section>
                <div className="history-statistics">
                    <StatisticsWidget stat={stats.total_training_count} desc={"Mängu"} />
                    <StatisticsWidget stat={stats.accuracy + "%"} desc={"Keskmine täpsus"} />
                    <StatisticsWidget stat={averageTime(stats.average_time)} desc={"Keskmine aeg"} />
                </div>
            </section>

            <section>
                {games.data.map((e)=><GameTile data={e} key={e.game_id} />)}

                {/* {games.data.map((e)=><p key={e.game_id} >{e.game_count} tehet ({(new Date(e.dt)).toLocaleString("et-EE").split(",")[0]})</p>)} */}
                {games.data.length <= 0 && <p>Tingimustele vastavaid tulemusi ei leitud</p>}
            </section>

            <section>
                <p style={{color:"grey"}}>Navigeeri lehel</p>
                {games.links.map((e)=>e.label != "&laquo; Previous" && e.label != "Next &raquo;" && <NavigatePagesButton data={e} key={e.label} />)}

                {/* {games.links.map((e)=>e.label != "&laquo; Previous" && e.label != "Next &raquo;" && <a style={{border: e.active ? "2px solid rgb(var(--primary-color))" : "", borderRadius:"4px", marginInline:"4px"}} href={e.url} alone="" className="no-anim" key={e.label}>{e.label.replace("&laquo; Previous", "Eelmine").replace("Next &raquo;", "Järgmine")}</a>)} */}
            </section>

    </>;
}