import Title from "@/Components/Music/Title";
import { Head } from "@inertiajs/react";
import "/public/css/muusika.css";
import MusicTile from "@/Components/Music/MusicTile";
import { act, useEffect, useRef, useState } from "react";
import SizedBox from "@/Components/SizedBox";


export default function MusicNew({auth, playlist, songs}){

    const audioRef = useRef();
    const [activeSong, setActiveSong] = useState(null);
    const [isPlaying, setIsPlaying] = useState(false);
    const [currentTime, setCurrentTime] = useState(0);
    const [duration, setDuration] = useState(0);



    const appearance = {
        "barokk":{
            "--background-color": "#4B000F",
            "--text-color": "white",
            "--button-color": "#D4AF37",
            "--button-text-color": "#4B000F",
            "--primary-font": "Cormorant Garamond",
            "--secondary-font": "Merriweather",
        },
        "klassitsism":{
            "--background-color": "#F2E6D8",
            "--text-color": "#2E2E2E",
            "--button-color": "#2E2E2E",
            "--button-text-color": "white",
            "--primary-font": "Cardo",
            "--secondary-font": "Work Sans",
        },
        "eesti":{
            "--background-color": "#C7EEFF",
            "--text-color": "black",
            "--button-color": "#0077C0",
            "--button-text-color": "white",
            "--primary-font": "Sedan SC",
            "--secondary-font": "Lexend",
        },
        "xx-sajand":{
            "--background-color": "#C7EEFF",
            "--text-color": "black",
            "--button-color": "#0077C0",
            "--button-text-color": "white",
            "--primary-font": "Sedan SC",
            "--secondary-font": "Lexend",
        },
        "ii-kursuse-klaveriteosed":{
            "--background-color": "white",
            "--text-color": "black",
            "--button-color": "black",
            "--button-text-color": "white",
            "--primary-font": "Sora",
            "--secondary-font": "Sora",
        },
        "ii-kursuse-sumfooniateosed":{
            "--background-color": "white",
            "--text-color": "black",
            "--button-color": "black",
            "--button-text-color": "white",
            "--primary-font": "Sora",
            "--secondary-font": "Sora",
        },
        "ii-kursuse-vokaalteosed":{
            "--background-color": "white",
            "--text-color": "black",
            "--button-color": "black",
            "--button-text-color": "white",
            "--primary-font": "Sora",
            "--secondary-font": "Sora",
        },
        "jazz":{
            "--background-color": "#0B0A25",
            "--text-color": "white",
            "--button-color": "#181844",
            "--button-text-color": "white",
            "--primary-font": "Bitter",
            "--secondary-font": "Bitter",
        },
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
        const newTime = e.target.value / 100 * duration;
        audioRef.current.currentTime = newTime;
        setCurrentTime(newTime);
    };

    if(playlist.link_id in appearance){
        for(var key of Object.keys(appearance[playlist.link_id])){
            console.log(key);
            
            document.documentElement.style.setProperty(key, appearance[playlist.link_id][key]);
        }
    }
    
    return <div>
        <Head title={playlist.name + " | Muusika kuulamiskava"} />

        <Title title={playlist.name}/>

        <div className="music-tiles">
            {songs.length <= 0 && <p>Siin ei ole veel teoseid...</p> }
            {songs.map(e => <MusicTile playlist={playlist} auth={auth} key={e.path} isActive={activeSong == e} isPlaying={activeSong == e && isPlaying} song={e} onClick={()=>songClick(e)} />)}
            {(playlist.owner == auth.user.id || auth.user.role.includes("music-admin")) && <a href={"/muusika/"+playlist.id+"/"+playlist.link_id+"/lisa"} style={{all:"unset", cursor:"pointer"}}>
                <div style={{display:"flex", flexDirection:"row", alignItems:"center", gap:"16px"}}>
                    <span style={{fontSize:"48px"}}>+</span>
                    <div style={{fontWeight:"bold"}}>
                        Lisa teoseid
                    </div>
                </div>
            </a>}
        </div>
        

        {activeSong != null && <SizedBox height="200px"/>}

        {activeSong != null && <div className="active-control">
            <MusicTile onClick={()=>setIsPlaying(i=>!i)} isActive={false} isPlaying={isPlaying} song={activeSong} />
            <div style={{display:"flex", flexDirection:'row', alignItems:"center", gap: "8px"}}>
                <p style={{fontVariantNumeric:"tabular-nums"}}>{humanReadableTime(currentTime)}</p>
                <input type="range" onChange={handleSeek} value={duration == 0 ? 0 : Math.round(100*currentTime/duration)} style={{width: "100%", margin:"0", paddingTop:"0", accentColor:"var(--button-text-color)"}} />
                <p style={{fontVariantNumeric:"tabular-nums"}}>{humanReadableTime(duration)}</p>
            </div>
        </div>}
    </div>;
}