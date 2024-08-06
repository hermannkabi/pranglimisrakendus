export default function TwoRowTextButton({upperText, lowerText, showArrow=true, upperColor=null, lowerColor=null, capitalizeLower=false, capitalizeUpper=false}){
    return <div className="two-row-text-button">
                <div>
                    <p style={{textTransform:capitalizeUpper ? "capitalize" : "none", color: upperColor}}>{upperText}</p>
                    <p style={{textTransform:capitalizeLower ? "capitalize" : "none", color: lowerColor}}>{lowerText}</p>
                </div>
                {showArrow && <i className="material-icons">arrow_forward_ios</i>}
            </div>;
}