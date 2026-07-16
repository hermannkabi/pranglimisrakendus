import { useState } from "react";
import SizedBox from "../SizedBox";

export default function Title({title, showBack=true, subtitleUser=null}){

   const [isValid, setIsValid] = useState(null);

   return <>        
        <div style={{margin:"16px 0", display:"flex", flexDirection:"column", alignItems:"center"}}>
                {!showBack && <SizedBox height={16} />}
                <div className="music-tiles" style={{display:"flex", flexDirection:"column", justifyContent:"center", borderRadius:"4px", backgroundColor:"var(--section-color)"}}>
                        <h1 style={{fontSize:"32px"}}>{title}</h1>
                </div>
                {subtitleUser && <div style={{display:"flex", flexDirection:"row", gap:"16px", alignItems:'center'}}><img className="profile-pic" style={{height:"32px"}} src={subtitleUser.profile_pic} alt="" /> <p style={{textTransform:"capitalize", marginBlock:"0"}}>{subtitleUser.eesnimi} {subtitleUser.perenimi}</p> </div> }
        </div>

   </>;
}