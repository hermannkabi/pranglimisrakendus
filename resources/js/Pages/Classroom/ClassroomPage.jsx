import Navbar from "@/Components/Navbar";
import SizedBox from "@/Components/SizedBox";
import StatisticsWidget from "@/Components/StatisticsWidget";
import LeaderboardRow from "@/Components/LeaderboardRow";
import { Head } from "@inertiajs/react";
import { useState } from "react";
import HorizontalInfoBanner from "@/Components/HorizontalInfoBanner";

export default function ClassroomPage({leaderboard, teacher, auth, className, stats, isTeacher, uuid}){

    const [copyText, setCopyText] = useState("Klassiga ühinemise link");

    function copyToClipboard(){
        navigator.clipboard.writeText(window.location.origin + "/classroom/" + uuid + "/join");
        setCopyText("Link kopeeritud!");

        setTimeout(() => {
            setCopyText("Klassiga ühinemise link");
        }, 2000);
    }

    return <>
    
        <Head title='Minu klass' />
        <Navbar user={auth.user} title="Minu klass" />
        <SizedBox height={36} />

        <h2>{className}</h2>
        <section>
            <div className="history-statistics">
                <StatisticsWidget stat={stats.studentsCount} desc="Õpilast" oneDesc="Õpilane" />
                <StatisticsWidget stat={stats.totalGameCount} desc="Mängu kokku" oneDesc="Mäng kokku" />

                <StatisticsWidget className="xp-stat" stat={stats.totalPointsCount} desc="XP kokku" oneDesc="XP kokku (nagu kuidas??)" />
            </div>
            {teacher != null && <StatisticsWidget link={"/profile/"+teacher.id} name={true} stat={teacher.eesnimi + " " + teacher.perenimi} condensed={true} desc="Õpetaja" />}
        
            
        </section>

        <section>
            <div className='header-container'>
                <h3 className='section-header'>Edetabel</h3>
            </div>
            {leaderboard.length <= 0 && <HorizontalInfoBanner text="Hetkel ei ole siin kedagi..." />}
            {leaderboard.map((e, index)=><LeaderboardRow key={e.user.id} index={index} player={auth.user.id == e.user.id} user={e.user} points={e.xp} /> )}
        </section>

        {isTeacher && <>
            <SizedBox height="16px" />
            <a alone="" onClick={copyToClipboard} >{copyText}&nbsp;<span className="material-icons no-anim" translate="no">link</span> </a> 
        </>}
        {isTeacher && <>
            <SizedBox height="16px" />
            <a alone=""  href={"/classroom/"+uuid+"/edit"}>Muuda klassi&nbsp;<span className="material-icons no-anim" translate="no">edit</span> </a> 
        </>}
        
    </>;
}