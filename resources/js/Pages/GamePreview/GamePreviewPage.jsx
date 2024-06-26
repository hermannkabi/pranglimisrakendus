import Navbar from "@/Components/Navbar";
import { Head } from "@inertiajs/react";
import "/public/css/preview.css";
import NumberInput from "@/Components/NumberInput";
import SizedBox from "@/Components/SizedBox";
import { useState, useEffect, useRef } from "react";
import CheckboxTile from "@/Components/CheckboxTile";
import LoadingSpinner from "@/Components/LoadingSpinner";

export default function GamePreviewPage({auth}){
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');

    const typeIndependents = ["lünkamine", "võrdlemine", "choose", "murruTaandamine"];

    const [message, setMessage] = useState();
    const [levels, setLevels] = useState([]);
    const [extra, setExtra] = useState([]);
    const [types, setTypes] = useState([]);

    const [loading, setLoading] = useState(false);


    window.addEventListener("beforeunload", function (e) {
        setLoading(false);
    });
    


    // This function is called once when the page is first loaded
    useEffect(()=>{
        // Whether or not the type select is shown
        showNumberType(true);

        showLevels(true);
    }, []);


    function getCheckedLevels(){
        var levels = [];

        $("input[type='checkbox']").each(function (){
            if($(this).is(":checked")){
                levels.push($(this).attr("level"));
            }
        });

        return levels;
    }

    function navigateToGame(){
        setMessage();
        var type = $("#game-type").val();

        // Natural, whole or fraction
        var numberType = $("#number-type").val();

        // These don't require a type, so set a random one
        // It's the same as type purely for a visual reason
        if(typeIndependents.includes(type)){
            numberType = type;
        }

        var time = parseFloat($("#number").val().replace(",", "."));

        var levels = getCheckedLevels();

    
        if(type == "choose"){
            window.scrollTo({ top: 0, behavior: 'smooth' });
            setMessage("Palun vali harjutusala");
            return;
        }

        if(levels.length <= 0){
            window.scrollTo({ top: 0, behavior: 'smooth' });
            setMessage("Palun vali vähemalt üks tase");
            return;
        }
        
        if(isNaN(time)){
            window.scrollTo({ top: 0, behavior: 'smooth' });
            setMessage("Palun sisesta aeg, kui palju soovid harjutada");
            return; 
        }

        if(time <= 0 || time > 10){
            window.scrollTo({ top: 0, behavior: 'smooth' });
            setMessage("Aeg peab olema vahemikus 0-10 min");
            return;
        }

        if(numberType == null){
            window.scrollTo({ top: 0, behavior: 'smooth' });
            setMessage("Palun vali arvuhulk");
            return;
        }

        if(type != "choose" && time != null && time > 0){
            setLoading(true);
            window.location.href = "/game/"+levels.join("")+"/"+type+"/"+time+"/"+numberType;
        }

    }


    function getParams(timeVal){
        return "?id="+$("#game-type").val() + "&time=" + (timeVal != null && Number.isInteger(timeVal) ? timeVal : $("#number").val()) + ("&type="+($("#number-type").val() ?? "choose")); 
    }

    function onTimeChange(val){
        window.history.replaceState(null, null, getParams(val));    
    }

    function showNumberType(instant){
        // These types are not dependent on this
        if(typeIndependents.includes($("#game-type").val())){
            $("#number-type").slideUp(instant ? 0 :100);
        }else{
            $("#number-type").slideDown(instant ? 0 :100);
        }
    }

    function showLevels(firstTime=false){

        setLevels([]);
        setExtra([]);


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

        var type = $("#game-type").val();

        const guideAvailable = ["liitmine", "korrutamine", "jaguvus", "kujundid", "astendamine", "võrdlemine"];

        if(guideAvailable.includes(type)){
            $("#guide").attr("href", "/how-to-play#"+type).slideDown(firstTime ? 0 : 200);
        }else{
            $("#guide").slideUp(firstTime ? 0 : 200);
        }

        var typeData = data[type];

        var lvls = [];
        var extras = [];

        if(typeData != null && typeData != undefined){
            if(typeof typeData === "string"){
                typeData = data[data[type]];
            }
    
            for(var i = 0; i<typeData.lvls; i++){
                lvls.push(<CheckboxTile level={i + 1} key={i} />);
            }
    
            extras = typeData.extra.map((val, i) => <CheckboxTile level={"★" + (i + 1)} levelChar={val} key={i} />);
    
            setLevels(lvls);
            setExtra(extras);
            setTypes(typeData.types);

            setTimeout(() => {
                if(urlParams.get("type").length == 0){
                    $("#number-type").val("choose").change();
                    return;
                }
                
                if(typeData.types.includes(urlParams.get("type"))){
                    $("#number-type").val(urlParams.get("type")).change();
                }

                if(typeof typeData.types != "string"){
                    for(var type of typeData.types){
                        if(type.value == urlParams.get("type")){
                            $("#number-type").val(urlParams.get("type")).change();
                        }
                    }
                }

                // If there is only 1 type, set the value to that
                if(typeData.types.length == 1){
                    $("#number-type").val(typeData.types[0]).change();
                }

            }, 10);
        }
    }

    $("#game-type").change(function (){
        window.history.replaceState(null, null, getParams());   
        
        showNumberType(false);
        showLevels();
    });

    $("#number-type").change(function (){
        window.history.replaceState(null, null, getParams());    
    });

    $("#number").change(onTimeChange);

    function showMoreOptions(){
        $("#more-options-arrow").css("transform", "rotate("+($(".more").is(":hidden") ? "-180deg" : "0deg")+")");
        $(".more").slideToggle(200);
    }


    return (
        <>
            <Head title="Mängu eelistused" />
            <Navbar title="Mängu eelistused" user={auth.user} />

            <SizedBox height={36} />

            <h2>Mängu eelvaade</h2>
            <div className="container">
                {message && <div style={{backgroundColor:"rgb(var(--section-color),  var(--section-transparency))", borderRadius:"var(--primary-btn-border-radius)", padding:"8px", marginBlock:"8px"}}>
                    <p style={{color:"rgb(var(--primary-color))"}}>ⓘ {message}</p>
                </div>}
                <div className="preferences">
                    <section>

                        <select style={{marginBottom:"4px"}} defaultValue={id ?? "choose"} id="game-type">
                            <option value="choose"> Vali harjutusala</option>

                            <optgroup label="Liitmine/lahutamine">
                                <option value="liitmine">Liitmine</option>
                                <option value="lahutamine">Lahutamine</option>
                                <option value="liitlahutamine">Liitlahutamine</option>
                            </optgroup>

                            <optgroup label="Korrutamine/jagamine">
                                <option value="korrutamine">Korrutamine</option>
                                <option value="jagamine">Jagamine</option>
                                <option value="korrujagamine">Korrujagamine</option>
                            </optgroup>

                            <optgroup label="Astendamine/juurimine">
                                <option value="astendamine">Astendamine</option>
                                <option value="juurimine">Juurimine</option>
                                <option value="astejuurimine">Astejuurimine</option>
                            </optgroup>

                            <option value="võrdlemine">Võrdlemine</option>
                            <option value="lünkamine">Lünkamine</option>
                            <option value="jaguvus">Jaguvusseadused</option>
                            <option value="murruTaandamine">Murru taandamine</option>
                            <option value="kujundid">Kujundid</option>
                        </select>
                        <a id="guide" style={{display:"none", fontSize:"16px", float: "right", marginBottom:"8px"}} alone="" href="/how-to-play">Kuidas mängida?</a>

                        <select defaultValue="null" name="" id="" style={{display:"none"}}>
                            <option value="null" disabled>Vali mängurežiim</option>
                            <option value="normal">Tavamäng</option>
                            <option value="sprint">Sprint</option>
                        </select>
                        <select defaultValue={urlParams.get("type") ?? "choose"} name="" id="number-type">
                            <option disabled value="choose">Vali arvuhulk</option>
                            {types.map((e)=>typeof e == "string" ? <option value={e} key={e}>{{"natural":"Naturaalarvud", "integer":"Täisarvud", "fraction":"Kümnendmurrud", "roman":"Rooma numbrid"}[e]}</option> : <option key={e.value} value={e.value}>{e.label}</option>)}                            
                        </select>

                        <NumberInput placeholder="Aeg (min)" id="number" onChange={onTimeChange} default={urlParams.get("time") ?? (Number.isInteger(parseInt(window.localStorage.getItem("default-time"))) ? (window.localStorage.getItem("default-time") == "0" ? "" : window.localStorage.getItem("default-time")) : "")}/>

                        {levels.length > 0 && <SizedBox height={16} />}
                        <a style={{visibility: levels.length > 0 ? "visible" : "hidden"}} alone="true" onClick={showMoreOptions}>Täpsemad valikud <i id="more-options-arrow" className="material-icons no-anim">keyboard_arrow_down</i> </a>
                        {levels.length > 0 && <SizedBox height={16} />}
                        <div className="more" hidden>
                            <div style={{textAlign:"start"}} className="lvls">
                                {levels}

                                {extra.length > 0 && <><br /><br /></>}
                                {extra}
                            </div>
                        </div>
                    </section>
                </div>
                <SizedBox height="16px" />
                <div className="start-btn">
                    <section>
                        <button onClick={navigateToGame}>{loading && <LoadingSpinner />} Alusta mängu</button>
                        {/* <a alone="" href={route("guide")} style={{display:"block", textAlign:"end", fontSize:"14px"}}>Kuidas mängida?</a> */}

                    </section>
                </div>
            </div>
        </>
    );
}