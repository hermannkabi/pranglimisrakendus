import Layout from "@/Components/2024SummerRedesign/Layout";
import TwoRowTextButton from "@/Components/2024SummerRedesign/TwoRowTextButton";

export default function NewClassroomPage({auth, myCompetitions, publicCompetitions}){

    function formatDateTime(datetimeStr) {
        const date = new Date(datetimeStr.replace(/-/g, "/"));
        const [datePart, timePart] = date.toLocaleString("et-EE", {
            year: "numeric",
            month: "2-digit",
            day: "2-digit",
            hour: "2-digit",
            minute: "2-digit",
        }).split(", ");
        
        return `${datePart}`;
    }

    return <>
        <Layout title="Otsi võistluseid" auth={auth}>
            <div className="two-column-layout">
                <div>

                    {/* My upcoming competitions */}
                    <div>
                        <h2>Minu võistlused</h2>

                        {myCompetitions.map(e=> <a key={e.competition_id} style={{all:"unset"}} href={"/competition/"+e.competition_id+"/view"}> <div onClick={null} style={{display:"flex", flexDirection:"row", alignItems:"flex-end", justifyContent:"space-between"}} className={"clickable section"}>
                                                    <TwoRowTextButton isActive={e.active} upperText={e.name} lowerText={e.participants_count + " võistleja"+(e.participants_count == 1 ? "" : "t")} showArrow={false} />
                                                    <p style={{textAlign:"right", color:"var(--grey-color)", marginBottom:"0", marginRight:"8px"}}>{e.active ? "Lõppeb" : "Algab"} {formatDateTime(e.active ? e.dt_end : e.dt_start)}</p>
                                                </div> </a>)}
                        {myCompetitions.length == 0 && <p style={{color:"var(--grey-color)"}}>Sa ei ole hetkel registreeritud ühelegi võistlusele</p> }
                    </div>

                    {/* Public competitions */}

                    <div>
                        <h2>Avalikud võistlused</h2>

                        {publicCompetitions.map(e=> <a key={e.competition_id}  style={{all:"unset"}} href={"/competition/"+e.competition_id+"/view"}> <div onClick={null} style={{display:"flex", flexDirection:"row", alignItems:"flex-end", justifyContent:"space-between"}} className={"clickable section"}>
                                                    <TwoRowTextButton isActive={e.active} upperText={e.name} lowerText={e.participants_count + " võistleja"+(e.participants_count == 1 ? "" : "t")} showArrow={false} />
                                                    <p style={{textAlign:"right", color:"var(--grey-color)", marginBottom:"0", marginRight:"8px"}}>{e.active ? "Lõppeb" : "Algab"} {formatDateTime(e.active ? e.dt_end : e.dt_start)}</p>
                                                </div> </a>)}
                        
                        {publicCompetitions.length == 0 && <p style={{color:"var(--grey-color)"}}>Hetkel ei ole saadaval ühtki avalikku võistlust</p> }

                    </div>

                </div>
            </div>
        </Layout>
    </>;
}