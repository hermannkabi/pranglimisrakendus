import SizedBox from "./SizedBox";

export default function InfoBanner({text, type}){
    return (
        <div className="section" style={{padding:"8px 16px", color: type == "error" ? "var(--red-color)" : "inherit"}}>
            <SizedBox height="8px" />
            <i className="material-icons" style={{color:type == "error" ? "var(--red-color)" : "var(--grey-color)"}}>{type == "error" ? "warning" : "info"}</i>
            <p style={{marginTop:"8px"}}>{text}</p>
        </div>
    );
}