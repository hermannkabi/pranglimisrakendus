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
    "jaguvus":"Jaguvusseadused"
};

var gameName = data.game == null ? "Tundmatu" : data.game in gameNames ? gameNames[data.game] : data.game.substring(0, 1).toUpperCase() + data.game.substring(1);

return <>
        <a href={"/game/"+data.game_id+"/details"} style={{all:"unset", cursor:"pointer"}}>
            <div className="game-tile" style={{overflow:"hidden", display:"flex", justifyContent:"space-between", flexDirection:"row", backgroundColor:"rgb(var(--section-color), var(--section-transparency))", borderRadius:"8px", margin:"8px", padding:"8px"}}>
                <div style={{textAlign:"start"}}> 
                    <h2 style={{color:"rgb(var(--primary-color))", fontSize:"1.3em", marginBlock:"4px"}}>{data.game == null ? "Tundmatu" : decodeURIComponent(gameName)}</h2>
                    <p style={{marginBlock:"4px", color:"grey"}}>{data.score_sum} punkt{data.score_sum == 1 ? "" : "i"} {isTypeShown && <span>·</span>} <span>{isTypeShown ? typeToReadable[data.game_type] : ""}</span></p>
                    
                    {/* <p style={{marginBlock:"4px", color:"grey"}}>{data.game_count} tehe{data.game_count == 1 ? "" : "t"} {isTypeShown && <span>·</span>} <span style={{textTransform:"capitalize"}}>{isTypeShown ? typeToReadable[data.game_type] : ""}</span></p> */}
                </div>
                <div style={{display:"inline-flex", alignItems:"center"}}>
                    <p style={{color:"grey"}}>{(new Date(data.dt)).toLocaleString("et-EE", {month:"2-digit", day:"2-digit", year:"numeric"}).split(",")[0]}</p>
                    <span style={{color:"grey"}} translate="no" className="material-icons">navigate_next</span>
                </div>
            </div>
        </a>
        
    </>;
}