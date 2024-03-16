import ApplicationLogo from "@/Components/ApplicationLogo";
import SizedBox from "@/Components/SizedBox";
import { Head } from "@inertiajs/react";
import "/public/css/welcome.css";
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
        if(clickEaster2 == 3){
            setClickEaster2(0);
            showPriscAnimation();
        }
    }, [clickEaster2]);

    setTimeout(() => {
        $(".sparkle:nth-of-type(1)").animate({"opacity": "1"}, {"duration":"200"});
    }, 200);

    setTimeout(() => {
        $(".sparkle:nth-of-type(3)").animate({"opacity": "1"}, {"duration":"300"});
    }, 300);

    setTimeout(() => {
        $(".sparkle:nth-of-type(2)").animate({"opacity": "1"}, {"duration":"400"});
    }, 350);



    return (
        <>

            <img id="priscilla" style={{zIndex:"1000", transition:"transform 1000ms", visibility:"hidden", position:"fixed", rotate:"20deg", height:"300px", bottom:"-300px", left:"-100px"}} src="/assets/eastereggs/priskilla.png" alt="" />

            <Head title="Tere tulemast" />
            <div className="home-navbar">
                <div className="title-div">
                    <ApplicationLogo height={50} />
                    <p className="title">Reaaler</p>
                </div>

                <a href={route("login")}>Logi sisse</a>
            </div>
            <SizedBox height={36} />
            <div className="main-content">
                <h1><span style={{color:"rgb(var(--primary-color))"}}>Reaaler</span> muudab <br />matemaatika <span className="shine" onClick={()=>setClickEaster2((e)=>e+1)}>säravaks<img className="sparkle " src="/assets/homepage/sparkle.png" alt="" /><img className="sparkle " src="/assets/homepage/sparkle.png" alt="" /><img className="sparkle " src="/assets/homepage/sparkle.png" alt="" /></span></h1>

                <button className="onboarding-btn" onClick={()=>window.location.href = route("register")}> <span translate="no" className="material-icons">calculate</span> Alusta kohe</button>
                &nbsp;&nbsp;<a alone="" href={route("login")}>Logi sisse&nbsp;&nbsp;<span className="material-icons" translate="no">arrow_right_alt</span></a>
            </div>

            <img className="game-img" src={window.localStorage.getItem("app-theme") == "dark" ? "/assets/homepage/game-dark.png" : "/assets/homepage/game.png"} />
        </>
    );
}