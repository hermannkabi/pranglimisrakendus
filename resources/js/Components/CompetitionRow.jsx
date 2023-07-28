export default function CompetitionRow({name, date}){
    return (
        <div style={{display:"grid", gridTemplateColumns:"repeat(2, 1fr)", marginBlock:"12px"}}>
            <p style={{textAlign:"start", marginBlock:"0"}}>{name}</p>
            <p style={{textAlign:"end", marginBlock:"0", color: "rgb(var(--primary-color))", fontWeight: "bold"}}>{date}</p>
        </div>
    );
}