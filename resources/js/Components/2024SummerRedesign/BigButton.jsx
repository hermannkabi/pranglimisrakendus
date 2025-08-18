export default function BigButton({title, subtitle, disabled=false, onClick, secondary=false}){

    return <div onClick={onClick} disabled={disabled} className={"section clickable " + (secondary ? "" : "tile-selected")}>
            <div className="two-row-text-button">
                <div>
                    <p>{title}</p>
                    <p>{subtitle}</p>
                </div>
            </div>
        </div>

}