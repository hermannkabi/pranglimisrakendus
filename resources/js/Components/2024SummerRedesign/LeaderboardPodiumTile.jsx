import { getDisplayName, showPublicName } from "@/utils/display_name";
import SizedBox from "../SizedBox";
import StreakActiveWidget from "./StreakActiveWidget";

export default function LeaderboardPodiumTile({auth, e, firstPlace=false, customLink=null}){
    console.log(e);
    
    return  <div style={{flex:"1 1 0px", textAlign:"center"}}>
                <span style={{fontSize: firstPlace ? "28px" : "24px", fontWeight:firstPlace ? "bold" : null}}>{e.place}</span>
                <br />
                <span style={{fontSize: "16px", color:"var(--grey-color)"}}>{e.xp} XP</span>

                <a style={{all:"unset"}} href={customLink ?? ("/profile/"+e.user.id)}>
                    <div style={{marginBlock:window.innerWidth < 600 ? "0" : null}} className="section clickable">
                        <i translate="no" style={{fontSize: firstPlace ? "50px" : "32px", color: ["2", "T2"].includes(e.place) ? "#9F9F9F" : ["1", "T1"].includes(e.place) ? "#F1C93C" : "#B78D65"}} className="material-icons-outlined">{firstPlace ? "trophy" : "workspace_premium"}</i>
                        <SizedBox height={window.innerWidth < 600 ? "0" : ["1", "T1", "2", "T2"].includes(e.place) ? "32px" : "12px"} />
                        {!firstPlace && <p style={{textTransform:"capitalize", marginBlock:"4px", display:"inline-flex", alignItems:'center', gap:"4px", fontWeight:auth.user.id == e.user.id ? "bold" : null, color: auth.user.id == e.user.id ? "rgb(var(--primary-color))" : null}}>{getDisplayName(auth.user, e.user)} {e.playedToday && <StreakActiveWidget />} </p>}

                        {firstPlace && <>
                            {e.playedToday && <> <StreakActiveWidget /> <br /> </> }
                            {!showPublicName(auth.user, e.user) &&
                            <><span style={{textTransform:"capitalize", marginBlock:"4px", fontWeight:"bold", fontSize:"20px", color: auth.user.id == e.user.id ? "rgb(var(--primary-color))" : null}}>{e.user.eesnimi}</span> <br />
                            <span style={{textTransform:"capitalize", marginTop:"0", marginBottom:"4px", color: auth.user.id == e.user.id ? "rgb(var(--primary-color), 0.6)" : "var(--grey-color)"}}>{e.user.perenimi}</span></>
                            }
                            {showPublicName(auth.user, e.user) &&
                                <span style={{textTransform:"capitalize", marginBlock:"4px", fontWeight:"500", fontSize:"20px", color: auth.user.id == e.user.id ? "rgb(var(--primary-color))" : null}}>{e.user.public_name}</span>
                            }
                        </>}
                        <br />
                        {window.innerWidth < 600 && <span style={{fontSize: "16px", color:"var(--grey-color)"}}>{e.xp} XP</span>}
                    </div>
                </a>
            </div>;
}