import GameTile from "@/Components/GameTile";
import SizedBox from "@/Components/SizedBox";
import Layout from "@/Components/2024SummerRedesign/Layout";
import InfoBanner from "@/Components/InfoBanner";
import StatisticsTile from "@/Components/2024SummerRedesign/StatisticsTile";
import TwoRowTextButton from "@/Components/2024SummerRedesign/TwoRowTextButton";
import VerticalStatTile from "@/Components/2024SummerRedesign/VerticalStatTile";
import StreakWidget from "@/Components/2024SummerRedesign/StreakWidget";
import { showFirstName, showPublicName } from "@/utils/display_name";


export default function PublicProfilePage({auth, user, klass, stats, lastGames}){

    const roles = {
        "teacher":"Õpetaja",
        "guest":"Külaline",
        "student":"Õpilane",
        "admin":"Admin",
        "music-admin":"Admin (muusika)",
        "valimised-admin":"Admin (valimised)",
    };

    function deleteUser(){
        if(confirm("See tegevus kustutab selle kasutaja ("+user.eesnimi + " " + user.perenimi +") ja kõik temaga seotud andmed Reaalerist. Kas oled kindel, et soovid jätkata?")){
            $.post("/profile/"+user.id+"/delete", {
                "_token":window.csrfToken,
            }).done(function (data){
                window.location.href = route("dashboard");
            }).fail(function (data){
                console.log(data);
            });
        }
    }

    return <>
        <Layout title="Avalik profiil" auth={auth}>
            {auth.user.id == user.id && <div className="section" style={{marginBottom:'16px'}}>
                <InfoBanner text={"Oled enda profiili avalikus vaates. Selliselt saavad sind vaadata sinu "+(auth.user.role == "teacher" ? "õpilased" : "õpetaja ja klassikaaslased")+". Profiili muutmiseks mine profiilivaatesse"} />
            </div>} 
            {stats.total_training_count > 0 && <div className="four-stat-row" style={{marginBottom:"16px"}}>
                <StreakWidget streak={stats.streak ?? 0} active={stats.streak_active} />
                <StatisticsTile stat={stats.total_training_count ?? "0"} label={"Mängu"} oneLabel={"Mäng"} icon={"sports_esports"} />
                <StatisticsTile stat={(stats.accuracy ?? "0") + "%"} label={"Vastamistäpsus"}icon={"percent"} />
                <a style={{textDecoration:"none", filter:"none", cursor:"pointer"}} className="clickable" href={"/stats/"+user.id}><StatisticsTile clickable={true} stat={"→"} label={"Kogu statistika"} icon={"query_stats"} /></a>
            </div>}

            <div className="two-column-layout">
                <div>
                    <div className="section" style={{position:"relative", backgroundImage:"url("+user.profile_pic+")", backgroundRepeat:"no-repeat", backgroundSize:"cover", backgroundBlendMode:"soft-light", backgroundPosition:"center"}}>
                        <div style={{display:"flex", flexDirection:"row", justifyContent:"space-between", alignItems:"center", marginInline:"8px"}}>
                            <div>
                                {showPublicName(auth.user, user) &&  <p style={{fontSize:"24px", fontWeight:"bold"}}>{user.public_name}</p>}
                                {!showPublicName(auth.user, user) && <TwoRowTextButton showArrow={false} capitalizeUpper={true} capitalizeLower={true} upperText={user.eesnimi} lowerText={user.perenimi} />}
                                {user.role.split(",").map(e=><span key={roles[e]} style={{display:"inline-block", backgroundColor:"rgb(var(--primary-color))", borderRadius:"4px", color:"white", fontSize:"16px", padding:"4px 6px", fontWeight:"normal", margin:"4px", marginTop:"0"}}>{roles[e]}</span>)}
                            </div>

                            <div>
                                <img src={user.profile_pic} style={{borderRadius:"50%", aspectRatio:'1', height:"100px", objectFit:"cover"}}/>
                            </div>
                        </div>
                        
                        
                        
                        <SizedBox height="150px" />
                        <p style={{position:"absolute", bottom:"16px", right:"16px", display:"flex", alignItems:'center', marginBlock:"0", color:"var(--grey-color)"}}>Reaaleris alates {(new Date(user.created_at)).toLocaleString("et-EE", {month:"2-digit", day:"2-digit", year:"numeric"}).split(",")[0]}</p>
                    </div>
                    <SizedBox height="8px" />
                    {lastGames.map((e, ind)=><GameTile data={e} key={ind} />)}
                    {lastGames.length <= 0 && <div className="section">
                        <InfoBanner text={showFirstName(auth.user, user) + " ei ole veel mängida jõudnud. Vaata veidi aja pärast uuesti!"} />
                    </div> }
                </div>

                <div>
                    <div style={{display:"grid", gridTemplateColumns:"repeat(2, 1fr)", gap:"16px"}}> 
                        <VerticalStatTile icon="school" text="Klass" value={klass == null ? "Klassi pole" : klass.klass_name} />
                        <VerticalStatTile icon="apartment" text="Kool" value={<><img style={{borderRadius:"50%", objectFit:"cover", height:"24px", aspectRatio:"1", margin:"0"}} src="https://reaaler.real.edu.ee/assets/logo.png" alt="" /> Tallinna Reaalkool</>} />
                    </div>

                    {lastGames.length > 0 && <div onClick={()=>window.location.href = "/game/history/"+user.id} className="section clickable" style={{position:"relative"}}>
                        <TwoRowTextButton upperText="Mängude ajalugu" lowerText="Vaata kõiki" />
                        <a href={"/game/history/"+user.id} style={{all:"unset", position:"absolute", top:"0", left:"0", height:"100%", width:"100%"}}></a>
                    </div>}
                    <div onClick={()=>window.location.href = "/competition/history/"+user.id} className="section clickable" style={{position:"relative"}}>
                        <TwoRowTextButton upperText="Võistluste ajalugu" lowerText="Vaata tulemusi" />
                        <a href={"/competition/history/"+user.id} style={{all:"unset", position:"absolute", top:"0", left:"0", height:"100%", width:"100%"}}></a>
                    </div>

                    {auth.user.role.split(",").includes("admin") && <div onClick={deleteUser} className="section clickable">
                        <div style={{color:"var(--red-color)",}}>
                            <i translate="no" style={{fontSize:"32px"}} className="material-icons-outlined">delete</i>
                            <p style={{marginTop:"8px", marginBottom:"0"}}>Kustuta konto Reaalerist</p>
                        </div>
                    </div>}
                </div>
            </div>
        </Layout>
    </>;
}