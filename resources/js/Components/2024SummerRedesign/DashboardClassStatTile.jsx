export default function DashboardClassStatTile({icon, text, value}){
    return  <div className="section class-stat" style={{padding:"16px", display:"flex", flexDirection:'row', justifyContent:"space-between", alignItems:'center'}}>
                <div className="stat-desc">
                    <i translate="no" className="material-icons-outlined">{icon}</i>
                    <p style={{marginTop:"4px"}}>{text}</p>
                </div>
                <h2 style={{color:"rgb(var(--primary-color))", marginBlock:"8px"}}>{value}</h2>
            </div>;
}