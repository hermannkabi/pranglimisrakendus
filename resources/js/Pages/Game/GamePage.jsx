import Navbar from "@/Components/Navbar";
import NumberButton from "@/Components/NumberButton";
import SizedBox from "@/Components/SizedBox";
import Timer from "@/Components/Timer";
import { Head } from "@inertiajs/react";
import { useEffect, useState } from "react";

export default function GamePage(){

    const [answer, setAnswer] = useState("");
    const [timeOver, setTimeOver] = useState(false);
    // How many operations have been correctly answered
    const [operationCount, setOperationCount] = useState(0);
    const [message, setMessage] = useState("");



    // Mousetrap key bindings
    Mousetrap.bind("enter", ()=>checkAnswer());
    Mousetrap.bind("backspace", ()=>handleRemoveClick());
    Mousetrap.bind("-", ()=>handleMinusClick());
    Mousetrap.bind(",", ()=>handleNumberClick(","));

    // Keys that behave as numbers for the handleNumberClick method
    const numberKeys = ["1", "2", "3", "4", "5", "6", "7", "8", "9", "0"];

    for(const char in numberKeys){
        Mousetrap.bind(char, ()=>handleNumberClick(char));
    }


    // Generate random operation
    const [num1, setNum1] = useState(0);
    const [num2, setNum2] = useState(0);

    function generateRandomOperation(){
        const number1 = Math.floor(Math.random()*20);
        const number2 = Math.floor(Math.random()*20);

        setNum1(number1);
        setNum2(number2);
    }

    useEffect(()=>{
        generateRandomOperation();
        window.addEventListener('beforeunload', (event) => {
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


        setAnswer(answer + number.toString());
        
    }

    function handleRemoveClick(){
        if(timeOver){return;}               

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
            // Currently hard-coded, but will change in the future
            const correct = num1 + num2;

            const formattedAnswer = answer.replace(",", ".");

            if(formattedAnswer.length <= 0){
                return;
            }

            if(parseFloat(formattedAnswer) == parseFloat(correct)){
                generateRandomOperation();
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
                            <Timer onTimerFinished={onTimerFinished} />
                        </div>
                    </div>
                    <h2 style={{overflowWrap:'anywhere'}}> <span id="operation">{num1}+{num2}</span> = <span id="answer">{answer}</span></h2>
                </div>
                <a style={{color:"grey", marginLeft:"auto"}} alone="" href="">Jäta vahele (3) {"\u00A0"} <span className="material-icons">fast_forward</span></a>
                <SizedBox height="24px" />
                <div style={{display:'grid', gridTemplateColumns:'repeat(4, 1fr)', width:'fit-content', margin:"auto"}}>
                    <NumberButton content="1" onClick={()=>handleNumberClick(1)} />
                    <NumberButton content="2" onClick={()=>handleNumberClick(2)} />
                    <NumberButton content="3" onClick={()=>handleNumberClick(3)} />
                    <NumberButton content="½" />

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