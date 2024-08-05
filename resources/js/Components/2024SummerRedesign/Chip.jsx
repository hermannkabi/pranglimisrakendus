export default function Chip({label, alt=null, active, onClick}){
    return <div onClick={onClick} style={{margin:"4px", display:"inline-block", verticalAlign:"middle", backgroundColor: active ? "rgb(var(--primary-color))" : "var(--chip-color)", padding:"12px 18px", borderRadius:"6px", color:active ? "white" : "inherit"}}>
        <span style={{fontSize:"20px", userSelect:"none"}}>{label} {alt != null && <span style={{color:"var(--grey-color)", marginLeft:"4px"}}>{alt}</span>}</span>
    </div>;
}