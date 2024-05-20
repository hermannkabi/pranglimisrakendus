export default function BigGameButton({symbol, text, value}){
    return (
            // <button onClick={navigateToPreview} style={{paddingBlock:"4px", width:"100%", margin:"8px auto", justifyContent:'space-between'}}>
            //     <span className="big-btn-texst">{text}</span>
            //     &nbsp;&nbsp;&nbsp;
            //     <div className="big-btn-symbol" style={{marginBottom:"4px", lineHeight:'1'}}>{symbol}</div>
            // </button>

                <div translate="no" className="big-btn" style={{position:"relative"}} >
                     <a style={{all:"unset", cursor:"pointer", position:"absolute", top:"0", left:"0", height:"100%", width:"100%"}} href={route("preview") + "?id="+value}></a>

                    <span className="symbol big-btn-symbol" style={{fontWeight:"normal", color:"#ffffff77"}}>{symbol}</span>
                    <span className="big-btn-text" style={{fontWeight:"bold", fontSize:"1.1rem"}}>{text}</span>
                </div>            
    );
}