import BigButton from "@/Components/2024SummerRedesign/BigButton";
import Chip from "@/Components/2024SummerRedesign/Chip";
import Layout from "@/Components/2024SummerRedesign/Layout";
import TwoRowTextButton from "@/Components/2024SummerRedesign/TwoRowTextButton";
import InfoBanner from "@/Components/InfoBanner";
import { useState } from "react";

export default function CompetitionAddParticipantsPage({auth, data, others, competitionId}){

    const [selectedClass, setSelectedClass] = useState();
    const [search, setSearch] = useState(null);

    const [selectedUsers, setSelectedUsers] = useState([]);


    const allStudentsNames = data.reduce((map, obj) => {
        map[obj.klass.klass_id] = obj.students
          .map(person => `${person.eesnimi}${person.perenimi}`) // Concatenate eesnimi and perenimi
          .join('').toLowerCase(); // Join them all together with spaces
        return map;
      }, {});

    
    function selectClass(klass){
        if(klass.students.length <= 0) return;
        if(klass.students.every(v => selectedUsers.includes(v))){
            setSelectedUsers(s=>s.filter(i=>!klass.students.includes(i)));
        }else{
            setSelectedUsers(s=> [...new Set([...s, ...klass.students])]);
        }
    }    

    function addParticipants(){
        var userIds = [...new Set(selectedUsers.map(e=>e.id))];

        if(userIds.length > 0){
            console.log(userIds);
            $.post("/competition/"+competitionId+"/participants/add", {
                "_token":window.csrfToken,
                "participants":userIds,
            }).done(function (data){
                window.location.href = "/competition/"+competitionId+"/view";
            }).fail(function (data){
                console.log("Viga");
                console.log(data);
            });
        }
    }

    return <Layout title="Lisa võistlejaid" auth={auth}>
        <div className="two-column-layout">
            <div>
                <h2>Lisa osalejaid</h2>
                <p>Saad lisada osalejaid klasside kaupa või ükshaaval</p>
                <div style={{position:'relative', marginBottom:"16px"}}>
                    <i translate="no" style={{position:"absolute", top:"50%", transform:"translateY(-50%)", left:"8px", color:"var(--grey-color)", fontSize:"28px"}} className="material-icons">search</i>
                    <input onChange={(e)=>setSearch(e.target.value)} autoComplete="off" placeholder="Otsi klassi või õpilast" style={{backgroundColor: "var(--section-color)", borderRadius:"6px", padding:"32px 16px", paddingLeft:"50px", width:"100%", boxSizing:"border-box", margin:"0"}} className="search" type="search" />
                </div>

                {data.filter((e)=> search == null || search.length <= 0 || (e.name+allStudentsNames[e.klass.klass_id]).toLowerCase().includes(search.toLowerCase())).map(e=><div key={e.klass.klass_id} onClick={()=>{                    
                        return setSelectedClass(e);
                    }} class_id={e.klass.klass_id} style={{display:"flex", flexDirection:"row", alignItems:"center", justifyContent:"space-between"}} className={"clickable section class-tile"}>
                    <TwoRowTextButton upperText={e.name} lowerText={e.students.length + " õpilast"} showArrow={false} />
                    {e.students.every(v => selectedUsers.includes(v)) && e.students.length > 0 && <i className="material-icons" style={{fontSize:'24px', marginRight:"16px"}}>check</i> }
                </div>)}

                {auth.user.role.split(",").includes("admin") && others.filter((e)=> (search == null || search.length <= 0 || (e.eesnimi+e.perenimi).toLowerCase().includes(search.toLowerCase()))).map(e=> <Chip icon={selectedUsers.includes(e) ? "check" : null} active={selectedUsers.includes(e)} onClick={()=>setSelectedUsers(s=>s.includes(e) ? s.filter(i=>i!=e) : [...s, e])} key={e.id} capitalize={true} label={e.eesnimi +" "+ e.perenimi}/> )}
                {auth.user.role.split(",").includes("admin") && <p style={{color:"var(--grey-color)"}}>Kokku {data.reduce((sum, obj) => sum + obj.students.length, 0) + others.length} õpilast</p>}
            </div>

            <div>
                {selectedClass == null && <InfoBanner text={"Õpilase lisamiseks vajuta ta peal. Klassi lisamiseks vali klass ja vajuta 'Lisa terve klass'"} /> }
                {selectedClass && <>
                    <div style={{marginTop:"0"}} className="section">
                        <div className="section clickable" onClick={()=>selectClass(selectedClass)}>
                            <TwoRowTextButton upperText={"Lisa terve klass"} lowerText={selectedClass.name} showArrow={true} />
                        </div>
                        <div>
                            {selectedClass.students.map(e=><Chip icon={selectedUsers.includes(e) ? "check" : null} active={selectedUsers.includes(e)} key={e.id} label={e.eesnimi + " " + e.perenimi} capitalize={true} onClick={()=>setSelectedUsers(s=>s.includes(e) ? s.filter(i=>i!=e) : [...s, e])} />)}
                            {selectedClass.students.length <= 0 && <p style={{color:"var(--grey-color)"}}>Selles klassis ei ole veel kedagi</p> }
                        </div>
                    </div>
                </>}

                {selectedUsers.length > 0 && <BigButton onClick={addParticipants} title={"Lisa osalejad"} subtitle={selectedUsers.length + " osaleja" + (selectedUsers.length == 1 ? "" : "t")} />}
            </div>
        </div>
    </Layout>;
}