import SizedBox from "@/Components/SizedBox";
import Layout from "@/Components/2024SummerRedesign/Layout";
import PasswordWidget from "@/Components/2024SummerRedesign/PasswordWidget";
import { useState } from "react";
import Chip from "@/Components/2024SummerRedesign/Chip";
import BigButton from "@/Components/2024SummerRedesign/BigButton";
import InfoBanner from "@/Components/InfoBanner";

export default function ClassroomEdit({klass, auth, students}){

    const [klassName, setKlassName] = useState(null);
    const [newPassword, setNewPassword] = useState(null);
    const [studentsToRemove, setStudentsToRemove] = useState([]);

    const isDisabled = (klassName == null || klassName.length <= 0) && (newPassword == null || newPassword.length <= 0) && studentsToRemove.length <= 0;


    function submitData(){
        $.post("/classroom/"+klass.uuid+"/edit", {
            "_token":window.csrfToken,
            "klass_name": klassName == null || klassName.length <= 0 ? klass.klass_name : klassName,
            "klass_password": newPassword == null || newPassword.length <= 0 ? klass.klass_password : newPassword,
            "removed_students": studentsToRemove.join(","),
        }).done(function (data){
            window.location.reload();
        }).fail(function (data){
            console.log(data);
        });
    }

    function deleteClass(){
        if(confirm("Kas oled kindel, et soovid selle klassi kustutada?")){
            $.post("/classroom/"+klass.uuid+"/delete", {
                "_token":window.csrfToken,
            }).done(function (data){
                window.location.href = route("dashboard");
            }).fail(function (data){
                console.log(data);
            });
        }
    }


    return <>
        <Layout title="Muuda klassi" auth={auth}>
            <div className="two-column-layout">
                <div>
                    <PasswordWidget onChange={(e)=>setKlassName(e.target.value)} inputName="klass_name" style={{marginBlock: "8px"}} isPassword={false} icon="edit" text="Klassi nimi" hintText={klass.klass_name} />
                    <PasswordWidget onChange={(e)=>setNewPassword(e.target.value)} inputName="klass_password" style={{marginBlock: "8px"}} text="Parool klassiga ühinemiseks" hintText={klass.klass_password} />

                    {students.length > 0 && <>
                        <SizedBox height="16px" />
                        <p style={{color:"var(--lightgrey-color)", textAlign:'left', width:"75%"}}>Vali need õpilased, kelle soovid klassi nimekirjast eemaldada</p>
                        <div>
                            {students.map((e)=>{
                                return <Chip key={e.id} onClick={()=>{
                                    var newList = [...studentsToRemove];

                                    if(newList.includes(e.id)){
                                        newList = newList.filter(i=>i != e.id);
                                    }else{
                                        newList.push(e.id);
                                    }

                                    setStudentsToRemove(newList);
                                }} active={studentsToRemove.includes(e.id)} capitalize={true} label={e.eesnimi} alt={e.perenimi} />
                            })}

                            {students.length <= 0 && <InfoBanner text="Selles klassis ei ole veel ühtegi õpilast" />}
                        </div>
                    </>}
                </div>


                <div>
                    <div onClick={deleteClass} className="section clickable red" style={{padding:"16px", display:"flex", justifyContent:"start", alignItems:"center", marginBlock:"0"}}>
                        <div style={{color:"var(--red-color)",}}>
                            <i translate="no" style={{fontSize:"32px"}} className="material-icons-outlined">delete</i>
                            <p style={{marginTop:"8px", marginBottom:"0"}}>Kustuta klass</p>
                        </div>
                    </div>

                    <BigButton onClick={submitData} disabled={isDisabled} title="Muuda klassi" subtitle={klassName == null || klassName.length <= 0 ? klass.klass_name : klassName} />
                </div>
            </div>
        </Layout>
    </>;
}