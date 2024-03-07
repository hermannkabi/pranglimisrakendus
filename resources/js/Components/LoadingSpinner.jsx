export default function LoadingSpinner({color=null}){
    var style = color != null ? {borderColor: "rgb(var(--primary-color)) transparent transparent transparent"} : {};
    return <div style={style} className="lds-ring"><div style={style}></div><div style={style}></div><div style={style}></div><div style={style}></div></div>;
}