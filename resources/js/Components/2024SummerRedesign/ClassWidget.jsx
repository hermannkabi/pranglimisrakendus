export default function ClassWidget({klass}){
    return  <div className="section clickable" style={{position:"relative", padding:"16px", display:"flex", justifyContent:"start", alignItems:"center", marginBlock:"0"}}>
                <div>
                    <i translate="no" style={{fontSize:"32px"}} className="material-icons-outlined">school</i>
                    <p style={{marginTop:"8px", marginBottom:"0"}}>{klass.klass_name}</p>
                </div>

                <a href={"classroom/"+klass.uuid+"/view"} style={{all:"unset", position:"absolute", top:"0", left:"0", height:"100%", width:"100%"}}></a>
            </div>;
}