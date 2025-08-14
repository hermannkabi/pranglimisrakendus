import InfoBanner from "@/Components/InfoBanner";
import Layout from "@/Components/2024SummerRedesign/Layout";
import PasswordWidget from "@/Components/2024SummerRedesign/PasswordWidget";
import BigButton from "@/Components/2024SummerRedesign/BigButton";
import { useState } from "react";
import TwoRowTextButton from "@/Components/2024SummerRedesign/TwoRowTextButton";
import TimeSelector from "@/Components/2024SummerRedesign/TimeSelector";
import VerticalStatTile from "@/Components/2024SummerRedesign/VerticalStatTile";
import Chip from "@/Components/2024SummerRedesign/Chip";

export default function NewCompetitionPage({auth, competition}){

    const [name, setName] = useState(competition == null ? null : competition.name);
    const [description, setDescription] = useState(competition == null ? null : competition.description);
    const [dtStart, setDtStart] = useState(competition == null ? null : competition.dt_start);
    const [dtEnd, setDtEnd] = useState(competition == null ? null : competition.dt_end);
    const [attemptCount, setAttemptCount] = useState(competition == null ? 0 : competition.attempt_count);
    const [isPublic, setIsPublic] = useState(competition == null ? false : competition.public);


    // Game states
    const [gameTime, setGameTime] = useState(competition == null ? 1 : JSON.parse(competition.game_data).aeg);
    const [gameTypes, setGameTypes] = useState(competition == null ? [] : JSON.parse(competition.game_data).mis.split(","));
    const [gameMode, setGameMode] = useState(competition == null ? null : JSON.parse(competition.game_data).tyyp);
    const [levels, setLevels] = useState(competition == null ? [] : JSON.parse(competition.game_data).level.split(""));    


    const [message, setMessage] = useState();

    const gameNames = {
        "lihtsustamine":"Murru taandamine",
        "murruTaandamine":"Murru taandamine",
        "jaguvus":"Jaguvusseadused"
    };

    const modeNames = {
        "natural":"Naturaalarvud",
        "integer":"Täisarvud",
        "fraction":"Kümnendmurrud",
        "roman":"Rooma numbrid",
    };
    
    function getGameName(type){
        return type == null ? "Tundmatu" : type in gameNames ? gameNames[type] : type.substring(0, 1).toUpperCase() + type.substring(1);
    }


    function saveCompetition(){

        if(name == null || name.length <= 0){
            setMessage("Palun sisesta võistluse nimi");
            return;
        }

        if(dtStart == null || dtStart.length <= 0 || dtEnd == null || dtEnd.length <= 0){
            setMessage("Palun sisesta võistluse algus- ja lõpuaeg");
            return;
        }

        if(gameTypes.length <= 0){
            setMessage("Palun vali vähemalt üks mängutüüp");
            return;
        }

        if(gameMode == null || gameMode.length <= 0){
            setMessage("Palun vali arvuhulk");
            return;
        }

        if(levels.length <= 0){
            setMessage("Palun vali vähemalt üks tase");
            return;
        }

        $.post(route("competitionNewPost"), {
            "_token":window.csrfToken,
            "name":name,
            "description":description,
            "dt_start":dtStart,
            "dt_end":dtEnd,
            "attempt_count":attemptCount,
            "public":isPublic ? 1 : 0,
            "game_data":JSON.stringify({"mis":gameTypes.join(","), "tyyp":gameMode, "level":levels.join(""), "aeg":gameTime}),
            "competition_id":competition == null ? null : competition.competition_id,
        }).done(function (data){
            window.location.href = "/competition/"+data+"/view";
        }).fail(function (data){
            console.log("Viga");
            console.log(data);
        });
    }

    function deleteCompetition(){
        if(confirm("Kas oled kindel, et tahad võistluse kustutada?")){
            $.post(route("competitionDelete"), {
                "_token":window.csrfToken,
                "competition_id":competition.competition_id,
            }).done(function (data){
                window.location.href = route("dashboard");
            }).fail(function (data){
                console.log("Viga");
                console.log(data);
            });
        }
    }

    return <>
        <Layout title={competition == null ? "Uus võistlus" : "Muuda võistlust"} auth={auth}>
                <div className="two-column-layout">
                    {/* Esimene tulp */}
                    <div>
                        <div className="section" style={{display:"flex", justifyContent:"space-between", alignItems:'center', marginTop:"0"}}>
                            <TwoRowTextButton showArrow={false} upperText="Mänguaeg" lowerText="Kui pikalt mängid?" />
                            <TimeSelector time={gameTime} onIncrease={()=>setGameTime(defaultTime => parseFloat(defaultTime) >= 9.5 ? 10 : parseFloat(defaultTime) + 0.5)} onDecrease={()=>setGameTime(defaultTime => parseFloat(defaultTime) <= 0.5 ? 0.5 : parseFloat(defaultTime) - 0.5)} />
                        </div>

                        <div className="section" style={{marginTop:"0", padding:"8px 16px", display:"flex", flexDirection:"row", justifyContent:"space-between", alignItems:"center"}}>
                            <VerticalStatTile padding="8px 0" icon="joystick" text="Vali mängutüübid" customValue={true} value={<>
                                {["liitmine", "lahutamine", "liitlahutamine", "korrutamine", "jagamine", "korrujagamine", "astendamine", "juurimine", "astejuurimine", "võrdlemine", "lünkamine", "murruTaandamine", "jaguvus"].map(e=><Chip key={e} label={getGameName(e)} icon={gameTypes.includes(e) ? "check" : null} active={gameTypes.includes(e)}  onClick={()=>{setGameTypes(gameTypes.includes(e) ? gameTypes.filter(i=>e!=i) : [...gameTypes, e]);}} />)}
                            </>} />
                        </div>

                        <div className="section" style={{marginTop:"0", padding:"8px 16px", display:"flex", flexDirection:"row", justifyContent:"space-between", alignItems:"center"}}>
                            <VerticalStatTile padding="8px 0" icon="pin" text="Vali arvuhulk" customValue={true} value={<>
                                <p style={{color:"var(--grey-color)"}}>NB! Arvuhulk peab olema toetatud kõigi valitud mängutüüpide poolt. Näiteks, ära vali Rooma numbreid, kui oled mängutüübina valinud nt astendamise.</p>
                                {["natural", "integer", "fraction", "roman"].map(e=><Chip key={e} label={modeNames[e]} icon={gameMode==e ? "check" : null} active={gameMode == e}  onClick={()=>{setGameMode(g=>g==e ? null : e);}} />)}
                            </>} />
                        </div>

                        <div className="section" style={{marginTop:"0", padding:"8px 16px", display:"flex", flexDirection:"row", justifyContent:"space-between", alignItems:"center"}}>
                            <VerticalStatTile padding="8px 0" icon="exercise" text="Tasemed" customValue={true} value={
                                <>
                                    <p style={{color:"var(--grey-color)"}}>NB! Tasemeid valides arvesta palun kõigi valitud mängutüüpidega - ära vali 6. taset, kui valid nt liitmise, või 4.-6. taset, kui valid võrdlemise</p>
                                    {["1", "2", "3", "4", "5", "6"].map(e=><Chip key={e} label={e + ". tase"} icon={levels.includes(e) ? "check" : null} active={levels.includes(e)}  onClick={()=>{setLevels(levels.includes(e) ? levels.filter(i=>e!=i) : [...levels, e]);}} />)}
                                </>
                            } />                        
                        </div>
                    </div>

                    {/* Teine tulp */}
                    <div>
                        <PasswordWidget defaultValue={name} required={true} onChange={(e)=>setName(e.target.value)} style={{marginBlock: "8px"}} isPassword={false} icon="edit" text="Võistluse nimi" />
                        <PasswordWidget defaultValue={description} required={false} onChange={(e)=>setDescription(e.target.value)} style={{marginBlock: "8px"}} isPassword={false} icon="description" text="Võistluse kirjeldus" isTextArea={true} />

                        <div style={{display:"grid", gridTemplateColumns:(window.innerWidth <= 600 ? "1fr" : "repeat(2, 1fr)"), gap:"16px"}}>
                            <PasswordWidget defaultValue={dtStart} required={true} onChange={(e)=>setDtStart(e.target.value)} style={{marginBlock: "8px"}} isPassword={false} icon="calendar_clock" text="Algusaeg" type="datetime-local" />
                            <PasswordWidget defaultValue={dtEnd} required={true} onChange={(e)=>setDtEnd(e.target.value)} style={{marginBlock: "8px"}} isPassword={false} icon="calendar_clock" text="Lõpuaeg" type="datetime-local" />
                        </div>
                        <div className="section" style={{display:"flex", justifyContent:"space-between", alignItems:'center', marginTop:"0"}}>
                            <TwoRowTextButton showArrow={false} upperText="Mängukordade arv" lowerText={attemptCount == 0 ? "Piiramatu kord mängukordi" : attemptCount+" mängukorda lubatud"}/>
                            <TimeSelector simple={true} time={attemptCount} onIncrease={()=>setAttemptCount(t => Math.min(10, t+1))} onDecrease={()=>setAttemptCount(t => Math.max(t-1, 0))} />
                        </div>
                        <div onClick={()=>setIsPublic(i=>!i)} className="section clickable" style={{display:"flex", flexDirection:"row", justifyContent:"space-between", alignItems:"center", padding:"16px 16px 4px 4px"}}>
                            <TwoRowTextButton upperText="Võistluse avalikkus" lowerText={isPublic ? "Võistlus on avalik" : "Ainult admin saab osalejaid lisada"} showArrow={false} />
                            <i translate="no" className="material-icons" style={{fontSize:"32px", marginBottom:"0", marginLeft:"8px"}}>{isPublic ? "visibility" : "visibility_off"}</i>
                        
                        </div>
                        {message && <div className="section">
                            <InfoBanner type={"error"} text={message} />
                        </div>}
                        <BigButton onClick={saveCompetition} title={competition == null ? "Loo võistlus" : "Muuda võistlust"} subtitle={name == null || name.length <= 0 ? "Uus võistlus" : name} />
                        
                        {competition != null && <div onClick={deleteCompetition} className="section clickable red" style={{padding:"16px", display:"flex", justifyContent:"start", alignItems:"center", marginBlock:"0"}}>
                            <div style={{color:"var(--red-color)",}}>
                                <i translate="no" style={{fontSize:"32px"}} className="material-icons-outlined">delete</i>
                                <p style={{marginTop:"8px", marginBottom:"0"}}>Kustuta võistlus</p>
                            </div>
                        </div>}
                    </div>
                </div>
        </Layout>
    </>;
}