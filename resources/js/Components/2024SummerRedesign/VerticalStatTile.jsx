export default function VerticalStatTile({icon, text, value, customValue=false, capitalize=false, marginBlock=null, padding=null}){
    return  <div className="section class-stat" style={{padding:padding ?? "16px", marginBlock:marginBlock}}>
                <div className="stat-desc">
                    <i translate="no" className="material-icons-outlined">{icon}</i>
                    <p style={{marginTop:"4px"}}>{text}</p>
                </div>
                {customValue && value}
                {!customValue && <p style={{textTransform: capitalize ? "capitalize" : null, display:"flex", alignItems:"center", gap:"8px", marginBottom:"8px", fontWeight:"bold", color:"var(--lightgrey-color)", fontSize:"24px"}}>{value}</p>}
            </div>;
}