export default function NumberButton({content, style, backgroundColor, textColor, lineHeight, fontSize, onClick, keyId = content, icon = false, disabled=false}){

    return (
        <div translate="no" onClick={disabled ? null : onClick} className="number-button section" style={{display:"flex", alignContent:"center", justifyContent:'center', cursor: disabled ? "not-allowed" : "normal", backgroundColor: backgroundColor ?? "var(--section-color)", padding:"16px", margin:"4px", borderRadius:"8px", color:"black", width:"32px", height:"32px", fontFamily:"", opacity:disabled ? "0.2" : "1", ...style}}>
            <span className={icon ? "material-icons" : ""} style={{lineHeight: lineHeight ?? '1', fontSize: fontSize ?? "32px", userSelect:"none", color: textColor ?? "var(--primary-header-color)"}}>{content}</span>
        </div>
    )
}