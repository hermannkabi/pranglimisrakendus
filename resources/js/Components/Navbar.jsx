import ApplicationLogo from "./ApplicationLogo";
import SizedBox from "./SizedBox";
import "/public/css/navbar.css";

export default function Navbar({user}){

    const name = (window.localStorage.getItem("first-name") ?? "") + " " + (window.localStorage.getItem("last-name") ?? "");

    const windowWidth = window.innerWidth;

    

    return (
        <>
            <div className="navbar" style={{display:"flex", alignItems:"center", justifyContent:"space-between", width:"100%"}}>
                <a href={route("dashboard")} style={{all:"unset", display:"flex", alignItems:"center"}}>
                    <div className="header" style={{display:"inline-flex",}}>
                        <ApplicationLogo style={{cursor:"pointer"}} height={window.innerWidth > 600 ? "60px" : "48px"}/>
                    </div>
                </a>
                <a href={route("profilePage")} style={{all:"unset"}}>
                    <div className="profile-btn" style={{right:"0", display:"flex", flexDirection:"row", gap:"16px", alignItems:"center"}}>
                        {(windowWidth > 600 || user.profile_pic == null) && <div>
                            <div style={{display:"flex", flexDirection:"row-reverse", alignItems:"center", gap:"4px"}}>
                                {/* <img className="profile-pic" style={{height:"24px"}} src={user.profile_pic} alt="" /> */}
                                <p className="name-text">{user == null ? name.trim().length > 0 ? name : "Mari Maasikas" : user.eesnimi.length <= 0 ? "Nimi puudub" : (user.eesnimi + " " + user.perenimi)}</p>
                            </div>
                            <p className="school-text">Tallinna Reaalkool</p>
                        </div>}
                        <img className="profile-pic" style={{height:"50px"}} src={user.profile_pic} alt="Kasutaja profiilipilt" />

                    </div>
                </a>
               
            </div>
            <SizedBox height="8px" />

        </>
        
    );
}