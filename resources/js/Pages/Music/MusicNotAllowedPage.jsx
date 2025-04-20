import { Head } from "@inertiajs/react";
import "/public/css/muusika.css";
import Title from "@/Components/Music/Title";
import SizedBox from "@/Components/SizedBox";

export default function MusicHomePage({auth}){
    
    return <div style={{minHeight:"100vh"}}>
        <Head title="Ligipääs keelatud | Muusika kuulamine" />
        <Title title="Ligipääs keelatud" showBack={false} />
        <SizedBox height={32} />
        <div style={{marginLeft:"32px"}}>
        <p style={{width:Math.max(500, window.innerWidth*0.5), textAlign:"left"}}>Ligipääs muusika kuulamiskavadele on keelatud. Allpool on põhjus(ed), miks sulle ligipääsu ei võimaldata:</p>
        <ul>
            {auth.user.role == "guest" && <li style={{width:Math.max(500, window.innerWidth*0.5), textAlign:"left"}}>Kasutad külaliskontot. Juriidilistel põhjustel ei võimaldata külaliskontoga ligipääsu kuulamiskavadele.</li>}
            {auth.user.email_verified_at == null && auth.user.google_id == null && <li style={{width:Math.max(500, window.innerWidth*0.5), textAlign:"left"}}>E-posti aadress ei ole kinnitatud. Tee seda <a alone="" href="/profile">siin</a>.</li>}
            {!(auth.user.role == "guest") && !(auth.user.email_verified_at == null || auth.user.google_id) && <li>Tundmatu viga. Palun võta ühendust veebilehe halduriga.</li> }
        </ul>
        </div>
    </div>;
}