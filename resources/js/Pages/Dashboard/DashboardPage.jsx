import BigGameButton from "@/Components/BigGameButton";
import Navbar from "@/Components/Navbar";
import SizedBox from "@/Components/SizedBox";
import StatisticsWidget from "@/Components/StatisticsWidget";
import { Head } from "@inertiajs/react";
import "/public/css/dashboard.css";

export default function Dashboard({auth}) {



    return (
        <>
            <Head title='Töölaud' />
            <Navbar user={auth.user} />
            <SizedBox height={36} />
            <h2>Tere, <span onClick={()=>window.location.href = route("profilePage")} style={{color:"rgb(var(--primary-color))", cursor:"pointer"}}>Mari!</span></h2>

            <section>
                <div className='header-container'>
                    <h3 className='section-header'>Statistika</h3>
                </div>
                <StatisticsWidget stat={42} desc="Treeningut" />
                <StatisticsWidget stat={"47%"} desc="Vastamistäpsus" />
                <StatisticsWidget stat={window.localStorage.getItem("last-active") ?? "-"} desc="Viimati aktiivne" />
                <StatisticsWidget stat={"6."} desc="Koht klassis " />
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
                <a alone='true' href={route("preview")}>Kõik harjutusalad →</a>
                <SizedBox height={8}/>

            </section>
        </>
    )
}