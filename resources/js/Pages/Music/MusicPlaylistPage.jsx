import Title from "@/Components/Music/Title";
import { Head } from "@inertiajs/react";
import "/public/css/muusika.css";
import MusicTile from "@/Components/Music/MusicTile";
import { act, useEffect, useRef, useState } from "react";
import SizedBox from "@/Components/SizedBox";


export default function MusicNew({auth, playlist, songs}){

    const timeUpdatePrecision = 100000;

    const audioRef = useRef();
    const testAudioRef = useRef();
    const testCurrentSong = useRef();
    const testOrderRef = useRef();

    const [activeSong, setActiveSong] = useState(null);
    const [isPlaying, setIsPlaying] = useState(false);
    const [currentTime, setCurrentTime] = useState(0);
    const [duration, setDuration] = useState(0);
    const [showPracticeScreen, setShowPracticeScreen] = useState(true);


    const [testPlaying, setTestPlaying] = useState(true);
    const [testShowGame, setTestShowGame] = useState(false);



    useEffect(()=>{
        testOrderRef.current = shuffleArray(songs);
    }, []);
   


    // const appearance = {
    //     "barokk":{
    //         "--background-color": "#4B000F",
    //         "--text-color": "white",
    //         "--button-color": "#D4AF37",
    //         "--button-text-color": "#4B000F",
    //         "--primary-font": "Cormorant Garamond",
    //         "--secondary-font": "Merriweather",
    //     },
    //     "klassitsism":{
    //         "--background-color": "#F2E6D8",
    //         "--text-color": "#2E2E2E",
    //         "--button-color": "#2E2E2E",
    //         "--button-text-color": "white",
    //         "--primary-font": "Cardo",
    //         "--secondary-font": "Work Sans",
    //     },
    //     "eesti":{
    //         "--background-color": "#C7EEFF",
    //         "--text-color": "black",
    //         "--button-color": "#0077C0",
    //         "--button-text-color": "white",
    //         "--primary-font": "Sedan SC",
    //         "--secondary-font": "Lexend",
    //     },
    //     "xx-sajand":{
    //         "--background-color": "#C7EEFF",
    //         "--text-color": "black",
    //         "--button-color": "#0077C0",
    //         "--button-text-color": "white",
    //         "--primary-font": "Sedan SC",
    //         "--secondary-font": "Lexend",
    //     },
    //     "ii-kursuse-klaveriteosed":{
    //         "--background-color": "white",
    //         "--text-color": "black",
    //         "--button-color": "black",
    //         "--button-text-color": "white",
    //         "--primary-font": "Sora",
    //         "--secondary-font": "Sora",
    //     },
    //     "ii-kursuse-sumfooniateosed":{
    //         "--background-color": "white",
    //         "--text-color": "black",
    //         "--button-color": "black",
    //         "--button-text-color": "white",
    //         "--primary-font": "Sora",
    //         "--secondary-font": "Sora",
    //     },
    //     "ii-kursuse-vokaalteosed":{
    //         "--background-color": "white",
    //         "--text-color": "black",
    //         "--button-color": "black",
    //         "--button-text-color": "white",
    //         "--primary-font": "Sora",
    //         "--secondary-font": "Sora",
    //     },
    //     "jazz":{
    //         "--background-color": "#0B0A25",
    //         "--text-color": "white",
    //         "--button-color": "#181844",
    //         "--button-text-color": "white",
    //         "--primary-font": "Bitter",
    //         "--secondary-font": "Bitter",
    //     },
    // }


    function songClick(e){

        if(activeSong == e){
            setIsPlaying(i=>!i);
            return;
        }

        if(activeSong != e){
            setIsPlaying(false);
            setCurrentTime(0);
            setDuration(0);
            if(audioRef.current){
                audioRef.current.pause();
            }    
            audioRef.current = null;

            navigator.mediaSession.metadata = new MediaMetadata({
                title: e.title,
                artist: e.artist,
                album: playlist.name,
                artwork:[
                    {"src":playlist.thumbnail}
                ]
            });


            navigator.mediaSession.setActionHandler("play", () => {
                setIsPlaying(true);
            });
        
            navigator.mediaSession.setActionHandler("pause", () => {
                setIsPlaying(false);
            });

            navigator.mediaSession.setActionHandler("seekto", (details) => {
                audioRef.current.currentTime = details.seekTime;

                setCurrentTime(details.seekTime);
            });
        
        }


        setActiveSong(e);
        audioRef.current = new Audio("/storage/music/"+e.path+".mp3");

        audioRef.current.addEventListener("loadedmetadata", () => {
            setDuration(audioRef.current.duration);
        });

        audioRef.current.addEventListener("timeupdate", () => {            
            setCurrentTime(audioRef.current.currentTime);
        });

        setIsPlaying(i=>!i);
        audioRef.current.play();
    }

    useEffect(()=>{
        if(activeSong){
            
            if(isPlaying){
                audioRef.current.play();
            }else{
                audioRef.current.pause();
            }
        }
    }, [isPlaying]);


    function humanReadableTime(time){
        var time = Math.round(time);
        var minutes = Math.floor(time/60);
        var seconds = Math.round(time - 60*minutes);

        return (minutes <= 9 ? "0"+minutes : minutes) + ":" + (seconds <= 9 ? "0"+seconds : seconds);
    }

    const handleSeek = (e) => {
        const newTime = e.target.value / timeUpdatePrecision * duration;
        audioRef.current.currentTime = newTime;
        setCurrentTime(newTime);
    };

    // if(playlist.link_id in appearance){
    //     for(var key of Object.keys(appearance[playlist.link_id])){            
    //         document.documentElement.style.setProperty(key, appearance[playlist.link_id][key]);
    //     }
    // }


    function shuffleArray(array) {
        const shuffled = [...array];
        for (let i = shuffled.length - 1; i > 0; i--) {
            const randomIndex = Math.floor(Math.random() * (i + 1));
            [shuffled[i], shuffled[randomIndex]] = [shuffled[randomIndex], shuffled[i]];
        }
        return shuffled;
    }
        
    function chooseRandomSong(){
        if(!testCurrentSong.current){
            return testOrderRef.current[0];
        }
        
        var currentIndex = testOrderRef.current.indexOf(testCurrentSong.current);
    
        if(testOrderRef.current.length <= currentIndex + 1){
            testOrderRef.current = shuffleArray(songs);            
            return testOrderRef.current[0];
        }
    
        return testOrderRef.current[currentIndex + 1];
    }
    
    
    function changeSong(){
        $("#correct-hint").hide();
        var song = chooseRandomSong();
    
        testCurrentSong.current = song;
    
        var audio = new Audio("/storage/music/"+song.path+".mp3");
        testAudioRef.current = audio;

        navigator.mediaSession.metadata = new MediaMetadata({
            title: "Harjuta kuulamiskava",
            artist: playlist.name,
            album: playlist.name,
            artwork:[
                {"src":playlist.thumbnail}
            ],
        });


        navigator.mediaSession.setActionHandler("play", () => {
            setTestPlaying(true);
            testAudioRef.current.play();
        });
    
        navigator.mediaSession.setActionHandler("pause", () => {
            setTestPlaying(false);
            testAudioRef.current.pause();
        });

        navigator.mediaSession.setActionHandler("nexttrack", () => {
            nextSong();
        });
    
        audio.currentTime = 0;
        $("#correct-name").text(song.title);
        $("#correct-artist").text(song.artist);
    
        audio.play();
    
        $(".test-toggle-song > .material-icons").text("pause");
    }

    function nextSong(){
        $(".correct-answer").text("Eelmine vastus oli");
                
        $("#correct-was-name").text(testCurrentSong.current.title);
        $("#correct-was-artist").text(testCurrentSong.current.artist);
    
        var audio = testAudioRef.current;
        audio.pause();
        audio.currentTime = 0;

        changeSong();
    }

    function toggleTestAudio(){
        var audio = testAudioRef.current;
        setTestPlaying(!testPlaying);
        testPlaying ? audio.pause() : audio.play();
    }
    
    
    return <div>
        <Head title={playlist.name + " | Muusika kuulamiskava"} />

        <Title title={playlist.name} thumbnail={playlist.thumbnail} />

        <div>
            <div onClick={()=>setShowPracticeScreen(true)} className={"mode-choice " + (showPracticeScreen ? " chosen" : "")}><img className="music-icon" src="/assets/music-icons/listen.png" alt="" /> Kuulamiskava</div>
            <div onClick={()=>setShowPracticeScreen(false)} className={"mode-choice " + (!showPracticeScreen ? " chosen" : "")}><img className="music-icon" src="/assets/music-icons/dumbbell.png" alt="" /> Harjuta</div>
        </div>

        <SizedBox height={32} />

        <div className="practice-screen" hidden={!showPracticeScreen}>
            <div className="music-tiles">
                {songs.length <= 0 && <p>Siin ei ole veel teoseid...</p> }
                {songs.map(e => <MusicTile playlist={playlist} auth={auth} key={e.path} isActive={activeSong == e} isPlaying={activeSong == e && isPlaying} song={e} onClick={()=>songClick(e)} />)}
                {(playlist.owner == auth.user.id || auth.user.role.includes("music-admin")) && playlist.id != null && <a href={"/muusika/"+playlist.id+"/"+playlist.link_id+"/lisa"} style={{all:"unset", cursor:"pointer"}}>
                    <div style={{display:"flex", flexDirection:"row", alignItems:"center", gap:"16px"}}>
                        <span style={{fontSize:"48px"}}>+</span>
                        <div style={{fontWeight:"bold"}}>
                            Lisa teoseid või halda kuulamiskava
                        </div>
                    </div>
                </a>}
            </div>
            

            {activeSong != null && <SizedBox height="150px"/>}

            {activeSong != null && <div className="active-control" >
                <div className="main">
                    <p onClick={()=>setIsPlaying(i=>!i)} style={{textAlign:"start", fontSize:"24px", margin:"8px 0 0 0"}}>{activeSong.title}</p>
                    <div style={{display:"flex", flexDirection:'row', alignItems:"center", gap: "8px"}}>
                        <p style={{fontVariantNumeric:"tabular-nums", fontSize:"14px", marginBlock:"0"}}>{humanReadableTime(currentTime)}</p>
                        <input type="range" onChange={handleSeek} value={duration ? Math.round(timeUpdatePrecision*currentTime/duration) : 0} min={0} max={timeUpdatePrecision} style={{width: "100%", margin:"0", paddingTop:"0", accentColor:"var(--button-border)"}} />
                        <p style={{fontVariantNumeric:"tabular-nums", fontSize:"14px", marginBlock:"0"}}>{humanReadableTime(duration)}</p>
                    </div>
                </div>

                <img className="music-icon" onClick={()=>setIsPlaying(i=>!i)} style={{height:"32px", width:"32px"}} src={"/assets/music-icons/"+(isPlaying ? "pause.png" : "play.png")}></img>            </div>}
        </div>

        <div className="test-screen" hidden={showPracticeScreen}>
            <br /><br /><br />
            <h2 style={{color:"var(--text-color)"}}>Testi enda teadmisi!</h2>
            <p style={{maxWidth:"min(50%, 400px)", margin:"auto", display: testShowGame ? "none" : ""}}>Kuula teoseid juhuslikus järjekorras ja katsu arvata, millega on tegu!</p>
            <br />
            <button style={{display: testShowGame ? "none" : "", color:"var(--text-color)"}} onClick={()=>{changeSong(); setTestShowGame(true);}} className="start-test nice-btn">Alusta</button>
            <div className="game-screen" hidden={!testShowGame}>
                <img className="music-icon test-toggle-song" onClick={toggleTestAudio} style={{height:"32px", width:"32px"}} src={"/assets/music-icons/"+(testPlaying ? "pause.png" : "play.png")}></img> 
                <br /><br /><button style={{color:"var(--text-color)"}} className="nice-btn next-song" onClick={nextSong}>Edasi</button>
                <br /><br />
                <p className="correct-answer" style={{color: "grey"}}></p>
                <div className="music-tile" style={{display: "block", pointerEvents: "none"}}>
                    <div className="info" style={{textAlign:"center"}}>
                        <p id="correct-was-name"></p>
                        <p id="correct-was-artist"></p>
                    </div>
                </div>

                <br /><br />

                <div>
                    <p onClick={()=>$("#correct-hint").fadeToggle(200)} id="view-correct" style={{color:"grey", cursor:"pointer"}}>Vaata õiget vastust</p>
                    <div hidden className="music-tile" style={{display: "block", pointerEvents: "none"}}>
                        <div className="info" style={{textAlign:"center", display:"none"}} id="correct-hint" hidden>
                            <p id="correct-name">Loo nimi</p>
                            <p id="correct-artist">Autori nimi</p>
                        </div>
                    </div>
                </div>
            </div>    
        </div>

    </div>;
}