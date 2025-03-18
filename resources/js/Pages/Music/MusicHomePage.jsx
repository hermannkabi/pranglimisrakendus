import { Head } from "@inertiajs/react";
import "/public/css/muusika.css";
import Title from "@/Components/Music/Title";
import SizedBox from "@/Components/SizedBox";

export default function MusicHomePage({auth, playlists}){
    
    return <div>
        <Head title="Muusika kuulamiskavad | Muusika kuulamine" />

        <Title title="Muusika kuulamiskavad" showBack={false} />


        <div className="btn-group">
            {playlists.map(e=> <a key={e.id} style={{all:"unset", cursor:"pointer"}} href={"/muusika/" + e.id + "/" + e.link_id}><div className="course-widget">
                <img src={e.thumbnail} alt="" />
                <div className="content">
                    <p style={{fontWeight:"bold"}}>{e.name}</p>
                    <p style={{color:"gray"}}>{e.songs_count} teos{e.songs_count == 1 ? "" : "t"}</p>
                </div>
            </div></a>)}
            {<div className="new-playlist" style={{position:"relative"}}>
                    <span style={{fontSize:"64px"}}>+</span>
                    <p>Uus kuulamiskava</p>
                    <a style={{all:"unset", position:"absolute", height:"100%", width:"100%", top:"0", left:"0"}} href={route("musicNew")}></a>
            </div>}
        </div>
        <SizedBox height={32} />
    </div>;
}