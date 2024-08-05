import Navbar from "@/Components/Navbar";
import OperationWidget from "@/Components/OperationWidget";
import SizedBox from "@/Components/SizedBox";
import StatisticsWidget from "@/Components/StatisticsWidget";
import { Head } from "@inertiajs/react";
import "/public/css/game_end.css";
import { Children, useEffect, useState } from "react";
import CheckboxTile from "@/Components/CheckboxTile";
import HorizontalRule from "@/Components/HorizontalRule";
import Layout from "@/Components/2024SummerRedesign/Layout";
import StatisticsTile from "@/Components/2024SummerRedesign/StatisticsTile";
import Chip from "@/Components/2024SummerRedesign/Chip";


export default function GameDetailsPage({game, auth, playedBy}){

    const [copyText, setCopyText] = useState("Jaga mängu");
    // First is for correct, second incorrect answers;
    const [filter, setFilter] = useState([true, true]);
    const [currentlyShownLog, setCurrentlyShownLog] = useState(JSON.parse(game.log));

    function averageTime(timeInSeconds){
        var minutes = Math.floor(timeInSeconds / 60);
        var seconds = timeInSeconds - 60*minutes;

        minutes = minutes <= 9 ? "0"+minutes.toString() : minutes.toString();
        seconds = seconds <= 9 ? "0"+seconds.toString() : seconds.toString();

        return minutes + ":" + seconds;
    }

    const userName = playedBy == null ? null : playedBy.eesnimi + " " + playedBy.perenimi;

    const typeToReadable = {
        "natural":"Naturaalarvud",
        "integer":"Täisarvud",
        "fraction":"Kümnendmurrud",
        "roman":"Rooma numbrid",
    };

    const gameNames = {
        "lihtsustamine":"Murru taandamine",
        "murruTaandamine":"Murru taandamine",
        "jaguvus":"Jaguvusseadused"
    };
    
    var name = game.game == null ? "Tundmatu" : game.game in gameNames ? gameNames[game.game] : game.game.substring(0, 1).toUpperCase() + game.game.substring(1);
    


    function copyToClipboard(){
        navigator.clipboard.writeText(window.location.origin + "/game/" + game.game_id + "/details");
        setCopyText("Link kopeeritud!");

        setTimeout(() => {
            setCopyText("Jaga mängu");
        }, 2000);
    }

    function filterOperations(filter){
        const animationTime = 150;
        $(".detailed-container").animate({
            opacity: 0,

        }, animationTime, function (){
            setCurrentlyShownLog(JSON.parse(game.log).filter((op) => (filter[0] && op.isCorrect) || (filter[1] && !op.isCorrect)));

            $(".detailed-container").animate({
                opacity: 1,
            }, animationTime);
        });
    }

    function updateChip(index){
        var newValue = !filter[index];
        var newFilter = [...filter];
        newFilter[index] = newFilter.includes(false) && newValue == false ? !newValue : newValue;

        setFilter(newFilter);
        filterOperations(newFilter);
    }

    return <>
        <Layout title="Detailne vaade">
            <div className="four-stat-row">
                <StatisticsTile stat={averageTime(game.time)} label={"Keskmine aeg"} icon={"hourglass_top"} />
                <StatisticsTile stat={game.game_count ?? "0"} label={"Tehete arv"} icon={"calculate"} />
                <StatisticsTile stat={game.score_sum ?? "0"} label={"Punkti kokku"} oneLabel={"Punkt kokku"} icon={"exercise"} compactNumber={true} />
                <StatisticsTile stat={(game.accuracy_sum ?? "0") + "%"} label={"Vastamistäpsus"}icon={"target"} />
            </div>
            <SizedBox height="16px" />
            <div className="two-column-layout">
                <div>
                    {playedBy != null && playedBy.id != auth.user.id &&<div onClick={()=>window.location.href = "/profile/"+playedBy.id} className="clickable section" style={{display:"flex", flexDirection:"row", justifyContent:"space-between", alignItems:"center", gap:"0 8px", marginBlock:"0", marginBottom:"24px", paddingRight:"16px"}}>
                        <div>
                            <SizedBox height="16px" />
                            <div className="stat-desc">
                                <i className="material-icons-outlined">person</i>
                                <p style={{marginTop:"4px"}}>Mängija</p>
                            </div>
                            <p style={{textTransform:"capitalize", marginBottom:"8px", fontWeight:"bold", color:"var(--lightgrey-color)", fontSize:"24px"}}>{userName}</p>
                        </div>                    
                        <img style={{height:"75px"}} className="profile-pic" src={playedBy.profile_pic} alt={userName} />
                    </div>}

                    {game.accuracy_sum != 0 && game.accuracy_sum != 100 && <div className="section">
                        <SizedBox height="16px" />
                        <div className="stat-desc">
                            <i className="material-icons-outlined">filter_alt</i>
                            <p style={{marginTop:"4px"}}>Filtreeri</p>
                        </div>
                        <div>
                            <Chip onClick={()=>updateChip(0)} alt={JSON.parse(game.log).filter((op)=>op.isCorrect).length} label={"Õiged vastused"} active={filter[0]} />
                            <Chip onClick={()=>updateChip(1)} alt={JSON.parse(game.log).filter((op)=>!op.isCorrect).length} label={"Valed vastused"} active={filter[1]} />
                        </div>
                    </div>}

                    <div className="detailed-container" style={{gridTemplateColumns:"1fr"}}>
                        {currentlyShownLog.map(function (op, i){
                            return (
                                <OperationWidget op={op} key={i} />
                            );
                        })}
                    </div>
                </div>

                <div>
                    <div style={{display:"grid", gridTemplateColumns:"repeat(2, 1fr)", gap:"16px"}}> 
                        <div className="section class-stat" style={{padding:"16px"}}>
                            <div className="stat-desc">
                                <i className="material-icons-outlined">calculate</i>
                                <p style={{marginTop:"4px"}}>Mängutüüp</p>
                            </div>
                            <p style={{marginBottom:"8px", fontWeight:"bold", color:"var(--lightgrey-color)", fontSize:"24px"}}>{decodeURIComponent(name)}</p>
                        </div>

                        <div className="section class-stat" style={{padding:"16px"}}>
                            <div className="stat-desc">
                                <i className="material-icons-outlined">pin</i>
                                <p style={{marginTop:"4px"}}>Arvuhulk</p>
                            </div>
                            <p style={{display:"flex", alignItems:"center", gap:"8px", marginBottom:"8px", fontWeight:"bold", color:"var(--lightgrey-color)", fontSize:"24px"}}>{typeToReadable[game.game_type] ?? "N/A"}</p>
                        </div>
                    </div>

                    <a alone="" style={{color:"var(--grey-color)"}} onClick={copyToClipboard}> <i className="material-icons no-anim">share</i>&nbsp; {copyText}</a>
                </div>
            </div>

            
        </Layout>
    </>;

    return <>

        <section>
            <div className='header-container'>
                <h3 className='section-header'>Andmed</h3>
            </div>
            
        </section>

        <section>
            <div className='header-container'>
                <h3 className='section-header'>Täpne tulemus</h3>
            </div>
            {game.accuracy_sum != 0 && game.accuracy_sum != 100 && <div>
                <p style={{color:"grey", marginBottom:"4px"}}>Filtreeri</p>
                <CheckboxTile forcedText={"Õiged vastused ("+JSON.parse(game.log).filter((op)=>op.isCorrect).length+")"} onChange={filterOperations} inputClass="correct-choice" />
                <CheckboxTile forcedText={"Valed vastused ("+JSON.parse(game.log).filter((op)=>!op.isCorrect).length+")"} onChange={filterOperations} inputClass="incorrect-choice" />
                <br />
                <HorizontalRule />
            </div>}

            <div className="detailed-container">
                {currentlyShownLog.map(function (op, i){
                    return (
                        <OperationWidget op={op} key={i} />
                    );
                })}
            </div>
        </section>
        <SizedBox height={16} />
        <a alone="" onClick={copyToClipboard} >{copyText}&nbsp;<span className="material-icons no-anim" translate="no">share</span></a>
        <SizedBox height={16} />

    </>;
}