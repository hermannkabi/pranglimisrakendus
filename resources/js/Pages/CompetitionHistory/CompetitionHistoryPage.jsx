import Layout from "@/Components/2024SummerRedesign/Layout";
import StatisticsTile from "@/Components/2024SummerRedesign/StatisticsTile";
import CompetitionTile from "@/Components/CompetitionTile";
import InfoBanner from "@/Components/InfoBanner";
import NavigatePagesButton from "@/Components/NavigatePagesButton";
import SizedBox from "@/Components/SizedBox";

export default function CompetitionHistoryPage({auth, competitions, stats, user}){    

    return <>
        <Layout title="Võistluste ajalugu" auth={auth}>
            <div className="four-stat-row">
                <StatisticsTile stat={stats.competitionCount ?? "0"} label={"Võistlust"} oneLabel={"Võistlus"} icon={"joystick"} />
                <StatisticsTile stat={(stats.bestRank == null ? "-" : (stats.bestRank.rank + ".") )} label={"Parim koht"} icon={"leaderboard"} />
                <StatisticsTile stat={stats.gamesCount} label={"Võistlusmängu"} oneLabel={"Võistlusmäng"} icon={"stadia_controller"} />
                <StatisticsTile stat={stats.competitionPoints ?? "0"} label={"Võistluspunkti"} oneLabel={"Võistluspunkt"} icon={"trophy"} compactNumber={true} />
            </div>
            <SizedBox height="16px" />
            <div className="two-column-layout">
                <div>
                    {competitions.data.map((e)=><CompetitionTile data={e} user={user} key={e.competition_id} />)}
                    {competitions.data.length <= 0 && <InfoBanner text="Lõpetatud võistluseid ei leitud. Aeg oma oskused teiste vastu proovile panna!" />}
                </div>

                <div>
                    {/* You may ask - why 3? */}
                    {/* Because Laravel gives the previous and next page links as well as the page 1 link (so 3 in total) */}
                    {competitions.links.length > 3 && <div className="section">
                        <SizedBox height="8px" />
                        <i translate="no" className="material-icons-outlined">explore</i>
                        <p style={{marginTop:"4px"}}>Navigeeri lehel</p>
                        {competitions.links.map((e)=>e.label != "&laquo; Previous" && e.label != "Next &raquo;" && <NavigatePagesButton data={e} key={e.label} />)}
                    </div>}
                </div>
            </div>
        </Layout>
    </>;
}