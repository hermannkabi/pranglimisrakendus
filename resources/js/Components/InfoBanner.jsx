export default function InfoBanner({text, transparent}){
    return <div style={{backgroundColor:"rgb(var(--section-color), "+(transparent ? 0 : "var(--section-transparency))"), borderRadius:"var(--primary-btn-border-radius)", padding:"8px", marginBlock:"8px"}}>
            <span className="material-icons" style={{color:"rgb(var(--primary-color))", display:"inline", marginBottom:"0"}}>info_outline</span> <p style={{color:"rgb(var(--primary-color))", marginTop:"0"}}>{typeof text == "string" ? text : text.map((i)=>(i.map((e)=><span style={{display:"block", marginBlock:"12px"}} key={e}>{e}</span>)))}</p>
        </div>;
}