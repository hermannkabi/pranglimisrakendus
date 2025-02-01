export default function Title({title, subtitle="Muusika kuulamine"}){
   return <div className="title-container">
            <h1 className="title">{title}</h1>
            <p className="type">{subtitle}</p>
    </div>;
}