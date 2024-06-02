export default function HorizontalInfoBanner({text, link=null}){
    return <p className="horizontal-info-banner">
            <span className="material-icons" translate="no">info</span>
            {text}
            {link != null && <a alone="" style={{color:"grey", fontWeight:"bold"}} href={link}>Mine&nbsp;<span className="material-icons" translate="no">arrow_forward</span></a>}
           </p>;
}