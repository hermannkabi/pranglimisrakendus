import SizedBox from "@/Components/SizedBox";
import "/public/css/dashboard.css";
import InfoBanner from "@/Components/InfoBanner";
import Layout from "@/Components/2024SummerRedesign/Layout";
import StatisticsTile from "@/Components/2024SummerRedesign/StatisticsTile";
import TwoRowTextButton from "@/Components/2024SummerRedesign/TwoRowTextButton";
import DashboardLeaderboardWidget from "@/Components/2024SummerRedesign/DashboardLeaderboardWidget";
import DashboardClassStatTile from "@/Components/2024SummerRedesign/DashboardClassStatTile";
import VerticalStatTile from "@/Components/2024SummerRedesign/VerticalStatTile";
import ClassWidget from "@/Components/2024SummerRedesign/ClassWidget";
import StreakWidget from "@/Components/2024SummerRedesign/StreakWidget";

export default function Dashboard({auth, stats, classData, competitionData, teacherData, psa=null}) {


    const totalTrainingCount = window.localStorage.getItem("total-training-count") ?? "0";

    console.log(stats);
    

    Mousetrap.bind("c h r i s e t t e", function (){
        $(".easteregg1").fadeIn(50, function (){
            setTimeout(() => {
                $(".easteregg1").fadeOut(50);
            }, 500);
        });
    });
    

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

    function truncateChars(text, charLimit = 200) {
        if (text.length <= charLimit) return text;
        return text.slice(0, charLimit).trimEnd() + '...';
    }

    function getRelativeTime(date) {
        const now = new Date();
        const diffInSeconds = Math.floor((new Date(date.replace(/-/g, "/")) - now) / 1000);

        const rtf = new Intl.RelativeTimeFormat('et', { numeric: 'auto' });

        const divisions = [
            { amount: 60, unit: 'second' },
            { amount: 60, unit: 'minute' },
            { amount: 24, unit: 'hour' },
            { amount: 7, unit: 'day' },
            { amount: 4.34524, unit: 'week' },
            { amount: 12, unit: 'month' },
            { amount: Number.POSITIVE_INFINITY, unit: 'year' }
        ];

        let duration = diffInSeconds;
        for (const { amount, unit } of divisions) {
            if (Math.abs(duration) < amount) {
            return rtf.format(Math.round(duration), unit);
            }
            duration /= amount;
        }
    }


    return (
        <>
            <Layout title="Töölaud" auth={auth}>
                <img className="easteregg1" style={{position:"fixed", top:"0", left:"0", display:"none", zIndex:"1000", width:"100%"}} src="/assets/eastereggs/chrisette2.jpg" alt="" />
                {(new URLSearchParams(window.location.search)).get("verified") != null && <div style={{marginBottom:"16px"}} className="section">
                    <InfoBanner type="success" text={"Sinu e-posti aadress on kinnitatud!"} />
                </div>}                
                {psa && <InfoBanner text={psa} />}
                {auth.user.role != "guest" && <div className="four-stat-row">
                    <StreakWidget streak={stats.streak} active={stats.streak_active} />
                    <StatisticsTile stat={stats.total_training_count ?? totalTrainingCount} label={"Mängu"} oneLabel={"Mäng"} icon={"sports_esports"} />
                    <StatisticsTile stat={(stats.accuracy ??(parseInt(window.localStorage.getItem("total-percentage") ?? "0")/parseInt(window.localStorage.getItem("total-training-count") ?? "1")).toFixed(0)) + "%"} label={"Vastamistäpsus"}icon={"percent"} />
                    <a style={{textDecoration:"none", filter:"none", cursor:"pointer"}} className="clickable" href={route("stats")}><StatisticsTile clickable={true} stat={"→"} label={"Kogu statistika"} icon={"query_stats"} /></a>
                </div>}

                {auth.user.role.split(",").includes("admin") && <> <SizedBox height={16} /> <div className="two-column-layout">
                    <div className="section clickable" style={{padding:"8px 16px", display:"flex", flexDirection:"row", justifyContent:"space-between", alignItems:"center", position:"relative"}}>
                        <VerticalStatTile padding="8px 0" marginBlock={0} icon="shield" text="Õpilaste & klasside haldamine" value={"Reaalerit kasutab " + stats.total_students + " õpilast ja " + stats.total_classes + " klassi"} />
                    
                        <a href={route("admin")} style={{all:"unset", position:"absolute", top:"0", left:"0", height:"100%", width:"100%"}}></a>
                    </div>

                    <div className="section clickable" style={{padding:"8px 16px", display:"flex", flexDirection:"row", justifyContent:"space-between", alignItems:"center", position:"relative"}}>
                        <VerticalStatTile padding="8px 0" marginBlock={0} icon="shield" text="Võistluste haldamine" value={"Reaaleris on toimunud " + stats.total_competitions + " võistlus" + (stats.total_competitions == 1 ? "" : "t")} />
                    
                        <a href={route("manageCompetitions")} style={{all:"unset", position:"absolute", top:"0", left:"0", height:"100%", width:"100%"}}></a>
                    </div>
                </div></>}

                {/* Teacher things start here */}
                {auth.user.role.includes("teacher") && !auth.user.email_verified_at && <div className="section" style={{marginBlock:"16px"}}><InfoBanner type="error" text="Õpetajale lubatud toimingute (nt klasside loomine) tegemiseks palun kinnita profiilivaates e-posti aadress" /></div> }

                {auth.user.role.includes("teacher") && auth.user.email_verified_at && teacherData.length > 0 && <div className="two-column-layout" style={{marginTop:"16px"}}>
                        <div className="section clickable" style={{marginBlock:"0", position:"relative"}}>
                            <TwoRowTextButton upperText="Minu klassid" lowerText="Uus klass" showArrow={window.innerWidth > 600} />
                            <SizedBox height="32px" />
                            <div style={{margin:"8px"}}>
                                <h2 style={{color:"rgb(var(--primary-color))", fontSize:"56px", marginBlock:"0"}}>{teacherData.length}</h2>
                                <p style={{color:"var(--grey-color)", marginBlock:"0"}}>Klassi kokku</p>
                            </div>
                            <SizedBox height="16px" />
                            <a href={route("newClass")} style={{all:"unset", position:"absolute", height:"100%", width:"100%", top:'0', left:"0"}}></a>
                        </div>

                    <div style={{display:"grid", gridTemplateRows:"repeat(2, 1fr)", gridTemplateColumns:"repeat(2, 1fr)", gap:"16px"}}>
                        {teacherData.slice(0, (teacherData.length == 4 ? 4 : 3)).map((e)=>{return <ClassWidget key={e.uuid} klass={e} /> })}
                        {teacherData.length > 4 && <div style={{position:'relative', marginBlock:"0", display:'flex', alignItems:'center'}} className="section clickable">
                            <TwoRowTextButton upperText={"Minu klassid"} lowerText={"Vaata kõiki"} showArrow={window.innerWidth > 600} />

                            <a href={route("classAll")} style={{all:"unset", position:"absolute", top:"0", left:"0", height:"100%", width:"100%"}}></a>
                        </div>}
                    </div>
                </div>}

                {auth.user.role.includes("teacher") && auth.user.email_verified_at && teacherData.length <= 0 && <a style={{all:"unset"}} href={route("newClass")}><div className="section clickable" style={{marginBlock:"0", marginTop:"16px"}}>
                    <TwoRowTextButton upperText="Klasse pole veel" lowerText="Uus klass" />
                    <p style={{marginInline:"8px", color:"var(--grey-color)", maxWidth:"max(50%, 300px)"}}>Sul ei ole Reaaleris veel ühtegi klassi. Selleks, et oma õpilaste tegemistel Reaaleris silma peal hoida, loo neile klass.</p>
                </div> </a>}


                {/* Guest and class data start here */}
                {auth.user.role == "guest" && <InfoBanner text="Külaliskontoga andmeid ei salvestata ja statistikat näha ei saa. Selleks palun loo endale konto" />}
                <SizedBox height="16px" />
                {auth.user.klass == null && teacherData == null && <a style={{all:"unset"}} href={route("classJoin")}><div className="section clickable" style={{marginBlock:"0", marginBottom:"16px"}}>
                    <TwoRowTextButton upperText="Liitu klassiga" lowerText="Otsi klassi" />
                    <p style={{marginInline:"8px", color:"var(--grey-color)", maxWidth:"max(50%, 300px)"}}>Reaaleri kogemus ei ole täielik, kui sa ei ole klassiga liitunud. Küsi klassi andmeid enda õpetajalt ja naudi teiste klassikaaslastega lõbusat võistlemist ja koos arenemist!</p>
                </div> </a>}
                {auth.user.klass != null && teacherData == null && classData != null && <><div className="class-grid">
                    {/* Class overview */}
                    <div style={{position:"relative"}} className="section clickable">
                        <div style={{display:"grid", gridTemplateColumns:"repeat(2, 1fr)"}}>
                            <div>
                                <TwoRowTextButton upperText="Minu klass" lowerText={classData.name} showArrow={window.innerWidth > 600 && !(window.innerWidth > 1000 && window.innerWidth < 1300)} />
                                <SizedBox height="32px" />
                                <div style={{margin:"8px"}}>
                                    <h2 style={{color:"rgb(var(--primary-color))", fontSize:"56px", marginBlock:"0"}}>{classData.myPlace + (classData.myPlace.startsWith("T") ? "" : ".")}</h2>
                                    <p style={{color:"var(--grey-color)", marginBlock:"0"}}>Koht klassis</p>
                                </div>
                            </div>
                            <div>
                                {[0, 1, 2].map(e=>classData.threeBest[e] != null && <DashboardLeaderboardWidget key={e} auth={auth} user={classData.threeBest[e].user} place={classData.threeBest[e].place} />)}
                                {!["T1", "1", "T2", "2", "T3", "3", "T4", "4"].includes(classData.myPlace) && <i translate="no" style={{color:"var(--grey-color)", fontSize:"28px", marginTop:"8px", marginBottom:"0"}} className="material-icons">unfold_more</i>}
                                {!["T1", "1", "T2", "2", "T3", "3"].includes(classData.myPlace) && <DashboardLeaderboardWidget auth={auth} user={auth.user} place={classData.myPlace} />}
                            </div>
                        </div>
                        <a href={"/classroom/"+classData.uuid+"/view"} style={{all:"unset", position:"absolute", height:"100%", width:"100%", top:'0', left:"0"}}></a>
                    </div>
                    <DashboardClassStatTile icon="person" text="Õpilasi kokku" value={classData.studentsCount} />
                    <DashboardClassStatTile icon="monitoring" text="XP kokku" value={Intl.NumberFormat('en', { notation: 'compact' }).format(classData.pointsCount).replace(".", ",")} />
                    <div onClick={()=>window.location.href = "/profile/"+classData.teacher[0].id} className="section clickable" style={{padding:"8px 16px", display:"flex", flexDirection:"row", justifyContent:"space-between", alignItems:"center", position:"relative"}}>
                        <VerticalStatTile padding="8px 0" marginBlock={0} capitalize={true} icon="school" text="Õpetaja" value={classData.teacher[0].eesnimi + " " + classData.teacher[0].perenimi} />

                        <img src={classData.teacher[0].profile_pic} style={{height:"75px", aspectRatio:"1", borderRadius:"50%", objectFit:"cover"}} />
                    
                        <a href={"/profile/"+classData.teacher[0].id} style={{all:"unset", position:"absolute", top:"0", left:"0", height:"100%", width:"100%"}}></a>
                    </div>
                </div><SizedBox height="16px" /></>}

                {competitionData && <><div className="class-grid competition-grid" style={{}}>
                    {/* Competition overview */}
                    <div style={{position:"relative"}} className="section clickable">
                        <div style={{display:"grid", gridTemplateColumns:window.innerWidth > 800 ? "repeat(2,1fr)" : "repeat(1, 1fr)"}}>
                            <div>
                                <TwoRowTextButton isActive={competitionData.competition.active} upperText={competitionData.competition.active ? "Käimasolev võistlus" : Date.now() < (new Date(competitionData.competition.dt_start.replace(/-/g, "/"))) ? "Tulevane võistlus" : "Eelmine võistlus"} lowerText={competitionData.competition.name} showArrow={window.innerWidth > 600 && !(window.innerWidth > 1000 && window.innerWidth < 1300)} />
                                <SizedBox height="32px" />
                                <div style={{margin:"8px"}}>
                                    {!(competitionData.competition.active || Date.now() < (new Date(competitionData.competition.dt_start.replace(/-/g, "/")))) && <h2 style={{color:"rgb(var(--primary-color))", fontSize:"56px", marginBlock:"0"}}>{competitionData.myPlace + "."} <span style={{color:"var(--grey-color)", marginBlock:"0", fontSize:"24px", fontWeight:"normal"}}>koht</span></h2>}
                                    {(competitionData.competition.active) && window.innerWidth > 600 && <div style={{display:"flex", alignItems:'center', gap:"8px"}}>
                                        <div>
                                            <h2 style={{color:"rgb(var(--primary-color))", fontSize:"48px", marginBlock:"0"}}>{competitionData.attemptsLeft == 0 ? "Vaata" : "Võistle"}</h2>
                                            <SizedBox height="8px" />
                                            <p style={{color:"var(--grey-color)", marginBlock:"0"}}>Võistluse lehele</p>
                                        </div>
                                        <i translate="no" style={{fontSize:"48px", color:"var(--lightgrey-color)"}} className="material-icons">arrow_forward_ios</i>
                                    </div>}
                                </div>
                            </div>
                            {!(competitionData.competition.active || Date.now() < (new Date(competitionData.competition.dt_start.replace(/-/g, "/")))) && <div>
                                {[0, 1, 2].map(e=>competitionData.threeBest[e] != null && <DashboardLeaderboardWidget key={e} auth={auth} user={competitionData.threeBest[e].user} place={competitionData.threeBest[e].rank_label} />)}
                                {!["T1", "1", "T2", "2", "T3", "3", "T4", "4"].includes(competitionData.myPlace) && <i translate="no" style={{color:"var(--grey-color)", fontSize:"28px", marginTop:"8px", marginBottom:"0"}} className="material-icons">unfold_more</i>}
                                {!["T1", "1", "T2", "2", "T3", "3"].includes(competitionData.myPlace) && <DashboardLeaderboardWidget auth={auth} user={auth.user} place={competitionData.myPlace} />}

                                <p style={{color:"var(--grey-color)"}}>Kokku {competitionData.totalParticipants} võistleja{competitionData.totalParticipants == 1 ? "" : "t"}</p>
                            </div>}

                            {(competitionData.competition.active || Date.now() < (new Date(competitionData.competition.dt_start.replace(/-/g, "/")))) && <div>
                                <p>{truncateChars(competitionData.competition.description ?? "Kirjeldust pole lisatud")}</p>
                                {competitionData.competition.active && <p><b>Võistlus lõppeb:</b> {getRelativeTime(competitionData.competition.dt_end)} ({formatDateTime(competitionData.competition.dt_end)})</p>}
                                {Date.now() < (new Date(competitionData.competition.dt_start.replace(/-/g, "/"))) && <p><b>Võistlus algab:</b> {getRelativeTime(competitionData.competition.dt_start)} ({formatDateTime(competitionData.competition.dt_start)})</p>}
                                {competitionData.attemptsLeft != -1 && competitionData.attemptsLeft != 0 && <p>Sul on jäänud veel <b>{competitionData.attemptsLeft}</b> mängukord{competitionData.attemptsLeft == 1 ? "" : "a"}</p>}
                                { competitionData.attemptsLeft == 0 && <p>Oled kõik mängukorrad ära kasutanud</p>}
                                
                                {competitionData.attemptsLeft == -1 && <p>Sellel võistlusel on <b>piiramatu</b> arv mängukordi</p>}

                            </div>}
                        </div>
                        <a href={"/competition/"+competitionData.competition.competition_id+"/view"} style={{all:"unset", position:"absolute", height:"100%", width:"100%", top:'0', left:"0"}}></a>
                    </div>
                    <div className="section class-stat">
                        <DashboardClassStatTile icon="social_leaderboard" text="Osalemisi kokku" value={competitionData.totalCompetitions} />
                    </div>
                    <div className={"section class-stat " + (competitionData.bestRank == null ? "" : "clickable")} style={{position:"relative"}}>
                        <DashboardClassStatTile icon="trophy" text="Parim tulemus" value={competitionData.bestRank == null ? "-" : competitionData.bestRank.rank + "."} />
                        <a style={{all:"unset", position:"absolute", top:"0", left:"0", height:"100%", width:"100%"}} disabled={competitionData.bestRank == null} href={competitionData.bestRank == null ? null : "/competition/"+competitionData.bestRank.competition_id+"/view"}></a>
                    </div>
                    <div onClick={()=>window.location.href = "/profile/"+classData.teacher[0].id} className="section clickable" style={{padding:"8px 16px", display:"flex", flexDirection:"row", justifyContent:"space-between", alignItems:"center", position:"relative"}}>
                        <VerticalStatTile padding="8px 0" marginBlock={0} icon="calendar_month" text="Otsi võistluseid" value={competitionData.nextCompetition ? "Sinu järgmine võistlus algab " + getRelativeTime(competitionData.nextCompetition.dt_start) : "Otsi endale veel võistlusi"} />
                    
                        <a href={route("competitionsView")} style={{all:"unset", position:"absolute", top:"0", left:"0", height:"100%", width:"100%"}}></a>
                    </div>
                </div><SizedBox height="16px" /></>}

                {competitionData == null && <a style={{all:"unset"}} href={route("competitionsView")}><div className="section clickable" style={{marginBlock:"0", marginBottom:"16px"}}>
                    <TwoRowTextButton upperText="Hakka võistlema" lowerText="Otsi võistluseid" />
                    <p style={{marginInline:"8px", color:"var(--grey-color)", maxWidth:"max(50%, 300px)"}}>Efektiivsemaks ja lõbusamaks arenemiseks proovi kindlasti Reaaleri võistluseid, kus saad teistega sõbralikult mõõtu võtta.</p>
                </div> </a>}
                
                <div className="two-column-layout">
                    <a style={{all:"unset"}} href={route('gameHistory')}>
                        <div className="section clickable" style={{marginBlock:"0", display:"flex", flexDirection:"row", justifyContent:"space-between", alignItems:"center", paddingRight:"24px"}}>
                            <TwoRowTextButton upperText="Varasemad mängud" lowerText="Vaata kõiki" />
                            <i style={{fontSize:"48px"}} className="material-icons-outlined">stadia_controller</i>
                        </div>
                    </a>

                    <a style={{all:"unset"}} href={route('competitionHistory')}>
                        <div className="section clickable" style={{marginBlock:"0", display:"flex", flexDirection:"row", justifyContent:"space-between", alignItems:"center", paddingRight:"24px"}}>
                            <TwoRowTextButton upperText="Varasemad võistlused" lowerText="Vaata kõiki" />
                            <i style={{fontSize:"48px"}} className="material-icons-outlined">leaderboard</i>
                        </div>
                    </a>
                    
                </div>
                
            </Layout>
        </>
    );
}