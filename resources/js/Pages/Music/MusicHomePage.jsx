import { Head } from "@inertiajs/react";
import "/public/css/muusika.css";

export default function MusicHomePage({auth, playlists}){

    console.log(playlists);
    
    return <div>
        <Head title="Vali kuulamiskava | Muusika kuulamine" />

        <div className="title-container">
            <h1 className="title">Vali kuulamiskava</h1>
            <p className="type">Muusika kuulamine</p>
        </div>

        <div className="btn-group">
            {playlists.map(e=> <a style={{all:"unset", cursor:"pointer"}} href={"muusika/" + e.link_id}><div className="course-widget">
                <img src={e.thumbnail} alt="" />
                <div className="content">
                    <p style={{fontWeight:"bold"}}>{e.name}</p>
                    <p style={{color:"#F0F0F0"}}>{e.songs_count} laul{e.songs_count == 1 ? "" : "u"}</p>
                </div>
            </div></a>)}
            {auth.user.role.includes("music-admin") && <div className="new-playlist" style={{position:"relative"}}>
                    <span style={{fontSize:"64px"}}>+</span>
                    <p>Uus kuulamiskava</p>
                    <a style={{all:"unset", position:"absolute", height:"100%", width:"100%", top:"0", left:"0"}} href={route("musicNew")}></a>

                </div>}
        </div>
    </div>;
}