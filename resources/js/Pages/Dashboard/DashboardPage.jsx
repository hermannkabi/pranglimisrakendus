import BigGameButton from "@/Components/BigGameButton";
import Navbar from "@/Components/Navbar";
import SizedBox from "@/Components/SizedBox";
import StatisticsWidget from "@/Components/StatisticsWidget";
import { Head } from "@inertiajs/react";
import "/public/css/dashboard.css";
import InfoBanner from "@/Components/InfoBanner";
import HorizontalInfoBanner from "@/Components/HorizontalInfoBanner";
import ProfileAction from "@/Components/ProfileAction";

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
            <Head title='Töölaud' />
            <Navbar user={auth.user} />
            <SizedBox height={36} />

            <img className="easteregg1" style={{position:"fixed", top:"0", left:"0", display:"none", zIndex:"1000", width:"100%"}} src="/assets/eastereggs/chrisette2.jpg" alt="" />
        

            <h2>Tere, <span onClick={()=>window.location.href = route("profilePage")} style={{cursor:"default", textTransform:"capitalize"}}>{auth.user == null ? (window.localStorage.getItem("first-name") ?? "Mari") : auth.user.eesnimi ?? window.localStorage.getItem("first-name") ?? "Mari"}!</span></h2>
            {(new URLSearchParams(window.location.search)).get("verified") != null && <section>
                <HorizontalInfoBanner text={"Sinu e-posti aadress on kinnitatud!"} />
            </section>}

            {auth.user.role == "teacher" && !auth.user.email_verified_at && <section>
                <HorizontalInfoBanner text={"Õpetajale lubatud toimingute (nt klasside loomine) tegemiseks palun kinnita profiilivaates e-posti aadress"} />
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

            <section>
                <div className='header-container'>
                    <h3 className='section-header'>Arvutamine</h3>
                </div>

                <div className="big-btns">
                    <BigGameButton symbol="+" text="Liitmine" value={"liitmine"}/>
                    <BigGameButton symbol="–" text="Lahutamine" value={"lahutamine"}/>
                    <BigGameButton symbol="×" text="Korrutamine" value={"korrutamine"}/>
                    <BigGameButton symbol="÷" text="Jagamine" value={"jagamine"}/>
                </div>
                <SizedBox height={24}/>
                <a alone='true' href={route("preview")}>Kõik harjutusalad <span translate="no" className="material-icons">navigate_next</span></a>
                <SizedBox height={8}/>

            </section>

            {auth.user.klass != null && teacherData == null && <section>
                <div className='header-container'>
                    <h3 style={{marginBottom:"0"}} className='section-header'>{classData.name}</h3>
                    {classData.teacher.length > 0 && <p style={{color:"grey", marginTop:"0"}}>õp <a style={{all:"unset", cursor:"pointer"}} href={"/profile/"+classData.teacher[0].id}><span style={{textTransform:"capitalize"}}>{classData.teacher[0].eesnimi} {classData.teacher[0].perenimi}</span></a></p>}
                </div>

                <div className="history-statistics">
                    <StatisticsWidget link={"classroom/"+classData.uuid+"/view"} textClass={classData.myPlace == 1 ? "fancy" : classData.myPlace == 2 ? "fancy2" : classData.myPlace == 3 ? "fancy3" : null} stat={classData.myPlace + "."} desc="Koht klassis" />
                    <StatisticsWidget link={"classroom/"+classData.uuid+"/view"} stat={classData.studentsCount} desc="Õpilast" oneDesc="Õpilane" />
                    <StatisticsWidget link={"classroom/"+classData.uuid+"/view"} stat={classData.pointsCount} desc="XP kokku" />
                </div>
                <SizedBox height={24}/>
                <a alone='true' href={"classroom/"+classData.uuid+"/view"}>Vaata klassi <span translate="no" className="material-icons">navigate_next</span></a>
                <SizedBox height={8}/>

            </section>} 

            {auth.user.role != "guest" && <section>
                <div className='header-container'>
                    <h3 className='section-header'>Statistika</h3>
                </div>
                <div className="stats-container">
                    <StatisticsWidget link={route("gameHistory")} stat={stats.total_training_count ?? totalTrainingCount} desc={"Mängu"} oneDesc={"Mäng"} />
                    <StatisticsWidget link={route("gameHistory")} stat={(stats.accuracy ??(parseInt(window.localStorage.getItem("total-percentage") ?? "0")/parseInt(window.localStorage.getItem("total-training-count") ?? "1")).toFixed(0)) + "%"} desc="Vastamistäpsus" />
                    <StatisticsWidget link={route("preview")} textClass={auth.user.streak_active ? null : "inactive"} stat={stats.streak ?? "-"} desc="Järjestikust päeva" oneDesc="Järjestikune päev" />
                    <StatisticsWidget link={route("gameHistory")} stat={stats.points ?? window.localStorage.getItem("total-points") ?? "0"} desc="Punkti" oneDesc={"Punkt"} />
                </div>
                {stats.total_training_count > 0 && <><SizedBox height={24}/>
                <a alone='true' href={route("gameHistory")}>Mängude ajalugu <span translate="no" className="material-icons">navigate_next</span></a>
                <SizedBox height={8}/></>}
            </section>}
            {auth.user.role == "guest" && <section style={{backgroundColor:"rgb(var(--section-color),  var(--section-transparency))", borderRadius:"var(--primary-btn-border-radius)", padding:"8px", marginBlock:"8px"}}>
                <div className='header-container'>
                    <h3 className='section-header'>Statistika</h3>
                </div>
                <i class="fa-solid fa-calculator"></i>
                <HorizontalInfoBanner text="Külaliskontoga andmeid ei salvestata ja statistikat näha ei saa. Selleks palun loo endale konto" />
            </section>}
        </>
    )
}