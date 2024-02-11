import { useState } from "react";

export default function CheckboxTile({level, levelChar=level}){


    const [checked, setChecked] = useState(level == levelChar);

    function changeChecked(){
        setChecked(check=>!check);
    }


    return (
        <>
            <div className="checkbox-group" onClick={changeChecked} style={{display:"inline-block"}}>
                <input char={levelChar} level={levelChar} type="checkbox" checked={checked} onClick={changeChecked} onChange={changeChecked} />
                <label style={{userSelect:"none"}}>{level}. tase</label>
            </div>
        </>
    );
}