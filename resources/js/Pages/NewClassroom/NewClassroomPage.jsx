import "/public/css/preview.css";
import InfoBanner from "@/Components/InfoBanner";
import Layout from "@/Components/2024SummerRedesign/Layout";
import PasswordWidget from "@/Components/2024SummerRedesign/PasswordWidget";
import BigButton from "@/Components/2024SummerRedesign/BigButton";
import { useState } from "react";

export default function NewClassroomPage({auth, errors}){

    const [klassName, setKlassName] = useState(null);

    return <>
        <Layout title="Uus klass">
            <form id="new-class-form" method="post" action={route("classStore")} autoComplete="off">
                <input type="hidden" name="_token" value={window.csrfToken}  />
                <div className="two-column-layout">
                    <div>
                        <PasswordWidget required={true} onChange={(e)=>setKlassName(e.target.value)} inputName="klass_name" style={{marginBlock: "8px"}} isPassword={false} icon="edit" text="Klassi nimi" />
                        <PasswordWidget required={true} inputName="klass_password" style={{marginBlock: "8px"}} text="Parool klassiga ühinemiseks" hintText="Loo parool..." />
                        <p style={{color:"var(--lightgrey-color)", textAlign:'left', width:"75%"}}>Õpilased, kes tahavad sinu klassiga ühineda, peavad esmalt sisestama selle parooli</p>
                        <PasswordWidget required={true} inputName="klass_password_confirmation" style={{marginBlock: "8px"}} text="Kinnita parool" />
                    </div>

                    {/* Teine tulp */}
                    <div>
                        {Object.keys(errors).length > 0 && <div className="section">
                            <InfoBanner text={errors[Object.keys(errors)[0]]} />
                        </div>}
                        <BigButton onClick={()=>{$("#new-class-form").submit()}} title="Loo klass" subtitle={klassName} />
                    </div>
                </div>
            </form>
        </Layout>
    </>;
}