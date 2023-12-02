import Navbar from "@/Components/Navbar";
import NumberButton from "@/Components/NumberButton";
import SizedBox from "@/Components/SizedBox";
import Timer from "@/Components/Timer";
import { Head } from "@inertiajs/react";
import { useEffect, useRef, useState } from "react";
import GameEndPage from "../GameEnd/GameEndPage";

export default function GamePage({data, time}){

    const [answer, setAnswer] = useState("");
    const [answerAsHtml, setAnswerAsHtml] = useState("");
    const [timeOver, setTimeOver] = useState(false);
    // How many operations have been correctly answered
    const [operationCount, setOperationCount] = useState(0);
    const [message, setMessage] = useState("");
    const [fractionState, setFractionState] = useState("off");
    const [isGap, setIsGap] = useState(false);
    const [skippedAmount, setSkippedAmount] = useState(0);
    const [level, setLevel] = useState(1);
    const [showResults, setShowResults] = useState(false);

    

    // How many can be skipped
    const maxSkip = 3;


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
        setShowResults(false);
        if((forcedIndex ?? (index + 1)) < operations.data[currentLevel.current].length){
            setIndex(forcedIndex ?? (index + 1));
            var regex = /\((\d+)\/(\d+)\)$/;
            var operationString = operations.data[currentLevel.current][forcedIndex ?? (index + 1)].operation.toString();
            setLevel(operations.data[currentLevel.current][forcedIndex ?? (index + 1)].level)

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

            setIsGap(operations.data[currentLevel.current][forcedIndex ?? index + 1].operation.includes("Lünk"));
            setOperation(operationString.replaceAll(".", ","));
        }else{

            // Get the index of the current level in levels
            var ind = levels.findIndex((lvl) => lvl==currentLevel.current);

            if((ind + 1) < levels.length){
                // Set the operationlist further
                currentLevel.current = (levels[ind + 1]);
                setIndex(0);
                setAnswer("");
                getNewOperation(0);
            }else{
                // End of everything
                onTimerFinished(true);                
            }

        }
    }

    if(data == null){
        return (
        <>
            <h1>Viga!</h1>
            <p>Sellist mängutüüpi ei leitud</p>
        </>)
    }

    var operations = {data};

    var levels = Object.keys(operations.data);


    // How many times the user has checked their answer
    const [totalAnsCount, setTotalAnsCount] = useState(0);

    // How many have been correct
    const [correctCount, setCorrectCount] = useState(0);

    // The level of the operation last correctly answered
    const [lastLevel, setLastLevel] = useState(1);

    // Points system is currently not the best
    const [points, setPoints] = useState(0);

    const [timeElapsed, setTimeElapsed] = useState(0);

    var operationLog = useRef([]);

    var currentLevel = useRef(levels[0]);

    


    function handleTimerTick(){
        const pointsLostPerSec = 3;
        setPoints(points=>Math.max(0, points - pointsLostPerSec));
    }


    useEffect(()=>{
        setShowResults(false);
        getNewOperation(0);
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

                if(answer.endsWith("(0/0)")){
                    ans = answer.replace("(0/0)", "");
                    setAnswer(ans);
                    setFractionState("off");
                    return;
                }

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
        setAnswer(answer.trimEnd().slice(0, -1));
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

            // Stats
            setTotalAnsCount(totalAnsCount + 1);

            const correct = operations.data[currentLevel.current][index].answer.toString();

            var formattedAnswer = answer.replace(",", ".");

            if(formattedAnswer.length <= 0){
                return;
            }

            var regex = /\((\d+)\/(\d+)\)/;
            var matches = formattedAnswer.match(regex);

            if(matches != null){
                var numerator = matches[1].toString();
                var denominator = matches[2].toString();
                formattedAnswer = formattedAnswer.replace(matches[0], "+"+((numerator/denominator)));
            }

            formattedAnswer = formattedAnswer.replace(",", ".");

            formattedAnswer = formattedAnswer.replace(" ", "");

            if(formattedAnswer.startsWith("-")){
                formattedAnswer = "-("+formattedAnswer.slice(1)+")";
            }

            formattedAnswer = eval(formattedAnswer);


            var isCorrect = parseFloat(parseFloat(formattedAnswer).toFixed(2)) == parseFloat(parseFloat(correct).toFixed(2));

            if(isCorrect){

                var basePoints = 100*(operations.data[currentLevel.current][index].level ?? 1);
                // Animation
                $(".point-span").removeClass("red").text("+"+basePoints).fadeIn(100);
                $(".point-span").css("transform", "translateY(0)");
                setTimeout(() => {
                    $(".point-span").fadeOut(100);
                    $(".point-span").css("transition", "none").css("transform", "translateY(-64px)").css("transition", "transform 400ms ease-in-out");
                }, 400);

                // Stats
                setCorrectCount(correctCount + 1);
                setLastLevel(operations.data[currentLevel.current][index].level);
                setPoints(points => points + basePoints);
            }else{
                // Animation
                $(".point-span").addClass("red").text("-"+(points <= 100 ? points : "100")).fadeIn(100);
                $(".point-span").css("transform", "translateY(0)");
                setTimeout(() => {
                    $(".point-span").fadeOut(100);
                    $(".point-span").css("transition", "none").css("transform", "translateY(-64px)").css("transition", "transform 400ms ease-in-out");
                }, 400);

                setPoints(Math.max(0, points - 100));
            }

            operationLog.current.push({
                "operation":operations.data[currentLevel.current][index].operation,
                "correct":correct,
                "answer":parseFloat(parseFloat(formattedAnswer).toFixed(2)),
                "isCorrect":isCorrect
            });

            setAnswer("");
            setOperationCount(operationCount + 1);
            getNewOperation();

        }
    }

    function onTimerFinished(endedBefore){
        // You should cancel any interval/etc here as the game end page is essentially rendered on top of this page
        setTimeOver(true);
        setMessage(endedBefore ? "Tehted said otsa!" : "Aeg sai otsa!");
        setTimeout(() => {
            setShowResults(true);
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
        
        return ans === "" ? (isGap ? "_" : "") : ans;
    }
    
    function handleArrow(){
        if(answer.includes(")")){
            setFractionState(fractionState == "up" ? "down" : "up");
        }
    }


    function skipOperation(){
        if(skippedAmount < maxSkip){
            getNewOperation();
            setSkippedAmount(skippedAmount +1);
            setAnswer("");
            setFractionState("off");    
        }
    }


    function cancelGame(){

        window.location.href = route("dashboard");
    }

    function getCurrentTime(timeLeft){
        setTimeElapsed(time - timeLeft);
    }
    
    
    return !showResults ? (
        <div>

            <Head title="Mäng" />
            <Navbar title="Mäng" />

            <SizedBox height="36px" />
            <div style={{display:"flex", flexDirection: "column", width:"max-content", maxWidth:"100%", margin:"auto"}}>
                
                {message && <div style={{backgroundColor:"rgb(0,0,0, 0.05)", borderRadius:"16px", padding:"8px", marginBlock:"8px"}}>
                    <p style={{color:"rgb(var(--primary-color))"}}>ⓘ {message}</p>
                </div>}
                <a onClick={()=>cancelGame()} style={{color:"rgb(var(--red-color))", marginLeft:"auto"}} alone="">Katkesta</a>

                <div style={{flex:'1', width:"auto", backgroundColor:"rgb(0,0,0, 0.05)", borderRadius:"16px", padding:"8px"}}>
                    <div style={{display:"grid", gridTemplateColumns:"repeat(2, 1fr)"}}>
                        <div style={{textAlign:"start"}}>
                            <h2 style={{marginBlock:"0"}}>{operationCount + 1}<span style={{fontSize:"18px", color:"grey"}}>{level}</span></h2>
                            <span className="point-span">+100</span>
                            <p style={{marginBlock:"0", fontWeight:'bold'}}>{points} punkti</p>
                        </div>
                        <div style={{textAlign:'end'}} id="timer-div">
                            {!timeOver && <Timer onTick={handleTimerTick} getCurrentTime={getCurrentTime} cancel={timeOver} onTimerFinished={()=>onTimerFinished()} time={Math.max(Math.round(time), 10)} />}
                        </div>
                    </div>
                    <h2 style={{overflowWrap:'anywhere'}}>{!isGap ? (<><span id="operation" dangerouslySetInnerHTML={{__html: operation}}></span> = <span id="answer" dangerouslySetInnerHTML={{__html: renderAnswer(answer)}}></span></>) : <><span id="operation-pre" dangerouslySetInnerHTML={{__html: operation.split("Lünk")[0]}}></span> <span id="answer" style={{textDecoration:"underline", textDecorationThickness:"4px", textUnderlineOffset:"2px", textDecorationSkipInk:"none"}} dangerouslySetInnerHTML={{__html: renderAnswer(answer)}}></span> <span id="operation-post" dangerouslySetInnerHTML={{__html: operation.split("Lünk")[1]}}></span></>}</h2>
                </div>
                {skippedAmount < maxSkip ? <a onClick={skipOperation} style={{color:"grey", marginLeft:"auto"}} alone="">Jäta vahele ({Math.max(maxSkip - skippedAmount, 0)}) {"\u00A0"} <span className="material-icons">fast_forward</span></a> : null}
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
    ) : <GameEndPage correct={correctCount} total={totalAnsCount} points={points} time={timeElapsed} lastLevel={lastLevel} log={operationLog.current} />;
}