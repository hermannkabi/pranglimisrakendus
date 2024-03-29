import { useState } from "react";

export default function CheckboxTile({level, levelChar=level, forcedText=null, onChange=null, inputClass=null, style=null}){


    const [checked, setChecked] = useState(level == levelChar);

    function changeChecked(){
        setChecked(check=>!check);

        if(onChange != null){
            onChange();
        }
    }


    return (
        <>
            <div className="checkbox-group" onClick={changeChecked} style={{...{display:"inline-block"}, ...style}}>
                <input name="checkbox" className={inputClass ?? ""} char={levelChar} level={levelChar} type="checkbox" checked={checked} onClick={changeChecked} onChange={changeChecked} />
                <label style={{userSelect:"none"}}>{forcedText ?? (level + ". tase")}</label>
            </div>
        </>
    );
}