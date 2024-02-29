import "/public/css/color_picker.css";

export default function ColorPicker({color, currentColor, onChange}){



    return <>
        <div onClick={()=>onChange(color)} style={{backgroundColor: (color == "default" || color == null ? "rgb(53, 81, 80)" : "rgb("+color+")")}} className={"color-picker"+(currentColor == color ? " selected" : "")}>

        </div>
    </>;
}