export default function SmallGameButton({icon, text, iconStyle, all, value}){

    return (
        <div style={{textAlign:"start", margin:"16px 8px"}}>
            <a href={route("preview") + (value ? "?id="+value : "")} alone="true" >{text}</a>
        </div>
    );
}