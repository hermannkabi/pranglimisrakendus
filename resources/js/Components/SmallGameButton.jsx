export default function SmallGameButton({text, all}){
    return (
        <>
            <button className="small-btn" secondary="true" style={{outlineWidth: (all ? "3px" : "2px"), fontWeight:(all ? "bold" : "normal")}}>
            {text}&nbsp;{all && <span className="material-icons">arrow_right_alt</span>}
            </button>
        </>
    );
}