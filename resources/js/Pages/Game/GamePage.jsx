import Navbar from "@/Components/Navbar";
import NumberButton from "@/Components/NumberButton";
import SizedBox from "@/Components/SizedBox";
import { Head } from "@inertiajs/react";
import { useEffect, useState } from "react";

export default function GamePage(){

    const [answer, setAnswer] = useState("");

    const [timer, setTimer] = useState(20);

    useEffect(() => {
        const interval = setInterval(() => updateTimer(), 1000);

        if(timer <= 0){
            alert("Aeg on otsas!")
            clearInterval(interval);
        }

        return () => clearInterval(interval);
    });

    function handleNumberClick(number){                  
        // Handle click so that there will be no leading zero
        if((answer == "0" || answer == "-0") && number != ","){
            setAnswer(answer.replace("0", number.toString()));
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
        setAnswer(answer.slice(0, -1));
    }

    function handleMinusClick(){
        var newAnswer = answer;
        if(answer.startsWith("-")){
            newAnswer = answer.slice(1);
        }else{
            newAnswer = "-" + answer;
        }
        setAnswer(newAnswer);
    }


    function checkAnswer(){
        // Currently hard-coded, but will change in the future
        const correct = "4";

        const formattedAnswer = answer.replace(",", ".");

        if(formattedAnswer.length <= 0){
            return;
        }

        if(parseFloat(formattedAnswer) == parseFloat(correct)){
            alert("Tubli!!!");
        }else{
            alert("Õige vastus oli "+correct);
        }
    }


    function updateTimer(){
        const proposedNewTime = timer - 1;
        setTimer(proposedNewTime < 0 ? 0 : proposedNewTime);
    }


    return (
        <>
            <Head title="Mäng" />
            <Navbar title="Mäng" />

            <SizedBox height="36px" />
            <div style={{display:"flex", flexDirection: "column", width:"max-content", maxWidth:"100%", margin:"auto"}}>
                <div style={{flex:'1', width:"auto", backgroundColor:"rgb(var(--primary-color), 0.05)", borderRadius:"16px", padding:"8px"}}>
                    <div style={{display:"grid", gridTemplateColumns:"repeat(2, 1fr)"}}>
                        <div style={{textAlign:"start"}}>
                            <h2 style={{marginBlock:"0"}}>1 <span style={{fontFamily:"Kanit", fontSize:"24px", color:"grey"}}>1</span></h2>
                            <p style={{marginBlock:"0", color:"rgb(var(--primary-color))", fontWeight:'bold'}}>0 punkti</p>
                        </div>
                        <div style={{textAlign:'end'}}>
                            <p style={{color:"rgb(var(--primary-color))", fontWeight:'bold', fontSize:'24px'}}>{Math.floor(timer/60) < 10 ? "0" + (Math.floor(timer/60)).toString() : Math.floor(timer/60)}:{(timer - Math.floor(timer/60) * 60) < 10 ? "0" + (timer - Math.floor(timer/60) * 60).toString() : (timer - Math.floor(timer/60) * 60)}</p>
                        </div>
                    </div>
                    <h2 style={{overflowWrap:'anywhere'}}> <span id="operation">2+2</span> = <span id="answer">{answer}</span></h2>
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
            

        </>
    );
}