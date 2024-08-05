import { useRef, useState } from "react";
import SizedBox from "../SizedBox";

export default function GameSectionDropdown({title, gameTypes=[]}){

    const [collapsed, setCollapsed] = useState(false);

    const collapsibleRef = useRef(null);
    const iconRef = useRef(null);


    const gameNames = {
        "lihtsustamine":"Murru taandamine",
        "murruTaandamine":"Murru taandamine",
        "jaguvus":"Jaguvusseadused"
    };
    
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
                <h3 style={{marginBlock:"0", textAlign:"left"}}>{title}</h3>
                <i ref={iconRef} style={{transition:"transform 200ms", height:"24px", width:"24px", color:"var(--primary-header-color)"}} className="material-icons">keyboard_arrow_up</i>
            </div>

            <div ref={collapsibleRef}>
                <SizedBox height="8px" />
                <div >
                    {gameTypes.map((e)=><a key={e} href={"/preview?id="+e} style={{all:"unset", cursor:"pointer", display:"block", marginBlock:"6px", textAlign:"left", marginLeft:"16px"}}>{getGameName(e)}</a>)}
                </div>
            </div>
            <SizedBox height="24px" />

        </div>
    </>;
}