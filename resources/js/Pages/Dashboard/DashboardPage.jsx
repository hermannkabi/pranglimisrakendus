import BigGameButton from "@/Components/BigGameButton";
import Navbar from "@/Components/Navbar";
import SizedBox from "@/Components/SizedBox";
import StatisticsWidget from "@/Components/StatisticsWidget";
import { Head } from "@inertiajs/react";
import "/public/css/dashboard.css";
import InfoBanner from "@/Components/InfoBanner";

export default function Dashboard({auth, stats}) {

    const totalTrainingCount = window.localStorage.getItem("total-training-count") ?? "0";

    return (
        <>
            <Head title='Töölaud' />
            <Navbar user={auth.user} />
            <SizedBox height={36} />

        
            <h2>Tere, <span onClick={()=>window.location.href = route("profilePage")} style={{color:"rgb(var(--primary-color))", cursor:"default", textTransform:"capitalize"}}>{auth.user == null ? (window.localStorage.getItem("first-name") ?? "Mari") : auth.user.eesnimi ?? window.localStorage.getItem("first-name") ?? "Mari"}!</span></h2>

            {auth.user.role != "guest" && <section>
                <div className='header-container'>
                    <h3 className='section-header'>Statistika</h3>
                </div>
                <div className="stats-container">
                    <StatisticsWidget stat={stats.total_training_count ?? totalTrainingCount} desc={"Treeningut"} oneDesc={"Treening"} />
                    <StatisticsWidget stat={(stats.accuracy ??(parseInt(window.localStorage.getItem("total-percentage") ?? "0")/parseInt(window.localStorage.getItem("total-training-count") ?? "1")).toFixed(0)) + "%"} desc="Vastamistäpsus" />
                    <StatisticsWidget stat={stats.last_active ?? window.localStorage.getItem("last-active") ?? "-"} desc="Viimati aktiivne" />
                    <StatisticsWidget stat={stats.points ?? window.localStorage.getItem("total-points") ?? "0"} desc="Punkti" oneDesc={"Punkt"} />
                </div>
            </section>}
            {auth.user.role == "guest" && <section style={{backgroundColor:"rgb(var(--section-color),  var(--section-transparency))", borderRadius:"var(--primary-btn-border-radius)", padding:"8px", marginBlock:"8px"}}>
                <div className='header-container'>
                    <h3 className='section-header'>Statistika</h3>
                </div>
                <p style={{color:"rgb(var(--primary-color))", marginInline:"16px"}}><span translate="no">ⓘ</span> Külaliskontoga andmeid ei salvestata ja statistikat näha ei saa. Selleks palun loo endale konto</p>
            </section>}

            <section>
                <div className='header-container'>
                    <h3 className='section-header'>Pranglimine</h3>
                </div>

                <div className="big-btns">
                    <BigGameButton symbol="+" text="Liitmine" value={"liitmine"}/>
                    <BigGameButton symbol="-" text="Lahutamine" value={"lahutamine"}/>
                    <BigGameButton symbol="×" text="Korrutamine" value={"korrutamine"}/>
                    <BigGameButton symbol="÷" text="Jagamine" value={"jagamine"}/>
                </div>
                <SizedBox height={24}/>
                <a alone='true' href={route("preview")}>Kõik harjutusalad <span translate="no" className="material-icons">navigate_next</span></a>
                <SizedBox height={8}/>

            </section>
        </>
    )
}