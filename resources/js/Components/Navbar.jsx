import ApplicationLogo from "./ApplicationLogo";
import SizedBox from "./SizedBox";
import "/public/css/navbar.css";

export default function Navbar({title, user}){
    return (
        <>
            <div className="navbar" style={{display:"flex", alignItems:"center", justifyContent:"space-between", width:"100%"}}>
                <a href={route("dashboard")} style={{all:"unset"}}>
                    <div className="header" style={{display:"inline-flex",}}>
                        <ApplicationLogo style={{cursor:"pointer"}} height="60px"/>
                        <div className="navbar-page-titles" style={{textAlign:"start", display:"inline-block", verticalAlign:"center", marginLeft:"16px"}}>
                            <h4 style={{marginBlock:"0", cursor:"pointer"}}>PRANGLIMISRAKENDUS</h4>
                            <p style={{marginBlock:"0"}}>{title}</p>
                        </div>
                    </div>
                </a>
                <a href={route("profilePage")} style={{all:"unset"}}>
                    <div className="profile-btn" style={{right:"0"}}>
                        <p className="name-text">{user == null ? "Mari Maasikas" : user.name}</p>
                        <p className="school-text">Kooli Põhikool, 9.a</p>
                    </div>
                </a>
               
            </div>
            <SizedBox height="8px" />

        </>
        
    );
}