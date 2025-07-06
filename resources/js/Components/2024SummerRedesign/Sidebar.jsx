import ApplicationLogo from "../ApplicationLogo";
import HorizontalRule from "../HorizontalRule";
import SizedBox from "../SizedBox";
import GameSectionDropdown from "./GameSectionDropdown";

export default function Sidebar({title, auth}){

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

            <div>
                <i onClick={toggleMobileMenu} id="mobile-hamburger-menu" style={{fontSize:'32px', marginLeft: "8px"}} className="material-icons">menu</i>
            </div>
        </div>
        <SizedBox height="8px" />
        <div className="mobile-navigation-content">
            <div>
                {lastUsed.length > 0 && <GameSectionDropdown title={"Hiljuti mängitud"} gameTypes={lastUsed}/>}
                <GameSectionDropdown title={"Põhitehted"} gameTypes={["liitmine", "lahutamine", "korrutamine", "jagamine"]}/>
                <GameSectionDropdown title={"Keerulisemad tehted"} gameTypes={["astendamine", "juurimine"]}/>
                <GameSectionDropdown title={"Mitmekülgsed mängud"} gameTypes={["võrdlemine", "lünkamine", "liitlahutamine", "korrujagamine", "astejuurimine"]}/>
                <GameSectionDropdown title={"Hariv ja lõbus"} gameTypes={["kujundid", "murruTaandamine", "jaguvus"]}/>
                <GameSectionDropdown isNew={true} title={"Muu kui matemaatika"} gameTypes={["muusika", "valimised"]} />
            </div>
            {auth != null && auth.user != null && <a style={{all:"unset"}} href={route("profilePage")}><div className="clickable section account-tile" style={{display:"flex", alignItems:"center", gap:"12px", justifyContent:"stretch", margin:"0", marginBlock:"8px"}}>
                <img style={{objectFit:"cover", borderRadius:"50%", aspectRatio:"1", height:"50px"}} src={auth.user.profile_pic} alt="" />
                <div style={{textAlign:"start"}}>
                    <p style={{textTransform:"capitalize", marginBottom:"0"}}>{auth.user.eesnimi} {auth.user.perenimi}</p>
                    <p style={{color:"var(--grey-color)", marginTop:"0"}}>Tallinna Reaalkool</p>
                </div>
            </div></a>}
            <SizedBox height="8px" />
            <div style={{borderBottom:"1px solid #5A5A5A", width:"100%"}}>

            </div>
        </div>
        
    </div>

    <div className="sidebar" style={{position:"relative", borderRight: "1px solid #5A5A5A", width:"350px", height:"100vh", overflow:"auto", padding:"8px", margin:"-8px", marginRight:"0px", paddingLeft:"16px"}}>
        <SizedBox height="8px" />
        <a href="/" style={{all:"unset", cursor:"pointer"}}>
            <div className="topbar" style={{marginLeft:"8px", display:"flex", flexDirection:"row", alignItems:"center"}}>
                <ApplicationLogo />
                <div style={{marginBlock:"0", textAlign:'left'}}>
                    <h2 style={{marginBlock:"0", fontSize:"20px", color:"rgb(var(--text-color))"}}>Reaaler</h2>
                    <p style={{marginBlock:"0", fontSize:"16px"}}>{title}</p>
                </div>
            </div>
        </a>
        <SizedBox height="24px" />
        <div style={{marginLeft:'8px'}}>
            {lastUsed.length > 0 && <GameSectionDropdown title={"Hiljuti mängitud"} gameTypes={lastUsed}/>}
            <GameSectionDropdown title={"Põhitehted"} gameTypes={["liitmine", "lahutamine", "korrutamine", "jagamine"]}/>
            <GameSectionDropdown title={"Keerulisemad tehted"} gameTypes={["astendamine", "juurimine"]}/>
            <GameSectionDropdown title={"Mitmekülgsed mängud"} gameTypes={["võrdlemine", "lünkamine", "liitlahutamine", "korrujagamine", "astejuurimine"]}/>
            <GameSectionDropdown title={"Hariv ja lõbus"} gameTypes={["kujundid", "murruTaandamine", "jaguvus"]}/>
            <GameSectionDropdown isNew={true} title={"Muu kui matemaatika"} gameTypes={["muusika", "valimised"]} />

        </div>
        {auth != null && auth.user != null && <a style={{all:"unset"}} href={route("profilePage")}><div className="clickable section account-tile" style={{display:"flex", alignItems:"center", gap:"12px", justifyContent:"stretch", marginInline:"0", position:"sticky", bottom:"8px", left:"0", right:"0"}}>
            <img style={{objectFit:"cover", borderRadius:"50%", aspectRatio:"1", height:"50px"}} src={auth.user.profile_pic} alt="" />
            <div style={{textAlign:"start"}}>
                <p style={{textTransform:"capitalize", marginBottom:"0"}}>{auth.user.eesnimi} {auth.user.perenimi}</p>
                <p style={{color:"var(--grey-color)", marginTop:"0"}}>Tallinna Reaalkool</p>
            </div>
        </div></a>}
    </div>
    </>);
}