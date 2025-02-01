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

    return <>
        <div className="music-tile" song-id={song.path} onClick={onClick}>
            <div style={{display:"flex"}}>
                {select && <input value={song.id} onClick={(e)=>e.stopPropagation()} style={{outline:"none"}} type="checkbox" />}
                <div className="info">
                    <p style={{transition:"color 100ms", fontWeight: isActive ? "bold" : "normal"}}>{song.title}</p>
                    <p>{song.artist}</p>
                </div>
            </div>
            <div style={{display:"flex", flexDirection:"row", gap:"8px"}}>
                <p className="play-btn"><i className='material-icons'>{isPlaying ? "pause" : "play_arrow"}</i></p>
                {playlist != null && auth != null && (playlist.owner == auth.user.id || auth.user.role.includes("music-admin")) && <p onClick={(e)=>onRemove(e)}><i className="material-icons">close</i></p>}
                {admin && <p onClick={(e)=>onDelete(e)}><i className="material-icons" style={{color:"red"}}>delete</i></p>}
            </div>
        </div>            
    </>;
}