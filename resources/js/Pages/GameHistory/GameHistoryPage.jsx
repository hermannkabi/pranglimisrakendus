import Navbar from "@/Components/Navbar";
import SizedBox from "@/Components/SizedBox";
import StatisticsWidget from "@/Components/StatisticsWidget";
import { Head } from "@inertiajs/react";

export default function GameHistoryPage({auth, games}){

    console.log(games);

    return <>
            <Head title="Mängude ajalugu" />
            <Navbar title="Mängude ajalugu" user={auth.user} />
            <SizedBox height={36} />

            <h2>Mängude ajalugu</h2>

            {/* DEMO section NOT real*/}
            <section>
                <div style={{display:"grid", gridTemplateColumns:"repeat(3, 1fr)", overflow:"scroll"}}>
                    <StatisticsWidget stat={27} desc={"Mängu"} />
                    <StatisticsWidget stat={"87%"} desc={"Keskmine täpsus"} />
                    <StatisticsWidget stat={"01:30"} desc={"Keskmine aeg"} />
                </div>
            </section>

            <section>
                {games.data.map((e)=><p key={e.game_id} >{e.game_count} tehet ({(new Date(e.dt)).toLocaleString("et-EE").split(",")[0]})</p>)}
                {games.data.length <= 0 && <p>Tingimustele vastavaid tulemusi ei leitud</p>}
            </section>

            <section>
                <p style={{color:"grey"}}>Navigeeri lehel</p>
                {games.links.map((e)=>e.label != "&laquo; Previous" && e.label != "Next &raquo;" && <a style={{border: e.active ? "2px solid rgb(var(--primary-color))" : "", borderRadius:"4px", marginInline:"4px"}} href={e.url} alone="" className="no-anim" key={e.label}>{e.label.replace("&laquo; Previous", "Eelmine").replace("Next &raquo;", "Järgmine")}</a>)}
            </section>

    </>;
}