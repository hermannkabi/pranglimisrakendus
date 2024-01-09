import { useState } from "react";

export default function CheckboxTile({level, levelChar=level}){


    const [checked, setChecked] = useState(levelChar == level);

    function changeChecked(){
        setChecked(check=>!check);
    }


    return (
        <>
            <div onClick={changeChecked} style={{display:"inline-block", cursor:"pointer"}}>
                <input level={levelChar} type="checkbox" checked={checked} onClick={changeChecked} onChange={changeChecked} style={{cursor:"pointer"}} />
                <label style={{userSelect:"none", cursor:"pointer"}}>{level}. tase</label>
            </div>
        </>
    );
}