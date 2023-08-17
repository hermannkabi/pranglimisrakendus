export default function SmallGameButton({icon, text, iconStyle, all, value}){

    let arrow = <span className="material-icons">arrow_right_alt</span>;

    return (
        <div style={{textAlign:"start", margin:"16px 8px"}}>
            <a style={{fontWeight: all && "bold"}} href={route("preview") + (value ? "?id="+value : "")} alone="true" >{text} {all && arrow}</a>
        </div>
    );
}