import ApplicationLogo from "@/Components/ApplicationLogo";
import SizedBox from "@/Components/SizedBox";
import { Head } from "@inertiajs/react";
import "/public/css/welcome.css";
import "/public/css/404.css";
import { useEffect, useState } from "react";


export default function WelcomePage(){

    const [clickEaster2, setClickEaster2] = useState(0);

    function showPriscAnimation(){
        $("#priscilla").css("transform", "translate(0px, -250px)").css("visibility", "visible");

        setTimeout(() => {
            $("#priscilla").css("transform", "translate(-100px, 250px)");
            setTimeout(() => {
                $("#priscilla").css("visibility", "hidden");
            }, 1000);
        }, 2000);
    }

    useEffect(()=>{
        if(clickEaster2 == 5){
            setClickEaster2(0);
            showPriscAnimation();
        }
    }, [clickEaster2]);

    

    setTimeout(() => {
        $(".main-content h1, .buttons").fadeIn(400);

        setTimeout(() => {
            $(".game-img").fadeIn(300);



            setTimeout(() => {
                $(".sparkle:nth-of-type(1)").animate({"opacity": "1"}, {"duration":"200"});
            }, 400);
        
            setTimeout(() => {
                $(".sparkle:nth-of-type(3)").animate({"opacity": "1"}, {"duration":"300"});
            }, 500);
        
            setTimeout(() => {
                $(".sparkle:nth-of-type(2)").animate({"opacity": "1"}, {"duration":"400"});
                $("h1 .shine").addClass("extra");

            }, 550);
        }, 500);

    }, 350);


    return (
        <>
            <div class="symbs">
                <span>+</span>
                <span>-</span>
                <span>+</span>
                <span>÷</span>
                <span>×</span>
                <span>÷</span>
            </div>


            <Head title="Tere tulemast!" />
            <div className="home-navbar">
                <div className="title-div">
                    <ApplicationLogo height={50} />
                    <p className="title">Reaaler</p>
                </div>

                <a href={route("login")}>Logi sisse</a>
            </div>
            <SizedBox height={36} />
            <div className="main-content">
                <h1 style={{display:"none"}}><span style={{color:"rgb(var(--primary-color))"}}>Reaaler</span> muudab <br />matemaatika <span className="shine" style={{userSelect:"none"}} onClick={()=>setClickEaster2((e)=>e+1)}>säravaks<img alt="Täht" className="sparkle " src="/assets/homepage/sparkle.png" /><img alt="Täht" className="sparkle " src="/assets/homepage/sparkle.png" /><img alt="Täht" className="sparkle " src="/assets/homepage/sparkle.png" /></span></h1>
                <div className="buttons" style={{display:"none"}}>
                    <button  className="onboarding-btn" onClick={()=>window.location.href = route("register")}> <span translate="no" className="material-icons">calculate</span> Alusta kohe</button>
                    <span style={{userSelect:"none"}}>&nbsp;&nbsp;</span><a alone="" href={route("login")}>Logi sisse&nbsp;&nbsp;<span className="material-icons" translate="no">arrow_right_alt</span></a>
                </div>
            </div>

            <img loading="lazy" style={{display:"none"}} className="game-img" src={window.localStorage.getItem("app-theme") == "dark" ? "/assets/homepage/game-dark.png" : "/assets/homepage/game.png"} />
            <img loading="lazy" id="priscilla" style={{zIndex:"1000", transition:"transform 1000ms", visibility:"hidden", position:"fixed", rotate:"20deg", height:"300px", bottom:"-300px", left:"-100px"}} src="/assets/eastereggs/priskilla.png" />

        </>
    );
}