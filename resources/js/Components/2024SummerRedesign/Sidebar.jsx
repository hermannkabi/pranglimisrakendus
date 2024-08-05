import ApplicationLogo from "../ApplicationLogo";
import SizedBox from "../SizedBox";
import GameSectionDropdown from "./GameSectionDropdown";

export default function Sidebar({title}){

    function toggleMobileMenu(){
        $(".mobile-navigation-content").slideToggle(200);
        $("#mobile-hamburger-menu").text($("#mobile-hamburger-menu").text() == "menu" ? "close" : "menu");
    }

    var lastUsed = JSON.parse(window.localStorage.getItem("last-used") == null || window.localStorage.getItem("last-used").length == 0 ? "[]" : window.localStorage.getItem("last-used"));

    return (<>

    <div className="sidebar-mobile">
        <div className="sidebar-mobile-topbar">
            <a href="/" style={{all:"unset", cursor:"pointer"}}>
                <div className="topbar" style={{display:"flex", flexDirection:"row", alignItems:"center"}}>
                    <ApplicationLogo />
                    <div style={{marginBlock:"0", textAlign:'left'}}>
                        <h2 style={{marginBlock:"0", fontSize:"20px", color:"rgb(var(--text-color))"}}>Reaaler</h2>
                        <p style={{marginBlock:"0", fontSize:"16px"}}>{title}</p>
                    </div>
                </div>
            </a>

            <i onClick={toggleMobileMenu} id="mobile-hamburger-menu" style={{fontSize:'32px'}} className="material-icons">menu</i>
        </div>
        <SizedBox height="8px" />
        <div className="mobile-navigation-content" style={{borderBottom:"1px solid #5A5A5A"}}>
            {lastUsed.length > 0 && <GameSectionDropdown title={"Hiljuti mängitud"} gameTypes={lastUsed}/>}
            <GameSectionDropdown title={"Põhitehted"} gameTypes={["liitmine", "lahutamine", "korrutamine", "jagamine"]}/>
            <GameSectionDropdown title={"Keerulisemad tehted"} gameTypes={["astendamine", "juurimine"]}/>
            <GameSectionDropdown title={"Mitmekülgsed mängud"} gameTypes={["võrdlemine", "lünkamine", "liitlahutamine", "korrujagamine", "astejuurimine"]}/>
            <GameSectionDropdown title={"Hariv ja lõbus"} gameTypes={["kujundid", "murruTaandamine", "jaguvus"]}/>
        </div>
    </div>

    <div className="sidebar" style={{position:"relative", borderRight: "1px solid #5A5A5A", width:"350px", height:"100vh", overflow:"auto", padding:"8px", margin:"-8px", marginRight:"0px", paddingLeft:"24px"}}>
        <SizedBox height="8px" />
        <a href="/" style={{all:"unset", cursor:"pointer"}}>
            <div className="topbar" style={{display:"flex", flexDirection:"row", alignItems:"center"}}>
                <ApplicationLogo />
                <div style={{marginBlock:"0", textAlign:'left'}}>
                    <h2 style={{marginBlock:"0", fontSize:"20px", color:"rgb(var(--text-color))"}}>Reaaler</h2>
                    <p style={{marginBlock:"0", fontSize:"16px"}}>{title}</p>
                </div>
            </div>
        </a>
        <SizedBox height="24px" />
        <div>
            {lastUsed.length > 0 && <GameSectionDropdown title={"Hiljuti mängitud"} gameTypes={lastUsed}/>}
            <GameSectionDropdown title={"Põhitehted"} gameTypes={["liitmine", "lahutamine", "korrutamine", "jagamine"]}/>
            <GameSectionDropdown title={"Keerulisemad tehted"} gameTypes={["astendamine", "juurimine"]}/>
            <GameSectionDropdown title={"Mitmekülgsed mängud"} gameTypes={["võrdlemine", "lünkamine", "liitlahutamine", "korrujagamine", "astejuurimine"]}/>
            <GameSectionDropdown title={"Hariv ja lõbus"} gameTypes={["kujundid", "murruTaandamine", "jaguvus"]}/>
        </div>
    </div>
    </>);
}