export default function StatisticsWidget({stat, desc, oneDesc=null}){
    return (
        <div className="stat-widget" style={{display:"flex", flexDirection:"column", alignItems:"center", justifyContent:"center", margin:"4px", backgroundColor: "rgb(var(--section-color), var(--section-transparency))", borderRadius:"var(--primary-btn-border-radius)"}}>
            <h3 style={{color:"rgb(var(--primary-color))", marginTop:"16px", margin:"8px", fontSize:"1.8rem", fontFamily:"Lexend Zetta", fontWeight:"bold"}}>{stat}</h3>
            <p style={{marginTop:"0px", margin:"8px", color:"#645F5F", fontSize:"0.9rem"}}>{stat=="1" && oneDesc != null ? oneDesc : desc}</p>
        </div>        
    );
}