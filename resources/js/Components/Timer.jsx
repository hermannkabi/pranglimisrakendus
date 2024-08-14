import { useEffect, useState } from "react";

export default function Timer({onTimerFinished, time, cancel, getCurrentTime, visible=true}){

    const [timer, setTimer] = useState(time);

    var interval;

    function updateTimer(){
        setTimer((actualTime)=>{
            if(actualTime - 1 <= 0){
                clearInterval(interval);
                onTimerFinished();
            }
            getCurrentTime(actualTime - 1 >= 0 ? actualTime - 1 : 0);
            return actualTime - 1 >= 0 ? actualTime - 1 : 0;
        });
    }

    useEffect(() => {
        if(cancel){
            alert("Cancelled!");
            clearInterval(interval);
            onTimerFinished();
        }
        interval = setInterval(() => updateTimer(), 1000);
        return () => clearInterval(interval);
    }, []);

    if(cancel){
        clearInterval(interval);
        setTimer(0);
        onTimerFinished();
    }


    return <p style={{marginBlock:"0", fontSize:'18px', visibility: visible ? "visible" : "hidden"}}>{Math.floor(timer/60) < 10 ? "0" + (Math.floor(timer/60)).toString() : Math.floor(timer/60)}:{(timer - Math.floor(timer/60) * 60) < 10 ? "0" + (timer - Math.floor(timer/60) * 60).toString() : (timer - Math.floor(timer/60) * 60)}</p>;
     
}