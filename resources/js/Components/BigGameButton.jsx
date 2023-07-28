export default function BigGameButton({symbol, text}){


    return (
        <button className="big-btn">
            <div className="big-btn-symbol">{symbol}</div>
            <span className="big-btn-text">{text}</span>
        </button>
    );
}