import SizedBox from "./SizedBox";

export default function ProfileWidget({auth, user, onImgChange=null, isPublic=false}){
    return <>
        <SizedBox height={32} />
        <div className="" style={{display:'flex', flexWrap:"wrap", justifyContent:"center", alignItems:"center", gap:"16px"}}>
            <div style={{overflow:"hidden"}}>
                <div  className="profile-widget" style={{display:"flex", flexDirection:"row", gap:"16px", alignItems:"center"}}>
                    {auth.user.id == user.id && !isPublic && <div style={{position:"relative", display:"inline", height:"fit-content"}} onClick={auth.user.role == "guest" ? null : onImgChange}>
                        <img style={{height:"64px", userSelect:"none"}} className="profile-pic" src={auth.user.profile_pic} alt={auth.user.eesnimi + " " + auth.user.perenimi} />
                        {auth.user.role != "guest" && <span style={{cursor:"pointer", position:"absolute", bottom:"0", right:"0", backgroundColor:"rgb(var(--primary-color), 0.9)", color:"white", borderRadius:"50%", padding:"4px", fontSize:"12px"}} className="material-icons">edit</span>}
                    </div>}
                    
                    {(auth.user.id != user.id || isPublic) && <img style={{height:"64px", userSelect:"none"}} className="profile-pic" src={user.profile_pic} alt={user.eesnimi + " " + user.perenimi} />}
                    <div className="name-email" style={{textAlign:"start", marginBottom:"8px"}}>
                        <div style={{}}><h1 translate="no" style={{marginTop:"4px", marginBottom:"0", textTransform:"capitalize", display:"inline", verticalAlign:"middle"}}>{user.eesnimi} {user.perenimi} </h1> {user.role != "student" && <span style={{backgroundColor:"rgb(var(--primary-color))", borderRadius:"4px", color:"white", fontSize:"12px", padding:"2px 4px", fontWeight:"normal", marginTop:"6px"}}>{user == null ? "Õpilane" : user.role == "teacher" ? "Õpetaja" : user.role == "guest" ? "Külaline" : user.role == null ? "Tavakonto" : user.role}</span>}</div>
                        {user.email.length > 0 && <p translate="no" style={{marginBottom:"0", color:"grey", fontSize:"20px", marginTop:"0"}}>{user == null ? "mari.maasikas@real.edu.ee" : user.email}</p>}
                    </div>
                </div>
            </div>
        </div>
    </>;
}