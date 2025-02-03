export default function Title({title, subtitle="Muusika kuulamine"}){
   return <a href={route("music")} style={{all:"unset", cursor:"pointer"}}>
        <div className="title-container">
                <h1 className="title">{title}</h1>
                <p className="type">{subtitle}</p>
        </div>
   </a>;
}