export default function StatisticsWidget({stat, desc, oneDesc=null, condensed=false, link=null, name=false, textClass=null}){
    return (
        <a href={link} style={{all:"unset", display:"inherit", cursor:"pointer"}}>
            <div className={"stat-widget " + textClass != null ? (textClass +"-div") : null} style={{display:"flex", flexDirection:"column", alignItems:"center", justifyContent:"center", margin:"4px", backgroundColor: "rgb(var(--section-color), var(--section-transparency))", borderRadius:"var(--primary-btn-border-radius)"}}>
                <h3 className={textClass} style={{color:"rgb(var(--primary-color))", marginTop:"16px", margin:"8px", fontSize: condensed ? "1.2rem" : "1.5rem", fontFamily: condensed ? "Lexend Zetta" : "Lexend Zetta", fontWeight:"bold", letterSpacing: condensed ? "-3px" : "normal", textTransform: name ? "capitalize" : "none"}}>{stat}</h3>
                <p className={textClass} style={{marginTop:"0px", margin:"8px", color:"#645F5F", fontSize:"0.9rem"}}>{stat=="1" && oneDesc != null ? oneDesc : desc}</p>
            </div> 
        </a>
               
    );
}