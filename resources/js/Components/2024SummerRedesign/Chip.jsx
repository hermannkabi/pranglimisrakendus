export default function Chip({label, capitalize=false, alt=null, active, onClick, icon=null, colors}){
    return <div onClick={onClick} style={{margin:"4px", display:"inline-block", verticalAlign:"middle", backgroundColor: active ? (colors != null ? colors.active : "rgb(var(--primary-color))") : (colors != null ? colors.background : "var(--chip-color)"), padding:"12px 18px", borderRadius:"6px", color:active ? "white" : "inherit"}}>
        <div style={{display:"flex", alignItems:"center", gap:"12px"}}>
            {icon != null && <i translate="no" className="material-icons-outlined">{icon}</i> }
            <span style={{fontSize:"20px", userSelect:"none", marginTop:"2px", textTransform: capitalize ? "capitalize" : null}}>{label} {alt != null && <span style={{color:"var(--lightgrey-color)", marginLeft:"4px"}}>{alt}</span>}</span>
        </div>
    </div>;
}