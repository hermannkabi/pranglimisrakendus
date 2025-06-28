export default function TimeSelector({time, onIncrease, onDecrease, simple=false}){

    function onChange(){
        console.log("erre");
        
    }

    return  <div style={{display:"inline-flex", flexDirection:"row", alignItems:'center', gap:"8px", marginRight:"8px"}}>
                {onIncrease != null && <i translate="no" onClick={onIncrease} style={{color: time >= 10 ? "var(--grey-color)" : "rgb(var(--primary-color))", fontSize:"32px"}} className="material-icons">add</i>}
                <div style={{width:simple ? "50px" : "75px", textAlign:"center", marginBlock:"8px", }}>
                    <h2 style={{marginBlock:"0", color:"rgb(var(--primary-color))", fontSize:"40px"}}>{time == "0" && !simple ? "-" : time.toString().replaceAll(".", ",")}</h2>
                    {!simple && <p style={{color:"var(--grey-color)", marginBlock:"0"}}>min</p>}
                </div>
                {onDecrease != null && <i translate="no" onClick={onDecrease} style={{color: time <= 0 ? "var(--grey-color)" : "rgb(var(--primary-color))", fontSize:"32px"}} className="material-icons">remove</i>}
            </div>;
}