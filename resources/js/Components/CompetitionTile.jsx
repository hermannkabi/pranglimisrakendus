import TwoRowTextButton from "./2024SummerRedesign/TwoRowTextButton";

export default function CompetitionTile({data}){
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
        <div style={{position:'relative'}} onClick={()=>window.location.href = "/competition/"+data.competition_id+"/view"} className="section clickable">
            <TwoRowTextButton upperText={data.name} lowerText={data.rank_label + ". koht"} />
            <p style={{marginBlock:"0", color:"var(--grey-color)", position:"absolute", bottom:"8px", right:"8px"}}>{formatDateTime(data.dt_start)}-{formatDateTime(data.dt_end)}</p>
        
            <a href={"/competition/"+data.competition_id+"/view"} style={{all:"unset", position:"absolute", top:"0", left:"0", height:"100%", width:"100%"}}></a>
        </div>
    </>;
}