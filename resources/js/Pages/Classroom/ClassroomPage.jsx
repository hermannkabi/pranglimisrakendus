import Navbar from "@/Components/Navbar";
import SizedBox from "@/Components/SizedBox";
import StatisticsWidget from "@/Components/StatisticsWidget";
import LeaderboardRow from "@/Components/LeaderboardRow";
import { Head } from "@inertiajs/react";

export default function ClassroomPage({leaderboard, teacher, auth, className, stats, isTeacher, uuid}){
    return <>
    
        <Head title='Minu klass' />
        <Navbar user={auth.user} title="Minu klass" />
        <SizedBox height={36} />

        <h2>{className}</h2>
        <section>
            <div className="history-statistics">
                <StatisticsWidget stat={stats.studentsCount} desc="Õpilast" oneDesc="Õpilane" />
                <StatisticsWidget stat={stats.totalGameCount} desc="Mängu kokku" oneDesc="Mäng kokku" />

                <StatisticsWidget stat={stats.totalPointsCount} desc="XP kokku" oneDesc="XP kokku (nagu kuidas??)" />
            </div>
            {teacher != null && <StatisticsWidget name={true} stat={teacher.eesnimi + " " + teacher.perenimi} condensed={true} desc="Õpetaja" />}
        
            
        </section>

        <section>
            <div className='header-container'>
                <h3 className='section-header'>Edetabel</h3>
            </div>
            {leaderboard.map((e, index)=><LeaderboardRow key={e.user.id} index={index} player={auth.user.id == e.user.id} user={e.user} points={e.xp} /> )}
        </section>

        {isTeacher && <>
            <SizedBox height="16px" />
            <a alone=""  href={"/classroom/"+uuid+"/edit"}>Muuda klassi&nbsp;<span className="material-icons no-anim" translate="no">edit</span> </a> 
        </>}

    </>;
}