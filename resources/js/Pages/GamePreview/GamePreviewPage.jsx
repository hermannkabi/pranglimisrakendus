import { useState, useEffect, useRef } from "react";
import Layout from "@/Components/2024SummerRedesign/Layout";
import TwoRowTextButton from "@/Components/2024SummerRedesign/TwoRowTextButton";
import InfoBanner from "@/Components/InfoBanner";
import Chip from "@/Components/2024SummerRedesign/Chip";
import BigButton from "@/Components/2024SummerRedesign/BigButton";
import VerticalStatTile from "@/Components/2024SummerRedesign/VerticalStatTile";


// Type in this context is the type of game (e.g. liitmine), not types as in natural, fraction etc
export default function GamePreviewPage({auth, type}){
    const urlParams = new URLSearchParams(window.location.search);

    const typeIndependents = ["lünkamine", "võrdlemine", "choose", "murruTaandamine"];
    const guideAvailable = ["liitmine", "korrutamine", "jaguvus", "kujundid", "astendamine", "võrdlemine"];

    const typeToName = {"natural":"Naturaalarvud", "integer":"Täisarvud", "fraction":"Kümnendmurrud", "roman":"Rooma numbrid"};

    const gameNames = {
        "lihtsustamine":"Murru taandamine",
        "murruTaandamine":"Murru taandamine",
        "jaguvus":"Jaguvusseadused"
    };
    
    function getGameName(type){
        return type == null ? "Tundmatu" : type in gameNames ? gameNames[type] : type.substring(0, 1).toUpperCase() + type.substring(1);
    }

    const data = {
        "liitmine":{
            "lvls":5,
            "extra":["A", "B", "C"],
            "types":["natural", "integer", "fraction", "roman"],
        },
        // If the value is a string, go to said key
        "lahutamine":"liitmine",
        "liitlahutamine":"liitmine",

        "korrutamine":{
            "lvls":6,
            "extra":["A", "B", "C"],
            "types":["natural", "integer", "fraction", "roman"],

        },
        "jagamine":"korrutamine",
        "korrujagamine":"korrutamine",

        "lünkamine":{
            "lvls":5,
            "extra":["A", "B", "C"],
            "types":["natural", "integer", "fraction"],
        },

        "võrdlemine":{
            "lvls":3,
            "extra":[],
            "types":[],
        },

        "astendamine":{
            "lvls":5,
            "extra":["A", "B", "C"],
            "types":[{value:"natural", label:"Naturaalarvud"}, {value:"fraction", label:"Harilikud murrud"}],
        },
        "juurimine":"astendamine",
        "astejuurimine":"astendamine",
        "jaguvus":{
            "lvls":5,
            "extra":["A", "B", "C"],
            "types":["natural", "integer"],
        },
        "murruTaandamine":{
            "lvls":6,
            "extra":["A", "B", "C"],
            "types":[],
        },
        "kujundid":{
            "lvls":5,
            "extra":[],
            "types":[{value: "kujundid", label: "Tavaline"}, {value: "color", label: "Erinevad värvid"}, {value: "size", label: "Erinevad suurused"}, {value: "all", label: "Erinevad värvid ja suurused"}],
        },
    };

    function getInitialGameMode(){
        var fromURL = urlParams.get("type");
        var types = (typeof data[type] == "string" ? data[data[type]] : data[type])["types"];

        if(typeIndependents.includes(type)) return type;

        if(typeof types[0] == "string"){
            if(types.includes(fromURL)){
                return fromURL;
            }else{
                return types[0];
            }
        }else{
            if(types.map(e=>e["value"]).includes(fromURL)){
                return fromURL;
            }else{
                return types[0]["value"];
            }
        }
    }

    function getAllGameModes(){
        var types = (typeof data[type] == "string" ? data[data[type]] : data[type])["types"];

        if(typeIndependents.includes(type)) return [type];

        if(typeof types[0] == "string") return types;
        return types.map(e=>e["value"]);
    }

    const [message, setMessage] = useState();
    const [levels, setLevels] = useState(Array.from(Array((typeof data[type] == "string" ? data[data[type]] : data[type])["lvls"])).map((x,i)=>i+1));
    const [gameTime, setGameTime] = useState(Math.min((Math.max(0.5, urlParams.get("time") != null ? urlParams.get("time") : (window.localStorage.getItem("default-time") ?? "0.5"))), 10));
    const [selectedGameMode, setSelectedGameMode] = useState(getInitialGameMode());

    useEffect(function (){
        window.history.replaceState(null, null, getParams());    
    }, [selectedGameMode, gameTime, levels]);

    function navigateToGame(){
        if(selectedGameMode == null || !getAllGameModes().includes(selectedGameMode)){
            setMessage("Palun vali arvuhulk" + selectedGameMode);
            return;
        }
        window.location.href = "/game/"+levels.join("")+"/"+type+"/"+gameTime+"/"+selectedGameMode;
    }

    function getParams(){
        return "?time=" + (gameTime) + ("&type="+selectedGameMode); 
    }

    return <>
        <Layout title="Mängu eelvaade" auth={auth}>
            <div className="two-column-layout">
                <div>
                    {message && <div className="section">
                        <InfoBanner text={message} />
                    </div>}
                    <div className="section" style={{display:"flex", justifyContent:"space-between", alignItems:'center', marginTop:"0"}}>
                        <TwoRowTextButton showArrow={false} upperText="Mänguaeg" lowerText="Kui pikalt mängid?" />

                        <div style={{display:"inline-flex", flexDirection:"row", alignItems:'center', gap:"8px", marginRight:"8px"}}>
                            <i translate="no" onClick={()=>setGameTime(defaultTime => parseFloat(defaultTime) >= 9.5 ? 10 : parseFloat(defaultTime) + 0.5)} style={{color: gameTime >= 10 ? "var(--grey-color)" : "rgb(var(--primary-color))", fontSize:"32px"}} className="material-icons">add</i>
                            <div style={{width:"75px", textAlign:"center", marginBlock:"8px", }}>
                                <h2 style={{marginBlock:"0", color:"rgb(var(--primary-color))", fontSize:"40px"}}>{gameTime == "0" ? "-" : gameTime.toString().replaceAll(".", ",")}</h2>
                                <p style={{color:"var(--grey-color)", marginBlock:"0"}}>min</p>
                            </div>
                            <i translate="no" onClick={()=>setGameTime(defaultTime => parseFloat(defaultTime) <= 0.5 ? 0.5 : parseFloat(defaultTime) - 0.5)} style={{color: gameTime <= 0.5 ? "var(--grey-color)" : "rgb(var(--primary-color))", fontSize:"32px"}} className="material-icons">remove</i>
                        </div>
                    </div>

                    {!(typeIndependents.includes(type)) && <div className="section" style={{marginTop:"0", padding:"8px 16px", display:"flex", flexDirection:"row", justifyContent:"space-between", alignItems:"center"}}>
                        <VerticalStatTile padding="8px 0" icon="pin" text="Arvuhulk" customValue={true} value={<>
                            <div>
                                {(typeof data[type] == "string" ? data[data[type]]["types"] : data[type]["types"]).map((e)=> <Chip key={e} onClick={()=>setSelectedGameMode(typeof e == "string" ? e : e["value"])} label={typeof e == "string" ? typeToName[e] : e["label"]} active={typeof e == "string" ? selectedGameMode == e : selectedGameMode == e["value"]} /> )}
                            </div>
                        </>} />
                    </div>}
                    {typeIndependents.includes(type) && <div className="section"><InfoBanner text={"Sellel mängutüübil ei ole võimalik arvuhulka muuta"} /></div> }

                    <div className="section" style={{marginTop:"0", padding:"8px 16px", display:"flex", flexDirection:"row", justifyContent:"space-between", alignItems:"center"}}>
                        <VerticalStatTile padding="8px 0" icon="exercise" text="Tasemed" customValue={true} value={
                            <div>
                                {Array.from(Array((typeof data[type] == "string" ? data[data[type]] : data[type])["lvls"])).map((x, e)=><Chip key={e} onClick={()=>{

                                    var newLevels = [...levels];

                                    if(newLevels.includes(e+1)){
                                        if(newLevels.length <= 1) return;
                                        newLevels = newLevels.filter(item => item != e+1);
                                    }else{
                                        newLevels.push(e+1);
                                    }

                                    newLevels.sort();

                                    return setLevels(newLevels);
                                }} label={(e+1)+". tase"} active={levels.includes(e+1)} /> )}
                                <br /><br/>

                                {(typeof data[type] != "string" ? data[type]["extra"] : data[data[type]]["extra"]).map((e, ind)=> <Chip key={ind} icon={"star"} label={(ind + 1) +". tase"} active={levels.includes(e)} onClick={()=>{
                                    var newLevels = [...levels];

                                    if(newLevels.includes(e)){
                                        if(newLevels.length <= 1) return;
                                        newLevels = newLevels.filter(item => item != e);
                                    }else{
                                        newLevels.push(e);
                                    }

                                    newLevels.sort();
                                    
                                    return setLevels(newLevels);
                                }} />)}
                            </div>} />                        
                    </div>
                </div>

                {/* Teine tulp */}
                <div>
                    <div className="section" style={{marginTop:"0", padding:"8px 16px", display:"flex", flexDirection:"row", justifyContent:"space-between", alignItems:"center"}}>
                        <VerticalStatTile padding="8px 0" icon="calculate" text="Mängutüüp" value={getGameName(type)} />
                        {guideAvailable.includes(type) && <a style={{all:"unset"}} href={route("guide") + "#"+type}><i translate="no" style={{fontSize:"40px"}} className="material-icons-outlined clickable">info</i></a> }
                    </div>

                    <BigButton onClick={navigateToGame} title={"Alusta"} subtitle={"Head mängu!"} />
                </div>
            </div>
        </Layout>
    </>;

}