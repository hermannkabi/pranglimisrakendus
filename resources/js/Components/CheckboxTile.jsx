import { useState } from "react";

export default function CheckboxTile({level}){


    const [checked, setChecked] = useState(true);

    function changeChecked(){
        setChecked(check=>!check);
    }


    return (
        <>
            <div onClick={changeChecked} style={{display:"inline-block", cursor:"pointer"}}>
                <input level={level} type="checkbox" checked={checked} onClick={changeChecked} onChange={changeChecked} style={{cursor:"pointer"}} />
                <label style={{userSelect:"none", cursor:"pointer"}}>{level}. tase</label>
            </div>
        </>
    );
}