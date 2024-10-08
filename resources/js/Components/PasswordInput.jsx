import { useState } from "react";

export default function PasswordInput(props){

    const [type, setType] = useState("password");
    const [value, setValue] = useState(props.value ?? "");

    function handleChange(event){
        setValue(event.target.value);
    }

    function handleClick(){
        setType(type == "password" ? "text" : "password");
    }

    const visibilityBtn = {
        color: "rgb(var(--primary-color))",
        cursor: "pointer",
        position: "absolute",
        right: "8px",
        top: "50%",
        transform: "translateY(-50%)",
        userSelect: "none",
    };

    return (
        <div style={{position:"relative", display:"inline-block", ...props.divstyle}}>
            <input type={type} {...props} value={value} onChange={handleChange}  />
            <span translate="no" onClick={handleClick} className="material-icons" style={visibilityBtn}>{type=="password" ? "visibility" : "visibility_off"}</span>
        </div>
    );
}