export default function Chip({label, capitalize=false, alt=null, active, onClick, icon=null, colors, classNames=null, disabled=false, img=null}){
    return <div className={"chip " + (classNames ?? "") + (disabled ? " disabled" : "")} onClick={onClick} style={{backgroundColor: active ? (colors != null ? colors.active : "rgb(var(--primary-color))") : (colors != null ? colors.background : "var(--chip-color)"), color:active ? "white" : "inherit", cursor:disabled ? "default" : "pointer"}}>
        <div style={{display:"flex", alignItems:"center", gap:"12px"}}>
            {icon != null && <i translate="no" className="material-icons-outlined">{icon}</i> }
            {img != null && <img src={img} className="profile-pic" style={{height:"30px"}} /> }
            <span style={{fontSize:"20px", userSelect:"none", marginTop:"2px", textTransform: capitalize ? "capitalize" : null}}>{label} {alt != null && <span style={{color:"var(--lightgrey-color)", marginLeft:"4px"}}>{alt}</span>}</span>
        </div>
    </div>;
}