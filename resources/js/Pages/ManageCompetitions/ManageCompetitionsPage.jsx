import InfoBanner from "@/Components/InfoBanner";
import Layout from "@/Components/2024SummerRedesign/Layout";
import PasswordWidget from "@/Components/2024SummerRedesign/PasswordWidget";
import BigButton from "@/Components/2024SummerRedesign/BigButton";
import { useState } from "react";
import TwoRowTextButton from "@/Components/2024SummerRedesign/TwoRowTextButton";
import StatisticsTile from "@/Components/2024SummerRedesign/StatisticsTile";
import VerticalStatTile from "@/Components/2024SummerRedesign/VerticalStatTile";
import Chip from "@/Components/2024SummerRedesign/Chip";
import SizedBox from "@/Components/SizedBox";

export default function ManageCompetitionsPage({auth, present, past, future}){

    const [selected, setSelected] = useState();

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

    const gameNames = {
        "lihtsustamine":"Murru taandamine",
        "murruTaandamine":"Murru taandamine",
        "jaguvus":"Jaguvusseadused"
    };

    var formattedName = (gameName) => gameName == null ? "Tundmatu" : gameName in gameNames ? gameNames[gameName] : gameName.substring(0, 1).toUpperCase() + gameName.substring(1);

    return <>
        <Layout title="Halda võistluseid" auth={auth}>
            <div className="four-stat-row">
                <StatisticsTile stat={present.length} label={"Hetkel toimuvat võistlust"} oneLabel={"Hetkel toimuv võistlus"} icon={"nest_clock_farsight_analog"} />
                <StatisticsTile stat={future.length} label={"Tulevast võistlust"} oneLabel={"Tulevane võistlus"} icon={"event_upcoming"} />
                <StatisticsTile stat={past.length} label={"Lõppenud võistlust"} oneLabel={"Lõppenud võistlus"} icon={"archive"} />
                <StatisticsTile stat={present.length + future.length + past.length} label={"Kokku võistluseid"} icon={"functions"}/>
            </div>
            <div className="two-column-layout">
                <div>

                    {/* Present competitions */}
                    <div>
                        <h2>Hetkel toimuvad võistlused</h2>

                        {present.map(e=> <div onClick={()=>setSelected(selected == e ? null : e)} key={e.competition_id} style={{display:"flex", flexDirection:"row", alignItems:"flex-end", justifyContent:"space-between"}} className={"clickable section " + (selected == e ? " tile-selected" : "")}>
                                                    <TwoRowTextButton showArrow={false} upperText={e.name} lowerText={e.participants_count + " võistleja"+(e.participants_count == 1 ? "" : "t")}  />
                                                    <p style={{textAlign:"right", color:"var(--grey-color)", marginBottom:"0", marginRight:"8px"}}>{e.active ? "Lõppeb" : "Algab"} {formatDateTime(e.active ? e.dt_end : e.dt_start)}</p>
                                                </div>)}
                        {present.length == 0 && <p style={{color:"var(--grey-color)"}}>Hetkel ei toimu ühtegi võistlust</p> }
                    </div>

                    {/* Future competitions */}

                    <div>
                        <h2>Tulevased võistlused</h2>

                        {future.map(e=><div key={e.competition_id} onClick={()=>setSelected(selected == e ? null : e)} style={{display:"flex", flexDirection:"row", alignItems:"flex-end", justifyContent:"space-between"}} className={"clickable section" + (selected == e ? " tile-selected" : "")}>
                                                    <TwoRowTextButton showArrow={false} upperText={e.name} lowerText={e.participants_count + " võistleja"+(e.participants_count == 1 ? "" : "t")} />
                                                    <p style={{textAlign:"right", color:"var(--grey-color)", marginBottom:"0", marginRight:"8px"}}>{e.active ? "Lõppeb" : "Algab"} {formatDateTime(e.active ? e.dt_end : e.dt_start)}</p>
                                                </div>)}
                        
                        {future.length == 0 && <p style={{color:"var(--grey-color)"}}>Ühtegi tulevast võistlust ei leitud</p> }

                    </div>

                    {/* Past competitions */}

                    <div>
                        <h2>Toimunud võistlused</h2>

                        {past.map(e=> <div key={e.competition_id} onClick={()=>setSelected(selected == e ? null : e)} style={{display:"flex", flexDirection:"row", alignItems:"flex-end", justifyContent:"space-between"}} className={"clickable section" + (selected == e ? " tile-selected" : "")}>
                                                    <TwoRowTextButton showArrow={false} upperText={e.name} lowerText={e.participants_count + " võistleja"+(e.participants_count == 1 ? "" : "t")} />
                                                    <p style={{textAlign:"right", color:"var(--grey-color)", marginBottom:"0", marginRight:"8px"}}>Lõppes {formatDateTime(e.dt_end)}</p>
                                                </div>)}
                        
                        {past.length == 0 && <p style={{color:"var(--grey-color)"}}>Ühtegi lõppenud võistlust ei ole veel toimunud</p> }

                    </div>
                </div>

                <div>
                    {selected && <div className="section" style={{position:"relative", padding:"8px 16px", display:"flex", flexDirection:"row", justifyContent:"space-between", alignItems:"center"}}>
                        <VerticalStatTile padding="8px 0" marginBlock={0} icon="info" text={selected.name} customValue={true} value={
                            <div style={{color:"var(--grey-color)"}}>
                                <p>{selected.description ?? "Kirjeldust pole lisatud"}</p>

                                <p><b>Algusaeg:</b> {formatDateTime(selected.dt_start)}</p>
                                <p><b>Lõpuaeg:</b> {formatDateTime(selected.dt_end)}</p>
                                <p><b>Lubatud mängukordi:</b> {selected.attempt_count == 0 ? "Piiramatu" : selected.attempt_count}</p>
                                <span><b>Mängutüübid: </b></span> {JSON.parse(selected.game_data)["mis"].split(",").map(e=><Chip disabled={true} key={e} label={formattedName(e)} />)}

                            </div>
                        } />
                    </div> }  
                    {selected && <div style={{display:"grid", gridTemplateColumns:"repeat(2, 1fr)", gap:"16px"}}>
                        <div onClick={()=>window.location.href = "/competition/"+selected.competition_id+"/edit"} className="section clickable" style={{padding:"16px", display:"flex", justifyContent:"start", alignItems:"center", marginBlock:"0"}}>
                            <div>
                                <i translate="no" style={{fontSize:"32px"}} className="material-icons-outlined">edit</i>
                                <p style={{marginTop:"8px", marginBottom:"0"}}>Muuda võistlust</p>
                            </div>
                        </div>
                        <div onClick={()=>window.location.href = "/competition/"+selected.competition_id+"/view"} className="section clickable" style={{padding:"16px", display:"flex", justifyContent:"start", alignItems:"center", marginBlock:"0"}}>
                            <div>
                                <i translate="no" style={{fontSize:"32px"}} className="material-icons-outlined">arrow_forward</i>
                                <p style={{marginTop:"8px", marginBottom:"0"}}>Võistluse leht</p>
                            </div>
                        </div>
                    </div>} 
                    <SizedBox height={16} />
                    <BigButton onClick={()=>window.location.href = route("competitionNew")} title={"Loo uus võistlus"} subtitle={"Uus võistlus"} />
                </div>

                
            </div>
        </Layout>
    </>;
}