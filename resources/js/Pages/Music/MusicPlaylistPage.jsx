import Title from "@/Components/Music/Title";
import { Head } from "@inertiajs/react";
import "/public/css/muusika.css";
import MusicTile from "@/Components/Music/MusicTile";
import { act, useEffect, useRef, useState } from "react";
import SizedBox from "@/Components/SizedBox";
import Layout from "@/Components/2024SummerRedesign/Layout";

export default function MusicNew({auth, playlist, songs, providedBy=null}){

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


    // Used for getting the state value in an event listener
    const activeSongRef = useRef(activeSong);
    useEffect(()=>{
        activeSongRef.current = activeSong;
    }, [activeSong]);

    useEffect(()=>{
        testOrderRef.current = shuffleArray(songs);
    }, []);


    function playNextTrack(type){
        const songsList = Array.from(groupByArtist(songs).values()).flat();


        var currentSongIndex = songsList.indexOf(activeSongRef.current);        
        var nextSongIndex = type == "next" ? 0 : (songsList.length - 1);

        if(type == "next" && songsList.length > (currentSongIndex + 1)){
            nextSongIndex = currentSongIndex + 1;
        }

        if(type == "previous" && currentSongIndex > 0){
            nextSongIndex = currentSongIndex - 1;
        }

        var nextSong = songsList[nextSongIndex];

        songClick(nextSong);
    }


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
            
            if(audioRef.current.currentTime / audioRef.current.duration == 1){
                setTimeout(() => {
                    //Check again, if the time has been changed, dont go to next track

                    if(audioRef.current.currentTime / audioRef.current.duration == 1){
                        playNextTrack("next");
                    }
                }, 2500);
            }
        });

        navigator.mediaSession.setActionHandler("nexttrack", () => {
            playNextTrack("next");
        });

        navigator.mediaSession.setActionHandler("previoustrack", () => {
            playNextTrack("previous");
        });

        navigator.mediaSession.setActionHandler("seekbackward", (details) => {
            var newTime = Math.max(0, audioRef.current.currentTime - (details.seekOffset || 10));
            audioRef.current.currentTime = newTime;

            setCurrentTime(newTime);
        });

        navigator.mediaSession.setActionHandler("seekforward", (details) => {
            var newTime = Math.min(audioRef.current.duration, audioRef.current.currentTime + (details.seekOffset || 10));
            audioRef.current.currentTime = newTime;

            setCurrentTime(newTime);
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
        setCurrentTime(newTime);            
        audioRef.current.currentTime = newTime;
    };

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

    function groupByArtist(list) {
        return list.reduce((map, obj) => {
            if (!map.has(obj.artist)) {
                map.set(obj.artist, []);
            }
            map.get(obj.artist).push(obj);
            return map;
        }, new Map());
    }

    function getSongsInOrder(artistMap) {
        return Array.from(artistMap.values()).flat();
    }

    return <Layout title={playlist.name} auth={auth}>
        <div>
        <Head title={playlist.name + " | Muusika kuulamiskava"} />
        <SizedBox height={32} />
        <div>
            <div onClick={()=>setShowPracticeScreen(true)} className={"mode-choice " + (showPracticeScreen ? " chosen" : "")}><img className="music-icon" src="/assets/music-icons/listen.png" alt="" /> Kuulamiskava</div>
            <div onClick={()=>setShowPracticeScreen(false)} className={"mode-choice " + (!showPracticeScreen ? " chosen" : "")}><img className="music-icon" src="/assets/music-icons/dumbbell.png" alt="" /> Harjuta</div>
        </div>

        <SizedBox height={32} />

        <div className="practice-screen" hidden={!showPracticeScreen}>
            <div className="music-tiles">
                {songs.length <= 0 && <p>Siin ei ole veel teoseid...</p> }
                {songs.length > 0 && Array.from(groupByArtist(songs).keys()).length > 1 && Array.from(groupByArtist(songs).entries()).map(([artist, artistSongs]) => {
                    return  <div key={artist}>
                                <SizedBox height="50px" />
                                <h2 style={{textAlign:"start"}}>{artist}</h2>
                                {artistSongs.map(e => <MusicTile playlist={playlist} auth={auth} key={e.path} isActive={activeSong == e} isPlaying={activeSong == e && isPlaying} song={e} onClick={()=>songClick(e)} />)}
                            </div>
                })}
                {songs.length > 0 && Array.from(groupByArtist(songs).keys()).length <= 1 && songs.map(e => <MusicTile playlist={playlist} auth={auth} key={e.path} isActive={activeSong == e} isPlaying={activeSong == e && isPlaying} song={e} onClick={()=>songClick(e)} />)}
                {(playlist.owner == auth.user.id || auth.user.role.includes("music-admin")) && playlist.id != null && <a href={"/muusika/"+playlist.id+"/"+playlist.link_id+"/lisa"} style={{all:"unset", cursor:"pointer"}}>
                    <div style={{display:"flex", flexDirection:"row", alignItems:"center", gap:"16px"}}>
                        <span style={{fontSize:"48px"}}>+</span>
                        <div style={{fontWeight:"bold"}}>
                            Lisa teoseid või halda kuulamiskava
                        </div>
                    </div>
                </a>}

                {activeSong != null && <SizedBox height="150px"/>}

                {activeSong != null && <div className="active-control" >
                    <div className="main">
                        <p onClick={()=>setIsPlaying(i=>!i)} style={{textAlign:"start", fontSize:(window.innerWidth <= 800 ? "18px" : "20px"), margin:(window.innerWidth <= 800 ? "0" : "8px 0 0 0")}}>{activeSong.title} <span style={{color:"gray"}}>| {activeSong.artist}</span> </p>
                        <div style={{display:"flex", flexDirection:'row', alignItems:"center", gap: "8px"}}>
                            <p style={{fontVariantNumeric:"tabular-nums", fontSize:"14px", marginBlock:"0"}}>{humanReadableTime(currentTime)}</p>
                            <input type="range" onChange={handleSeek} value={duration ? Math.round(timeUpdatePrecision*currentTime/duration) : 0} min={0} max={timeUpdatePrecision} style={{width: "100%", margin:"0", paddingTop:"0", accentColor:"var(--button-border)"}} />
                            <p style={{fontVariantNumeric:"tabular-nums", fontSize:"14px", marginBlock:"0"}}>{humanReadableTime(duration)}</p>
                        </div>
                    </div>

                    <img className="music-icon" onClick={()=>setIsPlaying(i=>!i)} style={{height:"32px", width:"32px"}} src={"/assets/music-icons/"+(isPlaying ? "pause.png" : "play.png")}></img>
                </div>}
            </div>
            

            
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
                        <p id="correct-was-name" style={{fontWeight:"bold"}}></p>
                        <p id="correct-was-artist"></p>
                    </div>
                </div>

                <br /><br />

                <div>
                    <p onClick={()=>$("#correct-hint").fadeToggle(200)} id="view-correct" style={{color:"grey", cursor:"pointer"}}>Vaata õiget vastust</p>
                    <div hidden className="music-tile" style={{display: "block", pointerEvents: "none"}}>
                        <div className="info" style={{textAlign:"center", display:"none"}} id="correct-hint" hidden>
                            <p id="correct-name" style={{fontWeight:"bold"}}>Loo nimi</p>
                            <p id="correct-artist">Autori nimi</p>
                        </div>
                    </div>
                </div>
            </div>    
        </div>

    </div>
    </Layout>;
}