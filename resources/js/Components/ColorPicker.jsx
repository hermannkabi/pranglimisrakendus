export default function ColorPicker({color, currentColor, onChange}){



    return <>
        <div onClick={()=>onChange(color)} style={{display:"flex", justifyContent:"center", alignItems:"center", backgroundColor: (color == "default" || color == null ? "rgb(53, 81, 80)" : "rgb("+color+")"), aspectRatio:'1', height:"30px", borderRadius:"4px"}}>
            {currentColor == color && <i className="material-icons" style={{color:"white"}}>check</i>}
        </div>
    </>;
}