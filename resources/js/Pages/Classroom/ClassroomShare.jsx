import SizedBox from "@/Components/SizedBox";
import { Head } from "@inertiajs/react";
import QRCode from "react-qr-code";
import "/public/css/welcome.css";
import ApplicationLogo from "@/Components/ApplicationLogo";


export default function ClassroomShare({klass}){

    return <>
        <Head title="Jaga klassi" />
        <div style={{display:"flex", justifyContent:"center", flexDirection:"column", alignItems:"center", height:"100vh", width:"100vw"}}>
            <div className="section" style={{width: window.innerWidth > 850 ? "70%" : "90%"}}>
                <div className="two-column-welcome" >
                    <div className="text-container">
                        <h1 className="main-title" style={{color:"var(--text-color)"}} >{klass.klass_name}</h1>
                        <p style={{wordWrap:"anywhere"}}>Klassiga liitumiseks skaneeri QR-koodi või kasuta allolevat linki</p>
                        <p style={{wordWrap: "anywhere", userSelect:"all", color:"var(--grey-color)"}}>{window.location.origin + "/classroom/"+klass.uuid+"/join"}</p>
                    </div>

                    <div className="img-container">
                        <QRCode fgColor="rgb(var(--text-color))" bgColor="transparent" size={200} value={window.location.origin + "/classroom/"+klass.uuid+"/join"} />
                    </div>
                </div>
            </div>
        </div>
        
        {/* <div style={{display:"flex", justifyContent:"center", flexDirection:"column", alignItems:"center", height:"100vh", width:"100vw"}}>
            <h1 style={{color:"rgb(var(--primary-color))"}}>{klass.klass_name}</h1>
            
            <div style={{width:"min(90%, 600px)"}}>
                <div className="section" style={{display:"flex", flexDirection:"column", alignItems:"center"}}>
                    <p style={{color:"var(--grey-color)", textAlign:"center"}}>Klassiga ühinemiseks kasuta QR-koodi...</p>
                    <QRCode bgColor="transparent" size={200} value={window.location.origin + "/classroom/"+klass.uuid+"/join"} />
                    <SizedBox height={16} />
                </div>

                <div className="section" style={{display:"flex", flexDirection:"column", alignItems:"center"}}>
                    <p style={{color:"var(--grey-color)"}}>... või liitu läbi selle lingi</p>

                    <p style={{textAlign:"center", userSelect:"all", wordWrap:"anywhere"}}>{window.location.origin + "/classroom/"+klass.uuid+"/join"}</p>
                </div>
            </div>
        </div> */}
    </>;
}