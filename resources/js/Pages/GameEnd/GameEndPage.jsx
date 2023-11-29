import Navbar from "@/Components/Navbar";
import { Head } from "@inertiajs/react";
import "/public/css/game_end.css";
import SizedBox from "@/Components/SizedBox";

export default function GameEndPage({correct, total, points, time, lastLevel}){


    const statNameStyle = {color:'gray', marginBlock: "0"};



    var accuracy = total == 0 ? 0 : Math.round(correct/total*100);

    function getHumanReadableTime(){
        if(time < 60){
            return Math.round(time) + " s";
        }else if(time%60 == 0){
            return time/60 + " min";
        }else{

            return Math.floor(time/60) + " min " + (time%60) +" s";
        }
    }


    const greetings = ["Hästi tehtud!", "Väga tubli!", "Suurepärane!", "Tubli töö!"];

    const greetingsHighAccuracy = ["Väga täpne!", "Täpne kui kellavärk!", "Sa ei eksi!"];

    const greetingsPerfectAccuracy = ["Sa ei eksi kunagi!", "Eksimatu!", "Imeline!", "Perfektne täpsus!"];

    function getTitle(greetingList){
        return greetingList[Math.floor(Math.random() * greetingList.length)]
    }

    var title;


    // Greeting algoritms

    if(accuracy == 100){
        title = getTitle(greetingsPerfectAccuracy);
    }else if(accuracy >= 90){
        title = getTitle(greetingsHighAccuracy);
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