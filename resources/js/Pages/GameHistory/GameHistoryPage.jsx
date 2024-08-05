import Layout from "@/Components/2024SummerRedesign/Layout";
import StatisticsTile from "@/Components/2024SummerRedesign/StatisticsTile";
import GameTile from "@/Components/GameTile";
import HorizontalInfoBanner from "@/Components/HorizontalInfoBanner";
import InfoBanner from "@/Components/InfoBanner";
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
        <Layout title="Mängude ajalugu">
            <div className="four-stat-row">
                <StatisticsTile stat={stats.total_training_count} label={"Mängu"} oneLabel={"Mäng"} icon={"sports_esports"} />
                <StatisticsTile stat={(stats.accuracy ?? "0") + "%"} label={"Vastamistäpsus"}icon={"percent"} />
                <StatisticsTile stat={averageTime(stats.average_time)} label={"Keskmine aeg"} icon={"hourglass_top"} />
                <StatisticsTile stat={stats.points ?? "0"} label={"Punkti kokku"} oneLabel={"Punkt kokku"} icon={"trophy"} compactNumber={true} />
            </div>
            <SizedBox height="16px" />
            <div className="two-column-layout">
                <div>
                    {games.data.map((e)=><GameTile data={e} key={e.game_id} />)}
                    {games.data.length <= 0 && <InfoBanner text="Mänge ei leitud. Aeg natuke peastarvutamisega tegeleda!" />}
                </div>

                <div>
                    {/* You may ask - why 3? */}
                    {/* Because Laravel gives the previous and next page links as well as the page 1 link (so 3 in total) */}
                    {games.links.length > 3 && <div className="section">
                        <SizedBox height="8px" />
                        <i className="material-icons-outlined">explore</i>
                        <p style={{marginTop:"4px"}}>Navigeeri lehel</p>
                        {games.links.map((e)=>e.label != "&laquo; Previous" && e.label != "Next &raquo;" && <NavigatePagesButton data={e} key={e.label} />)}
                    </div>}
                </div>
            </div>
        </Layout>
    </>;

    return <>


            <section>
            </section>

            

    </>;
}