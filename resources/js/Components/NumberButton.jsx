import { useState } from 'react';
import '/public/css/number_button.css';

export default function NumberButton({content, style, backgroundColor, textColor, lineHeight, fontSize, onClick, keyId = content, icon = false, disabled=false}){

    return (
        <div translate="no" onClick={disabled ? null : onClick} className="number-button" style={{display:"flex", alignContent:"center", justifyContent:'center', cursor: disabled ? "not-allowed" : "normal", backgroundColor: backgroundColor ?? "rgb(var(--section-color), var(--section-transparency))", padding:"16px", margin:"4px", borderRadius:"8px", color:"black", width:"32px", height:"32px", fontFamily:"", opacity:disabled ? "0.5" : "1", ...style}}>
            <span className={icon ? "material-icons" : ""} style={{lineHeight: lineHeight ?? '1', fontSize: fontSize ?? "32px", userSelect:"none", color: textColor ?? "rgb(var(--text-color))"}}>{content}</span>
        </div>
    )
}