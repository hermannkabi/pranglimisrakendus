// Käitub väiksemates laiustes pisut veidralt (overflow sectionist). Vaata üle!

export default function OperationWidget({op}){

    const size = "40px";
    const correctColor = "green";
    const incorrectColor = "red";

    const color = op.isCorrect ? correctColor : incorrectColor;

    const char = op.isCorrect ? "done" : "close";

    const resultCircle = {
        backgroundColor: color,
        borderRadius:size,
        height: size,
        width: size,
        display: "flex",
        alignItems: "center",
        justifyContent: "center",
        margin:"4px 8px",
        minWidth:"40px",
    };

    // Returns the correct HTML string for the gap type
    function gapHtml(op){

        if(!op.isCorrect){
            return op.operation.replace("Lünk", ` <span class='correct'>`+op.correct.toString().replaceAll(".", ",")+`</span> <span class='underline incorrect'>`+op.answer.toString().replaceAll(".", ",")+`</span> `)
        }

        return op.operation.replace("Lünk", `<span class='underline correct'>`+op.answer.toString().replaceAll(".", ",")+`</span>`)
    }

    
    // Returns the correct HTML for compare type
    function compareHtml(op){
        if(!op.isCorrect){
            return op.operation.replaceAll(".", ",").replace("%SYMB%", ` <span class='incorrect nounderline'>`+op.answer.toString().replaceAll(".", ",")+`</span> <span class='correct'>(`+op.correct.toString().replaceAll(".", ",")+`)</span>`)
        }

        return op.operation.replace("%SYMB%", `<span class='correct'>`+op.answer.toString().replaceAll(".", ",")+`</span>`)
    }

    return (
    <>
        <div style={{backgroundColor:"rgb(var(--section-color),  var(--section-transparency))", display:"inline-flex", borderRadius:"8px", alignItems:"center", margin:"8px", padding:"8px", paddingRight:"16px"}}>
            <div style={resultCircle}><span style={{color:"white", userSelect:"none"}} className="material-icons">{char}</span></div>
            <div style={{textAlign: "left", marginInline:"8px"}}>
                <p style={{fontWeight:"bold", marginBlock:"0", fontSize:"24px"}} dangerouslySetInnerHTML={{"__html":op.operation.includes("Lünk") ? gapHtml(op) : op.operation.includes("%SYMB%") ? compareHtml(op) : op.operation.toString().replaceAll(".", ",")}}></p>
                {!op.operation.includes("Lünk") && !op.operation.includes("%SYMB%") && <p style={{marginBlock:"4px", color:color, fontSize:"20px"}}>{op.answer.toString().replaceAll(".", ",")} <span style={{color:correctColor}}>{op.isCorrect ? "" : "("+op.correct.toString().replaceAll(".", ",")+")"}</span></p>}
            </div>
        </div>
    </>);
}