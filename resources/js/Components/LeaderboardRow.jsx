import SizedBox from "./SizedBox";

export default function leaderboardRow({points, index, player, user}){
    // return (
    //     <div style={{display:"flex", flexDirection:'row', justifyContent:"space-between", gap:"16px", marginBlock:"24px"}}> 
    //         <div style={{display:"flex", flexDirection:"row", gap:"8px", alignItems:"center"}}>
    //             <span className={index == 0 ? "fancy" : index == 1 ? "fancy2" : index == 2 ? "fancy3" : null} style={{color: (player ? "rgb(var(--primary-color))" : "inherit")}}>{index + 1}. </span>
    //             <img src={user.profile_pic} alt={user.eesnimi + " " + user.perenimi} className="profile-pic" style={{height:"24px"}} />
    //             <a href={"/profile/"+user.id} style={{all:"unset", cursor:"pointer"}}><p style={{textAlign:"start", marginBlock:"0", color: (player ? "rgb(var(--primary-color))" : "inherit"), fontWeight: (player ? "bold" : "normal"), textTransform:"capitalize"}}> {(user.eesnimi + " " + user.perenimi)}</p></a>
                
    //         </div>
    //         <p style={{textAlign:"end", marginBlock:"0", color: (player ? "rgb(var(--primary-color))" : "inherit"), fontWeight: (player ? "bold" : "normal")}}>{points} XP</p>
    //     </div>
    // );

    return (
        <a style={{all:"unset", cursor:"pointer"}} href={"/profile/"+user.id}>
            <div className="leaderboard-row" style={{marginBlock:"8px", display:"flex", flexDirection:"row", justifyContent:"space-between", alignItems:"center"}}>
                <div style={{display:"flex", flexDirection:"row", alignItems:"center"}}>
                    <div style={{height:"36px", aspectRatio: "1", borderRadius:"50%", display:"flex", alignItems:"center", justifyContent:"center", fontWeight: (index <= 2 ? "bold" : "normal")}} className={index == 0 ? "fancy-div fancy" : index == 1 ? "fancy2-div fancy2" : index == 2 ? "fancy3-div fancy3" : null}><span>{index + 1}</span></div>
                    <SizedBox width={12} />
                    <div style={{display:"flex", flexDirection:"column", alignItems:"start", fontSize:"18px", fontWeight: player ? "bold" : "normal"}}>
                        <span style={{color: player ? "rgb(var(--primary-color))" : "inherit"}}>{user.eesnimi}</span><br />
                        <span style={{fontSize:"80%", textTransform:"uppercase", color: player ? "rgb(var(--primary-color), 0.5)" : "grey"}}>{user.perenimi}</span>
                    </div>
                </div>

                <div>
                    <span style={{fontSize:"18px"}}>{points}</span>
                    <span style={{fontSize:"12px", color:"grey"}}>XP</span>
                </div>
            </div>
        </a>
    );
}