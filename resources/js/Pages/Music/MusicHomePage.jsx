import { Head } from "@inertiajs/react";
import "/public/css/muusika.css";
import Title from "@/Components/Music/Title";
import SizedBox from "@/Components/SizedBox";
import Layout from "@/Components/2024SummerRedesign/Layout";
import "/public/css/welcome.css";


export default function MusicHomePage({auth, playlists}){
    
    return <Layout title={"Muusika kuulamiskavad"} auth={auth}>
        <div>
        <Head title="Muusika kuulamiskavad | Muusika kuulamine" />
        <Title title="Muusika kuulamiskavad" showBack={false} />

        <div className="playlist-widget-container" style={{display:"flex", flexDirection:"column", justifyContent:"center", alignItems:"stretch"}}>
            {playlists.map(e=> <a key={e.id} style={{all:"unset", cursor:"pointer", marginBlock:"16px"}} href={"/muusika/" + e.id + "/" + e.link_id}><div className="playlist-widget" style={{backgroundImage: "url('"+e.thumbnail+"')"}}>
                <div className="content">
                    <p className="playlist-title" style={{fontWeight:"bold", color: "rgb(var(--white))"}}>{e.name}</p>
                    <SizedBox height="4px" />
                    <p style={{fontSize:"22px", color:"rgb(var(--white))"}}>{e.songs_count} teos{e.songs_count == 1 ? "" : "t"}</p>
                </div>
            </div></a>)}
            {/* {<div className="new-playlist playlist-widget" style={{position:"relative", color: "var(--text-color)"}}>
                    <span style={{fontSize:"64px"}}>+</span>
                    <p>Uus kuulamiskava</p>
                    <a style={{all:"unset", position:"absolute", height:"100%", width:"100%", top:"0", left:"0"}} href={route("musicNew")}></a>
            </div>} */}
            <SizedBox height="16px" />
            <a style={{all:"unset", cursor:"pointer", margin:"auto"}} href={route("musicNew")}>
                <div className="text-button">
                    <i className="material-icons-outlined">add</i>
                    <span>Uus kuulamiskava</span>
                </div>
            </a>
        </div>
        <SizedBox height={32} />
    </div>
    </Layout>;
}