import BigButton from "@/Components/2024SummerRedesign/BigButton";
import Layout from "@/Components/2024SummerRedesign/Layout";
import InfoBanner from "@/Components/InfoBanner";
import { useEffect, useState } from "react";

export default function UIPage({auth}){

    const [color, setColor] = useState(null);

    function hexToRgb(hex) {
        if(hex == null) return null;
        var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result ? {
          r: parseInt(result[1], 16),
          g: parseInt(result[2], 16),
          b: parseInt(result[3], 16)
        } : null;
      }

    useEffect(()=>{
        var r = document.querySelector(':root');

        var result = hexToRgb(color);

        if(result != null){
            r.style.setProperty('--primary-color', result.r + ","+result.g+","+result.b);
        }

    }, [color]);

    return (
        <Layout title="UI" auth={auth}>
            <InfoBanner text="Tere tulemast Reaalerisse! Siin lehel saad testida erineva põhivärvi mõju komponentidele. Kleebi HEX värv allolevasse kasti, et testima hakata!" />
            <div style={{position:'relative', marginBottom:"16px"}}>
                <i translate="no" style={{position:"absolute", top:"50%", transform:"translateY(-50%)", left:"8px", color:"var(--grey-color)", fontSize:"28px"}} className="material-icons">palette</i>
                <input onChange={(e)=>setColor(e.target.value)} autoComplete="off" placeholder="Kleebi värv siia" style={{backgroundColor: "var(--section-color)", borderRadius:"6px", padding:"32px 16px", paddingLeft:"50px", width:"100%", boxSizing:"border-box", margin:"0"}} className="search" type="search" />
            </div>
            <div className="section clickable">
                <h1>Siin on natuke teksti</h1>
                <p>Ja tavaline tekst oleks selline...</p>
                <p>(PS! Seda kasti saab vajutada)</p>
            </div>
            <BigButton title="Nupp" subtitle="Alapealkirjaga" />
        </Layout>
    );
}