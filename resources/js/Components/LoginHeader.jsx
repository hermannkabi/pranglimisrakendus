import ApplicationLogo from "@/Components/ApplicationLogo";
import SizedBox from "./SizedBox";


export default function LoginHeader({pageName, description="PRANGLIMISRAKENDUS", topMargin="100px", googleLogo=false}){

    const windowWidth = window.innerWidth;
    const google = "https://upload.wikimedia.org/wikipedia/commons/thumb/c/c1/Google_%22G%22_logo.svg/1024px-Google_%22G%22_logo.svg.png";


    return (
        <>
            <SizedBox height={windowWidth <= 600 ? "16px" : topMargin} />
            {!googleLogo && <ApplicationLogo height="100px" style={{marginBottom:"16px"}} />}
            {googleLogo && <img src={google} height={100} width={100} />}
            <h1 style={{marginBlock: "0"}}>{pageName}</h1>
            <p style={{marginTop: "0", fontSize:"16px"}}>{description}</p>
        </>
    );
}