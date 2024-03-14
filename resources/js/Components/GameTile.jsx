export default function GameTile({data}){

const typeToReadable = {
    "natural":"Naturaalarvud",
    "integer":"Täisarvud",
    "fraction":"Kümnendmurrud",
};

const isTypeShown = data.game_type != null && data.game_type in typeToReadable;

return <>
        <a href={"/game/"+data.game_id+"/details"} style={{all:"unset", cursor:"pointer"}}>
            <div style={{overflow:"hidden", display:"flex", justifyContent:"space-between", flexDirection:"row", backgroundColor:"rgb(var(--section-color), var(--section-transparency))", borderRadius:"8px", margin:"8px", padding:"8px"}}>
                <div style={{textAlign:"start"}}> 
                    <h2 style={{color:"rgb(var(--primary-color))", fontSize:"1.3em", marginBlock:"4px", textTransform:"capitalize"}}>{data.game ?? "Tundmatu"}</h2>
                    <p style={{marginBlock:"4px", color:"grey"}}>{data.score_sum} punkt{data.score_sum == 1 ? "" : "i"} {isTypeShown && <span>·</span>} <span style={{textTransform:"capitalize"}}>{isTypeShown ? typeToReadable[data.game_type] : ""}</span></p>
                    
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