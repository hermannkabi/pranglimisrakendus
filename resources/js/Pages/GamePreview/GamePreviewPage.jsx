import Navbar from "@/Components/Navbar";
import { Head } from "@inertiajs/react";
import "/public/css/preview.css";
import NumberInput from "@/Components/NumberInput";
import SizedBox from "@/Components/SizedBox";

export default function GamePreviewPage(){
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');

    function navigateToGame(){
        return window.location.href = "/game";
    }

    return (
        <>
            <Head title="Mängu eelistused" />
            <Navbar title="Mängu eelistused" />

            <div className="container">
                <div className="preferences">
                    <section>
                        <select defaultValue={id ?? "all"} id="game-type">
                            <option disabled value="all"> Vali harjutusala</option>
                            <option value="multiply">Korrutamine</option>
                            <option value="divide">Jagamine</option>
                            <option value="add">Liitmine</option>
                            <option value="subtract">Lahutamine</option>
                            <option value="addsub">Liitlahutamine</option>
                            <option value="multidiv">Korrujagamine</option>
                            <option value="compare">Võrdlemine</option>
                            <option value="gap">Lünkamine</option>
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
                        <NumberInput placeholder="Aeg" />
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