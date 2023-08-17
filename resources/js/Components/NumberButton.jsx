import '/public/css/number_button.css';

export default function NumberButton({content, style, backgroundColor, textColor, lineHeight, fontSize, onClick}){

    return (
        <div onClick={onClick} className="number-button" style={{display:"flex", alignContent:"center", justifyContent:'center', cursor:"pointer", backgroundColor: backgroundColor ?? "rgb(var(--primary-color), 0.15)", padding:"16px", margin:"4px", borderRadius:"8px", color:"rgb(var(--primary-color))", width:"32px", height:"32px", fontFamily:"", ...style}}>
            <span style={{lineHeight: lineHeight ?? '1', fontSize: fontSize ?? "32px", userSelect:"none", color: textColor ?? "inherit"}}>{content}</span>
        </div>
    )
}