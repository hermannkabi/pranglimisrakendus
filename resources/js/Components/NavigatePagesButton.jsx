export default function NavigatePagesButton({data}){
    return <>
        <a style={{all:"unset", cursor:"pointer"}} href={data.url}>
            <div style={{aspectRatio:"1", borderRadius:"6px", backgroundColor:data.active ? "rgb(var(--primary-color))" : "transparent", display:"inline-flex", height:"32px", justifyContent:"center", alignItems:"center", color: data.active ? "white" : "var(--primary-header-color)", border: ("2px solid " + (data.active ? "rgb(var(--primary-color))" : "var(--primary-header-color)")), margin:"2px 4px"}}>
                <span style={{fontWeight:"bold"}}>{data.label}</span>
            </div>
        </a>
    </>;
}