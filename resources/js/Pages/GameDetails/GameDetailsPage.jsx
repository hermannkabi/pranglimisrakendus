import Navbar from "@/Components/Navbar";
import OperationWidget from "@/Components/OperationWidget";
import SizedBox from "@/Components/SizedBox";
import StatisticsWidget from "@/Components/StatisticsWidget";
import { Head } from "@inertiajs/react";
import "/public/css/game_end.css";
import { useState } from "react";
import CheckboxTile from "@/Components/CheckboxTile";
import HorizontalRule from "@/Components/HorizontalRule";


export default function GameDetailsPage({game, auth, playedBy}){

    const [copyText, setCopyText] = useState("Jaga");

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
            setCopyText("Jaga");
        }, 2000);
    }

    function filterOperations(){
        const animationTime = 150;
        $(".detailed-container").animate({
            opacity: 0,

        }, animationTime, function (){
            setCurrentlyShownLog(JSON.parse(game.log).filter((op) => ($(".correct-choice").is(":checked") && op.isCorrect) || ($(".incorrect-choice").is(":checked") && !op.isCorrect)));

            $(".detailed-container").animate({
                opacity: 1,
            }, animationTime);
        });
    }

    return <>
            <Head title={name == null ? "Mäng" : decodeURIComponent(name)} />
            <Navbar title={name ?? "Mäng"} user={auth.user} />
            <SizedBox height={36} />

        <h2 style={{marginBottom:"8px"}}>{name == null ? "Detailne vaade" : decodeURIComponent(name)}</h2>

        {playedBy != null && playedBy.id != auth.user.id &&<div style={{display:"flex", flexDirection:"row", justifyContent:"center", alignItems:"center", gap:"0 8px", marginBlock:"0", marginBottom:"24px"}}>
            <img style={{height:"32px"}} className="profile-pic" src={playedBy.profile_pic} alt={userName} />
            <p style={{display:"inline", marginBlock:"0", color:"grey", textTransform:"capitalize"}}>{userName}</p>
        </div>}

        <section>
            <div className='header-container'>
                <h3 className='section-header'>Andmed</h3>
            </div>
            <div className="stats-container">
                <StatisticsWidget stat={game.game_count} desc="Tehet" oneDesc={"Tehe"} />
                <StatisticsWidget stat={game.score_sum} desc="Punkti" oneDesc="Punkt" />
                <StatisticsWidget stat={game.accuracy_sum + "%"} desc="Täpsus"  />
                {/* <StatisticsWidget stat={(new Date(game.dt)).toLocaleDateString("et-EE", {month:"2-digit", day:"2-digit", year:"numeric"})} desc="Kuupäev" condensed={true} /> */}
                <StatisticsWidget stat={averageTime(game.time)} desc="Kulunud aeg" />
                {/* <StatisticsWidget stat={typeToReadable[game.game_type]} desc="Arvuhulk" condensed={true}  /> */}
            </div>
            
            {game.game_type != null && game.game_type in typeToReadable && <><SizedBox height="4px" /><StatisticsWidget stat={typeToReadable[game.game_type]} desc="Arvuhulk"  condensed={true} /></>}
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