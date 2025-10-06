export default function HorizontalRule({width = "17%", style, id}){
    return (
        <div id={id} style={{display:"flex", flexDirection:"row", gap:"8px", justifyContent:"center", alignItems:"center", margin:"8px"}}>
            <div style={{height:"6px", backgroundColor:"var(--lightgrey-color)", margin:"16px", borderRadius:"4px", width:width, ...style}}></div>
            <span style={{fontWeight:"bold", display:"inline"}}>Minu klass</span>
            <div style={{height:"6px", backgroundColor:"var(--lightgrey-color)", margin:"16px", borderRadius:"4px", width:width, ...style}}></div>
        </div>
    );
}