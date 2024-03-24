export default function leaderboardRow({points, index, player, user}){
    return (
        <div style={{display:"flex", flexDirection:'row', justifyContent:"space-between", gap:"16px", marginBlock:"24px"}}> 
            <div style={{display:"flex", flexDirection:"row", gap:"8px", alignItems:"center"}}>
                <span className={index == 0 ? "fancy" : index == 1 ? "fancy2" : index == 2 ? "fancy3" : null} style={{color: (player ? "rgb(var(--primary-color))" : "inherit")}}>{index + 1}. </span>
                <img src={user.profile_pic} alt={user.eesnimi + " " + user.perenimi} className="profile-pic" style={{height:"24px"}} />
                <a href={"/profile/"+user.id} style={{all:"unset", cursor:"pointer"}}><p style={{textAlign:"start", marginBlock:"0", color: (player ? "rgb(var(--primary-color))" : "inherit"), fontWeight: (player ? "bold" : "normal"), textTransform:"capitalize"}}> {(user.eesnimi + " " + user.perenimi)}</p></a>
                
            </div>
            <p style={{textAlign:"end", marginBlock:"0", color: (player ? "rgb(var(--primary-color))" : "inherit"), fontWeight: (player ? "bold" : "normal")}}>{points} XP</p>
        </div>
    );
}