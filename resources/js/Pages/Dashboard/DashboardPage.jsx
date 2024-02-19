import BigGameButton from "@/Components/BigGameButton";
import Navbar from "@/Components/Navbar";
import SizedBox from "@/Components/SizedBox";
import StatisticsWidget from "@/Components/StatisticsWidget";
import { Head } from "@inertiajs/react";
import "/public/css/dashboard.css";

export default function Dashboard({auth}) {

    const totalTrainingCount = window.localStorage.getItem("total-training-count") ?? "0";

    return (
        <>
            <Head title='Töölaud' />
            <Navbar user={auth.user} />
            <SizedBox height={36} />

            {<section style={{backgroundColor:"rgb(var(--section-color),  var(--section-transparency))", borderRadius:"var(--primary-btn-border-radius)", padding:"8px", marginBlock:"8px"}}>
                <p style={{color:"rgb(var(--primary-color))"}}>ⓘ Tagasiside küsitlus asub <a href="https://docs.google.com/forms/d/e/1FAIpQLSc9gNf1wVw7GemStNCxaXL7jXjlghtnlti9u3aNjfqS6pnYog/viewform?vc=0&c=0&w=1&flr=0">siin</a></p>
            </section>}

            <h2>Tere, <span onClick={()=>window.location.href = route("profilePage")} style={{color:"rgb(var(--primary-color))", cursor:"default"}}>{auth.user == null ? (window.localStorage.getItem("first-name") ?? "Mari") : auth.user.eesnimi ?? window.localStorage.getItem("first-name") ?? "Mari"}!</span></h2>

            <section>
                <div className='header-container'>
                    <h3 className='section-header'>Statistika</h3>
                </div>
                <StatisticsWidget stat={totalTrainingCount} desc={"Treening"+(totalTrainingCount == "1" ? "" : "ut")} />
                <StatisticsWidget stat={(parseInt(window.localStorage.getItem("total-percentage") ?? "0")/parseInt(window.localStorage.getItem("total-training-count") ?? "1")).toFixed(0) + "%"} desc="Vastamistäpsus" />
                <StatisticsWidget stat={window.localStorage.getItem("last-active") ?? "-"} desc="Viimati aktiivne" />
                <StatisticsWidget stat={window.localStorage.getItem("total-points") ?? "0"} desc="Punkti" />
            </section>

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
                <a alone='true' href={route("preview")}>Kõik harjutusalad <span className="material-icons">navigate_next</span></a>
                <SizedBox height={8}/>

            </section>
        </>
    )
}