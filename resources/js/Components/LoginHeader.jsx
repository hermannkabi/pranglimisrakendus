import ApplicationLogo from "@/Components/ApplicationLogo";


export default function LoginHeader({pageName}){
    return (
        <>
            <div style={{height:"100px"}}></div>
            <ApplicationLogo height="100px" style={{marginBottom:"16px"}} />
            <h1 style={{marginBlock: "0"}}>{pageName}</h1>
            <p style={{marginTop: "0", fontSize:"16px"}}>PRANGLIMISRAKENDUS</p>
        </>
    );
}