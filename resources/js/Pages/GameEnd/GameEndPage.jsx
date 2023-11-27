import Navbar from "@/Components/Navbar";
import { Head } from "@inertiajs/react";
import "/public/css/game_end.css";
import SizedBox from "@/Components/SizedBox";

export default function GameEndPage(){


    const statNameStyle = {color:'gray', marginBlock: "0"};

    const greetings = ["Hästi tehtud!", "Väga tubli!", "Suurepärane!", "Tubli töö!"];

    var title = greetings[Math.floor(Math.random() * greetings.length)];


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
                            <h3 style={{marginBlock:0}}>20s</h3>
                        </div>
                        <div className="stat-row">
                            <p style={statNameStyle}>TEHETE ARV</p>
                            <h3 style={{marginBlock:0}}>12</h3>
                        </div>
                        <div className="stat-row">
                            <p style={statNameStyle}>VIIMANE TASE</p>
                            <h3 style={{marginBlock:0}}>3</h3>
                        </div>
                        <div className="stat-row">
                            <p style={statNameStyle}>VASTAMISTÄPSUS</p>
                            <h3 style={{marginBlock:0}}>75%</h3>
                        </div>
                        <div className="stat-row">
                            <p style={statNameStyle}><b>PUNKTE</b></p>
                            <h3 style={{marginBlock:0}}>1200</h3>
                        </div>
                    </div>

                </section>
                {/* Buttons */}
                <section>
                    <div className="btn-container">
                        <button onClick={()=>history.back()} style={{flex:'1'}} secondary="true">Proovi uuesti</button>
                        <button onClick={()=>window.location.href = route("dashboard")} style={{flex:'1'}}>Edasi</button>
                    </div>
                </section>
            </div>
        </>
    );
}