import Navbar from "@/Components/Navbar";
import NumberButton from "@/Components/NumberButton";
import SizedBox from "@/Components/SizedBox";
import Timer from "@/Components/Timer";
import { Head } from "@inertiajs/react";
import { useEffect, useRef, useState } from "react";
import GameEndPage from "../GameEnd/GameEndPage";

export default function GamePage({data, time}){

    // CONSTANTS

    // How many can be skipped
    const maxSkip = 3;

    // How many points are lost every second
    // Should this be different?
    const pointsLostPerSec = 2;

    // If there are multiple levels chosen, the app shows this amount of operations per level
    // If there is only one, it currently uses all operations in this level
    const operationsPerLevel = 10;


    const showAnimation = window.localStorage.getItem("points-animation") != "off";


    // STATE

    // The answer of the user
    const [answer, setAnswer] = useState("");
    const [answerAsHtml, setAnswerAsHtml] = useState("");

    // Is the time over?
    const [timeOver, setTimeOver] = useState(false);

    // How many operations have been correctly answered
    const [operationCount, setOperationCount] = useState(0);

    // Message to show on the top part of the page
    const [message, setMessage] = useState("");

    // Is the fraction visible and if so, is focus in the numerator or denominator
    const [fractionState, setFractionState] = useState("off");

    // Is the operation of the type 'gap' (lünkamine)
    const [isGap, setIsGap] = useState(false);

    // How many operations have been skipped
    // If you skip an operation, the operation count etc won't go up
    const [skippedAmount, setSkippedAmount] = useState(0);

    // The level of the current operation
    const [level, setLevel] = useState(1);

    // Whether the game page is visible, or the results page
    const [showResults, setShowResults] = useState(false);

    // Some operations become pointless with fractions enabled (such as division)
    const [fractionAllowed, setFractionAllowed] = useState(true);
    

    // MOUSETRAP KEY BINDINGS

    Mousetrap.bind("enter", ()=>checkAnswer());
    Mousetrap.bind("backspace", ()=>handleRemoveClick());
    Mousetrap.bind("-", ()=>handleMinusClick());
    Mousetrap.bind(",", ()=>handleNumberClick(","));
    Mousetrap.bind(".", ()=>handleNumberClick(","));
    Mousetrap.bind("up", ()=>handleArrow("up"));
    Mousetrap.bind("down", ()=>handleArrow("down"));
    Mousetrap.bind("left", ()=>handleArrow("left"));
    Mousetrap.bind("right", ()=>handleArrow("right"));




    // Keys that behave as numbers for the handleNumberClick method

    const numberKeys = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "0"];

    for(const char in numberKeys){
        Mousetrap.bind(char, ()=>handleNumberClick(char));
    }


    // The index of the operation (used together with currentLevel)
    const [index, setIndex] = useState(0);

    // The operation string that's shown on the screen
    const [operation, setOperation] = useState("");


    // Compare operation type state
    const [compare, setCompare] = useState(false);

    // Two operation states instead of one for compare type
    const [operation1, setOperation1] = useState("");
    const [operation2, setOperation2] = useState("");


    // A function to determine the number of operations to show at this level
    function numberOfOperations(lastLevel){
        if(levels.length == 1 || lastLevel){
            return operations.data[currentLevel.current].length;
        }else{
            return Math.min(operationsPerLevel, operations.data[currentLevel.current].length);
        }
    }


    // Gets a new operation of (index + 1) or forcedIndex, if it exists
    // If the level is completed (operationsPerLevel), goes to the next level
    // If the operation is the last one in the last level, ends the round
    function getNewOperation(forcedIndex){
        // Ensure game page is visible
        setShowResults(false);

        // If the current level is the last one, do everything from there
        var isLastLevel = levels[levels.length - 1] == currentLevel.current;

        // How many operations to do per level
        // This code may be updated in the future to reflect dynamic levels (when the user is unable to complete a level, they may come back to the previous ones)
        if((forcedIndex ?? (index + 1)) < numberOfOperations(isLastLevel)){
            
            // Sets state
            setIndex(forcedIndex ?? (index + 1));

            // Check for võrdlemine here (it has a different syntax with two operations instead of one)
            if(!("operation" in operations.data[currentLevel.current][forcedIndex ?? (index + 1)])){
                setCompare(true);
                setOperation1(operations.data[currentLevel.current][forcedIndex ?? (index + 1)].operation1.replaceAll(".", ","));
                setOperation2(operations.data[currentLevel.current][forcedIndex ?? (index + 1)].operation2.replaceAll(".", ","));
                setDtStartedLast(Date.now());
                setLevel(operations.data[currentLevel.current][forcedIndex ?? (index + 1)].level)

                return;
            }

            // Basic operation data
            var regex = /\((\d+)\/(\d+)\)$/;
            var operationString = operations.data[currentLevel.current][forcedIndex ?? (index + 1)].operation.toString();

            // Sets level of the new operation
            setLevel(operations.data[currentLevel.current][forcedIndex ?? (index + 1)].level)


            // Handle fraction rendering
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

            // Handles rendering exponents and radicals
            if(operationString.includes("EXP")){
                // Splits the operation into two parts - base and exponent
                var parts = operationString.split("EXP");
                var base = parts[0];
                var exponent = parts[1];

                operationString = "<div class='exp'><span class='base'>"+base+"</span><sup class='exponent'>"+exponent+"</sup></div>";

            }else if(operationString.includes("RAD")){
                // Don't know how I'm going to create this
                
                // Same code as in the exponent part
                var parts = operationString.split("RAD");
                var radicand = parts[0];
                var radIndex = parts[1];

                operationString = `<span class="rad"><span class="index">`+(radIndex == "2" ? "" : radIndex) +`</span><span class='radic'>&radic;</span><span class='radicand'>`+radicand+`</span>`;


            }

            // Check if operation is of type 'gap'
            setIsGap(operations.data[currentLevel.current][forcedIndex ?? index + 1].operation.includes("Lünk"));

            // Show operation to user
            setOperation(operationString.replaceAll(".", ","));
            setDtStartedLast(Date.now());

            // Check if the operation contains the multiply and divide chars
            // If it does, we disable entering fractions
            setFractionAllowed(!(operationString.includes("·") || operationString.includes(":")))
        }else{
            // The current level has ended

            // Get the index of the current level in levels
            var ind = levels.findIndex((lvl) => lvl==currentLevel.current);

            // Check if a higher level exists
            if((ind + 1) < levels.length){

                // Set the operationlist further
                currentLevel.current = (levels[ind + 1]);
                setIndex(0);
                setAnswer("");
                getNewOperation(0);

            }else{

                // Ends the round
                // The true attribute states that the operations have ended, not the time (more accurate message)
                onTimerFinished("Tehted said otsa!");                
            }

        }
    }


    // If data is unusable, show a basic error screen
    if(data == null || data.length <= 0){
        console.log(data);
        return (
        <>
            <h1>Viga!</h1>
            <p>{data == null ? "Sellist mängutüüpi ei leitud" : "Data on tühi!"}</p>
        </>)
    }


    // The most important variable of this view
    // The data is a Map with keys of levels and values of arrays including the operation, answer, level etc
    var operations = {data};

    console.log(operations);
    // var operations = {
    //     data:{
    //         1:[
    //             {"op1":"3+4", "op2":"2+6", "answer":"r"},
    //             {"op1":"1+1", "op2":"1+0", "answer":"l"},
    //             {"op1":"5+5", "op2":"4+6", "answer":"e"},
    //         ]
    //     }
    // };

    // A list of levels that have been requested by the user
    var levels = Object.keys(operations.data);

    // STATISTICS STATE

    // How many times the user has checked their answer
    const [totalAnsCount, setTotalAnsCount] = useState(0);

    // How many have been correct
    const [correctCount, setCorrectCount] = useState(0);

    // The level of the operation last correctly answered
    const [lastLevel, setLastLevel] = useState(1);

    // Points system currently takes into account the level of the operation and the time elapsed
    const [points, setPoints] = useState(0);

    // How many seconds have elapsed since the start of the game
    const [timeElapsed, setTimeElapsed] = useState(0);

    // The datetime of the moment where the current operation was started
    const [dtStartedLast, setDtStartedLast] = useState(Date.now());

    // A list of objects that include data about operations that have been answered, such as:
    // the operation itself, the user's answer, the correct answer, whether the answer was correct
    var operationLog = useRef([]);

    // Current level that operations are taken from
    var currentLevel = useRef(levels[0]);


    // This function is called once when the page is first loaded
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

        // If there is a fraction, change it (add a number to either the numerator or the denominator)
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


    // Handles the remove button click and the backspace character press
    function handleRemoveClick(){
        if(timeOver){return;}

        if(answer.endsWith(")")){
            var regex = /\((\d+)\/(\d+)\)/;
            var ans = answer;

            if(answer.includes(")")){

                // If the answer has an empty fraction, delete the whole fraction
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


    // Handles the minus click and minus character press
    // Toggles between negative and positive
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


    // Called when the fraction button is clicked
    function handleFraction(){
        if(!fractionAllowed) return;
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


    // Handles the keyboard arrow keys
    // Checks if the type is compare, if so, handles that
    function handleArrow(type){

        if(compare){
            if(type=="up" || type=="down"){
                handleCompareAnswer("c");
            }else{
                handleCompareAnswer(type);
            }

            return;
        }

        if(answer.includes(")")){
            setFractionState(fractionState == "up" ? "down" : "up");
        }
    }


    // Functions for type compare



    // Compare answer done
    function handleCompareAnswer(answered){
        
        var isCorrect = operations.data[currentLevel.current][(index)].answer == answered;

        var symbs = {
            "left":">",
            "right":"<",
            "c":"="
        };

        onAnswer(isCorrect, {
            "operation":operations.data[currentLevel.current][index].operation1 + " %SYMB% " + operations.data[currentLevel.current][index].operation2,
            "correct":symbs[operations.data[currentLevel.current][index].answer],
            "answer":symbs[answered],
            "isCorrect":isCorrect
        });
    }


    // Checks if the answer is correct
    // Note: This function checks the answer to questions that have a numeric one
    // For other types (e.g. compare), we use custom functions
    // Therefore, things that ALL operation checkings need (dynamic points, animations etc) are done in another function
    function checkAnswer(forceTrue){
        if(!timeOver){

            const correct = operations.data[currentLevel.current][index].answer.toString();

            var formattedAnswer = answer.replace(",", ".");

            // Don't continue if the answer is empty
            if(formattedAnswer.length <= 0){
                return;
            }

            // Remove the fraction so that when the next operation appears, a fraction is not shown by default
            setFractionState("off");

            // Handle fraction
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

            // At last, evaluate the string
            formattedAnswer = eval(formattedAnswer);

            // Is the answer correct
            var isCorrect = parseFloat(parseFloat(formattedAnswer).toFixed(2)) == parseFloat(parseFloat(correct).toFixed(2));

            onAnswer(isCorrect, {
                "operation":operations.data[currentLevel.current][index].operation,
                "correct":correct,
                "answer":parseFloat(parseFloat(formattedAnswer).toFixed(2)),
                "isCorrect":isCorrect
            });

        }else{
            // If timer is over, show detailed resutls
            setShowResults(true);
        }
    }


    // This function does NOT deal with checking the answer, simply assigning points and so on
    function onAnswer(isCorrect, data){

        // Total answer count is increased
        setTotalAnsCount(totalAnsCount + 1);

        if(isCorrect){

            var pointsLost = pointsLostPerSec * Math.round((Date.now() - dtStartedLast)/1000);

            var level = operations.data[currentLevel.current][index].level ?? 1;

            if(["A", "B", "C"].includes(level)){
                var data = {"A":10, "B":12, "C":14};
                level = data[level];
            }

            // 100 points per level (e.g. level 3 gets 300 points)
            var basePoints = 100*level - pointsLost;

            // A floating point count animation
            if(showAnimation){
                $(".point-span").removeClass("red").text("+"+basePoints).fadeIn(100);
                $(".point-span").css("transform", "translateY(0)");
                setTimeout(() => {
                    $(".point-span").fadeOut(100);
                    $(".point-span").css("transition", "none").css("transform", "translateY(-64px)").css("transition", "transform 400ms ease-in-out");
                }, 400);
            }
            

            // Correct answer stats go here
            setCorrectCount(correctCount + 1);
            setLastLevel(operations.data[currentLevel.current][index].level ?? 1);
            setPoints(points => points + basePoints);

        }else{
            // Decreasing points animation
            // If points is zero, don't show anything
            if(showAnimation){
                $(".point-span").addClass("red").text(points == 0 ? "" : ("-"+(points <= 100 ? points : "100"))).fadeIn(100);
                $(".point-span").css("transform", "translateY(0)");
                setTimeout(() => {
                    $(".point-span").fadeOut(100);
                    $(".point-span").css("transition", "none").css("transform", "translateY(-64px)").css("transition", "transform 400ms ease-in-out");
                }, 400);
            }

            setPoints(Math.max(0, points - 100));
        }


        // Add data to the list of operations that have been answered
        operationLog.current.push(data);

        // Get a new operation and set the default answer to it
        setOperationCount(operation => operation + 1);
        setAnswer("");
        getNewOperation();
    }


    // A function that is called:
    // 1. When the timer ends
    // 2. When the operations run out (then with endedBefore = true)
    function onTimerFinished(message){

        // IMPORTANT!!!
        // You should cancel any interval/etc here as the game end page is essentially rendered on top of this page

        setTimeOver(true);
        setMessage(message ?? "Aeg sai otsa!");

        // Wait for a moment before rendering the results page
        setTimeout(() => {
            setShowResults(true);
        }, 750);
    }


    // Renders tge answer
    // Basically deals with fractions and gaps if the operation type is 'gap'
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
            
                ans = ans.replace(match, ' <div class="frac"><span class="top '+(fractionState == "up" ? 'bordered' : '')+'">'+numerator+'</span><span class="symbol">/</span><span class="bottom '+(fractionState == "down" ? 'bordered' : '')+'">'+denominator+'</span></div>')
            });
        }
        
        return ans === "" ? (isGap ? "_" : "") : ans;
    }

    
    // Skips an operation if skippedAmount is less than maxSkip
    function skipOperation(){
        if(skippedAmount < maxSkip && !timeOver){
            getNewOperation();
            setSkippedAmount(skippedAmount +1);
            setAnswer("");
            setFractionState("off");    
        }
    }


    // If the cancel button is pressed, redirect to dashboard
    // Alternatively, navigate to the results page
    function cancelGame(){
        // window.location.href = route("dashboard");
        onTimerFinished("Mäng on katkestatud!");
    }


    // How many seconds have elapsed since the start of game
    function getCurrentTime(timeLeft){
        setTimeElapsed(time - timeLeft);
    }
    

    $(".frac .top, .frac .bottom").click(function (){
        setFractionState($(this).hasClass("top") ? "up" : "down");
    });
    
    return !showResults ? (
        <div>

            <Head title="Mäng" />
            <Navbar title="Mäng" />

            <SizedBox height="36px" />
            <div style={{display:"flex", flexDirection: "column", width:"max-content", maxWidth:"100%", margin:"auto"}}>
                
                {/* Message on top of the page */}
                {message && <div style={{backgroundColor:"rgb(var(--section-color),  var(--section-transparency))", borderRadius:"var(--primary-btn-border-radius)", padding:"8px", marginBlock:"8px"}}>
                    <p style={{color:"rgb(var(--primary-color))"}}>ⓘ {message}</p>
                </div>}

                {/* Cancel button */}
                <a onClick={()=>cancelGame()} style={{color:"rgb(var(--red-color))", marginLeft:"auto"}} alone="">Katkesta</a>

                {/* A backgrounded section containing all the data */}
                <div style={{flex:'1', width:"auto", backgroundColor:"rgb(var(--section-color), var(--section-transparency))", borderRadius:"var(--primary-btn-border-radius)", padding:"8px"}}>

                    <div style={{display:"grid", gridTemplateColumns:"repeat(2, 1fr)"}}>

                        {/* Current operation number & level, total points etc. */}
                        <div style={{textAlign:"start"}}>
                            <h2 style={{marginBlock:"0"}}>{operationCount + 1}<span style={{fontSize:"18px", color:"grey"}}>{level.toString().replace("A", " ★1").replace("B", " ★2").replace("C", " ★3")}</span></h2>
                            <span className="point-span">+100</span>
                            <p style={{marginBlock:"0", fontWeight:'bold'}}>{points} punkti</p>
                        </div>

                        {/* Timer */}
                        <div style={{textAlign:'end'}} id="timer-div">
                            {!timeOver && <Timer visible={window.localStorage.getItem("timer-visibility") != "hidden"} getCurrentTime={getCurrentTime} cancel={timeOver} onTimerFinished={()=>onTimerFinished()} time={Math.max(Math.round(time), 10)} />}
                        </div>
                    </div>

                    {/* The operation data  and answer*/}
                    {compare && <h2 style={{overflowWrap:'anywhere'}}><><span id="operation1" dangerouslySetInnerHTML={{__html: operation1}}></span> <span> <span style={{color:"gray", fontSize:"0.8em"}}>?</span> </span> <span id="operation2" dangerouslySetInnerHTML={{__html: operation2}}></span></></h2>}
                    {!compare && <h2 style={{overflowWrap:'anywhere'}}>{!isGap ? (<><span id="operation" dangerouslySetInnerHTML={{__html: operation}}></span> = <span id="answer" dangerouslySetInnerHTML={{__html: renderAnswer(answer)}}></span></>) : <><span id="operation-pre" dangerouslySetInnerHTML={{__html: operation.split("Lünk")[0]}}></span> <span id="answer" style={{textDecoration:"underline", textDecorationThickness:"4px", textUnderlineOffset:"2px", textDecorationSkipInk:"none"}} dangerouslySetInnerHTML={{__html: renderAnswer(answer)}}></span> <span id="operation-post" dangerouslySetInnerHTML={{__html: operation.split("Lünk")[1]}}></span></>}</h2>}
                </div>

                {/* Skip button */}
                {skippedAmount < maxSkip ? <a onClick={skipOperation} style={{color:"grey", marginLeft:"auto"}} alone="">Jäta vahele ({Math.max(maxSkip - skippedAmount, 0)}) {"\u00A0"} <span className="material-icons">fast_forward</span></a> : null}
                
                <SizedBox height="24px" />
                
                {/* On-screen keyboard */}
                {!compare && <div style={{display:'grid', gridTemplateColumns:'repeat(4, 1fr)', width:'fit-content', margin:"auto"}}>
                    <NumberButton content="1" onClick={()=>handleNumberClick(1)} />
                    <NumberButton content="2" onClick={()=>handleNumberClick(2)} />
                    <NumberButton content="3" onClick={()=>handleNumberClick(3)} />
                    <NumberButton disabled={!fractionAllowed} content={fractionState == "up" ? "arrow_downward" : fractionState == "down" ? "arrow_upward" : "½"} icon={fractionState == "up" || fractionState == "down"} onClick={()=>handleFraction()}/>

                    <NumberButton content="4" onClick={()=>handleNumberClick(4)} />
                    <NumberButton content="5" onClick={()=>handleNumberClick(5)} />
                    <NumberButton content="6" onClick={()=>handleNumberClick(6)} />
                    <NumberButton content="-" onClick={handleMinusClick} />

                    <NumberButton content="7" onClick={()=>handleNumberClick(7)} />
                    <NumberButton content="8" onClick={()=>handleNumberClick(8)} />
                    <NumberButton content="9" onClick={()=>handleNumberClick(9)} />
                    <NumberButton content="," onClick={()=>handleNumberClick(",")} />

                    <NumberButton backgroundColor="#f3a3a4" textColor="white" lineHeight="2.25" fontSize="16px" content="backspace" icon={true} onClick={handleRemoveClick} />
                    <NumberButton content="0" onClick={()=>handleNumberClick(0)} />
                    <NumberButton backgroundColor="#466362" textColor="white" style={{gridColumn:"span 2", width:"auto"}} content="check" icon={true} onClick={checkAnswer} />
                </div>}

               {/* Compare type on screen keyboard */}
               {compare && <div style={{display:'grid', gridTemplateColumns:'repeat(3, 1fr)', width:'fit-content', margin:"auto"}}>
                    <NumberButton content=">" onClick={()=>handleCompareAnswer("left")} />
                    <NumberButton content="=" onClick={()=>handleCompareAnswer("c")} />
                    <NumberButton content="<" onClick={()=>handleCompareAnswer("right")} />
                </div>}
            </div>
            

        </div>
    ) : <GameEndPage correct={correctCount} total={totalAnsCount} points={points} time={timeElapsed} lastLevel={lastLevel} log={operationLog.current} />;
}