import { useState } from 'react';
import '/public/css/number_button.css';

export default function NumberButton({content, style, backgroundColor, textColor, lineHeight, fontSize, onClick, keyId = content}){

    return (
        <div onClick={onClick} className="number-button" style={{display:"flex", alignContent:"center", justifyContent:'center', cursor:"pointer", backgroundColor: backgroundColor ?? "rgb(0,0,0, 0.05)", padding:"16px", margin:"4px", borderRadius:"8px", color:"black", width:"32px", height:"32px", fontFamily:"", ...style}}>
            <span style={{lineHeight: lineHeight ?? '1', fontSize: fontSize ?? "32px", userSelect:"none", color: textColor ?? "inherit"}}>{content}</span>
        </div>
    )
}