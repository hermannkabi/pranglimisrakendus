import { useState } from "react";

export default function NumberChoice({defaultValue, id, maxValue=10}){

    const [value, setValue] = useState(defaultValue);

    function addToValue(){
        var newVal = Math.min(value + 1, maxValue);
        setValue(newVal);
    }

    function subtractFromValue(){
        var newVal = Math.max(0, value - 1);
        setValue(newVal);
    }

    return (
        <>
        <input type="hidden" id={id} value={value} />
        <div style={{border:"2px solid rgb(var(--primary-color))", borderRadius:"8px", display:"flex", marginInline:"4px", justifyContent:"space-between"}}>
            <div translate="no" onClick={subtractFromValue} style={{transition:"background-color 100ms, color 100ms", backgroundColor: "rgb(var(--primary-color))", display:"flex", alignItems:"center", padding:"8px 16px"}}>
                <span style={{color: "white", userSelect:"none"}} className="material-icons">remove</span>
            </div>
            <p translate="no" style={{padding:"4px 16px", userSelect:"none"}}>{value == 0 ? "-" : value ?? "-"} {value == 0 || value == null ? "" : "min"}</p>
            <div translate="no" onClick={addToValue} style={{transition:"background-color 100ms, color 100ms", backgroundColor:"rgb(var(--primary-color))", display:"flex", alignItems:"center", padding:"8px 16px"}}>
                <span style={{color: "white", userSelect:"none"}} className="material-icons">add</span>
            </div>
        </div>
        </>
    );
}