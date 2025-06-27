import { useState, useEffect } from "react";
import Layout from "@/Components/2024SummerRedesign/Layout";
import TwoRowTextButton from "@/Components/2024SummerRedesign/TwoRowTextButton";
import InfoBanner from "@/Components/InfoBanner";
import Chip from "@/Components/2024SummerRedesign/Chip";
import BigButton from "@/Components/2024SummerRedesign/BigButton";
import VerticalStatTile from "@/Components/2024SummerRedesign/VerticalStatTile";
import TimeSelector from "@/Components/2024SummerRedesign/TimeSelector";


// Type in this context is the type of game (e.g. liitmine), not types as in natural, fraction etc
export default function GamePreviewPage({auth, type, competition=null, attemptsLeft}){
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

    const competitionGameData = competition == null ? null : JSON.parse(competition.game_data);
    const competitionAllowedTypes = competition == null ? null : competitionGameData["mis"].split(",");

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

        if(competition != null) return competitionGameData["tyyp"];

        var fromURL = urlParams.get("type");
        var types = (typeof data[typeState] == "string" ? data[data[typeState]] : data[typeState])["types"];

        if(typeIndependents.includes(typeState)) return typeState;

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
        var types = (typeof data[typeState] == "string" ? data[data[typeState]] : data[typeState])["types"];

        if(typeIndependents.includes(typeState)) return [typeState];

        if(typeof types[0] == "string") return types;
        return types.map(e=>e["value"]);
    }

    const [typeState, setTypeState] = useState(type);
    const [message, setMessage] = useState();
    const [levels, setLevels] = useState(Array.from(Array((typeof data[type] == "string" ? data[data[type]] : data[type])["lvls"])).map((x,i)=>i+1));
    const [gameTime, setGameTime] = useState(auth.user.role == "guest" ? 0.5 : competition != null ? competitionGameData["aeg"] : Math.min((Math.max(0.5, urlParams.get("time") != null ? urlParams.get("time") : (window.localStorage.getItem("default-time") ?? "0.5"))), 10));
    const [selectedGameMode, setSelectedGameMode] = useState(getInitialGameMode());

    useEffect(function (){
        window.history.replaceState(null, null, getParams());    
    }, [selectedGameMode, gameTime, levels]);

    useEffect(function (){
        setSelectedGameMode(getInitialGameMode());
        setLevels(Array.from(Array((typeof data[typeState] == "string" ? data[data[typeState]] : data[typeState])["lvls"])).map((x,i)=>i+1));
    }, [typeState]);

    function navigateToGame(){
        if(selectedGameMode == null || !getAllGameModes().includes(selectedGameMode)){
            setMessage("Palun vali arvuhulk" + selectedGameMode);
            return;
        }

        $.post(route("previewPost"), {
            "_token":window.csrfToken,
            "level": competition != null ? competitionGameData["level"] : levels.join(""),
            "mis": typeState,
            "aeg": gameTime,
            "tyyp": selectedGameMode,
            "competition_id":competition == null ? null : competition.competition_id,
        }).done(function (data){
            window.location.href = "/game";
        }).fail(function (data){
            console.log("Viga");
            console.log(data);
        });
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
                    {auth.user.role == 'guest' && <div className="section">
                        <InfoBanner text="Külaliskontoga on mänguaeg piiratud 30 sekundile" />
                    </div>}
                    {competition != null && <InfoBanner text={"Oled alustamas mängu võistlusel "+competition.name+(attemptsLeft == -1 ? ". Sellel võistlusel on võimalik mängida piiramatu arv kordi." : ". Sellel võistlusel on sul jäänud veel "+attemptsLeft+" mängukorda.")} />}
                    {competition != null && competitionAllowedTypes.length > 1 && <div className="section">
                        <VerticalStatTile padding="8px 0" icon="joystick" text="Vali mängutüüp" customValue={true} value={
                            <div>
                                {competitionAllowedTypes.map((e)=><Chip key={e} label={getGameName(e)} active={typeState==e} onClick={()=>setTypeState(e)} />)}
                            </div>} />  
                    </div>}

                    <div className="section" style={{display:"flex", justifyContent:"space-between", alignItems:'center', marginTop:"0"}}>
                        <TwoRowTextButton showArrow={false} upperText="Mänguaeg" lowerText="Kui pikalt mängid?" />
                        <TimeSelector time={gameTime} onIncrease={auth.user.role == "guest" || competition != null ? null : ()=>setGameTime(defaultTime => parseFloat(defaultTime) >= 9.5 ? 10 : parseFloat(defaultTime) + 0.5)} onDecrease={auth.user.role == "guest" || competition != null ? null : ()=>setGameTime(defaultTime => parseFloat(defaultTime) <= 0.5 ? 0.5 : parseFloat(defaultTime) - 0.5)} />
                    </div>

                    {!(typeIndependents.includes(typeState)) && <div className="section" style={{marginTop:"0", padding:"8px 16px", display:"flex", flexDirection:"row", justifyContent:"space-between", alignItems:"center"}}>
                        <VerticalStatTile padding="8px 0" icon="pin" text="Arvuhulk" customValue={true} value={<>
                            {competition == null && <div>
                                {(typeof data[typeState] == "string" ? data[data[typeState]]["types"] : data[typeState]["types"]).map((e)=> <Chip key={typeof e == "string" ? e : e["value"]} onClick={competition != null ? null : ()=>setSelectedGameMode(typeof e == "string" ? e : e["value"])} label={typeof e == "string" ? typeToName[e] : e["label"]} active={typeof e == "string" ? selectedGameMode == e : selectedGameMode == e["value"]} /> )}
                            </div>}
                            {competition != null && <p style={{color:"var(--grey-color)", marginBottom:"0", fontWeight:"bold", fontSize:"20px"}}>{typeToName[selectedGameMode]}</p> }
                        </>} />
                    </div>}
                    {typeIndependents.includes(typeState) && <div className="section"><InfoBanner text={"Sellel mängutüübil ei ole võimalik arvuhulka muuta"} /></div> }

                    <div className="section" style={{marginTop:"0", padding:"8px 16px", display:"flex", flexDirection:"row", justifyContent:"space-between", alignItems:"center"}}>
                        <VerticalStatTile padding="8px 0" icon="exercise" text="Tasemed" customValue={true} value={
                            <>
                                {competition != null && competitionGameData["level"].split("").map((e, i)=><Chip key={i} label={e + ". tase"} />) }
                                {competition == null && <div>
                                    {Array.from(Array((typeof data[typeState] == "string" ? data[data[typeState]] : data[typeState])["lvls"])).map((x, e)=><Chip key={e} onClick={()=>{

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

                                    {(typeof data[typeState] != "string" ? data[typeState]["extra"] : data[data[typeState]]["extra"]).map((e, ind)=> <Chip key={ind} icon={"star"} label={(ind + 1) +". tase"} active={levels.includes(e)} onClick={()=>{
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
                                </div>}
                            </>
                        } />                        
                    </div>
                </div>

                {/* Teine tulp */}
                <div>
                    <div className="section" style={{marginTop:"0", padding:"8px 16px", display:"flex", flexDirection:"row", justifyContent:"space-between", alignItems:"center"}}>
                        <VerticalStatTile padding="8px 0" icon="calculate" text="Mängutüüp" value={getGameName(typeState)} />
                        {guideAvailable.includes(typeState) && <a style={{all:"unset"}} href={route("guide") + "#"+typeState}><i translate="no" style={{fontSize:"40px"}} className="material-icons-outlined clickable">info</i></a> }
                    </div>

                    <BigButton onClick={navigateToGame} title={"Alusta"} subtitle={competition != null ? "Head võistlemist!" : "Head mängu!"} />
                </div>
            </div>
        </Layout>
    </>;

}