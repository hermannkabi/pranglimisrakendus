import ApplicationLogo from "./ApplicationLogo";
import SizedBox from "./SizedBox";
import "/public/css/navbar.css";

export default function Navbar({user}){
    return (
        <>
            <div className="navbar" style={{display:"flex", alignItems:"center", justifyContent:"space-between", width:"100%"}}>
                <a href={route("dashboard")} style={{all:"unset"}}>
                    <div className="header" style={{display:"inline-flex",}}>
                        <ApplicationLogo style={{cursor:"pointer"}} height="60px"/>
                    </div>
                </a>
                <a href={route("profilePage")} style={{all:"unset"}}>
                    <div className="profile-btn" style={{right:"0"}}>
                        <p className="name-text">{user == null ? "Mari Maasikas" : user.name}</p>
                        <p className="school-text">Kooli Põhikool</p>
                    </div>
                </a>
               
            </div>
            <SizedBox height="8px" />

        </>
        
    );
}