import { useState } from "react";
import SizedBox from "../SizedBox";

export default function Title({title, showBack=true, thumbnail=null, subtitleUser=null}){

   const [isValid, setIsValid] = useState(null);

   return <>
        <div style={{display:"flex", flexDirection:"column", gap:"8px", alignItems:"start", margin:"16px 24px"}}>
                {showBack && <a style={{all:"unset", cursor:"pointer"}} onClick={()=>history.back()}><img className="music-icon" src="/assets/music-icons/left-arrow.png" alt="" /></a>}
                {!showBack && <SizedBox height={16} />}
                <div style={{display:"flex", flexDirection:"row", alignItems:"center", gap:"16px"}}>
                        {thumbnail && isValid && <img src={thumbnail} alt="" style={{height:"50px", aspectRatio:"1", borderRadius:"4px", objectFit:"cover"}} /> }
                        <h1 className="big-title" style={{marginBlock:"0"}}>{title}</h1>
                </div>
                {subtitleUser && <div style={{display:"flex", flexDirection:"row", gap:"16px", alignItems:'center'}}><img className="profile-pic" style={{height:"32px"}} src={subtitleUser.profile_pic} alt="" /> <p style={{textTransform:"capitalize", marginBlock:"0"}}>{subtitleUser.eesnimi} {subtitleUser.perenimi}</p> </div> }
        </div>

        <SizedBox height={80} />

        <img src="/assets/music-icons/pattern.png" style={{height:"450px"}} alt="" className="pattern" />
   </>;
}