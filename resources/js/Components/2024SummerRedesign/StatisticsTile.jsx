import SizedBox from "../SizedBox";

export default function StatisticsTile({stat, label, icon, iconColor=null, disabled=false, oneLabel=null, compactNumber=false, style=null, labelColor=null}){
    return <>
        <div className="section statistics-tile" style={{position:"relative", margin:"0", ...style}}>
            <div>
                <h2 title={stat + " " + label.toLowerCase()} style={{marginBlock:"0", color: labelColor ?? (disabled ? "var(--grey-color)" : "rgb(var(--primary-color))")}}>{compactNumber ? Intl.NumberFormat('en', { notation: 'compact' }).format(stat).toString().replaceAll(".", ",") : stat}</h2>
                <SizedBox height={8} />
                <p style={{marginBlock:"0", color: "var(--grey-color)", fontSize:"18px"}}>{stat == "1" && oneLabel ? oneLabel : label}</p>
            </div>
            
            {!(window.innerWidth > 1000 && window.innerWidth < 1300) && <i translate="no" className="material-icons-outlined" style={{color: iconColor == null || disabled ? "var(--grey-color)" : iconColor, position:"absolute", right:"8px", top:"8px"}}>{icon}</i>}
        </div>
    </>;
}