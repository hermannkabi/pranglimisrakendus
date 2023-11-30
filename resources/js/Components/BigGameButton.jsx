export default function BigGameButton({symbol, text, value}){

    function navigateToPreview(){
        window.location.href=route("preview") + "?id="+value;
    }

    return (
            // <button onClick={navigateToPreview} style={{paddingBlock:"4px", width:"100%", margin:"8px auto", justifyContent:'space-between'}}>
            //     <span className="big-btn-texst">{text}</span>
            //     &nbsp;&nbsp;&nbsp;
            //     <div className="big-btn-symbol" style={{marginBottom:"4px", lineHeight:'1'}}>{symbol}</div>
            // </button>

            <div onClick={navigateToPreview} className="big-btn">
                <span style={{fontWeight:"bold", fontSize:"2rem"}}>{symbol}</span>
                <br />
                <span style={{fontWeight:"bold", fontSize:"1.2rem"}}>{text}</span>
            </div>
    );
}