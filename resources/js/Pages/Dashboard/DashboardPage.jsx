import SizedBox from "@/Components/SizedBox";
import StatisticsWidget from "@/Components/StatisticsWidget";
import "/public/css/dashboard.css";
import InfoBanner from "@/Components/InfoBanner";
import HorizontalInfoBanner from "@/Components/HorizontalInfoBanner";
import Layout from "@/Components/2024SummerRedesign/Layout";
import StatisticsTile from "@/Components/2024SummerRedesign/StatisticsTile";
import TwoRowTextButton from "@/Components/2024SummerRedesign/TwoRowTextButton";

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
            <Layout title="Töölaud">
                <img className="easteregg1" style={{position:"fixed", top:"0", left:"0", display:"none", zIndex:"1000", width:"100%"}} src="/assets/eastereggs/chrisette2.jpg" alt="" />
                <div className="section" style={{marginBottom:"16px"}}>
                <InfoBanner>
                    <p>Tere tulemast uude Reaalerisse! Palun anna meile tagasisidet <a alone="" href="https://forms.gle/iQWEqL8GBZLJFJom8">siin</a></p>
                </InfoBanner>
                </div>

                {auth.user.role == "teacher" && !auth.user.email_verified_at && <div className="section" style={{marginBottom:"16px"}}><InfoBanner type="error" text="Õpetajale lubatud toimingute (nt klasside loomine) tegemiseks palun kinnita profiilivaates e-posti aadress" /></div> }
                {(new URLSearchParams(window.location.search)).get("verified") != null && <div style={{marginBottom:"16px"}} className="section">
                    <InfoBanner text={"Sinu e-posti aadress on kinnitatud!"} />
                </div>}
                {auth.user.role != "guest" && <div className="four-stat-row">
                    <StatisticsTile iconColor="#F3AF71" disabled={auth.user.streak_active == 0} stat={stats.streak} label={"Järjestikust päeva"} oneLabel={"Järjestikune päev"} icon={"local_fire_department"} />
                    <StatisticsTile stat={stats.total_training_count ?? totalTrainingCount} label={"Mängu"} oneLabel={"Mäng"} icon={"sports_esports"} />
                    <StatisticsTile stat={(stats.accuracy ??(parseInt(window.localStorage.getItem("total-percentage") ?? "0")/parseInt(window.localStorage.getItem("total-training-count") ?? "1")).toFixed(0)) + "%"} label={"Vastamistäpsus"}icon={"percent"} />
                    <StatisticsTile stat={stats.points ?? window.localStorage.getItem("total-points") ?? "0"} label={"Punkti kokku"} oneLabel={"Punkt kokku"} icon={"trophy"} compactNumber={true} />
                </div>}
                {auth.user.role == "guest" && <InfoBanner text="Külaliskontoga andmeid ei salvestata ja statistikat näha ei saa. Selleks palun loo endale konto" />}
                <SizedBox height="16px" />
                {auth.user.klass == null && teacherData == null && <div onClick={()=>window.location.href=route("classJoin")} className="section clickable">
                    <TwoRowTextButton upperText="Liitu klassiga" lowerText="Otsi klassi" />
                    <p style={{marginInline:"8px", color:"var(--grey-color)", maxWidth:"60%"}}>Reaaleri kogemus ei ole täielik, kui sa ei ole klassiga liitunud. Küsi klassi andmeid enda õpetajalt ja naudi teiste klassikaaslastega lõbusat võistlemist ja koos arenemist!</p>
                </div> }
                {auth.user.klass != null && teacherData == null && classData != null && <div className="class-grid">
                    {/* Class overview */}
                    <div onClick={()=>window.location.href = "/classroom/"+classData.uuid+"/view"} className="section clickable">
                        <div style={{display:"grid", gridTemplateColumns:"repeat(2, 1fr)"}}>
                            <div>
                                <TwoRowTextButton upperText="Minu klass" lowerText={classData.name} showArrow={window.innerWidth > 600} />
                                <SizedBox height="32px" />
                                <div style={{margin:"8px"}}>
                                    <h2 style={{color:"rgb(var(--primary-color))", fontSize:"56px", marginBlock:"0"}}>{classData.myPlace + (classData.myPlace.startsWith("T") ? "" : ".")}</h2>
                                    <p style={{color:"var(--grey-color)", marginBlock:"0"}}>Koht klassis</p>
                                </div>
                            </div>
                            <div>
                                {[0, 1, 2].map(e=>classData.threeBest[e] != null && <div key={e} style={{display:"flex", flexDirection:"row", justifyContent:"start", alignItems:'center', gap:"8px"}}>
                                    <div style={{backgroundColor:classData.threeBest[e].user.id == auth.user.id ? "var(--grey-color)" : "var(--lightgrey-color)", fontSize:"16px", color:"white", borderRadius:"50%", display:"inline-flex", justifyContent:"center", alignItems:'center', height:"28px", aspectRatio:"1"}}> <span>{classData.threeBest[e].place}</span> </div>
                                    <p style={{textTransform:"capitalize", fontWeight: classData.threeBest[e].user.id == auth.user.id ? "bold" : "normal"}}>{classData.threeBest[e].user.eesnimi} {classData.threeBest[e].user.perenimi}</p>
                                </div>)}
                                {!["T1", "1", "T2", "2", "T3", "3", "T4", "4"].includes(classData.myPlace) && <i style={{color:"var(--grey-color)", fontSize:"28px"}} className="material-icons">unfold_more</i>}
                                {!["T1", "1", "T2", "2", "T3", "3"].includes(classData.myPlace) && <div style={{display:"flex", flexDirection:"row", justifyContent:"start", alignItems:'center', gap:"8px"}}>
                                    <div style={{backgroundColor:"var(--grey-color)", fontSize:"16px", color:"white", borderRadius:"50%", display:"inline-flex", justifyContent:"center", alignItems:'center', height:"28px", aspectRatio:"1"}}> <span>{classData.myPlace}</span> </div>
                                    <p style={{textTransform:"capitalize", fontWeight:'bold'}}>{auth.user.eesnimi} {auth.user.perenimi}</p>
                                </div>}
                            </div>
                        </div>
                    </div>
                    <div className="section class-stat" style={{padding:"16px", display:"flex", flexDirection:'row', justifyContent:"space-between", alignItems:'center'}}>
                        <div className="stat-desc">
                            <i className="material-icons-outlined">person</i>
                            <p style={{marginTop:"4px"}}>Õpilasi kokku</p>
                        </div>
                        <h2 style={{color:"rgb(var(--primary-color))", marginBlock:"8px"}}>{classData.studentsCount}</h2>
                    </div>
                    <div className="section class-stat" style={{padding:"16px", display:"flex", flexDirection:'row', justifyContent:"space-between", alignItems:'center'}}>
                        <div className="stat-desc">
                            <i className="material-icons-outlined">monitoring</i>
                            <p style={{marginTop:"4px"}}>XP kokku</p>
                        </div>
                        <h2 style={{color:"rgb(var(--primary-color))", marginBlock:"8px"}}>{Intl.NumberFormat('en', { notation: 'compact' }).format(classData.pointsCount)}</h2>
                    </div>
                    <div onClick={()=>window.location.href = "/profile/"+classData.teacher[0].id} className="section clickable" style={{padding:"8px 16px", display:"flex", flexDirection:"row", justifyContent:"space-between", alignItems:"center"}}>
                        <div>
                            <SizedBox height="8px" />
                            <div>
                                <i style={{fontSize:"32px"}} className="material-icons-outlined">school</i>
                                <p style={{marginTop:"4px"}}>Õpetaja</p>
                            </div>
                            {classData.teacher.length > 0 && <p style={{textTransform:"capitalize", marginBottom:"8px", fontWeight:"bold", color:"var(--lightgrey-color)", fontSize:"24px"}}>{classData.teacher[0].eesnimi} {classData.teacher[0].perenimi}</p>}
                        </div>

                        <img src={classData.teacher[0].profile_pic} style={{height:"75px", aspectRatio:"1", borderRadius:"50%", objectFit:"cover"}} />
                    </div>
                </div> }
                <SizedBox height="8px" />
                <div className="two-column-layout">
                    <div className="section clickable" onClick={()=>window.location.href = route('gameHistory')}>
                        <TwoRowTextButton upperText="Varasemad mängud" lowerText="Vaata kõiki" />
                    </div>

                    <div onClick={()=>window.location.href = route('profilePage')} className="section clickable" style={{display:"flex", flexDirection:'row', justifyContent:"space-between", alignItems:'center'}}>
                        <TwoRowTextButton capitalizeLower={true} upperText="Minu konto" lowerText={auth.user.eesnimi + " " + auth.user.perenimi} />
                        <img src={auth.user.profile_pic} style={{height:"50px", aspectRatio:"1", borderRadius:"50%", objectFit:"cover"}} />
                    </div>
                </div>
                
            </Layout>
        </>
    );

    return (
        <>
            {auth.user.role == "teacher" && !auth.user.email_verified_at && <section>
                <HorizontalInfoBanner text={""} />
            </section>}

            {teacherData != null && auth.user.email_verified_at && <section>
                <div className='header-container'>
                    <h3 className='section-header'>Minu klassid</h3>
                </div>
                {teacherData.length <= 0 && <HorizontalInfoBanner text="Klasse veel pole. Loo uus allpool oleva lingiga" />}
                <div className="stats-container">
                    {teacherData.map((e)=>{return <StatisticsWidget link={"classroom/"+e.uuid+"/view"} key={e.uuid} condensed={true} stat={e.klass_name} desc={"Klass"} /> })}
                </div>
                <><SizedBox height={24}/>
                <a alone='true' href={route("newClass")}>Uus klass&nbsp;<span translate="no" className="material-icons no-anim">add</span></a>
                <SizedBox height={8}/></>
            </section>}
        </>
    )
}