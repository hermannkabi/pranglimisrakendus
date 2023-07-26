export default function HorizontalRule({width = "50%"}){
    return (
        <>
            <div style={{height:"6px", backgroundColor:"lightgrey", margin:"32px auto", borderRadius:"4px", width:width}}></div>
        </>
    );
}