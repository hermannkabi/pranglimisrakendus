import { getDisplayName } from "@/utils/display_name";

export default function DashboradLeaderboardWidget({place, user, auth}){
    return <div style={{display:"flex", flexDirection:"row", justifyContent:"start", alignItems:'center', gap:"8px"}}>
        <div style={{backgroundColor: user.id == auth.user.id ? "var(--grey-color)" : "var(--lightgrey-color)", fontSize:"16px", color:"white", borderRadius:"50%", display:"inline-flex", justifyContent:"center", alignItems:'center', height:"28px", aspectRatio:"1"}}> <span>{place}</span> </div>
        <p style={{textTransform:"capitalize", fontWeight:user.id == auth.user.id ? 'bold' : null}}>{getDisplayName(auth.user, user)}</p>
    </div>;
}