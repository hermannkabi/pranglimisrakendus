import Navbar from "@/Components/Navbar";
import { Head } from "@inertiajs/react";
import "/public/css/preview.css";
import NumberInput from "@/Components/NumberInput";
import SizedBox from "@/Components/SizedBox";
import { useState, useEffect } from "react";
import CheckboxTile from "@/Components/CheckboxTile";

export default function GamePreviewPage(){
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');

    const [message, setMessage] = useState();

    // This function is called once when the page is first loaded
    useEffect(()=>{
        // Whether or not the type select is shown
        showNumberType(true);
    }, []);

    function navigateToGame(){
        setMessage();
        var type = $("#game-type").val();

        // Natural, whole or fraction
        var numberType = $("#number-type").val();

        var time = parseInt($("#number").val());

        var levels = [];

        $("input[type='checkbox']").each(function (){
            if($(this).is(":checked")){
                levels.push($(this).attr("level"));
            }
        });

        if(levels.length <= 0){
            setMessage("Palun vali vähemalt üks tase");
            return;
        }

        if(type == "choose"){
            setMessage("Palun vali harjutusala");
            return;
        }
        
        if(isNaN(time)){
            setMessage("Palun sisesta aeg, kui palju soovid harjutada");
            return; 
        }

        if(time <= 0){
            setMessage("Aeg peab olema suurem nullist");
            return;
        }

        if(numberType == null){
            setMessage("Palun vali arvuhulk");
            return;
        }

        if(type != "choose" && time != null && time > 0){
            return window.location.href = "/game/"+levels.join("")+"/"+type+"/"+time+"/"+numberType;
        }

    }


    function getParams(timeVal){
        return "?id="+$("#game-type").val() + "&time=" + (timeVal != null && Number.isInteger(timeVal) ? timeVal : $("#number").val()) + "&type="+($("#number-type").val() ?? ""); 
    }

    function onTimeChange(val){
        window.history.replaceState(null, null, getParams(val));    
    }

    function showNumberType(instant){
        // These types are not dependent on this
        if(["lünkamine", "võrdlemine"].includes($("#game-type").val())){
            $("#number-type").slideUp(instant ? 0 :100);
        }else{
            $("#number-type").slideDown(instant ? 0 :100);
        }
    }

    $("#game-type").change(function (){
        window.history.replaceState(null, null, getParams());   
        
        showNumberType(false);
    });

    $("#number-type").change(function (){
        window.history.replaceState(null, null, getParams());    
    });

    $("#number").change(onTimeChange);

    return (
        <>
            <Head title="Mängu eelistused" />
            <Navbar title="Mängu eelistused" />

            <h2>Mängu eelvaade</h2>
            <div className="container">
                {message && <div style={{backgroundColor:"rgb(var(--section-color),  var(--section-transparency))", borderRadius:"16px", padding:"8px", marginBlock:"8px"}}>
                    <p style={{color:"rgb(var(--primary-color))"}}>ⓘ {message}</p>
                </div>}
                <div className="preferences">
                    <section>

                        <select defaultValue={id ?? "choose"} id="game-type">
                            <option value="choose"> Vali harjutusala</option>
                            <option value="korrutamine">Korrutamine</option>
                            <option value="jagamine">Jagamine</option>
                            <option value="liitmine">Liitmine</option>
                            <option value="lahutamine">Lahutamine</option>
                            <option value="liitlahutamine">Liitlahutamine</option>
                            <option value="korrujagamine">Korrujagamine</option>
                            <option value="võrdlemine">Võrdlemine</option>
                            <option value="lünkamine">Lünkamine</option>
                            <option value="sega">Segaarvutused</option>
                        </select>

                        <select name="" id="">
                            <option disabled selected>Vali mängurežiim</option>
                            <option value="normal">Tavamäng</option>
                            <option value="sprint">Sprint</option>
                        </select>

                        <select defaultValue={urlParams.get("type") ?? ""} name="" id="number-type">
                            <option disabled selected>Vali arvuhulk</option>
                            <option value="natural">Naturaalarvud</option>
                            <option value="integer">Täisarvud</option>
                            <option value="fraction">Kümnendmurrud</option>
                        </select>

                        <NumberInput placeholder="Aeg (min)" id="number" onChange={onTimeChange} defaultValue={urlParams.get("time") ?? (Number.isInteger(parseInt(window.localStorage.getItem("default-time"))) ? window.localStorage.getItem("default-time") : "")}/>

                        <SizedBox height={16} />
                        <a alone="true" onClick={()=>$(".more").slideToggle(200)}>Täpsemad valikud</a>
                        <SizedBox height={16} />
                        <div hidden className="more">
                            <div style={{textAlign:"start"}}>
                                <CheckboxTile level={"1"} />
                                <CheckboxTile level={"2"} />
                                <CheckboxTile level={"3"} />
                                <CheckboxTile level={"4"} />
                                <CheckboxTile level={"5"} />
                                {/* <br /><br />
                                <CheckboxTile level={"★ 1"} levelChar={"A"} />
                                <CheckboxTile level={"★ 2"} levelChar={"B"} /> */}
                            </div>
                        </div>
                    </section>
                </div>
                <SizedBox height="16px" />
                <div className="start-btn">
                    <section>
                        <p style={{fontSize:"1.5rem", color:"rgb(var(--primary-color))", marginBottom:"8px"}}>Harjutusmäng</p>
                        <button onClick={navigateToGame}>Alusta mängu</button>
                    </section>
                </div>
            </div>
        </>
    );
}