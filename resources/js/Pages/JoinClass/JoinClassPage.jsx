import Navbar from "@/Components/Navbar";
import SizedBox from "@/Components/SizedBox";
import { Head } from "@inertiajs/react";
import "/public/css/preview.css";
import InfoBanner from "@/Components/InfoBanner";


export default function JoinClassPage({auth, classData, allClasses, errors}){

    console.log(errors);

    function onClassRemove(){
        if(confirm("Kas oled kindel, et soovid end klassist eemaldada?")){
            $.post(route("classRemove"), {"_token":window.csrfToken}).done(function (data){
                window.location.href = route("dashboard");
            }).fail(function (data){
                console.log(data);
            });
        }
    }   

    return <>
        <Head title='Ühine klassiga' />
        <Navbar user={auth.user} title="Ühine klassiga" />
        <SizedBox height={36} />

        <h2>{classData == null ? "Ühine klassiga" : "Vaheta klassi"}</h2>
            <div className="container">
                {Object.keys(errors).length > 0 && <InfoBanner text={errors[Object.keys(errors)[0]]} />}
                {classData != null && <section>
                    <InfoBanner transparent={true} text={"Sa juba oled klassis ("+classData.klass_name+"). Vajutades 'Lahku klassist' või lisades end mõnda teise klassi eemaldatakse sind praegusest klassist."} />
                    <button onClick={onClassRemove} style={{marginTop:"0", width:"100%", height:"100%", boxSizing:"border-box", marginInline:"-8px"}} secondary=""> <span className="material-icons" translate="no">close</span> Lahku klassist</button>
                </section>}
                <div className="preferences">
                    <section>
                        <form action={route("join")} method="post" autoComplete="off">
                            <input type="hidden" name="" id="" autoComplete="false" />
                            <input type="hidden" name="_token" value={window.csrfToken}  />
                            <select style={{marginBottom:"8px"}} name="klass_id" required>
                                <option disabled selected value="">Vali klass</option>
                                {allClasses.map((e)=> <option key={e.klass_id} value={e.klass_id}>{e.klass_name}</option> )}
                            </select>
                            <input autoComplete="new-password" required style={{width:"100%", height:"100%", boxSizing:"border-box", marginInline:"-8px"}} type="password" name="klass_password" placeholder="Parool klassiga ühinemiseks" />
                            <button style={{width:"100%", height:"100%", boxSizing:"border-box", marginInline:"-8px"}} type="submit">Ühine</button>
                        </form>
                    </section>
                </div>
            </div>
    </>;
}