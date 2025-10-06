import NumberButton from "@/Components/NumberButton";
import SizedBox from "@/Components/SizedBox";
import Timer from "@/Components/Timer";
import { Head } from "@inertiajs/react";
import { useEffect, useRef, useState } from "react";
import GameEndPage from "../GameEnd/GameEndPage";
import InfoBanner from "@/Components/InfoBanner";

export default function GamePage({mis, tyyp, raw_level, data, time, competition, auth}){

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

    const flippedKeyboard = window.localStorage.getItem("flip-keyboard") == "true";


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
    const showResults = useRef(false);

    // Some operations become pointless with fractions enabled (such as division)
    const [fractionAllowed, setFractionAllowed] = useState(true);
    const [minusAllowed, setMinusAllowed] = useState(true);
    const [commaAllowed, setCommaAllowed] = useState(true);
    

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

    // Division law type
    const [divisionLaw, setDivisionLaw] = useState(false);

    // Simplify type
    const [simplify, setSimplify] = useState(false);

    // Count shapes type
    const [shapes, setShapes] = useState(false);

    // What shape is the user counting
    const [whatShape, setWhatShape] = useState("<div class='shape circle inline'>");

    const [debugValue, setDebugValue] = useState(0);




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
        showResults.current = false;

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
            var regex = /\((-?\d+)\/(-?\d+)\)/;
            var operationString = operations.data[currentLevel.current][forcedIndex ?? (index + 1)].operation.toString();

            var matches = operationString.match(regex);

            // Sets level of the new operation
            setLevel(operations.data[currentLevel.current][forcedIndex ?? (index + 1)].level)

            // Handles rendering exponents and radicals
            // Since there might be fractions here, this has to be before fraction rendering
            if(operationString.includes("EXP")){
                // Splits the operation into two parts - base and exponent
                var parts = operationString.split("EXP");
                var base = parts[0];
                var exponent = parts[1];

                // operationString = "<div class='exp'><span class='base'>"+base+"</span><sup class='exponent'>"+exponent+"</sup></div>";
                operationString = "<math><msup class='math-style'><mn>"+base+"</mn><mi class='"+(matches != null ? "math-pow-transformed" : "")+"'>"+exponent+"</mi></msup></math>";

            }else if(operationString.includes("RAD")){
                // Don't know how I'm going to create this
                
                // Same code as in the exponent part
                var parts = operationString.split("RAD");
                var radicand = parts[0];
                var radIndex = parts[1];


                operationString = `<math><mroot><mi class='math-style'>`+radicand+`</mi><mn class='math-style root-alignment'>`+(radIndex == "2" ? "" : radIndex)+`</mn></mroot></math>`;

                //operationString = `<span class="rad"><span class="index">`+(radIndex == "2" ? "" : radIndex) +`</span><span class='radic'>&radic;</span><span class='radicand'>`+radicand+`</span>`;
            }

            // Handle fraction rendering
            if(matches!= null){
                matches.forEach(function (match){
                    var matchList = regex.exec(match);
                    if(matchList == null) return;
                    var numerator = parseInt(matchList[1]);
                    var denominator = parseInt(matchList[2]);
                    var fullPart = null;
    
                    if(numerator > denominator && (!operationString.includes("LIHT") || !["1", "2", "3"].includes(operations.data[currentLevel.current][forcedIndex ?? (index + 1)].level)) && (!operationString.includes("div"))){
                        fullPart = Math.floor(numerator / denominator) != (numerator / denominator) ? Math.floor(numerator / denominator) : null;
                        //numerator -=  (fullPart ?? 0) * denominator;
                    }

                    operationString = operationString.replace(match, '<math>'+(operationString.includes("msup") ? '<mrow><mo>(</mo>' : '')+'<mfrac class="math-style"><mn>'+numerator+'</mn><mi>'+denominator+'</mi></mfrac>'+(operationString.includes("msup") ? '<mo>)</mo></mrow>' : '')+'</math>');

                    // operationString = operationString.replace(match, (fullPart == null ? '' : fullPart.toString()) + ' <div class="frac"><span>'+numerator+'</span><span class="symbol">/</span><span class="bottom">'+denominator+'</span></div>')
                });
            }

            


            // Check if game is of type shape count
            var isArray = Array.isArray(operations.data[currentLevel.current][forcedIndex ?? index + 1].operation);
            if(isArray){
                setShapes(true);

                // A really long string containing all the shapes that are in this operation
                var shapesHTMLString = "";
                var numToShape={
                    0:"space",
                    1:"square",
                    2:"circle",
                    3:"triangle",
                };

                var numToColor={
                    1:"green",
                    2:"red",
                    3:"blue",
                };

                var numToSize={
                    1:"small",
                    2:"medium",
                    3:"big",
                };
                
                for(var idx = 0; idx < operations.data[currentLevel.current][forcedIndex ?? index + 1].operation.length; idx++){
                    
                    var shapeData = operations.data[currentLevel.current][forcedIndex ?? index + 1].operation[idx];

                    var shapeClassList = numToShape[shapeData.shape]  + " " + (shapeData.color != null ? numToColor[shapeData.color] : "") + " " + (shapeData.size != null ? numToSize[shapeData.size] : "");
                    
                    shapesHTMLString += "<div class='shape "+shapeClassList+"'></div>";
                }

                // Sets the column count to the squre root of the ans count
                // This makes the shapes appear in a square (somewhat)
                var columnCount = Math.floor(Math.sqrt(operations.data[currentLevel.current][forcedIndex ?? index + 1].operation.length));

                operationString = "<div class='shapes-container' style='grid-template-columns: repeat("+columnCount+", 1fr)'>"+shapesHTMLString+"</div>";

                var ansData = operations.data[currentLevel.current][forcedIndex ?? index + 1].answer;
                var whatShapeClassList = numToShape[ansData.shape] + " " + (ansData.color != null ? numToColor[ansData.color] : "") + " " + (ansData.size != null ? numToSize[ansData.size] : "");
                setWhatShape("<div class='shape inline "+ whatShapeClassList + "'></div>");
            }

            // Check if operation is of type 'gap'
            setIsGap(operations.data[currentLevel.current][forcedIndex ?? index + 1].operation.includes("Lünk"));

            // Check if operation is of type 'division law'
            setDivisionLaw(operationString.includes("⋮"));

            // Check if operation is of type 'simplify'
            setSimplify(operationString.includes("LIHT"));

            if(operationString.includes("LIHT")){
                setAnswer("(0/0)");
            }

            setFractionState(operationString.includes("LIHT") ? "up" : "off");

            // Show operation to user
            setOperation(operationString.replaceAll(".", ",").replaceAll("LIHT", "").replaceAll(",png", ".png"));
            setDtStartedLast(Date.now());

            // Check if the operation contains the multiply and divide chars
            // If it does, we disable entering fractions
            // Similarly with comma and minus
            //console.log(( operationString));
            setFractionAllowed(!(operationString.includes("·") || operationString.includes(":") || isArray ));
            setCommaAllowed(!isArray);
            setMinusAllowed(!isArray);

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

    //console.log(operations);
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
        window.addEventListener('beforeunload', handleBeforeUnload);
        showResults.current = false;
        getNewOperation(0);
    }, []);


    // Handles numbers, minus and comma
    function handleNumberClick(number){   
        if(timeOver){return;}    

        if(compare) return;
        if(divisionLaw) return;

        if(answer.length <= 0){setDebugValue(Date.now() - dtStartedLast)}



        // Check if comma and minus are allowed, if not, return
        if((number == "," && !commaAllowed) || (number=="-" && !minusAllowed)) return;

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

        if(compare) return;
        if(divisionLaw) return;

        if(answer.endsWith(")")){
            var regex = /\((\d+)\/(\d+)\)/;
            var ans = answer;

            if(answer.includes(")")){

                // If the answer has an empty fraction, delete the whole fraction
                if(answer.endsWith("(0/0)") && !simplify){
                    ans = answer.replace("(0/0)", "");
                    setAnswer(ans.length == 0 && isGap ? "_" : "");
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
        
        if(!minusAllowed) return;

        if(compare) return;
        if(divisionLaw) return;


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
    // type can be left, right, up or down
    function handleArrow(type){

        if(timeOver) return;

        if(compare){
            if(type=="up" || type=="down"){
                handleCompareAnswer("c");
            }else{
                handleCompareAnswer(type);
            }

            return;
        }

        if(divisionLaw){
            // Since the correct button is on the left, map the left arrow to true
            handleDivisionLaw(type=="left");
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
            "isCorrect":isCorrect,
            "level":currentLevel.current,
        });
    }

    function handleDivisionLaw(ans){
        var isCorrect = operations.data[currentLevel.current][(index)].answer == ans;

        onAnswer(isCorrect, {
            "operation":operations.data[currentLevel.current][index].operation.replaceAll(" ", ""),
            "correct":operations.data[currentLevel.current][index].answer ? "Jah" : "Ei",
            "answer":ans ? "Jah" : "Ei",
            "isCorrect":isCorrect,
            "level":currentLevel.current,
        });
    }


    // Checks if the answer is correct
    // Note: This function checks the answer to questions that have a numeric one
    // For other types (e.g. compare), we use custom functions
    // Therefore, things that ALL operation checkings need (dynamic points, animations etc) are done in another function
    function checkAnswer(forceTrue){
        if(!timeOver){

            if(shapes && answer.length > 0){
                const correct = operations.data[currentLevel.current][index].answer.ans;

                var isCorrect = answer == correct;
                onAnswer(isCorrect, {
                    "operation":operations.data[currentLevel.current][index].operation.length + " kujundit",
                    "correct":correct.toString(),
                    "answer": answer.toString(),
                    "isCorrect":isCorrect,
                    "level":currentLevel.current,
                });
                return;
            }

            

            const correct = operations.data[currentLevel.current][index].answer.toString();

            var formattedAnswer = answer.replace(",", ".");

            // Don't continue if the answer is empty
            if(formattedAnswer.length <= 0){
                return;
            }
            // Don't continue if the answer is 0/0
            if(formattedAnswer.trim() == "(0/0)"){
                return;
            }

            // Remove the fraction so that when the next operation appears, a fraction is not shown by default
            setFractionState("off");

            var isCorrect = false;

            if(simplify){
                if(!formattedAnswer.includes("(")){
                    formattedAnswer = "(" + formattedAnswer + "/1)";
                }

                isCorrect = formattedAnswer == correct;
            }else{
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
                isCorrect = parseFloat(parseFloat(formattedAnswer).toFixed(2)) == parseFloat(parseFloat(correct).toFixed(2));
            }

            onAnswer(isCorrect, {
                "operation":operations.data[currentLevel.current][index].operation ?? "",
                "correct": simplify ? correct : parseFloat(correct).toFixed(2).replace(".00", ""),
                "answer": simplify ? formattedAnswer : parseFloat(parseFloat(formattedAnswer).toFixed(2)),
                "isCorrect":isCorrect,
                "level":currentLevel.current,
            });

        }else{
            // If timer is over, show detailed resutls
            showResults.current = true;
        }
    }


    // This function does NOT deal with checking the answer, simply assigning points and so on
    function onAnswer(isCorrect, data){

        if(Date.now() - dtStartedLast <= 500) return;

        // Total answer count is increased
        setTotalAnsCount(totalAnsCount + 1);

        var level = operations.data[currentLevel.current][index].level ?? 1;

        if(isCorrect){

            var pointsLost = pointsLostPerSec * Math.round((Date.now() - dtStartedLast)/1000);


            var levelPoints = level;
            if(["A", "B", "C"].includes(level)){
                levelPoints = {"A":10, "B":15, "C":20}[level];
            }

            // 100 points per level (e.g. level 3 gets 300 points)
            var basePoints = 100*levelPoints - pointsLost;

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
            var pointsLostForType = mis == "jaguvus" ? ({1:1,2:2,3:3,4:4,5:5, "A":10, "B":15, "C":20}[level]*100 - 50)  : 100;

            // Decreasing points animation
            // If points is zero, don't show anything
            if(showAnimation){
                $(".point-span").addClass("red").text(points == 0 ? "" : ("-"+(points <= 100 ? points : pointsLostForType.toString()))).fadeIn(100);
                $(".point-span").css("transform", "translateY(0)");
                setTimeout(() => {
                    $(".point-span").fadeOut(100);
                    $(".point-span").css("transition", "none").css("transform", "translateY(-64px)").css("transition", "transform 400ms ease-in-out");
                }, 400);
            }

            setPoints(Math.max(0, points - pointsLostForType));
        }


        // Add data to the list of operations that have been answered
        data["level"] = level;
        operationLog.current.push(data);

        // Get a new operation and set the default answer to it
        setOperationCount(operation => operation + 1);
        setAnswer("");
        getNewOperation();
    }


    // A function that is called:
    // 1. When the timer ends
    // 2. When the operations run out (then with endedBefore = true)
    function onTimerFinished(message, dontSave=false){
        window.removeEventListener('beforeunload', handleBeforeUnload);

        if(dontSave){
            window.location.href = route('dashboard');
            return;
        }
        // IMPORTANT!!!
        // You should cancel any interval/etc here as the game end page is essentially rendered on top of this page

        setTimeOver(true);
        setMessage(message ?? "Aeg sai otsa!");

        // Wait for a moment before rendering the results page
        setTimeout(() => {    
            showResults.current = true;
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
        
        return ans.length <= 0 || ans == "" ? (isGap ? "_" : "") : ans;
    }

    
    // Skips an operation if skippedAmount is less than maxSkip
    function skipOperation(){
        if(skippedAmount < maxSkip && !timeOver){
            getNewOperation();
            setSkippedAmount(skippedAmount +1);
            setAnswer(simplify ? "(0/0)" : "");
            if(!simplify){
                setFractionState("off");    
            }
        }
    }


    // If the cancel button is pressed, redirect to dashboard
    // Alternatively, navigate to the results page
    function cancelGame(){
        // window.location.href = route("dashboard");
        onTimerFinished("Mäng on katkestatud!", totalAnsCount <= 0);
    }


    // How many seconds have elapsed since the start of game
    function getCurrentTime(timeLeft){
        setTimeElapsed(time - timeLeft);
    }
    

    $(".frac .top, .frac .bottom").click(function (){
        setFractionState($(this).hasClass("top") ? "up" : "down");
    });


    function obfuscateOperation(oper){

        // Don't obfuscate sqrt
        if(oper.includes("span") || oper.includes("math")){
            return oper;
        }

        var newOper = "";
        for(var i = 0; i<oper.length; i++){
            if(oper[i] == "1"){
                var randChoice = ["hjsdhjsdhjshd", "ihbjgdjhsdhjsd", "qdffgkfgkjgdf"][Math.floor(Math.random() * 3)];
                newOper += "<"+randChoice+"></"+randChoice+">";
            }else if(oper[i] == "9"){
                newOper += "<sdjkhskdsdsde></sdjkhskdsdsde>";
            }else{
                newOper += "<"+["suva", "oper", "bads", "skdjkds", "sjkd", "sdjnc", "uysrwj", "icok", "karlerik", "karl"][Math.floor(Math.random() * 10)]+">" + oper[i];
            }
            newOper += "<span>"+["&#x200B;", "&#8203;", "&ZeroWidthSpace;", "&zwnj;"][Math.floor(Math.random() * 4)]+"</span>";
        }

        return newOper;
    }

    // Number is the lower one of the two possibilities (i.e 1 -> 1 or 7)
    function flippedKeyboardKey(number){
        var newNumber = flippedKeyboard ? (number > 3 ? number - 6 : number + 6) : number;
        return <NumberButton content={newNumber.toString()} onClick={()=>handleNumberClick(newNumber)} />
    }


    function handleBeforeUnload(e) {
        if(!showResults.current){
            // Cancel the event
            e.preventDefault(); // If you prevent default behavior in Mozilla Firefox prompt will always be shown
            // Chrome requires returnValue to be set
            e.returnValue = '';
        }
    }


      
    
    return !timeOver ? (
        <div>

            <Head title="Mäng" />
            <SizedBox height="72px" />

            <div style={{display:"flex", flexDirection: "column", width:"max-content", maxWidth:"100%", margin:"auto", alignItems:'center'}}>
                
                

                <div style={{minWidth:"100%", fontSize:"16px", display:"flex", flexDirection:"row", justifyContent:"center", alignItems:"stretch", gap:"8px"}}>
                    <div onClick={cancelGame} style={{display:"flex",flexShrink:"2", color:"var(--red-color)", justifyContent:"space-between", padding:"8px 12px", alignItems:"center"}} className="section clickable">
                        <p>Katkesta</p>
                        <i translate="no" className="material-icons">close</i>
                    </div>
                    <div onClick={skipOperation} disabled={maxSkip - skippedAmount <= 0} style={{maxWidth:"200px", display:"flex", flex:"1", justifyContent:"space-between", padding:"8px 12px", alignItems:"center"}} className="section clickable">
                        <div>
                            <p style={{marginBlock:"0"}}>Jäta vahele</p>
                            <p style={{marginBlock:"0", color:"var(--grey-color)", fontSize:"14px"}}>{Math.max(maxSkip - skippedAmount, 0)} jäänud</p>
                        </div>
                        <i translate="no" className="material-icons-outlined">fast_forward</i>
                    </div>
                </div>
                {/* Message on top of the page */}
                {message && <div className="section" style={{marginBlock:"8px", minWidth:"100%", paddingInline:"0"}}>
                    <InfoBanner text={message} />
                </div>}
                {/* A backgrounded section containing all the data */}
                <div className="section" style={{textAlign:"center", flex:'1', width:"auto", padding:"8px", paddingInline:"0", minWidth:"100%"}}>
                    <div style={{paddingInline:"8px"}}>
                    <SizedBox height="8px" />

                    <div style={{display:"flex", flexDirection:"row", justifyContent:"space-between", alignItems:"center"}}>
                        {/* Current operation number & level, total points etc. */}
                        <div style={{textAlign:"start", display:"flex", justifyContent:"start", flexDirection:"row", alignItems:'center', gap:"16px"}}>
                            <span title={operationCount + 1 + ". tehe"} style={{fontSize:"24px", fontWeight:"bold"}}>{operationCount + 1}</span>
                            <div>
                                <p style={{display:"flex", alignItems:"center", gap:"6px", marginBlock:"0"}}> <i title="Tehte tase" translate="no" style={{fontSize:"18px"}} className="material-icons-outlined">exercise</i> {level.toString().replace("A", " ★1").replace("B", " ★2").replace("C", " ★3")}</p>
                                <span style={{marginLeft:"24px"}} className="point-span">+100</span>
                                <p style={{marginBlock:"0", fontSize:"18px", display:"flex", alignItems:"center", gap:"6px"}}><i title={points + " punkti"} translate="no" style={{fontSize:"18px"}} className="material-icons-outlined">trophy</i> {points}</p>
                            </div>
                        </div>

                        {/* Timer */}
                        <div style={{textAlign:'end'}} id="timer-div">
                            {!timeOver && <Timer visible={window.localStorage.getItem("timer-visibility") != "hidden"} getCurrentTime={getCurrentTime} cancel={timeOver} onTimerFinished={()=>onTimerFinished()} time={Math.max(Math.round(time), 30)} />}
                        </div>
                    </div>
                    <SizedBox height="16px" />
                    {/* The operation data  and answer*/}
                    {compare && <h2 translate="no" style={{overflowWrap:'anywhere'}}><><span id="operation1" dangerouslySetInnerHTML={{__html: operation1}}></span> <span> <span style={{color:"gray", fontSize:"0.8em"}}>?</span> </span> <span id="operation2" dangerouslySetInnerHTML={{__html: operation2}}></span></></h2>}
                    {shapes && <span translate="no"><span id="operation" dangerouslySetInnerHTML={{__html: operation}}></span> <br /> <br /> <span style={{display:"inline-flex"}}>Mitu <span className="what-shape" dangerouslySetInnerHTML={{__html: whatShape}}></span>? <SizedBox width={8} /> </span><span id="answer" dangerouslySetInnerHTML={{__html: renderAnswer(answer)}}></span></span>}
                    {!compare && !shapes && <h2 translate="no" style={{textAlign:"center", overflowWrap:'anywhere', fontWeight:"bold", fontSize:"28px"}}>{!isGap ? (<>{Math.random() > 0.5 ? <span></span> : null}<span dangerouslySetInnerHTML={{__html: obfuscateOperation(operation)}}></span> {!divisionLaw && !shapes && <span>=</span>} <span style={{color:"var(--grey-color)"}} id="answer" dangerouslySetInnerHTML={{__html: renderAnswer(answer)}}></span>{Math.random() > 0.5 ? <span></span> : null}</>) : <><span id="operation-pre" dangerouslySetInnerHTML={{__html: operation.split("Lünk")[0]}}></span> <span id="answer" style={{textDecoration:"underline", textDecorationThickness:"4px", textUnderlineOffset:"2px", textDecorationSkipInk:"none"}} dangerouslySetInnerHTML={{__html: renderAnswer(answer)}}></span> <span id="operation-post" dangerouslySetInnerHTML={{__html: operation.split("Lünk")[1]}}></span></>}</h2>}
                    <SizedBox height="16px" />
                    </div>
                </div>
                
                <SizedBox height="8px" />
                
                {/* On-screen keyboard */}
                {!compare && !divisionLaw && <div style={{display:'grid', gridTemplateColumns:'repeat(4, 1fr)', width:'fit-content', margin:"auto", marginInline:"-4px"}}>
                    {flippedKeyboardKey(1)}
                    {flippedKeyboardKey(2)}
                    {flippedKeyboardKey(3)}
                    <NumberButton disabled={!fractionAllowed} content={fractionState == "up" ? "arrow_downward" : fractionState == "down" ? "arrow_upward" : "½"} icon={fractionState == "up" || fractionState == "down"} onClick={()=>handleFraction()}/>

                    <NumberButton content="4" onClick={()=>handleNumberClick(4)} />
                    <NumberButton content="5" onClick={()=>handleNumberClick(5)} />
                    <NumberButton content="6" onClick={()=>handleNumberClick(6)} />
                    <NumberButton disabled={!minusAllowed} content="-" onClick={handleMinusClick} />

                    {flippedKeyboardKey(7)}
                    {flippedKeyboardKey(8)}
                    {flippedKeyboardKey(9)}
                    <NumberButton disabled={!commaAllowed} content="," onClick={()=>handleNumberClick(",")} />

                    <NumberButton backgroundColor="var(--red-color)" textColor="white" lineHeight="1.7" fontSize="20px" content="backspace" icon={true} onClick={handleRemoveClick} />
                    <NumberButton content="0" onClick={()=>handleNumberClick(0)} />
                    <NumberButton backgroundColor="rgb(var(--primary-color))" textColor="white" style={{gridColumn:"span 2", width:"auto"}} content="arrow_forward" icon={true} onClick={checkAnswer} />
                </div>}

               {/* Compare type on screen keyboard */}
               {compare && <div style={{display:'grid', gridTemplateColumns:'repeat(3, 1fr)', width:'fit-content', margin:"auto"}}>
                    <NumberButton content=">" onClick={()=>handleCompareAnswer("left")} />
                    <NumberButton content="=" onClick={()=>handleCompareAnswer("c")} />
                    <NumberButton content="<" onClick={()=>handleCompareAnswer("right")} />
                </div>}

                {/* Division law keyboard (yes/no)*/}
                {divisionLaw && <div style={{display:'grid', gridTemplateColumns:'repeat(2, 1fr)', width:'fit-content', margin:"auto"}}>
                    <NumberButton textColor={"green"} icon={true} content="done" onClick={()=>handleDivisionLaw(true)} />
                    <NumberButton textColor={"red"} icon={true} content="close" onClick={()=>handleDivisionLaw(false)} />
                </div>}

            </div>
            

        </div>
    ) : <GameEndPage competition={competition} mis={mis} tyyp={tyyp} raw_level={raw_level} correct={correctCount} total={totalAnsCount} points={points} time={timeElapsed} lastLevel={lastLevel} log={operationLog.current} auth={auth} />;
}