import { useState } from "react";

export default function NumberInput(props){

    const [value, setValue] = useState(props.default.toString().replace(".", ","));

    function increment(){
        if(isNaN(value) || value.length == 0){
            setValue(1);

            if(props.onChange != null){
                props.onChange(1);
            }
            return;
        }
        setValue(parseInt(value) + 1);


        if(props.onChange != null){
            props.onChange(parseInt(value) + 1);
        }
    }

    function decrement(){
        if(isNaN(value) || value.length == 0){
            setValue(0);
            return;
        }
        setValue(parseInt(value) - 1);


        if(props.onChange != null){
            props.onChange(parseInt(value) - 1);
        }
    }

    function handleChange(e){
        setValue(e.target.value);

    }

    return (
        <div className="number-select">
            <span translate="no" style={{marginTop:"-2px"}} onClick={decrement} className="number-edit-btn remove material-icons no-anim">remove</span>
            <span translate="no" style={{marginTop:"-2px"}} onClick={increment} className="number-edit-btn add material-icons no-anim">add</span>
        
            <input style={{marginTop:"0"}} {...props} type="text" inputMode="numeric" value={value === undefined || value.length == 0 ? '' : value} onChange={handleChange} />
        </div>
    );
}