export default function ProfileAction({symbol=null, red=false, selected=true, icon, label, link=null, onClick=null, smallLabel=null, disabled=false, style=null}){

    const color = red ? "var(--darkred-color)" : "var(--primary-color)";
    
    var onClicked = disabled ? null : onClick;

    return <>
            <div onClick={onClicked} style={{overflow:"hidden", opacity: !disabled ? "1" : "0.5", cursor: link || onClick ? "pointer" : "default", userSelect:"none", backgroundColor: selected ? "rgb("+color+", var(--section-transparency))" : "transparent", borderRadius:"8px", display:"inline-flex", flexDirection:"row", alignItems:"center", gap:"8px", margin:"8px", padding:"4px 8px", justifyContent:"stretch", paddingRight:"24px", position:"relative", ...style}}>
                
                {link && <a href={disabled ? null : link} style={{all:"unset", cursor:"pointer", position:"absolute", top:"0", left:"0", height:"100%", width:"100%"}}></a> }
                
                <div style={{aspectRatio:"1", transition:"all 200ms", display:"flex", alignItems:"center", justifyContent:"center", backgroundColor:"rgb("+color+", "+(selected ? "1" : "var(--section-transparency)"), padding:"8px", borderRadius:"50%"}}>
                    {symbol && <span className="material-icons" style={{fontSize:"32px", color: selected ? "white" : "rgb("+color+")"}}>{symbol}</span> }
                    {icon && <span className="material-icons" translate="no" style={{color: selected ? "white" : "rgb("+color+")"}}>{icon}</span>}
                </div>

                <div style={{textAlign:"start", marginBlock: smallLabel ? "8px" : "0"}}>
                    <p style={{wordWrap:"anywhere", lineHeight:"1", color:"rgb("+color+")", fontWeight: "normal", marginBlock: smallLabel ? "0" : "16px"}}>{label}</p>
                    <p style={{color:"grey", fontSize:"12px", margin:"0", marginTop:"4px"}}>{smallLabel}</p>
                </div>
            </div>
    </>;
}