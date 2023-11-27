export default function StatisticsWidget({stat, desc}){
    return (
        <div className="stat-widget" style={{display:"block", marginInline:"12px", marginBlock:"16px"}}>
            <h3 style={{marginBottom:"0px", fontSize:"2rem", fontFamily:"Lexend Zetta", fontWeight:"bold"}}>{stat}</h3>
            <p style={{marginTop:"0", color:"#645F5F", fontSize:"1rem"}}>{desc}</p>
        </div>        
    );
}