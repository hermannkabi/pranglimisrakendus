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

export default function Dashboard({auth, stats, classData, teacherData}) {


    const totalTrainingCount = window.localStorage.getItem("total-training-count") ?? "0";

    Mousetrap.bind("c h r i s e t t e", function (){
        $(".easteregg1").fadeIn(50, function (){
            setTimeout(() => {
                $(".easteregg1").fadeOut(50);
            }, 500);
        });
    });


    return (
        <>
            <Layout title="Töölaud" auth={auth}>
                <img className="easteregg1" style={{position:"fixed", top:"0", left:"0", display:"none", zIndex:"1000", width:"100%"}} src="/assets/eastereggs/chrisette2.jpg" alt="" />
                {(new URLSearchParams(window.location.search)).get("verified") != null && <div style={{marginBottom:"16px"}} className="section">
                    <InfoBanner type="success" text={"Sinu e-posti aadress on kinnitatud!"} />
                </div>}
                <div className="section">
                    <div style={{marginBottom:"16px", display:"flex", justifyContent:"space-between"}}>

                        <InfoBanner>
                            <p>Tere tulemast uude Reaalerisse! Palun anna meile tagasisidet <a alone="" href="https://forms.gle/iQWEqL8GBZLJFJom8">siin</a></p>
                        </InfoBanner>

                        {auth.user.role.includes("admin") && <a href={route("admin")} style={{all:"unset", cursor:"pointer", display:"inline-flex", flexDirection:"column", justifyContent:"center"}}>
                            <div className="section clickable">
                                <TwoRowTextButton upperText={"Admin"} lowerText={"Vaata edasi"} />
                            </div>
                        </a>}
                    </div>
                </div>
                {auth.user.role != "guest" && <div className="four-stat-row">
                    <StreakWidget streak={stats.streak} active={stats.streak_active} />
                    <StatisticsTile stat={stats.total_training_count ?? totalTrainingCount} label={"Mängu"} oneLabel={"Mäng"} icon={"sports_esports"} />
                    <StatisticsTile stat={(stats.accuracy ??(parseInt(window.localStorage.getItem("total-percentage") ?? "0")/parseInt(window.localStorage.getItem("total-training-count") ?? "1")).toFixed(0)) + "%"} label={"Vastamistäpsus"}icon={"percent"} />
                    <StatisticsTile stat={stats.points ?? window.localStorage.getItem("total-points") ?? "0"} label={"Punkti kokku"} oneLabel={"Punkt kokku"} icon={"trophy"} compactNumber={true} />
                </div>}

                {/* Teacher things start here */}
                {auth.user.role.includes("teacher") && !auth.user.email_verified_at && <div className="section" style={{marginBlock:"16px"}}><InfoBanner type="error" text="Õpetajale lubatud toimingute (nt klasside loomine) tegemiseks palun kinnita profiilivaates e-posti aadress" /></div> }

                {auth.user.role.includes("teacher") && auth.user.email_verified_at && teacherData.length > 0 && <div className="two-column-layout" style={{marginTop:"16px"}}>
                    <div>
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
                    <DashboardClassStatTile icon="monitoring" text="XP kokku" value={Intl.NumberFormat('en', { notation: 'compact' }).format(classData.pointsCount)} />
                    <div onClick={()=>window.location.href = "/profile/"+classData.teacher[0].id} className="section clickable" style={{padding:"8px 16px", display:"flex", flexDirection:"row", justifyContent:"space-between", alignItems:"center", position:"relative"}}>
                        <VerticalStatTile padding="8px 0" marginBlock={0} capitalize={true} icon="school" text="Õpetaja" value={classData.teacher[0].eesnimi + " " + classData.teacher[0].perenimi} />

                        <img src={classData.teacher[0].profile_pic} style={{height:"75px", aspectRatio:"1", borderRadius:"50%", objectFit:"cover"}} />
                    
                        <a href={"/profile/"+classData.teacher[0].id} style={{all:"unset", position:"absolute", top:"0", left:"0", height:"100%", width:"100%"}}></a>
                    </div>
                </div><SizedBox height="16px" /></>}
                
                <div className="two-column-layout">
                    <a style={{all:"unset"}} href={route('gameHistory')}>
                        <div className="section clickable" style={{marginBlock:"0"}}>
                            <TwoRowTextButton upperText="Varasemad mängud" lowerText="Vaata kõiki" />
                        </div>
                    </a>

                    <a style={{all:"unset"}} href={route('profilePage')}>
                        <div className="section clickable" style={{display:"flex", flexDirection:'row', justifyContent:"space-between", alignItems:'center', marginBlock:"0"}}>
                            <TwoRowTextButton capitalizeLower={true} upperText="Minu konto" lowerText={auth.user.eesnimi + " " + auth.user.perenimi} />
                            <img src={auth.user.profile_pic} style={{height:"50px", aspectRatio:"1", borderRadius:"50%", objectFit:"cover"}} />
                        </div>
                    </a>
                    
                </div>
                
            </Layout>
        </>
    );
}