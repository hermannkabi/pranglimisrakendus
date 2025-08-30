export default function WelcomeTile({icon, title, message}){
    return <div className="about-tile">
                <div className="title">
                    <div style={{backgroundColor:"rgba(var(--primary-color), 0.1)", padding:"4px", display:"flex", borderRadius:"4px"}}>
                        <i style={{color:"rgb(var(--primary-color))"}} className="material-icons">{icon}</i>
                    </div>
                    <h3>{title}</h3>
                </div>
                <p>{message}</p>
            </div>;
}