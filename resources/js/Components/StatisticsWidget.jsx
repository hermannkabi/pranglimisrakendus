export default function StatisticsWidget({stat, desc}){
    return (
        <section className="stats-section" style={{marginInline:"8px"}}>
            <div className="stat-widget" style={{display:"inline-block", marginInline:"12px"}}>
                <h3 style={{marginBottom:"8px"}}>{stat}</h3>
                <p style={{marginTop:"0"}}>{desc}</p>
            </div>
        </section>
        
    );
}