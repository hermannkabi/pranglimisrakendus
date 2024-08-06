export default function Chip({label, alt=null, active, onClick, icon=null}){
    return <div onClick={onClick} style={{margin:"4px", display:"inline-block", verticalAlign:"middle", backgroundColor: active ? "rgb(var(--primary-color))" : "var(--chip-color)", padding:"12px 18px", borderRadius:"6px", color:active ? "white" : "inherit"}}>
        <div style={{display:"flex", alignItems:"center", gap:"12px"}}>
            {icon != null && <i className="material-icons-outlined">{icon}</i> }
            <span style={{fontSize:"20px", userSelect:"none", marginTop:"2px"}}>{label} {alt != null && <span style={{color:"var(--grey-color)", marginLeft:"4px"}}>{alt}</span>}</span>
        </div>
    </div>;
}