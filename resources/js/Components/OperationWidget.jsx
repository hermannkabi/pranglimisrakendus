// Käitub väiksemates laiustes pisut veidralt (overflow sectionist). Vaata üle!

export default function OperationWidget({op}){

    const size = "40px";
    const correctColor = "#445F5E";
    const incorrectColor = "var(--red-color)";

    const color = op.isCorrect ? correctColor : incorrectColor;

    const char = op.isCorrect ? "done" : "close";

    const regex = /\((-?\d+)\/(-?\d+)\)/;


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
            return op.operation.replace("Lünk", ` <span class='correct'>`+(typeof op.correct == "string" ? op.correct : op.correct.toFixed(2)).replaceAll(".", ",")+`</span> <span class='underline incorrect'>`+op.answer.toString().replaceAll(".", ",")+`</span> `)
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

    function expHtml(op){
        const parts = op.operation.split("EXP");
        var base = parts[0];
        var exponent = parts[1];

        var isFracInside = op.operation.match(regex);

        return "<math><msup class='math-style'>"+(isFracInside ? "<mrow><mo>(</mo>" : "")+"<mn>"+(isFracInside ? fracRender(base) : base)+"</mn>"+(isFracInside ? "<mo>)</mo></mrow>" : "")+"<mi>"+exponent+"</mi></msup></math>";
    }

    function radHtml(op){
        const parts = op.operation.split("RAD");
        var radicand = parts[0];
        var radIndex = parts[1];

        return `<math><mroot><mi class='math-style'>`+(radicand.match(regex) != null ? fracRender(radicand) : radicand)+`</mi><mn>`+(radIndex == "2" ? "" : radIndex)+`</mn></mroot></math>`;
    }

    // Returns an HTML code of the fraction
    function fracRender(operationString){
        var result = "";
        var matches = operationString.match(regex);
        if(matches != null){
            matches.forEach(function (match){
                var matchList = regex.exec(match);
                if(matchList == null) return;
                var numerator = parseInt(matchList[1]);
                var denominator = parseInt(matchList[2]);
                result = operationString.replace("LIHT", "").replace(match, '<math>'+(operationString.includes("msup") ? '<mrow><mo>(</mo>' : '')+'<mfrac class="math-style"><mn>'+numerator+'</mn><mi>'+denominator+'</mi></mfrac>'+(operationString.includes("msup") ? '<mo>)</mo></mrow>' : '')+'</math>');
            });

            return result;
        }
    }

    function fracHtml(op){
        return fracRender(op.operation) + "<span> = </span>" + "<div class='"+(op.isCorrect ? "correct" : "incorrect")+"' style='display:inline; font-size:1em;' />" + (op.answer == null ? "-" : fracRender(op.answer)) + "</div>" + (op.isCorrect ? "" : " <div class='correct' style='display:inline; font-size:1em;'>"+fracRender(op.correct)+"</div>");
    }

    return (
    <>
        <div className="section" translate="no" style={{position:"relative", display:"inline-flex", alignItems:"center", padding:"16px 8px"}}>
            <div style={resultCircle}><span style={{color:"white", userSelect:"none"}} className="material-icons">{char}</span></div>
            <div style={{textAlign: "left", marginInline:"8px"}}>
                <p style={{fontWeight:"bold", marginBlock:"0", fontSize:"24px", wordWrap:"anywhere"}} dangerouslySetInnerHTML={{"__html":op.operation.includes("Lünk") ? gapHtml(op) : op.operation.includes("%SYMB%") ? compareHtml(op) : op.operation.includes("EXP") ? expHtml(op) : op.operation.includes("RAD") ? radHtml(op) : op.operation.includes("LIHT") ? fracHtml(op) : op.operation.match(regex) != null ? fracRender(op.operation) : op.operation.toString().replaceAll(".", ",")}}></p>
                {!op.operation.includes("Lünk") && !op.operation.includes("%SYMB%") && !op.operation.includes("LIHT") && <p style={{marginBlock:"2px", color:color, fontSize:"24px", fontWeight:"bold"}}>{op.answer != null && op.answer.toString().replaceAll(".", ",")} <span style={{color:correctColor}}>{op.isCorrect ? "" : "("+op.correct.replaceAll(".", ",")+")"}</span></p>}            
            </div>
            {op.level && <p title="Tehte tase" style={{color:"var(--grey-color)", marginBottom:"0", display:"flex", alignItems:"center", gap:"6px", position:"absolute", right:"8px", bottom:"8px"}}> <i translate="no" style={{fontSize:"20px"}} className="material-icons-outlined">exercise</i> {op.level.toString().replaceAll("A", "★ 1").replaceAll("B", "★ 2").replaceAll("C", "★ 3")}</p>}
        </div>
    </>);
}