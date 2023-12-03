import Navbar from "@/Components/Navbar";
import { Head } from "@inertiajs/react";
import "/public/css/preview.css";
import NumberInput from "@/Components/NumberInput";
import SizedBox from "@/Components/SizedBox";
import { useState } from "react";
import CheckboxTile from "@/Components/CheckboxTile";

export default function GamePreviewPage(){
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');

    const [message, setMessage] = useState();

    function navigateToGame(){
        setMessage();
        var type = $("#game-type").val();
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

        if(type != "choose" && time != null && time > 0){
            return window.location.href = "/game/"+levels.join("")+"/"+type+"/"+time;
        }

    }

    return (
        <>
            <Head title="Mängu eelistused" />
            <Navbar title="Mängu eelistused" />

            <h2>Mängu eelvaade</h2>
            <div className="container">
                {message && <div style={{backgroundColor:"rgb(0,0,0, 0.05)", borderRadius:"16px", padding:"8px", marginBlock:"8px"}}>
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
                            <option value="addsub">Liitlahutamine</option>
                            <option value="multidiv">Korrujagamine</option>
                            <option value="compare">Võrdlemine</option>
                            <option value="lünkamine">Lünkamine</option>
                            <option value="random">Segaarvutused</option>
                        </select>

                        <select name="" id="">
                            <option disabled selected>Vali mängurežiim</option>
                            <option value="normal">Tavamäng</option>
                            <option value="sprint">Sprint</option>
                        </select>

                        <select name="" id="">
                            <option disabled selected>Vali arvuhulk</option>
                            <option value="natural">Naturaalarvud</option>
                            <option value="whole">Täisarvud</option>
                            <option value="fraction">Murdarvud</option>
                        </select>

                        <NumberInput placeholder="Aeg (min)" id="number"/>

                        <a alone="true" onClick={()=>$(".more").slideToggle(200)}>Täpsemad valikud</a>
                        <div hidden className="more">
                            <SizedBox height={16} />
                            <div style={{textAlign:"start"}}>
                                <CheckboxTile level={"1"} />
                                <CheckboxTile level={"2"} />
                                <CheckboxTile level={"3"} />
                                <CheckboxTile level={"4"} />
                                <CheckboxTile level={"5"} />
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