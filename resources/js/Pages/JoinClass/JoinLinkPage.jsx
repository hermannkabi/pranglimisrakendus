import BigButton from "@/Components/2024SummerRedesign/BigButton";
import Layout from "@/Components/2024SummerRedesign/Layout";
import InfoBanner from "@/Components/InfoBanner";
import Navbar from "@/Components/Navbar";
import SizedBox from "@/Components/SizedBox";
import { Head } from "@inertiajs/react";


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
                        <p style={{maxWidth:"40%"}}><span style={{fontWeight:"bold", textTransform:"capitalize"}}>{invited_by}</span> kutsus sind selle klassiga ühinema</p>
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

    return <>
        <Head title='Kutse klassiga liitumiseks' />
        <Navbar user={auth.user} title="Kutse liitumiseks" />
        <SizedBox height={36} />

        <h2>Kutse</h2>
        {(current_klass == null || current_klass.klass_id != klass.klass_id) && <section>
            {current_klass != null && <InfoBanner text={"Oled hetkel klassi "+current_klass.klass_name+" nimekirjas. Kui võtad kutse vastu, eemaldatakse sind sellest klassist."} />}

            <p><span style={{textTransform:"capitalize"}}>{invited_by}</span> kutsus sind ühinema klassiga <b>{klass.klass_name}</b>. Kutse vastuvõtmiseks vajutage allolevat nuppu</p>

            <button style={{width: "min(400px, 100%)"}} onClick={acceptInvitation}>Liitu klassiga</button>
        </section>}

        {current_klass != null && current_klass.klass_id == klass.klass_id && <section>
            <p>Sa juba oled selles klassis!</p>
        </section>}
    </>;

}