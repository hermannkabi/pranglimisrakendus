import { useState } from "react";

export default function NumberInput(props){

    const [value, setValue] = useState();

    function increment(){
        if(isNaN(value) || value.length == 0){
            setValue(0);
            return;
        }
        setValue(parseInt(value) + 1);
    }

    function decrement(){
        if(isNaN(value) || value.length == 0){
            setValue(0);
            return;
        }
        setValue(parseInt(value) - 1);
    }

    function handleChange(e){
        setValue(e.target.value);
    }

    return (
        <div className="number-select">
            <span onClick={decrement} className="number-edit-btn remove material-icons no-anim">remove</span>
            <span onClick={increment} className="number-edit-btn add material-icons no-anim">add</span>
        
            <input {...props} type="text" pattern="-?[0-9]+" inputMode="numeric" value={value === undefined || value.length == 0 ? '' : value} onChange={handleChange} />
        </div>
    );
}