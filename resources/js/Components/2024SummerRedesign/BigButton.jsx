export default function BigButton({title, subtitle, disabled=false, onClick}){

    return <div onClick={onClick} disabled={disabled} className="section tile-selected clickable">
            <div className="two-row-text-button">
                <div>
                    <p>{title}</p>
                    <p>{subtitle}</p>
                </div>
            </div>
        </div>

}