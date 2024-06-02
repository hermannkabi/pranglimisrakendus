export default function BigGameButton({symbol, text, value}){
    return (
        <div translate="no" className="big-btn" style={{position:"relative"}} >
                <a style={{all:"unset", cursor:"pointer", position:"absolute", top:"0", left:"0", height:"100%", width:"100%"}} href={route("preview") + "?id="+value}></a>
            <img className="symbol" style={{position:"absolute"}} src={"/assets/icons/" + symbol + ".svg"} height={60} alt="" />
            <span className="big-btn-text" style={{fontSize:"1.1rem"}}>{text}</span>
        </div>            
    );
}