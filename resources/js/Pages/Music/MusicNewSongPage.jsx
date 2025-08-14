import Title from "@/Components/Music/Title";
import { Head } from "@inertiajs/react";
import "/public/css/muusika.css";
import Chip from "@/Components/2024SummerRedesign/Chip";
import { useState } from "react";
import Layout from "@/Components/2024SummerRedesign/Layout";

export default function MusicNewSongPage({auth, playlists}){

    const [chosenLists, setChosenLists] = useState([]);
    const [error, setError] = useState(null);


    function submitForm(){
        var title = $(".song-title").val();
        var artist = $(".song-author").val();

        if(!title){
            setError("Palun sisesta teose pealkiri");
            return;
        }

        if(!artist){
            setError("Palun sisesta teose autor");
            return;
        }

        let fileData = $(".file-select")[0].files[0]; // Get file
        let formData = new FormData();
        formData.append("file", fileData);
        formData.append("title", title);
        formData.append("artist", artist);
        formData.append("playlists", chosenLists.map(e=>e.id).join(";"));
        formData.append("_token", window.csrfToken); // CSRF protection

        
  
        $.ajax({
          url: route("musicNewSongPost"),
          type: "POST",
          data: formData,
          contentType: false,
          processData: false,
          success: function (response) {
            alert("File uploaded successfully!");
            console.log(response);
            
          },
          error: function (xhr) {
            alert("Upload failed: " + xhr.responseText);
          }
        });
    }

    return <Layout auth={auth} title={"Lisa uus teos"}>
        <>
        <Head title="Lisa uus teos | Muusika kuulamine" />
        <Title title="Lisa uus teos" />

        <div className="container" style={{width:"min(400px, 90%)", margin:"auto"}}>
            <input className="file-select" style={{width:"calc(100% - 12px)"}} type="file" placeholder="Laadi muusikafail üles" accept=".mp3" /><br />

            <input className="song-title" style={{width:"calc(100% - 12px)"}} type="text" placeholder="Teose pealkiri" /><br />
            <input className="song-author" style={{width:"calc(100% - 12px)"}} type="text" placeholder="Teose autor" />
            <div className="section" style={{backgroundColor:"var(--button-fill)"}}>
                <p>Vali kuulamiskavad, kuhu soovid teose lisada</p>
                {playlists.map(e=> <Chip key={e.id} active={chosenLists.includes(e)} onClick={()=>setChosenLists(chosenLists.includes(e) ? chosenLists.filter(i=>i!=e) : [...chosenLists, e])} icon={chosenLists.includes(e) ? "check" : null} label={e.name} colors={{active:"var(--button-color)", background:"rgb(174, 174, 174)"}}/>)}
            </div>
            {error && <p style={{color:"red", textAlign:"start", marginBottom:"0"}}>{error}</p>}
            <button className="nice-btn" onClick={submitForm} style={{width:"100%", paddingBlock:"16px"}}>Lisa teos</button>
        </div>
    </>
    </Layout>;
}