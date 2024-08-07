import OperationWidget from "@/Components/OperationWidget";
import SizedBox from "@/Components/SizedBox";
import "/public/css/game_end.css";
import { useState } from "react";
import Layout from "@/Components/2024SummerRedesign/Layout";
import StatisticsTile from "@/Components/2024SummerRedesign/StatisticsTile";
import Chip from "@/Components/2024SummerRedesign/Chip";
import VerticalStatTile from "@/Components/2024SummerRedesign/VerticalStatTile";


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
        "kujundid":"Tavaline",
        "color":"Erinevad värvid",
        "size":"Erinevad suurused",
        "all":"Erinevad värvid ja suurused"    
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
                    {playedBy != null && playedBy.id != auth.user.id && <div className="clickable section" style={{position:"relative", display:"flex", flexDirection:"row", justifyContent:"space-between", alignItems:"center", gap:"0 8px", marginBlock:"0", marginBottom:"24px", paddingRight:"16px"}}>
                        <VerticalStatTile marginBlock={0} capitalize={true} icon="person" text="Mängija" value={userName} />                    
                        <img style={{height:"75px"}} className="profile-pic" src={playedBy.profile_pic} alt={userName} />

                        <a href={"/profile/"+playedBy.id} style={{all:"unset", position:"absolute", top:"0", left:"0", height:"100%", width:"100%"}}></a>
                    </div>}

                    {game.accuracy_sum != 0 && game.accuracy_sum != 100 && <VerticalStatTile icon="filter_alt" text="Filtreeri" customValue={true} value={<>
                        <div>
                            <Chip onClick={()=>updateChip(0)} alt={JSON.parse(game.log).filter((op)=>op.isCorrect).length} label={"Õiged vastused"} active={filter[0]} />
                            <Chip onClick={()=>updateChip(1)} alt={JSON.parse(game.log).filter((op)=>!op.isCorrect).length} label={"Valed vastused"} active={filter[1]} />
                        </div>
                    </>} />}

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
                        <VerticalStatTile icon="calculate" text="Mängutüüp" value={decodeURIComponent(name)} />
                        <VerticalStatTile icon="pin" text="Arvuhulk" value={typeToReadable[game.game_type] ?? "N/A"} />
                    </div>
        
                    <VerticalStatTile icon="calendar_month" text="Kuupäev" value={(new Date(game.dt)).toLocaleString("et-EE", {month:"long", day:"2-digit", year:"numeric"}).split(",")[0]} />

                    <a alone="" style={{color:"var(--grey-color)"}} onClick={copyToClipboard}> <i translate="no" className="material-icons no-anim">share</i>&nbsp; {copyText}</a>
                </div>
            </div>

            
        </Layout>
    </>;
}