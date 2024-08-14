import TwoRowTextButton from "./2024SummerRedesign/TwoRowTextButton";

export default function GameTile({data}){

const typeToReadable = {
    "natural":"Naturaalarvud",
    "integer":"Täisarvud",
    "fraction":"Kümnendmurrud",
    "roman":"Rooma numbrid",
    "kujundid":"Tavaline",
    "color":"Erinevad värvid",
    "size":"Erinevad suurused",
    "all":"Erinevad värvid ja suurused"
};

const isTypeShown = data.game_type != null && data.game_type in typeToReadable;

const gameNames = {
    "lihtsustamine":"Murru taandamine",
    "murruTaandamine":"Murru taandamine",
    "jaguvus":"Jaguvusseadused"
};

var gameName = data.game == null ? "Tundmatu" : data.game in gameNames ? gameNames[data.game] : data.game.substring(0, 1).toUpperCase() + data.game.substring(1);

return <>
    <div style={{position:'relative'}} onClick={()=>window.location.href = "/game/"+data.game_id+"/details"} className="section clickable">
        <TwoRowTextButton upperText={data.game == null ? "Tundmatu" : decodeURIComponent(gameName)} lowerText={isTypeShown ? typeToReadable[data.game_type] : (data.score_sum+" punkt"+(data.score_sum == 1 ? "" : "i"))} />
        <p style={{marginBlock:"0", color:"var(--grey-color)", position:"absolute", bottom:"8px", right:"8px"}}>{(new Date(data.dt.replace(/-/g, "/"))).toLocaleString("et-EE", {month:"2-digit", day:"2-digit", year:"numeric"}).split(",")[0]}</p>
    
        <a href={"/game/"+data.game_id+"/details"} style={{all:"unset", position:"absolute", top:"0", left:"0", height:"100%", width:"100%"}}></a>
    </div>
</>;
}