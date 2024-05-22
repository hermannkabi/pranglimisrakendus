import Navbar from "@/Components/Navbar";
import PasswordInput from "@/Components/PasswordInput";
import SizedBox from "@/Components/SizedBox";
import { Head } from "@inertiajs/react";
import "/public/css/preview.css";
import CheckboxTile from "@/Components/CheckboxTile";


export default function ClassroomEdit({klass, auth, students}){

    function submitData(e){
        e.preventDefault();

        var removeStudents = "";

        $("input[type='checkbox']").each(function (){
            if($(this).is(":checked")){
                removeStudents += $(this).attr("level"); 
                removeStudents += ",";
            }
        });

        removeStudents = removeStudents.substring(0, removeStudents.length - 1);

        $.post("/classroom/"+klass.uuid+"/edit", {
            "_token":window.csrfToken,
            "klass_name": $("#name").val(),
            "klass_password": $("#pass").val(),
            "removed_students": removeStudents,
        }).done(function (data){
            window.location.href = route("dashboard");
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
            <Head title='Muuda klassi' />
            <Navbar user={auth.user} />
            <SizedBox height={36} />

            <h2>Muuda klassi</h2>

            <div className="container">
                <div className="preferences">
                    <section>
                        <form onSubmit={submitData} method="post" autoComplete="off">
                            <input type="hidden" name="_token" value={window.csrfToken}  />
                            
                            <input id="name" required style={{width:"100%", height:"100%", boxSizing:"border-box", marginInline:"-8px"}} type="text" name="klass_name" placeholder="Klassi nimi" defaultValue={klass.klass_name} />
                            <PasswordInput id="pass" name="klass_password" value={klass.klass_password} divstyle={{width:"100%", height:"100%", boxSizing:"border-box", marginInline:"-8px"}} style={{width:"100%", height:"100%", boxSizing:"border-box", marginInline:"-8px"}} />  

                            <SizedBox height="16px" />

                            {students.length > 0 && <a alone="" onClick={()=>{$("#remove-students-arrow").css("transform", "rotate("+($("#remove-students").is(":hidden") ? "-180deg" : "0deg")+")"); $("#remove-students").slideToggle(200); }}>Eemalda õpilasi <i id="remove-students-arrow" className="material-icons no-anim">keyboard_arrow_down</i></a>}

                            <div id="remove-students" hidden>
                                <p style={{color:"grey"}}>Vali need õpilased, keda soovid klassi nimekirjast eemaldada</p>

                                <div>
                                    {students.map((e)=>{
                                       return <CheckboxTile key={e.id} level={"A"} levelChar={e.id} style={{textTransform:"capitalize"}} forcedText={e.eesnimi + " " + e.perenimi} />
                                    })}
                                </div>
                            </div>

                            <SizedBox height="16px" />

                            <button style={{width:"100%", height:"100%", boxSizing:"border-box", marginInline:"-8px"}} type="submit">Muuda</button>
                        </form>
                    </section>
                    <SizedBox height="16px" />
                    <a onClick={deleteClass} red="" alone="">Kustuta klass&nbsp;<span translate="no" className="material-icons no-anim">delete</span> </a>
                </div>
            </div>
            
    </>;
}