import ProfileAction from "./ProfileAction";

export default function RadioChoice({icon, text, selected, onClick, smallLabel=null}){
    
    return <ProfileAction icon={icon} label={text} selected={selected} onClick={onClick} smallLabel={selected ? smallLabel : null} />
    
    return (
        <div translate="no" className="radio-choice" onClick={onClick} style={{border:"2px solid rgb(var(--primary-color))", borderRadius:"8px", display:"inline-flex", marginInline:"4px", outlineOffset:"0px", outline: !selected ? "" : "1px solid rgb(var(--primary-color))"}}>
            <div style={{transition:"background-color 100ms, color 100ms", backgroundColor: selected ? "rgb(var(--primary-color))" : "transparent", display:"flex", alignItems:"center", padding:"8px 16px"}}>
                <span style={{color: selected ? "white" : "rgb(var(--primary-color))", userSelect:"none"}} className="material-icons">{icon}</span>
            </div>
            <p style={{padding:"4px 16px", userSelect:"none", wordWrap:"anywhere"}}>{text}</p>
        </div>
    );
}