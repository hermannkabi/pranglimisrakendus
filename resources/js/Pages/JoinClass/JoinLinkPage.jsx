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
            console.log("Tehtud!");
            window.location.href = route("dashboard");
        }).fail(function (data){
            console.log(data);
        });
    }

    return <>
        <Head title='Kutse klassiga liitumiseks' />
        <Navbar user={auth.user} title="Kutse liitumiseks" />
        <SizedBox height={36} />

        <h2>Kutse</h2>
        {(current_klass == null || current_klass.klass_id != klass.klass_id) && <section>
            {current_klass != null && <InfoBanner text={"Oled hetkel klassi "+current_klass.klass_name+" nimekirjas. Kui võtad kutse vastu, eemaldatakse sind sellest klassist."} />}

            <p><span style={{textTransform:"capitalize"}}>{invited_by}</span> kutsus sind ühinema klassiga <b>{klass.klass_name}</b>. Kutse vastuvõtmiseks vajutage allolevat nuppu</p>

            <button onClick={acceptInvitation}>Liitu klassiga</button>
        </section>}

        {current_klass != null && current_klass.klass_id == klass.klass_id && <section>
            <p>Sa juba oled selles klassis!</p>
        </section>}
    </>;

}