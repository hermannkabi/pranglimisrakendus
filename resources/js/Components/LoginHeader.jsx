import ApplicationLogo from "@/Components/ApplicationLogo";
import SizedBox from "./SizedBox";
import "/public/css/auth_layout.css";



export default function LoginHeader({pageName, description="REAALER", topMargin="100px", forceCenter=false}){

    const google = "/assets/google_logo.png";

    const size = 75;

    return (
        <>
        <div className="auth-header" style={{display:"flex", flexDirection:"column", justifyContent:"space-between", alignItems:"start"}}>
           <a style={{all:"unset", cursor:"pointer"}} href={route("welcome")}> <div style={{display:"flex", flexDirection:"row", gap:"8px", fontWeight:"bold", alignItems:"center"}}>
                <ApplicationLogo color={"rgb(var(--primary-btn-text-color))"} size="80px"/>
            </div></a>
            <div style={{flex:"1"}}>
                <div style={{marginTop:"30vh"}}>
                    <h1 style={{marginBlock: "0", color:"rgb(var(--primary-btn-text-color))"}}>{pageName}</h1>
                    <SizedBox height={12} />
                    <p style={{marginTop: "0", fontSize:"24px", color:"var(--lightgrey-color)"}}>{description}</p>
                </div>
            </div>
        </div>

        <div className="auth-header-mobile" style={{margin:"auto", textAlign:"center", display:"none"}}>
            <SizedBox height={12} />
            <a style={{all:"unset"}} href={route("welcome")}><ApplicationLogo size="75px"/></a>
            <h1 style={{marginBlock: "0", color:"rgb(var(--text-color))"}}>{pageName}</h1>
            <p style={{marginTop: "0", fontSize:"18px", color:"var(--lightgrey-color)"}}>{description}</p>

        </div></>
    );
}