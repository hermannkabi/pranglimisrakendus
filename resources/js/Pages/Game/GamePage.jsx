import Navbar from "@/Components/Navbar";
import NumberButton from "@/Components/NumberButton";
import SizedBox from "@/Components/SizedBox";
import Timer from "@/Components/Timer";
import { Head } from "@inertiajs/react";
import { useEffect, useState } from "react";

export default function GamePage({data}){

    const [answer, setAnswer] = useState("");
    const [answerAsHtml, setAnswerAsHtml] = useState("");
    const [timeOver, setTimeOver] = useState(false);
    // How many operations have been correctly answered
    const [operationCount, setOperationCount] = useState(0);
    const [message, setMessage] = useState("");
    const [fractionState, setFractionState] = useState("off");

    let onBeforeUnloadListener;



    // Mousetrap key bindings
    Mousetrap.bind("enter", ()=>checkAnswer());
    Mousetrap.bind("backspace", ()=>handleRemoveClick());
    Mousetrap.bind("-", ()=>handleMinusClick());
    Mousetrap.bind(",", ()=>handleNumberClick(","));
    Mousetrap.bind(".", ()=>handleNumberClick(","));
    Mousetrap.bind("up", ()=>handleArrow());
    Mousetrap.bind("down", ()=>handleArrow());



    // Keys that behave as numbers for the handleNumberClick method
    const numberKeys = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "0"];

    for(const char in numberKeys){
        Mousetrap.bind(char, ()=>handleNumberClick(char));
    }


    // Generate random operation
    const [index, setIndex] = useState(0);
    const [operation, setOperation] = useState("");


    function getNewOperation(forcedIndex){
        if((index + 1) < operations.data.length){
            setIndex(forcedIndex ?? index + 1);
            var regex = /\((\d+)\/(\d+)\)$/;
            var operationString = operations.data[forcedIndex ?? index + 1].operation.toString();
            console.log(operationString);
            var matches = operationString.match(regex);
            if(matches!= null){
                matches.forEach(function (match){
                    var matchList = regex.exec(match);
                    if(matchList == null) return;
                    var numerator = parseInt(matchList[1]);
                    var denominator = parseInt(matchList[2]);
                    var fullPart = null;
    
                    if(numerator > denominator){
                        fullPart = Math.floor(numerator / denominator);
                        numerator -= fullPart * denominator;
                    }
                    operationString = operationString.replace(match, (fullPart == null ? '' : fullPart.toString()) + ' <div class="frac"><span>'+numerator+'</span><span class="symbol">/</span><span class="bottom">'+denominator+'</span></div>')
                });
            }
            

            setOperation(operationString);
        }else{
            alert("Should be getting new operations");
        }
    }

    // Archaic structure, new one with Objects
    // var operations = [
    //     {
    //         "operation":"2 + (7/2)",
    //         "answer":"5.5",
    //     },
    //     {
    //         "operation":"3+5",
    //         "answer":"8",
    //     },
    //     {
    //         "operation":"4+8",
    //         "answer":"12",
    //     },
    //     {
    //         "operation":"6+6",
    //         "answer":"12",
    //     },
    //     {
    //         "operation":"9+6",
    //         "answer":"15",
    //     },
    // ];
    var operations = {data};

    useEffect(()=>{
        getNewOperation(0);
        window.addEventListener('beforeunload', onBeforeUnloadListener = (event) => {
            event.preventDefault();
            event.returnValue = "Kui sulged selle vahelehe, kaotad sellega käimasoleva mängu! Kas tahad sulgeda?";
        });
    }, []);

    // Handles numbers, minus and comma
    function handleNumberClick(number){   
        if(timeOver){return;}               
        // Handle click so that there will be no leading zero
        if((answer == "0" || answer == "-0") && number != ","){
            setAnswer(answer.replace("0", number.toString()));
            return;
        }

        // Check if there is already a comma in the answer, if there is, don't do anything
        if(number == "," && answer.includes(",")){
            return;
        }

        // Add leading zero to comma if necessary
        if((answer == "" || answer == "-") && number == ","){
            setAnswer(answer + "0,");
            return;
        }

        // If there is a fraction, ignore commas
        if(answer.includes("(") && number == ","){
            return;
        }

        // If there is a fraction, change it
        if(answer.endsWith(")")){
            var regex = /\((\d+)\/(\d+)\)/;
            var ans = answer;

            var matches = ans.match(regex);
            if(matches != null){
                matches.forEach(function (match){
                    var matchList = regex.exec(match);
                    if(matchList == null) return ans;
                    var numerator = matchList[1].toString();
                    var denominator = matchList[2].toString();

                    if(fractionState == "up"){
                        numerator += number.toString();
                        
                        if(numerator.startsWith("0") && numerator.length > 0){
                            numerator = numerator.slice(1);
                        }
                    }

                    if(fractionState == "down"){
                        denominator += number.toString();

                        if(denominator.startsWith("0") && denominator.length > 0){
                            denominator = denominator.slice(1);
                        }
                    }
                    
                    ans = ans.replace(match, '('+numerator.toString()+'/'+denominator.toString()+')')
                });

                setAnswer(ans);
            }

            return;
        }

        setAnswer(answer + number.toString());
        
    }

    function handleRemoveClick(){
        if(timeOver){return;}               
        if(answer.endsWith(")")){
            var regex = /\((\d+)\/(\d+)\)/;
            var ans = answer;

            if(answer.includes(")")){
                var matches = ans.match(regex);
                if(matches != null){
                    matches.forEach(function (match){
                        var matchList = regex.exec(match);
                        if(matchList == null) return;
                        var numerator = matchList[1].toString();
                        var denominator = matchList[2].toString();
                    
                        if(fractionState == "up"){
                            numerator = numerator.slice(0, -1);
                            if(numerator.toString().length <= 0){
                                numerator = "0";
                            }
                        }else{
                            denominator = denominator.slice(0, -1);
                            if(denominator.toString().length <= 0){
                                denominator = "0";
                            }
                        }

                        if(numerator.startsWith("0") && numerator.length > 1){
                            numerator = numerator.slice(1);
                        }

                        if(denominator.startsWith("0") && denominator.length > 1){
                            denominator = denominator.slice(1);
                        }

                        ans = ans.replace(match, '('+numerator.toString()+'/'+denominator.toString()+')');
                    });
                } 
                setAnswer(ans);
                return;
            }

            setAnswer(answer.replace(regex, ""));
            return;
        }
        setAnswer(answer.slice(0, -1));
    }

    function handleMinusClick(){
        if(timeOver){return;}               

        var newAnswer = answer;
        if(answer.startsWith("-")){
            newAnswer = answer.slice(1);
        }else{
            newAnswer = "-" + answer;
        }
        setAnswer(newAnswer);
    }


    function checkAnswer(){
        if(!timeOver){

            setFractionState("off");

            const correct = operations.data[index].answer.toString();

            var formattedAnswer = answer.replace(",", ".");

            if(formattedAnswer.length <= 0){
                return;
            }

            var regex = /\((\d+)\/(\d+)\)/;
            var matches = formattedAnswer.match(regex);

            if(matches != null){
                var numerator = matches[1].toString();
                var denominator = matches[2].toString();
                formattedAnswer = formattedAnswer.replace(matches[0], (formattedAnswer.startsWith("-") ? "-" : "+")+((numerator/denominator)));
            }

            formattedAnswer = formattedAnswer.replace(",", ".");

            formattedAnswer = formattedAnswer.replace(" ", "");

            formattedAnswer = eval(formattedAnswer);

            console.log(formattedAnswer);

            if(parseFloat(formattedAnswer) == parseFloat(correct)){
                getNewOperation();
                setAnswer("");
                setOperationCount(operationCount + 1);
            }else{
                alert("Õige vastus oli "+correct);
            }
        }
    }

    function onTimerFinished(){
        setTimeOver(true);
        setMessage("Aeg sai otsa!");
        setTimeout(() => {
            window.removeEventListener('beforeunload', onBeforeUnloadListener);
            window.location.href = route("gameEnd");
        }, 750);
    }

    function handleFraction(){
        if(answer.includes(",")){
            return;
        }
        if(answer.includes("(") && answer.includes(")")){
            setFractionState(fractionState == "down" ? "up" : "down");
        }else{
            setAnswer(answer + " (0/0)");
            setFractionState("up");
        }
    }


    function renderAnswer(text){
        var regex = /\((\d+)\/(\d+)\)$/;
        var ans = text;

        var matches = ans.match(regex);
        if(matches != null){
            matches.forEach(function (match){
                var matchList = regex.exec(match);
                if(matchList == null) return ans;
                var numerator = parseInt(matchList[1]);
                var denominator = parseInt(matchList[2]);
            
                ans = ans.replace(match, ' <div class="frac"><span class="'+(fractionState == "up" ? 'bordered' : '')+'">'+numerator+'</span><span class="symbol">/</span><span class="bottom '+(fractionState == "down" ? 'bordered' : '')+'">'+denominator+'</span></div>')
            });
        }
        
        return ans;
    }
    
    function handleArrow(){
        if(answer.includes(")")){
            setFractionState(fractionState == "up" ? "down" : "up");
        }
    }
    
    return (
        <div>

            <Head title="Mäng" />
            <Navbar title="Mäng" />

            <SizedBox height="36px" />
            <div style={{display:"flex", flexDirection: "column", width:"max-content", maxWidth:"100%", margin:"auto"}}>
                
                {message && <div style={{backgroundColor:"rgb(var(--primary-color), 0.05)", borderRadius:"16px", padding:"8px", marginBlock:"8px"}}>
                    <p style={{color:"rgb(var(--primary-color))"}}>ⓘ {message}</p>
                </div>}
                <div style={{flex:'1', width:"auto", backgroundColor:"rgb(var(--primary-color), 0.05)", borderRadius:"16px", padding:"8px"}}>
                    <div style={{display:"grid", gridTemplateColumns:"repeat(2, 1fr)"}}>
                        <div style={{textAlign:"start"}}>
                            <h2 style={{marginBlock:"0"}}>{operationCount + 1} <span style={{fontFamily:"Kanit", fontSize:"24px", color:"grey"}}>1</span></h2>
                            <p style={{marginBlock:"0", color:"rgb(var(--primary-color))", fontWeight:'bold'}}>{operationCount * 100} punkti</p>
                        </div>
                        <div style={{textAlign:'end'}}>
                            {/* <Timer onTimerFinished={onTimerFinished} /> */}
                        </div>
                    </div>
                    <h2 style={{overflowWrap:'anywhere'}}> <span id="operation" dangerouslySetInnerHTML={{__html: operation}}></span> = <span id="answer" dangerouslySetInnerHTML={{__html: renderAnswer(answer)}}></span></h2>
                </div>
                <a style={{color:"grey", marginLeft:"auto"}} alone="" href="">Jäta vahele (3) {"\u00A0"} <span className="material-icons">fast_forward</span></a>
                <SizedBox height="24px" />
                <div style={{display:'grid', gridTemplateColumns:'repeat(4, 1fr)', width:'fit-content', margin:"auto"}}>
                    <NumberButton content="1" onClick={()=>handleNumberClick(1)} />
                    <NumberButton content="2" onClick={()=>handleNumberClick(2)} />
                    <NumberButton content="3" onClick={()=>handleNumberClick(3)} />
                    <NumberButton content={fractionState == "up" ? "⬇" : fractionState == "down" ? "⬆" : "½"} onClick={()=>handleFraction()}/>

                    <NumberButton content="4" onClick={()=>handleNumberClick(4)} />
                    <NumberButton content="5" onClick={()=>handleNumberClick(5)} />
                    <NumberButton content="6" onClick={()=>handleNumberClick(6)} />
                    <NumberButton content="-" onClick={handleMinusClick} />

                    <NumberButton content="7" onClick={()=>handleNumberClick(7)} />
                    <NumberButton content="8" onClick={()=>handleNumberClick(8)} />
                    <NumberButton content="9" onClick={()=>handleNumberClick(9)} />
                    <NumberButton content="," onClick={()=>handleNumberClick(",")} />

                    <NumberButton backgroundColor="#f3a3a4" textColor="white" lineHeight="2.25" fontSize="16px" content="⌫" onClick={handleRemoveClick} />
                    <NumberButton content="0" onClick={()=>handleNumberClick(0)} />
                    <NumberButton backgroundColor="#466362" textColor="white" style={{gridColumn:"span 2", width:"auto"}} content="✓" onClick={checkAnswer} />
                </div>
            </div>
            

        </div>
    );
}