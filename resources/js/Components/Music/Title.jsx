import SizedBox from "../SizedBox";

export default function Title({title, showBack=true}){
   return <>
        <div style={{display:"flex", flexDirection:"column", gap:"8px", alignItems:"start", margin:"16px 24px"}}>
                {showBack && <a style={{all:"unset", cursor:"pointer"}} onClick={()=>history.back()}><img className="music-icon" src="/assets/music-icons/left-arrow.png" alt="" /></a>}
                {!showBack && <SizedBox height={16} />}
                <h1 className="big-title" style={{marginTop:"0"}}>{title}</h1>
        </div>

        <SizedBox height={64} />

        <img src="/assets/music-icons/pattern.png" style={{height:"450px"}} alt="" className="pattern" />
   </>;
}