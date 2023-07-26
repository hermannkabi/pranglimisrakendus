import ApplicationLogo from "@/Components/ApplicationLogo";
import SizedBox from "./SizedBox";


export default function LoginHeader({pageName, description="PRANGLIMISRAKENDUS", topMargin="100px"}){
    return (
        <>
            <SizedBox height={topMargin} />
            <ApplicationLogo height="100px" style={{marginBottom:"16px"}} />
            <h1 style={{marginBlock: "0"}}>{pageName}</h1>
            <p style={{marginTop: "0", fontSize:"16px"}}>{description}</p>
        </>
    );
}