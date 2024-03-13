export default function NavigatePagesButton({data}){
    return <>
        <a style={{all:"unset", cursor:"pointer"}} href={data.url}>
            <div style={{aspectRatio:"1", borderRadius:"6px", backgroundColor:"rgb(var(--section-color), var(--section-transparency))", display:"inline-flex", height:"32px", justifyContent:"center", alignItems:"center", color:"rgb(var(--primary-color))", border: data.active ? "2px solid rgb(var(--primary-color))" : "", margin:"2px 4px"}}>
                <span>{data.label}</span>
            </div>
        </a>
    </>;
}