export default function HorizontalRule({width = "50%", style, id}){
    return (
        <>
            <div id={id} style={{height:"6px", backgroundColor:"rgb(var(--primary-color))", margin:"16px auto", borderRadius:"4px", width:width, ...style}}></div>
        </>
    );
}