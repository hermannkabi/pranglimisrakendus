import Navbar from "@/Components/Navbar";
import { Head } from "@inertiajs/react";
import "/public/css/game_end.css";
import SizedBox from "@/Components/SizedBox";
import OperationWidget from "@/Components/OperationWidget";
import { useEffect } from "react";


export default function GameEndPage({correct, total, points, time, lastLevel, log, auth}){


    useEffect(()=>{
        saveGame();
    }, []);

    // Style of the description of the statistic
    const statNameStyle = {color:'gray', marginBlock: "0"};

    // Percentage of correctly answered operations
    var accuracy = total == 0 ? 0 : Math.round(correct/total*100);

    // Returns a string of human readable time (e.g. 1 min 30 sec)
    function getHumanReadableTime(){
        if(time < 60){
            return Math.round(time) + " s";
        }else if(time%60 == 0){
            return time/60 + " min";
        }else{

            return Math.floor(time/60) + " min " + (time%60) +" s";
        }
    }


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




    // Get a random title from the list of greetings
    function getTitle(greetingList){
        return greetingList[Math.floor(Math.random() * greetingList.length)]
    }

    var title;

    // Greeting algorithm
    if(accuracy == 100){
        title = getTitle(greetingsPerfectAccuracy);
    }else if(accuracy >= 90){
        title = getTitle(greetingsHighAccuracy);
    }else if(accuracy >= 80){
        title = getTitle(greetingsMedPoints);
    }else if(accuracy == 0){
        title = getTitle(greetingsZeroAccuracy);
    }else if(total*100 > points){
        title = getTitle(greetingsLowPoints);
    }else{
        title = getTitle(greetings);
    }

    function dateToString(date){
        return (date.getDate() + 1 < 9 ? "0" : "") + date.getDate().toString() + "." + (date.getMonth() + 1 < 9 ? "0" : "") + (date.getMonth() + 1).toString() + "." + date.getFullYear();
    }
    
    function saveGame(){
        // Only save such games that at least tried one operation
        if(total <= 0) return;

        window.localStorage.setItem("last-active", dateToString(new Date(Date.now())));
        window.localStorage.setItem("total-training-count", parseInt(window.localStorage.getItem("total-training-count") ?? 0)+1);
        window.localStorage.setItem("total-points", parseInt(window.localStorage.getItem("total-points") ?? 0)+points);
        
        // The average percentage works by saving a sum of all the percentages and dividing it by total-training-count
        window.localStorage.setItem("total-percentage", parseInt(window.localStorage.getItem("total-percentage") ?? 0)+accuracy);

    }

    return (
        <>
            <Head title="Lõpeta mäng" />
            <Navbar title="Lõpeta mäng" user={auth.user} />
            <SizedBox height={36} />

            

            <div className="container">
                {/* Greeting */}
                <h2 style={{marginBottom: 0}}>{title}</h2>
                {/* Stats */}
                <section>
                    <div className="stat-container">
                        <div className="stat-row">
                            <p style={statNameStyle}>KULUNUD AEG</p>
                            <h3 style={{marginBlock:0}}>{getHumanReadableTime()}</h3>
                        </div>
                        <div className="stat-row">
                            <p style={statNameStyle}>TEHETE ARV</p>
                            <h3 style={{marginBlock:0}}>{total}</h3>
                        </div>
                        {lastLevel && <div className="stat-row">
                            <p style={statNameStyle}>VIIMANE TASE</p>
                            <h3 style={{marginBlock:0}}>{lastLevel.toString().replace("A", "★ 1").replace("B", "★ 2").replace("C", "★ 3") ?? "N/A"}</h3>
                        </div>}
                        <div className="stat-row">
                            <p style={statNameStyle}>VASTAMISTÄPSUS</p>
                            <h3 className={accuracy == 100 ? "fancy" : ""} style={{marginBlock:0}}>{accuracy.toString().replace(".", ",")}%</h3>
                        </div>
                        <div className="stat-row">
                            <p style={statNameStyle}><b>PUNKTE</b></p>
                            <h3 style={{marginBlock:0}}>{points}</h3>
                        </div>

                        {/* Detailed results toggle */}
                        {total > 0 && <a alone="" onClick={()=>$(".ss").slideToggle(200)}>Täpne ülevaade</a>}

                        {/* Detailed resuls div */}
                        {/* Updated looks from 14.01.2024 */}
                        <div className="ss" style={{display:"none"}}>
                            <SizedBox height={16} />
                            <div className="detailed-container">
                                {log.map(function (op, i){
                                    return (
                                        <OperationWidget op={op} key={i} />
                                    );
                                })}
                            </div>
                        </div>
                    </div>

                </section>
                
                {/* Buttons */}
                <section>
                    <div className="btn-container">
                        <button onClick={()=>location.reload()} style={{flex:'1'}} secondary="true">Proovi uuesti</button>
                        <button onClick={()=>window.location.href = route("dashboard")} style={{flex:'1'}}>Edasi</button>
                    </div>
                </section>
            </div>
        </>
    );
}


