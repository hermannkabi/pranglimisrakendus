export default function BigGameButton({symbol, text}){


    return (
            <button style={{paddingBlock:"4px", width:"100%", margin:"8px auto", justifyContent:'space-between'}}>
                <span className="big-btn-texst">{text}</span>
                &nbsp;&nbsp;&nbsp;
                <div className="big-btn-symbol" style={{marginBottom:"4px", lineHeight:'1'}}>{symbol}</div>
            </button>
    );
}