export default function ProfileAction({red=false, icon, label, link=null, onClick=null, smallLabel=null, disabled=false}){

    const color = red ? "var(--darkred-color)" : "var(--primary-color)";
    
    var onClicked = disabled ? null : onClick;

    return <>
            <div onClick={onClicked} style={{overflow:"hidden", opacity: !disabled ? "1" : "0.5", cursor: link || onClick ? "pointer" : "default", userSelect:"none", backgroundColor:"rgb("+color+", var(--section-transparency))", borderRadius:"8px", display:"inline-flex", flexDirection:"row", alignItems:"center", gap:"8px", margin:"8px", padding:"4px 8px", justifyContent:"stretch", paddingRight:"24px", position:"relative"}}>
                
                {link && <a href={link} style={{all:"unset", cursor:"pointer", position:"absolute", top:"0", left:"0", height:"100%", width:"100%"}}></a> }
                
                <div style={{display:"flex", alignItems:"center", backgroundColor:"rgb("+color+", var(--section-transparency))", padding:"8px", borderRadius:"50%"}}>
                    <span className="material-icons" translate="no" style={{color:"rgb("+color+")"}}>{icon}</span>
                </div>

                <div style={{textAlign:"start", marginBlock: smallLabel ? "8px" : "0"}}>
                    <p style={{wordWrap:"anywher", lineHeight:"1", color:"rgb("+color+")", fontWeight: "normal", marginBlock: smallLabel ? "0" : "16px"}}>{label}</p>
                    <p style={{color:"grey", fontSize:"12px", margin:"0", marginTop:"4px"}}>{smallLabel}</p>
                </div>
            </div>
    </>;
}