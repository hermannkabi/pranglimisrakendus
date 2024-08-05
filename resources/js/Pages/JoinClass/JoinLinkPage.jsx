import BigButton from "@/Components/2024SummerRedesign/BigButton";
import Layout from "@/Components/2024SummerRedesign/Layout";
import SizedBox from "@/Components/SizedBox";

export default function JoinLinkPage({auth, klass, current_klass, invited_by}){

    function acceptInvitation(){
        $.ajax({
            url:"/classroom/" + klass.uuid + "/join",
            type:"post",
            data: {
                "_token":window.csrfToken,
            },
        }).done(function (data){
            window.location.href = route("dashboard");
        }).fail(function (data){
            console.log(data);
        });
    }

    return <>
        <Layout title="Kutse klassiga liitumiseks">
            <div className="two-column-layout">
                <div>
                    <div className="section">
                        <h2 style={{color:"rgb(var(--primary-color))", fontSize:"56px", marginTop:"0"}}>{klass.klass_name}</h2>
                        <SizedBox height="8px" />
                        <p style={{maxWidth:"max(40%, 300px)"}}><span style={{fontWeight:"bold", textTransform:"capitalize"}}>{invited_by}</span> kutsus sind selle klassiga ühinema</p>
                        <SizedBox height="24px" />
                        {current_klass == null || current_klass.klass_id != klass.klass_id && <p style={{color:"var(--lightgrey-color)"}}>Korraga saad olla ühes klassis. Kui sa selle klassiga liitud, eemaldatakse sind sinu praeguse klassi nimekirjast!</p>}
                        {!(current_klass == null || current_klass.klass_id != klass.klass_id) && <p style={{color:"var(--lightgrey-color)"}}>Korraga saad olla ühes klassis. Kui sa oled klassis ja ühined uuega, eemaldatakse sind automaatselt vana klassi nimekirjast!</p>}
                    </div>
                </div>

                <div>
                    <BigButton title="Ühine klassiga" subtitle={klass.klass_name} onClick={acceptInvitation} />
                </div>
            </div>
        </Layout>
    </>;
}