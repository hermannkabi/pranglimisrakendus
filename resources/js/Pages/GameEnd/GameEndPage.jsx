import "/public/css/game_end.css";
import SizedBox from "@/Components/SizedBox";
import OperationWidget from "@/Components/OperationWidget";
import { useEffect, useState } from "react";
import LoadingSpinner from "@/Components/LoadingSpinner";
import Layout from "@/Components/2024SummerRedesign/Layout";
import StatisticsTile from "@/Components/2024SummerRedesign/StatisticsTile";
import Chip from "@/Components/2024SummerRedesign/Chip";
import VerticalStatTile from "@/Components/2024SummerRedesign/VerticalStatTile";
import InfoBanner from "@/Components/InfoBanner";


export default function GameEndPage({correct, total, points, time, lastLevel, log, auth, mis, tyyp, raw_level}){


    const [currentlyShownLog, setCurrentlyShownLog] = useState(log);
    const [gameSaved, setGameSaved] = useState(auth.user.role == "guest" || total == 0 || false);
    const [showGameSavedDialog, setShowGameSavedDialog] = useState(false);
    const [filter, setFilter] = useState([true, true]);


    const [title, setTitle] = useState(null);

    // Default greetings
    const greetings = ["Hästi tehtud!", "Väga tubli!", "Suurepärane!", "Tubli töö!"];

    // Greetings for high accuracy
    const greetingsHighAccuracy = ["Väga täpne!", "Täpne kui kellavärk!", "Sa ei eksi!"];

    // Greetings for 100% accuracy
    const greetingsPerfectAccuracy = ["Sa ei eksi kunagi!", "Eksimatu!", "Imeline!", "Perfektne täpsus!"];

    // Greetings for 0% accuracy
    const greetingsZeroAccuracy = ["Nojah siis!", "Enam halvemini ei saagi!", "Käid ikka Reaalis?", "Ikka üsna kehv!", ":("];


    const greetingsMedPoints = ["Päris hästi!", "Hea mäng!", "Sa oled tubli!", "Hea tulemus!"];

    // Greetings for low points
    // If your average points are lower than 100 (minimum level base points), it was probably not your best...
    const greetingsLowPoints = ["Pole hullu!", "Harjutamine teeb meistriks!", "Järgmine kord paremini!", "Tubli, et proovisid!"];



    // Percentage of correctly answered operations
    var accuracy = total == 0 ? 0 : Math.round(correct/total*100);


    // Get a random title from the list of greetings
    function getTitle(greetingList){
        return greetingList[Math.floor(Math.random() * greetingList.length)]
    }

    useEffect(()=>{

        // Greeting algorithm
        if(accuracy == 100){
            setTitle(getTitle(greetingsPerfectAccuracy));
        }else if(accuracy >= 90){
            setTitle(getTitle(greetingsHighAccuracy));
        }else if(accuracy >= 80){
            setTitle(getTitle(greetingsMedPoints));
        }else if(accuracy == 0){
            setTitle(getTitle(greetingsZeroAccuracy));
        }else if(total*100 > points){
            setTitle(getTitle(greetingsLowPoints));
        }else{
            setTitle(getTitle(greetings));
        }

        saveGame();

        
    }, []);

    function getTime(timeInSeconds){
        var minutes = Math.floor(timeInSeconds / 60);
        var seconds = timeInSeconds - 60*minutes;

        minutes = minutes <= 9 ? "0"+minutes.toString() : minutes.toString();
        seconds = seconds <= 9 ? "0"+seconds.toString() : seconds.toString();

        return minutes + ":" + seconds;
    }

    function dateToString(date){
        return (date.getDate() + 1 < 9 ? "0" : "") + date.getDate().toString() + "." + (date.getMonth() + 1 < 9 ? "0" : "") + (date.getMonth() + 1).toString() + "." + date.getFullYear();
    }
    
    function saveGame(){
        // Only save such games that at least tried one operation and are at least 30 sec long
        if(total <= 0) return;
        if(time < 30){
            setGameSaved(true);
            return;
        };

        window.localStorage.setItem("last-active", dateToString(new Date(Date.now())));
        window.localStorage.setItem("total-training-count", parseInt(window.localStorage.getItem("total-training-count") ?? 0)+1);
        window.localStorage.setItem("total-points", parseInt(window.localStorage.getItem("total-points") ?? 0)+points);
        
        // The average percentage works by saving a sum of all the percentages and dividing it by total-training-count
        window.localStorage.setItem("total-percentage", parseInt(window.localStorage.getItem("total-percentage") ?? 0)+accuracy);

        // Save the game type as 'last used'
        var lastUsed = JSON.parse(window.localStorage.getItem("last-used") == null || window.localStorage.getItem("last-used").length == 0 ? "[]" : window.localStorage.getItem("last-used"));
        var gameType = decodeURIComponent(mis);
        if(!lastUsed.includes(gameType)){
            if(lastUsed.length >= 3) lastUsed.pop();
            lastUsed.unshift(gameType);
        }
        window.localStorage.setItem("last-used", JSON.stringify(lastUsed));

        // Real saving (non-guests only)
        if(auth.user.role != "guest"){
            $.post(route("gameStore"), {
                "_token":window.csrfToken,
                'score_sum':points,
                'experience':"0",
                'accuracy_sum':accuracy,
                'game_count': total,
                'last_level':lastLevel.toString(),
                'last_equation':"0",
                'time':time,
                'log':JSON.stringify(log),
                'game':mis,
                'game_type': tyyp,
            }).done(function (data){
                setGameSaved(true);
            }).fail(function (data){
                console.log(data);
            });
        }
        
    }

    function filterOperations(newFilter){
        const animationTime = 150;
        $(".detailed-container").animate({
            opacity: 0,

        }, animationTime, function (){
            setCurrentlyShownLog(log.filter((op) => (newFilter[0] && op.isCorrect) || (newFilter[1] && !op.isCorrect)));

            $(".detailed-container").animate({
                opacity: 1,
            }, animationTime);
        });
    }

    function navigateAway(onComplete){
        if(!gameSaved){
            setShowGameSavedDialog(true);
        }else{
            onComplete();
        }
    }

    function updateChip(index){
        var newValue = !filter[index];
        var newFilter = [...filter];

        var shouldNotChange = newFilter.includes(false) && newValue == false;
        newFilter[index] = shouldNotChange ? !newValue : newValue;

        setFilter(newFilter);
        if(!shouldNotChange){
            filterOperations(newFilter);
        }
    }

    function playAgain(){
        $.post(route("previewPost"), {
            "_token":window.csrfToken,
            "level":raw_level,
            "mis": mis,
            "aeg": time/60,
            "tyyp": tyyp,
        }).done(function (data){
            window.location.href = "/game";
        }).fail(function (data){
            console.log("Viga");
            console.log(data);
        });
    }

    return <>
        <Layout title={"Lõpeta mäng"} auth={auth}>
            {time < 30 && <InfoBanner text="See mäng kestis alla 30 sekundi. Nii lühikesi mänge sinu kontole ei salvestata!" />}
            {showGameSavedDialog && <div className="section" style={{display:"flex", justifyContent:"center", alignItems:"center", marginTop:"0", marginBottom:"8px"}}>
                {gameSaved ? <i translate="no" className="material-icons">check</i> : <LoadingSpinner color={true} />}
                <p style={{marginLeft:"8px"}}>{gameSaved ? "Mäng salvestatud!" : "Salvestan mängu..."}</p>
            </div>}
            <div className="four-stat-row">
                <StatisticsTile stat={points} label={"Punkti"} icon={"exercise"} compactNumber={true} />
                <StatisticsTile stat={getTime(time)} label={"Kulunud aeg"} icon={"hourglass_top"} />
                <StatisticsTile stat={total} label={"Tehet"} oneLabel={"Tehe"} icon={"calculate"} />
                <StatisticsTile stat={accuracy + "%"} label={"Täpsus"} icon={"target"}/>
            </div>
            <SizedBox height="16px" />
            <div className="two-column-layout reverse">
                <div>
                    {accuracy != 0 && accuracy != 100 && <VerticalStatTile icon="filter_alt" text="Filtreeri" customValue={true} value={<>
                        <div>
                            <Chip onClick={()=>updateChip(0)} alt={log.filter((op)=>op.isCorrect).length} label={"Õiged vastused"} active={filter[0]} />
                            <Chip onClick={()=>updateChip(1)} alt={log.filter((op)=>!op.isCorrect).length} label={"Valed vastused"} active={filter[1]} />
                        </div>
                    </>} />}

                    <div className="detailed-container" style={{display:"grid", gridTemplateColumns:"1fr"}}>
                        {currentlyShownLog.map(function (op, i){
                            return (
                                <OperationWidget op={op} key={i} />
                            );
                        })}
                        {currentlyShownLog.length <= 0 && <div className="section">
                            <InfoBanner text="Sa ei esitanud vastust ühelegi tehtele" />
                        </div> }
                    </div>
                </div>

                {/* Teine tulp */}
                <div>
                    <SizedBox height="8px" />
                    <div className="two-column-layout">
                        <div onClick={()=>navigateAway(playAgain)} className="section clickable" style={{padding:"16px", display:"flex", justifyContent:"start", alignItems:"center", marginBlock:"0"}}>
                            <div>
                                <i translate="no" style={{fontSize:"32px"}} className="material-icons-outlined">refresh</i>
                                <p style={{marginTop:"8px", marginBottom:"0"}}>Mängi uuesti</p>
                            </div>
                        </div>
                        <div onClick={()=>navigateAway(()=>window.location.href = route("dashboard"))} className="section clickable" style={{padding:"16px", display:"flex", justifyContent:"start", alignItems:"center", backgroundColor:"rgb(var(--primary-color))", color:"white", marginBlock:"0"}}>
                            <div>
                                <i translate="no" style={{fontSize:"32px"}} className="material-icons-outlined">{time < 30 ? "home" : "save"}</i>
                                <p style={{marginTop:"8px", marginBottom:"0"}}>Edasi</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Layout>
    </>;
}


