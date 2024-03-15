import ApplicationLogo from "@/Components/ApplicationLogo";
import LoginHeader from "@/Components/LoginHeader";
import SizedBox from "@/Components/SizedBox";
import { Head } from "@inertiajs/react";
import "/public/css/welcome.css";


export default function WelcomePage(){

    setTimeout(() => {
        $(".sparkle:nth-of-type(1)").animate({"opacity": "1"}, {"duration":"200"});
    }, 0);

    setTimeout(() => {
        $(".sparkle:nth-of-type(3)").animate({"opacity": "1"}, {"duration":"300"});
    }, 100);

    setTimeout(() => {
        $(".sparkle:nth-of-type(2)").animate({"opacity": "1"}, {"duration":"400"});
    }, 200);

    return (
        <>
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
                <h1><span style={{color:"rgb(var(--primary-color))"}}>Reaaler</span> muudab <br />matemaatika <span className="shine">säravaks<img className="sparkle " src="/assets/homepage/sparkle.png" alt="" /><img className="sparkle " src="/assets/homepage/sparkle.png" alt="" /><img className="sparkle " src="/assets/homepage/sparkle.png" alt="" /></span></h1>

                <button className="onboarding-btn" onClick={()=>window.location.href = route("register")}> <span translate="no" className="material-icons">calculate</span> Alusta kohe</button>
                &nbsp;&nbsp;<a alone="" href={route("login")}>Logi sisse&nbsp;&nbsp;<span className="material-icons" translate="no">arrow_right_alt</span></a>
            </div>

            <img className="game-img" src={window.localStorage.getItem("app-theme") == "dark" ? "/assets/homepage/game-dark.png" : "/assets/homepage/game.png"} />
        </>
    );
}