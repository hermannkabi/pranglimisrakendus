import Title from "@/Components/Music/Title";
import { Head } from "@inertiajs/react";
import "/public/css/muusika.css";
import MusicTile from "@/Components/Music/MusicTile";
import { act, useEffect, useRef, useState } from "react";
import SizedBox from "@/Components/SizedBox";
import Layout from "@/Components/2024SummerRedesign/Layout";


export default function MusicNew({auth, playlist, songs}){

    const audioRef = useRef();
    const [activeSong, setActiveSong] = useState(null);
    const [isPlaying, setIsPlaying] = useState(false);
    const [currentTime, setCurrentTime] = useState(0);
    const [duration, setDuration] = useState(0);

    const [error, setError] = useState(null);



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
                album: "Muusika kuulamine",
                artwork:[
                    {"src":"http://127.0.0.1:8000/music-logo.png"}
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
        var minutes = Math.floor(time/60);
        var seconds = Math.round(time - 60*minutes);

        return (minutes <= 9 ? "0"+minutes : minutes) + ":" + (seconds <= 9 ? "0"+seconds : seconds);
    }

    const handleSeek = (e) => {
        const newTime = e.target.value / 100 * duration;
        audioRef.current.currentTime = newTime;
        setCurrentTime(newTime);
    };

    
    function submitForm(){
        var songs = [];

        $("input[type=checkbox]:checked").each(function () {
            songs.push($(this).val());
        });

        if(songs.length <= 0){
            setError("Palun vali teosed, mida kuulamiskavasse lisada");
            return;
        }


        let formData = new FormData();
        formData.append("songs", songs.join(";"));
        formData.append("_token", window.csrfToken); // CSRF protection

        
        $.ajax({
          url: "/muusika/"+playlist.id + "/" + playlist.link_id+"/lisa",
          type: "POST",
          data: formData,
          contentType: false,
          processData: false,
          success: function (response) {
            window.location.href = "/muusika/"+playlist.id + "/" + playlist.link_id;
          },
          error: function (xhr) {
            alert("Upload failed: " + xhr.responseText);
          }
        });
    }

    function deletePlaylist(){
        if(confirm("Kas oled kindel, et soovid selle kuulamiskava kustutada?")){
            let formData = new FormData();
            formData.append("id", playlist.id); // CSRF protection
            formData.append("_token", window.csrfToken); // CSRF protection
            $.ajax({
                url: route("deletePlaylist"),
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
            success: function (response) {
                window.location.href = route("music");
            },
            error: function (xhr) {
                alert("Upload failed: " + xhr.responseText);
            }
            });
        }
    }


    return <Layout auth={auth} title={"Lisa teoseid"}>
        <>
        <Head title="Lisa teoseid | Muusika kuulamiskava" />

        <Title title="Lisa teoseid"/>

        <div className="music-tiles">
            {songs.map(e => <MusicTile admin={auth.user.role.includes("music-admin")} select={true} key={e.path} isActive={activeSong == e} isPlaying={activeSong == e && isPlaying} song={e} onClick={()=>songClick(e)} />)}
        </div>

        <SizedBox height={50} />
        <div style={{width:"min(400px, 90%)", margin:"auto"}}>
            {error && <p style={{color:"red", textAlign:"start", marginBottom:"0"}}>{error}</p>}
            <button className="nice-btn" onClick={submitForm} style={{width:"100%", paddingBlock:"16px"}}>Lisa teosed</button>
            <SizedBox height={16} />
            <a onClick={deletePlaylist} alone="" style={{color:"red"}}> <i className="material-icons no-anim">delete</i> Kustuta kuulamiskava </a>
            <SizedBox height={16} />
        </div>
    </>
    </Layout>;
}