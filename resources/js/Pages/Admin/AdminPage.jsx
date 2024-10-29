import Chip from "@/Components/2024SummerRedesign/Chip";
import Layout from "@/Components/2024SummerRedesign/Layout";
import TwoRowTextButton from "@/Components/2024SummerRedesign/TwoRowTextButton";
import InfoBanner from "@/Components/InfoBanner";
import { useState } from "react";

export default function AdminPage({auth, data}){

    const [selectedClass, setSelectedClass] = useState();
    const [search, setSearch] = useState(null);


    const allStudentsNames = data.reduce((map, obj) => {
        map[obj.klass.klass_id] = obj.students
          .map(person => `${person.eesnimi}${person.perenimi}`) // Concatenate eesnimi and perenimi
          .join('').toLowerCase(); // Join them all together with spaces
        return map;
      }, {});

    return <Layout title="Admin" auth={auth}>
        <div className="two-column-layout">
            <div>
                <div style={{position:'relative', marginBottom:"16px"}}>
                    <i translate="no" style={{position:"absolute", top:"50%", transform:"translateY(-50%)", left:"8px", color:"var(--grey-color)", fontSize:"28px"}} className="material-icons">search</i>
                    <input onChange={(e)=>setSearch(e.target.value)} autoComplete="off" placeholder="Otsi klassi või õpilast" style={{backgroundColor: "var(--section-color)", borderRadius:"6px", padding:"32px 16px", paddingLeft:"50px", width:"100%", boxSizing:"border-box", margin:"0"}} className="search" type="search" />
                </div>

                {data.filter((e)=> search == null || search.length <= 0 || (e.name+e.teacher.eesnimi+e.teacher.perenimi+allStudentsNames[e.klass.klass_id]).toLowerCase().includes(search.toLowerCase())).map(e=><div key={e.klass.klass_id} onClick={()=>{
                        return setSelectedClass(e);
                    }} class_id={e.klass.klass_id} style={{display:"flex", flexDirection:"row", alignItems:"flex-end", justifyContent:"space-between"}} className="clickable section class-tile">
                    <TwoRowTextButton upperText={e.name} lowerText={e.teacher.eesnimi + " " + e.teacher.perenimi} capitalizeLower={true} showArrow={false} />
                    <p style={{textAlign:"right", color:"var(--grey-color)", marginBottom:"0", marginRight:"8px"}}>{e.students.length} õpilast</p>
                </div>)}
                <p style={{color:"var(--grey-color)"}}>Kokku {data.reduce((sum, obj) => sum + obj.students.length, 0)} õpilast</p>
            </div>

            <div>
                {selectedClass == null && <InfoBanner text={"Täpsema info saamiseks palun vali klass"} /> }
                {selectedClass && <>
                    <div style={{marginTop:"0"}} className="section">
                       <a style={{all:"unset", cursor:"pointer", display:"inline-flex"}} href={"/classroom/"+selectedClass.klass.uuid+"/view"}>
                            <div className="section clickable">
                                <TwoRowTextButton upperText={selectedClass.name} lowerText={selectedClass.teacher.eesnimi + " " + selectedClass.teacher.perenimi} capitalizeLower={true} />
                            </div>
                       </a>
                        <div>
                            {selectedClass.students.map(e=> <a style={{all:"unset", cursor:"pointer"}} href={'/profile/'+e.id}><Chip key={e.id} label={e.eesnimi} alt={e.perenimi} capitalize={true} /></a> )}
                            {selectedClass.students.length <= 0 && <p style={{color:"var(--grey-color)"}}>Selles klassis ei ole veel kedagi</p> }
                        </div>
                    </div>
                </>}
            </div>
        </div>
    </Layout>;
}