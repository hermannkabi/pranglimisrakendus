import SizedBox from "@/Components/SizedBox";
import "/public/css/preview.css";
import InfoBanner from "@/Components/InfoBanner";
import Layout from "@/Components/2024SummerRedesign/Layout";
import { useRef, useState } from "react";
import BigButton from "@/Components/2024SummerRedesign/BigButton";
import TwoRowTextButton from "@/Components/2024SummerRedesign/TwoRowTextButton";

export default function JoinClassPage({auth, classData, allClasses}){

    const useFocus = () => {
        const htmlElRef = useRef(null)
        const setFocus = () => {htmlElRef.current &&  htmlElRef.current.focus()}
    
        return [ htmlElRef, setFocus ] 
    }

    const [search, setSearch] = useState(null);
    const [selectedClass, setSelectedClass] = useState(null);
    const [password, setPassword] = useState(null);
    const [error, setError] = useState(null);
    const [passwordRef, setPasswordFocus] = useFocus()

    function onClassRemove(){
        if(confirm("Kas oled kindel, et soovid end klassist eemaldada?")){
            $.post("/classroom/remove/" + auth.user.id , {"_token":window.csrfToken}).done(function (data){
                window.location.href = route("dashboard");
            }).fail(function (data){
                console.log(data);
            });
        }
    }   

    function toggleVisibility(){
        var isVisible = document.getElementById("password").type == "text";
        document.getElementById("toggle-visibility").innerHTML = isVisible ? "visibility" : "visibility_off";
        document.getElementById("password").type = isVisible ? "password" : "text";
    
    }

    function joinClass(){
        if(selectedClass != null && password != null){
            $.post(route("join"), {
                "_token":window.csrfToken,
                "klass_id":selectedClass.klass_id,
                "klass_password":password,
            }).done(function (data){
                if(data == 0){
                    window.location.href = route("dashboard");
                }else{
                    setError(data);
                }
            }).fail(function (data){
                console.log(data);
                setError(data["responseJSON"].message);
            });
        }
    }

    return (
        <Layout title="Liitu klassiga">
            <div className="two-column-layout">

                {/* Class list */}
                <div>
                {classData != null && <div onClick={onClassRemove} style={{marginTop:"0", paddingBlock:"32px", marginBottom:"32px", display:"flex", justifyContent:"space-between", alignItems:"center"}} className="section clickable">
                    <TwoRowTextButton upperText="Lahku klassist" lowerText={classData.klass_name} showArrow={false} />
                    <i style={{fontSize:"50px", marginRight:"16px"}} className="material-icons">logout</i>
                </div>}

                {/* Search input */}
                <div style={{position:'relative', marginBottom:"16px"}}>
                    <i style={{position:"absolute", top:"50%", transform:"translateY(-50%)", left:"8px", color:"var(--grey-color)", fontSize:"28px"}} className="material-icons">search</i>
                    <input onChange={(e)=>setSearch(e.target.value)} autoComplete="off" placeholder="Otsi klassi" style={{backgroundColor: "var(--section-color)", borderRadius:"6px", padding:"32px 16px", paddingLeft:"50px", width:"100%", boxSizing:"border-box", margin:"0"}} className="search" type="search" />
                </div>

                {/* Class list start */}
                <div>
                    {allClasses.filter((e)=> search == null || search.length <= 0 || (e.klass_name+e.teacher_name).toLowerCase().includes(search.toLowerCase())).map(e=> <div onClick={()=>{
                        if(selectedClass != e) setPasswordFocus();
                        return setSelectedClass(selectedClass => selectedClass == e ? null : e);
                    }} class_id={e.klass_id} key={e.klass_id} style={{display:"flex", flexDirection:"row", alignItems:"flex-end", justifyContent:"space-between"}} className={"clickable section class-tile" + (selectedClass == e ? " tile-selected" : "")}>
                    <TwoRowTextButton upperText={e.klass_name} lowerText={e.teacher_name} showArrow={false} />
                    <p style={{textAlign:"right", color:"var(--grey-color)", marginBottom:"0", marginRight:"8px"}}>{e.student_count} õpilast</p>
                    </div> )}
                </div>

                </div>

                {/* Actions (password  & submit) */}
                <div>
                    <div style={{textAlign:"left", position:"relative", backgroundColor:"var(--section-color)", borderRadius:"6px", padding:"16px"}}>
                        <i className="material-icons" style={{color:"var(--lightgrey-color)"}}>lock_outlined</i>

                        <p style={{marginTop:"6px", marginBottom:'24px'}}>Klassi salasõna</p>
                        <input ref={passwordRef} onChange={(e)=>setPassword(e.target.value)} autoComplete="new-password" id="password" style={{all:"unset", fontWeight:"bold", width:"100%"}} placeholder="Sisesta salasõna..." type="password" />
                        <i onClick={toggleVisibility} id="toggle-visibility" className="material-icons" style={{color:"var(--grey-color)", position:"absolute", bottom:"16px", right:"16px", cursor:"pointer"}}>visibility</i>
                    </div>
                    <SizedBox height="8px" />
                    {error && <InfoBanner type="error" text={error} />}
                    <SizedBox height="2px" />

                    <BigButton title="Ühine klassiga" subtitle={selectedClass == null ? "Vali klass" : selectedClass.klass_name} onClick={joinClass} disabled={selectedClass == null || password == null} />
                    {classData != null && <p style={{color:"var(--lightgrey-color)", textAlign:'left', width:"75%"}}>Kui ühined uue klassiga, eemaldatakse sind  praegusest klassist automaatselt.</p> }
                </div>
            </div>

        </Layout>
    );
}