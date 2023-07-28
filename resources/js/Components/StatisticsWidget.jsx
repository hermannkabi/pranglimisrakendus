export default function StatisticsWidget({stat, desc}){
    return (
        <div className="stat-widget" style={{display:"inline-block", marginInline:"24px"}}>
            <h3 style={{marginBottom:"8px"}}>{stat}</h3>
            <p style={{marginTop:"0"}}>{desc}</p>
        </div>
    );
}