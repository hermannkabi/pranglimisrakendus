import BigButton from "@/Components/2024SummerRedesign/BigButton";
import Layout from "@/Components/2024SummerRedesign/Layout";
import TwoRowTextButton from "@/Components/2024SummerRedesign/TwoRowTextButton";
import InfoBanner from "@/Components/InfoBanner";

export default function AllClassesPage({auth, classes}){

    return <>
        <Layout title="Minu klassid" auth={auth}>
            <div className="two-column-layout">
                <div>
                    {classes.map(e=> <div className="section clickable" style={{position:"relative"}}>
                        <TwoRowTextButton upperText={e.klass_name} lowerText={e.studentsCount + " õpilast"} />

                        <a href={"/classroom/"+e.uuid+"/view"} style={{all:"unset", position:"absolute", top:"0", left:"0", height:"100%", width:"100%"}}></a>
                    </div> )}

                    {classes.length <= 0 && <div className="section">
                        <InfoBanner text="Sul ei ole veel klasse" />
                    </div> }
                </div>

                {/* Teine tulp */}
                <div>
                    <div style={{position:"relative", cursor:"pointer"}}>
                        <BigButton title="Midagi puudu?" subtitle="Loo uus klass" />
                        <a href={route("newClass")} style={{all:"unset", position:"absolute", top:"0", left:"0", height:"100%", width:"100%"}}></a>
                    </div>

                </div>
            </div>
        </Layout>
    </>;
}