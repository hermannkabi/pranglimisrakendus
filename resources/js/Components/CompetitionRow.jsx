export default function CompetitionRow({name, date, place}){
    return place ? (
        <div style={{display:"grid", gridTemplateColumns:"repeat(2, 1fr)", marginBlock:"12px", alignItems:"center"}}>
            <div>
                <p style={{textAlign:"start", marginBlock:"0", fontWeight:"bold", color: "rgb(var(--primary-color))"}}>{name}</p>
                <p style={{textAlign:"start", marginBlock:"0"}}>{date}</p>
            </div>
            <p style={{textAlign:"end", marginBlock:"0", color: "rgb(var(--primary-color))", fontWeight: "bold"}}>{place}. koht</p>
        </div>
    ) :  (
        <div style={{display:"grid", gridTemplateColumns:"repeat(2, 1fr)", marginBlock:"42px", alignItems:"center"}}>
            <p style={{textAlign:"start", marginBlock:"0", color: "rgb(var(--primary-color))", fontWeight: "bold"}}>{name}</p>
            <p style={{textAlign:"end", marginBlock:"0"}}>{date}</p>
        </div>
    );
}