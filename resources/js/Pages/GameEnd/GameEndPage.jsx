import Navbar from "@/Components/Navbar";
import { Head } from "@inertiajs/react";
import "/public/css/game_end.css";
import SizedBox from "@/Components/SizedBox";


export default function GameEndPage({correct, total, points, time, lastLevel, log}){

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
    }else if(total*100 > points){
        title = getTitle(greetingsLowPoints);
    }else{
        title = getTitle(greetings);
    }
    

    return (
        <>
            <Head title="Lõpeta mäng" />
            <Navbar title="Lõpeta mäng" />
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
                            <h3 style={{marginBlock:0}}>{lastLevel ?? "N/A"}</h3>
                        </div>}
                        <div className="stat-row">
                            <p style={statNameStyle}>VASTAMISTÄPSUS</p>
                            <h3 style={{marginBlock:0}}>{accuracy.toString().replace(".", ",")}%</h3>
                        </div>
                        <div className="stat-row">
                            <p style={statNameStyle}><b>PUNKTE</b></p>
                            <h3 style={{marginBlock:0}}>{points}</h3>
                        </div>

                        {/* Detailed results toggle */}
                        {total > 0 && <a alone="" onClick={()=>$(".ss").slideToggle(200)}>Täpne ülevaade</a>}

                        {/* Detailed resuls div */}
                        <div className="ss" hidden>
                            {log.map(function (op, i){
                                return (
                                    <div key={i}>
                                        <h3 style={{color:'gray', marginBottom:"0"}}><b>{op.operation.replace("Lünk", " _ ")}</b></h3>
                                        <span style={{display:"block"}}>Vastus: <span style={{color:op.isCorrect ? "green" : "red", textDecoration:op.isCorrect ? "none" : "line-through"}}>{op.answer}</span> {!op.isCorrect && <span style={{color:'green'}}>{op.correct}</span>}</span>
                                    </div>
                                );
                            })}
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