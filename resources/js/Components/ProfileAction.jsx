export default function ProfileAction({red=false, icon, label, link=null, onClick=null, smallLabel=null, disabled=false}){

    const color = red ? "var(--darkred-color)" : "var(--primary-color)";
    
    var onClick = disabled ? null : onClick != null ? onClick : link != null ? ()=>window.location.href = link : null;

    return <>
            <div onClick={onClick} style={{overflow:"hidden", opacity: !disabled ? "1" : "0.5", cursor: link || onClick ? "pointer" : "default", userSelect:"none", backgroundColor:"rgb("+color+", var(--section-transparency))", borderRadius:"8px", display:"inline-flex", flexDirection:"row", alignItems:"center", gap:"8px", margin:"8px", padding:"4px 8px", justifyContent:"stretch", paddingRight:"24px"}}>
                <div style={{display:"flex", alignItems:"center", backgroundColor:"rgb("+color+", var(--section-transparency))", padding:"8px", borderRadius:"50%"}}>
                    <span className="material-icons" translate="no" style={{color:"rgb("+color+")"}}>{icon}</span>
                </div>

                <div style={{textAlign:"start", marginBlock: smallLabel ? "8px" : "0"}}>
                    <p style={{wordWrap:"anywhere", lineHeight:"1", color:"rgb("+color+")", fontWeight:smallLabel ? "bold" : "normal", marginBlock: smallLabel ? "0" : "16px"}}>{label}</p>
                    <p style={{color:"grey", fontSize:"12px", margin:"0", marginTop:"4px"}}>{smallLabel}</p>
                </div>
            </div>
    </>;
}