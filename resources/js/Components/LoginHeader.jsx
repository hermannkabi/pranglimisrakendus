import ApplicationLogo from "@/Components/ApplicationLogo";
import SizedBox from "./SizedBox";
import "/public/css/auth_layout.css";



export default function LoginHeader({pageName, description="REAALER", topMargin="100px", googleLogo=false, forceCenter=false}){

    const google = "/assets/google_logo.png";

    const size = 75;

    return (
        <div className="auth-header" style={forceCenter ? {textAlign:"center"} : {}}>
            <SizedBox height="8px" />
            {!googleLogo && <a style={{all:"unset"}} href={route("welcome")}> <ApplicationLogo height="75px" style={{marginBottom:"16px"}} /> </a>}
            {googleLogo && <img src={google} height={size} width={size} style={{userSelect:"none"}} />}
            <h1 style={{marginBlock: "0"}}>{pageName}</h1>
            <p style={{marginTop: "0", fontSize:"16px"}}>{description}</p>
        </div>
    );
}