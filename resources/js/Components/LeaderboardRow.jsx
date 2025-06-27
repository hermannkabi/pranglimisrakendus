import StreakActiveWidget from "./2024SummerRedesign/StreakActiveWidget";
import TwoRowTextButton from "./2024SummerRedesign/TwoRowTextButton";
import SizedBox from "./SizedBox";

export default function leaderboardRow({place, points, index, player, user, playedToday=false}){
    var isPodium = ["1", "2", "3", "T1", "T2", "T3"].includes(place);
    var isFirst = ["1", "T1"].includes(place);
    var isSecond = ["2", "T2"].includes(place);
    var isThird = ["3", "T3"].includes(place);

    return (
        <a style={{all:"unset", cursor:"pointer"}} href={"/profile/"+user.id}>
            <div className="section clickable" style={{position:"relative", marginBlock:"8px", display:"flex", flexDirection:"row", justifyContent:"space-between", alignItems:"center"}}>
                <div style={{display:"flex", flexDirection:"row", alignItems:"center"}}>
                    <div style={{height:"50px", fontSize:"24px", aspectRatio: "1", borderRadius:"50%", display:"flex", alignItems:"center", justifyContent:"center", fontWeight: (isPodium ? "bold" : "normal")}} className={isFirst ? "fancy-div fancy" : isSecond ? "fancy2-div fancy2" : isThird ? "fancy3-div fancy3" : "leaderboard-regular"}><span>{place ?? (index + 1)}</span></div>
                    <SizedBox width={12} />
                    <TwoRowTextButton upperColor={player ? "rgb(var(--primary-color))" : null} lowerColor={player ? "rgba(var(--primary-color), 0.65)" : null} showArrow={false} capitalizeLower={true} capitalizeUpper={true} upperText={user.eesnimi} lowerText={user.perenimi} />
                    <SizedBox width="4px" />
                    {playedToday && <StreakActiveWidget />}
                </div>

                {points != null && <div style={{position:"absolute", bottom:"8px", right:"8px", color:"var(--grey-color)"}}>
                    <span >{points} XP</span>
                </div>}
            </div>
        </a>
    );
}