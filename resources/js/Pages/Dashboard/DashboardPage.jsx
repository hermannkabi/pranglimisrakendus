import Navbar from '@/Components/Navbar';
import { Head } from '@inertiajs/react';
import "/public/css/dashboard.css";
import StatisticsWidget from '@/Components/StatisticsWidget';
import BigGameButton from '@/Components/BigGameButton';
import SmallGameButton from '@/Components/SmallGameButton';
import LeaderboardRow from '@/Components/LeaderboardRow';
import CompetitionRow from '@/Components/CompetitionRow';
import SizedBox from '@/Components/SizedBox';

export default function Dashboard({auth}) {


    

    return (
        <>
            <Head title='Töölaud' />
            <Navbar title="Töölaud" user={auth.user}/>
            <div className='layout'>
                <div className='main-column'>
                    <section id='my-stats' >
                        <div className='header-container' style={{display:"flex", justifyContent:"space-between"}}>
                            <h3 className='section-header'>Minu statistika</h3>
                            <a style={{fontSize:"16px"}} alone="true" href="">Vaata rohkem&nbsp;<span className="material-icons">arrow_right_alt</span></a>
                        </div>
                        <div className='stat-container'>
                            <StatisticsWidget stat={42} desc="Treeningut" />
                            <StatisticsWidget stat={"47%"} desc="Vastamistäpsus" />
                            <StatisticsWidget stat={"28.07.2023"} desc="Viimati aktiivne" />
                        </div>
                        <SizedBox className="stats-bottom-margin" height='30px' />
                    </section>  
                    <section id='game-btns'>
                        <div className='header-container'>
                            <h3 className='section-header'>Vali harjutusala</h3>
                        </div>
                        <br />
                        <div className='big-game-btns'>
                            <BigGameButton symbol="×" text="Korrutamine" />
                            <BigGameButton symbol="÷" text="Jagamine" />
                            <BigGameButton symbol="+" text="Liitmine" />
                            <BigGameButton symbol="-" text="Lahutamine" />
                        </div>
                        <div className='small-game-btns'>
                            <SmallGameButton text="Liitlahutamine" />
                            <SmallGameButton text="Korrujagamine" />
                            <SmallGameButton text="Võrdlemine" />
                            <SmallGameButton text="Lünkamine" />
                            <SmallGameButton text="Segaarvutused" />
                            <SmallGameButton text="Kõik harjutused" all="true"/>
                        </div>
                        <br />
                    </section>
                </div>
                <div className='sidebar'>
                    <section id='leaderboard-section'>
                        <div className='header-container'>
                            <h3 className='section-header'>Klassi parimad</h3>
                        </div>
                        <div className='leaderboard' style={{marginInline:"8px", textAlign:"start"}}>
                            {Array.apply(null, Array(5)).map(function (current, index) {
                                const eesnimed=["Aleksandr","Sergei","Vladimir","Andrei","Aleksei","Martin","Andres","Dmitri","Igor","Toomas","Viktor","Margus","J\xfcri","Roman","Oleg","Kristjan","Rein","Maksim","Urmas","Aivar","Alexander","Jaan","Marko","Nikolai","Artur","Peeter","Ivan","Tarmo","Jevgeni","Indrek","Priit","Juri","Sander","Meelis","Mihhail","Aleksander","Nikita","Andrus","Raivo","Mati","Jaanus","Pavel","Valeri","Siim","Marek","Rasmus","Mihkel","Tiit","Artjom","T\xf5nu","Olga","Irina","Jelena","Svetlana","Tatjana","Anna","Valentina","Galina","Natalja","Maria","Marina","Tiina","Katrin","Sirje","Anne","Jekaterina","Natalia","Julia","Kristina","\xdclle","Tiiu","Ene","Diana","Viktoria","Nadežda","Piret","Aleksandra","Anneli","Riina","Tamara","Laura","Niina","Kadri","Kristi","Ljudmila","Merike","Maie","Anu","Marika","Eve","Tatiana","Mare","Elena","Oksana","Kristiina","Triin","Malle","Reet","Annika","Jana"];
                                const perenimed=["Ivanov","Tamm","Saar","Sepp","M\xe4gi","Kask","Smirnov","Kukk","Petrov","Ilves","Rebane","P\xe4rn","Vassiljev","Kuznetsov","Oja","Koppel","Luik","Kaasik","Kuusk","Raudsepp","Lepik","Karu","P\xf5der","Lepp","K\xfctt","Vaher","Pavlov","Kallas","Kivi","Mets","Kangur","Popov","L\xf5hmus","Stepanov","Liiv","Laur","Mihhailov","Kuusik","Volkov","Fjodorov","Teder","J\xf5gi","Peterson","Ots","Toom","K\xf5iv","M\xf6lder","Leppik","Sokolov","Puusepp","Raud",];
                                const name = eesnimed[Math.floor(Math.random()*eesnimed.length)] + " " + perenimed[Math.floor(Math.random()*perenimed.length)];
                                const uuid = crypto.randomUUID();
                                const points = Math.floor(Math.random()*50)*100;
                                return <LeaderboardRow index={index} name={name} points={points} key={uuid}/>
                            })}
                            <p style={{fontWeight:"bold"}}>...</p>
                            <LeaderboardRow player="true" index={8} name={"Eesnimi Perenimi"} points={400} />
                        </div>
                    </section>

                    <section id='my-comps'>
                        <div className='header-container'>
                            <h3 className='section-header'>Minu võistlused</h3>
                        </div>

                        <div className='competitions' style={{marginInline:"8px", textAlign:"start"}}>
                                <CompetitionRow name="Võistlus 1" date="28.07.2023" />
                                <CompetitionRow name="Võistlus 2" date="29.03.2006" />
                            </div>
                    </section>
                </div>
            </div>
        </>
    );
}
