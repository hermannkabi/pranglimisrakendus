import { forwardRef, useState } from "react";

const PasswordWidget = forwardRef((props, ref)=>{

    const {onChange, defaultValue=null, text="Sisesta parool", icon="lock", hintText=(text+"..."), isPassword=true, style, inputName, required, isTextArea=false} = props;

    const [type, setType] = useState(isPassword ? "password" : "text");
        
    return <div style={{textAlign:"left", position:"relative", backgroundColor:"var(--section-color)", borderRadius:"6px", padding:"16px", ...style}}>
        <i translate="no" className="material-icons-outlined" style={{color:"var(--lightgrey-color)"}}>{icon}</i>

        <p style={{marginTop:"6px", marginBottom:'24px'}}>{text} {required && <span style={{color:"#c42121"}}>*</span>}</p>
        {!isTextArea && <input defaultValue={defaultValue} required={required} name={inputName} ref={ref} onChange={onChange} autoComplete="new-password" style={{all:"unset", fontWeight:"bold", width:"100%"}} placeholder={hintText} type={props.type ?? type} />}
        {isPassword && <i translate="no" onClick={()=>setType(t=>t=="password" ? "text" : "password")} className="material-icons" style={{color:"var(--grey-color)", position:"absolute", bottom:"16px", right:"16px", cursor:"pointer"}}>{type == "password" ? "visibility" : "visibility_off"}</i>}

        {isTextArea && <textarea defaultValue={defaultValue} required={required} name={inputName} ref={ref} onChange={onChange} style={{all:"unset", fontWeight:"bold", width:"100%", height:"100px", resize:"vertical"}} placeholder={hintText}></textarea>}
    </div>; 
});


export default PasswordWidget;