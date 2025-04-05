import SizedBox from "../SizedBox";

export default function MusicTile({song, isPlaying=false, isActive=false, onClick, admin=false, select=false, auth, playlist}){


    function onDelete(e){
        e.stopPropagation();
        if(confirm("Kas oled kindel, et soovid selle teose kõikidest kuulamiskavadest kustutada?")){
            $.post(route("musicRemoveSong"), {
                "_token":window.csrfToken,
                "id": song.id,
            }).done(function (data){
                window.location.reload();
            }).fail(function (data){
                console.log("Viga");
                console.log(data);
            });
        }
    }

    function onRemove(e){
        e.stopPropagation();
        if(confirm("Kas oled kindel, et soovid selle teose kuulamiskavast eemaldada?")){
            $.post(route("musicRemoveSongFrom"), {
                "_token":window.csrfToken,
                "id": song.id,
                "playlist": playlist.id,
            }).done(function (data){
                window.location.reload();
            }).fail(function (data){
                console.log("Viga");
                console.log(data);
            });
        }
    }

    return <div>
            <div className="music-tile" style={isActive ? {backgroundColor:"var(--button-fill)", borderRadius:"4px", scale:"1.05", padding:"4px 2.5%"} : {}} song-id={song.path} onClick={onClick}>
                <div style={{display:"flex"}}>
                    {select && <input value={song.id} onClick={(e)=>e.stopPropagation()} style={{outline:"none"}} type="checkbox" />}
                    <div className="info">
                        <p className="song-title" style={{transition:"color 100ms", fontWeight: isActive ? "bold" : "normal"}}>{song.title}</p>
                        <p className="song-artist">{song.artist}</p>
                    </div>
                </div>
                <div style={{display:"flex", flexDirection:"row", gap:"8px", alignItems:"center"}}>
                    <p className="play-btn"><img className="music-icon" src={"/assets/music-icons/"+(isPlaying ? "pause.png" : "play.png")}></img></p>
                    {playlist != null && auth != null && (playlist.owner == auth.user.id || auth.user.role.includes("music-admin")) && <p onClick={(e)=>onRemove(e)}><img className="music-icon" style={{height: "24px", width:"24px"}} src={"/assets/music-icons/close.png"}></img></p>}
                    {admin && <p onClick={(e)=>onDelete(e)}><img className="music-icon" style={{height: "24px", width:"24px"}} src={"/assets/music-icons/delete.png"}></img></p>}
                </div>
            </div>            
    </div>;
}