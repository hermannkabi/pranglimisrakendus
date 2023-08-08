export default function leaderboardRow({name, points, index, player}){
    return (
        <div style={{display:"grid", gridTemplateColumns:"repeat(2, 1fr)", marginBlock:"12px"}}> 
            <p style={{textAlign:"start", marginBlock:"0", color: (player ? "rgb(var(--primary-color))" : "inherit"), fontWeight: (player ? "bold" : "normal")}}>{index + 1}. {name}</p>
            <p style={{textAlign:"end", marginBlock:"0", color: (player ? "rgb(var(--primary-color))" : "inherit"), fontWeight: (player ? "bold" : "normal")}}>{points}</p>
        </div>
    );
}