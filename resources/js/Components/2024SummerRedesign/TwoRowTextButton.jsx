export default function TwoRowTextButton({upperText, lowerText, showArrow=true, capitalizeLower=false, capitalizeUpper=false}){
    return <div className="two-row-text-button">
                <div>
                    <p style={{textTransform:capitalizeUpper ? "capitalize" : "none"}}>{upperText}</p>
                    <p style={{textTransform:capitalizeLower ? "capitalize" : "none"}}>{lowerText}</p>
                </div>
                {showArrow && <i className="material-icons">arrow_forward_ios</i>}
            </div>;
}