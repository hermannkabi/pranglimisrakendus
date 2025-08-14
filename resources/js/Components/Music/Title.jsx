import { useState } from "react";
import SizedBox from "../SizedBox";

export default function Title({title, showBack=true, subtitleUser=null}){

   const [isValid, setIsValid] = useState(null);

   return <>        
        <div style={{margin:"16px 24px", display:"flex", flexDirection:"column", alignItems:"center"}}>
                {!showBack && <SizedBox height={16} />}
                <h1 className="big-title" style={{marginBlock:"0", color:"rgb(var(--primary-color))"}}>{title}</h1>
                {subtitleUser && <div style={{display:"flex", flexDirection:"row", gap:"16px", alignItems:'center'}}><img className="profile-pic" style={{height:"32px"}} src={subtitleUser.profile_pic} alt="" /> <p style={{textTransform:"capitalize", marginBlock:"0"}}>{subtitleUser.eesnimi} {subtitleUser.perenimi}</p> </div> }
        </div>

        <SizedBox height={80} />

        <img src="/assets/music-icons/pattern.png" style={{height:"450px", pointerEvents:"none"}} alt="" className="pattern" />
   </>;
}