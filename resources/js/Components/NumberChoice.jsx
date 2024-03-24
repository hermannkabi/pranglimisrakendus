import { useEffect, useState } from "react";

export default function NumberChoice({defaultValue, id, maxValue=10, onChange=null}){

    const [value, setValue] = useState(defaultValue ?? "");

    const delta = 0.5;
    const color = "var(--primary-color)";


    useEffect(()=>{
        if(onChange != null) onChange();
    }, [value]);

    function addToValue(){
        var newVal = Math.min(value + delta, maxValue);
        setValue(newVal);
    }

    function subtractFromValue(){
        var newVal = Math.max(0, value - delta);
        setValue(newVal);
    }

    return <>
        <input type="hidden" id={id} value={value} />
    
        <div style={{display:"flex", flexGrow:"1", flexDirection:"row", boxSizing:"border-box" ,overflow:"hidden", userSelect:"none", backgroundColor:"rgb("+color+", var(--section-transparency))", borderRadius:"8px", alignItems:"center", gap:"8px", margin:"8px", padding:"4px 8px", justifyContent:"space-between", position:"relative"}}>                
            <div onClick={subtractFromValue} style={{cursor:"pointer", transition:"all 200ms", display:"flex", alignItems:"center", backgroundColor:"rgb("+color+", 1", padding:"8px", borderRadius:"50%"}}>
                <span className="material-icons" translate="no" style={{color: "white"}}>remove</span>
            </div>

            <div style={{textAlign:"center"}}>
                <p style={{wordWrap:"anywhere", lineHeight:"1", color:"rgb("+color+")", fontWeight: "normal", marginBlock: "16px"}}>{value == 0 ? "-" : value.toString().replace(".", ",") ?? "-"} {value == 0 || value == null ? "" : "min"}</p>
            </div>

            <div onClick={addToValue} style={{cursor:"pointer", transition:"all 200ms", display:"flex", alignItems:"center", backgroundColor:"rgb("+color+", 1", padding:"8px", borderRadius:"50%"}}>
                <span className="material-icons" translate="no" style={{color: "white"}}>add</span>
            </div>
        </div>
    </> ;

    return (
        <>
        <input type="hidden" id={id} value={value} />
        <div style={{border:"2px solid rgb(var(--primary-color))", borderRadius:"8px", display:"flex", marginInline:"4px", justifyContent:"space-between"}}>
            <div translate="no" onClick={subtractFromValue} style={{transition:"background-color 100ms, color 100ms", backgroundColor: "rgb(var(--primary-color))", display:"flex", alignItems:"center", padding:"8px 16px"}}>
                <span style={{color: "white", userSelect:"none"}} className="material-icons">remove</span>
            </div>
            <p translate="no" style={{padding:"4px 16px", userSelect:"none"}}>{value == 0 ? "-" : value.toString().replace(".", ",") ?? "-"} {value == 0 || value == null ? "" : "min"}</p>
            <div translate="no" onClick={addToValue} style={{transition:"background-color 100ms, color 100ms", backgroundColor:"rgb(var(--primary-color))", display:"flex", alignItems:"center", padding:"8px 16px"}}>
                <span style={{color: "white", userSelect:"none"}} className="material-icons">add</span>
            </div>
        </div>
        </>
    );
}