import { useRef, useState } from "react";
import SizedBox from "../SizedBox";

export default function GameSectionDropdown({title, gameTypes=[], isNew=false}){

    const [collapsed, setCollapsed] = useState(false);

    const collapsibleRef = useRef(null);
    const iconRef = useRef(null);


    const gameNames = {
        "lihtsustamine":"Murru taandamine",
        "murruTaandamine":"Murru taandamine",
        "jaguvus":"Jaguvusseadused",
        "muusika": "Muusika kuulamiskavad",
        "valimised": "Rebaste valimised",
    };

    const links = {
        "muusika":"/muusika",
        "valimised":"/valimised",

    }
    
    function getGameName(type){
        return type == null ? "Tundmatu" : type in gameNames ? gameNames[type] : type.substring(0, 1).toUpperCase() + type.substring(1);
    }

    function toggleCollapsed(){
        collapsed ? $(collapsibleRef.current).slideDown(200) : $(collapsibleRef.current).slideUp(200);
        $(iconRef.current).css("transform", "rotate("+(collapsed ? "0deg" : "180deg")+")");
        setCollapsed(collapsed => !collapsed);
    }

    return <>
        <div>
            <div onClick={toggleCollapsed} style={{display:"flex", flexDirection:"row", gap:"8px", cursor:"pointer"}}>
                <h3 style={{marginBlock:"0", textAlign:"left"}}>{title} {isNew && <span style={{backgroundColor:"rgb(var(--primary-color))", color:"rgb(var(--primary-btn-text-color))", fontSize:"12px", padding:"4px 8px", borderRadius:"50px"}}>UUS!</span>} </h3>
                <i translate="no" ref={iconRef} style={{transition:"transform 200ms", height:"24px", width:"24px", color:"var(--primary-header-color)"}} className="material-icons">keyboard_arrow_up</i>
            </div>

            <div ref={collapsibleRef}>
                <SizedBox height="8px" />
                <div >
                    {gameTypes.map((e)=>{
                        return <div className="sidebar-link" style={{display:"flex", flexDirection:"row", gap:"4px", alignItems:"center"}}>
                            <a key={e} href={e in links ? links[e] : "/preview/"+e} style={{all:"unset", fontWeight:decodeURIComponent(window.location.pathname).startsWith(e in links ? links[e] : "/preview/"+e)  ? "bold" : "normal", cursor:"pointer", display:"block", marginBlock:"6px", textAlign:"left", marginLeft:"16px"}}>{getGameName(e)}</a>
                            <i className="material-icons">arrow_forward</i>
                        </div>
                    })}
                </div>
            </div>
            <SizedBox height="24px" />

        </div>
    </>;
}