export default function leaderboardRow({points, index, player, user}){
    return (
        <div style={{display:"grid", gridTemplateColumns:"repeat(2, 1fr)", marginBlock:"12px"}}> 
            <div style={{display:"flex", flexDirection:"row", gap:"8px", alignItems:"center"}}>
                <span style={{color: (player ? "rgb(var(--primary-color))" : "inherit")}}>{index + 1}. </span>
                <img src={user.profile_pic} alt={user.eesnimi + " " + user.perenimi} className="profile-pic" style={{height:"24px"}} />
                <a href={"/profile/"+user.id} style={{all:"unset", cursor:"pointer"}}><p style={{textAlign:"start", marginBlock:"0", color: (player ? "rgb(var(--primary-color))" : "inherit"), fontWeight: (player ? "bold" : "normal"), textTransform:"capitalize"}}> {(user.eesnimi + " " + user.perenimi)}</p></a>
                
            </div>
            <p style={{textAlign:"end", marginBlock:"0", color: (player ? "rgb(var(--primary-color))" : "inherit"), fontWeight: (player ? "bold" : "normal")}}>{points} XP</p>
        </div>
    );
}