export default function BigGameButton({symbol, text, value}){
    return (
        <div translate="no" className="big-btn" style={{position:"relative"}} >
                <a style={{all:"unset", cursor:"pointer", position:"absolute", top:"0", left:"0", height:"100%", width:"100%"}} href={route("preview") + "?id="+value}></a>
            {/* <img className="symbol" style={{position:"absolute", backgroundColor:"rgb(var(--primary-color))", mask:"url(/assets/icons/"+symbol+".svg) no-repeat center"}} src={"/assets/icons/" + symbol + ".svg"} height={60} alt="" /> */}
            <div className="symbol" style={{backgroundColor:"rgb(var(--primary-color))", mask:"url(/assets/icons/"+symbol+".svg) no-repeat center"}}></div>
            <span className="big-btn-text" style={{fontSize:"1.1rem"}}>{text}</span>
        </div>            
    );
}