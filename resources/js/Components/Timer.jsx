import { useEffect, useState } from "react";

export default function Timer({onTimerFinished}){

    const [timer, setTimer] = useState(20);

    var interval;

    function updateTimer(){
        setTimer((actualTime)=>{
            if(actualTime - 1 <= 0){
                clearInterval(interval);
                onTimerFinished();
            }
            return actualTime - 1 >= 0 ? actualTime - 1 : 0;
        });
        
    }

    useEffect(() => {
        interval = setInterval(() => updateTimer(), 1000);
        return () => clearInterval(interval);
    }, []);


    return <p style={{color:"rgb(var(--primary-color))", fontWeight:'bold', fontSize:'24px'}}>{Math.floor(timer/60) < 10 ? "0" + (Math.floor(timer/60)).toString() : Math.floor(timer/60)}:{(timer - Math.floor(timer/60) * 60) < 10 ? "0" + (timer - Math.floor(timer/60) * 60).toString() : (timer - Math.floor(timer/60) * 60)}</p>;
     
}