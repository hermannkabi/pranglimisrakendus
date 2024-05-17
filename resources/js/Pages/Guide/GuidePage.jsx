import Navbar from "@/Components/Navbar";
import SizedBox from "@/Components/SizedBox";
import { Head } from "@inertiajs/react";
import "/public/css/guide.css";
import HorizontalRule from "@/Components/HorizontalRule";

export default function GuidePage({auth}){
    return <>
        <Head title="Kuidas mängida?" />
        {auth.user != null && <Navbar title="Kuidas mängida" user={auth.user} />}

        <SizedBox height={36} />

        <h2>Kuidas mängida?</h2>

        <section id="liitmine">
            <div className='header-container'>
                <h3 className='section-header'>Liitmine</h3>
            </div>

            <div className="guide-div">
                <h4>Ühelised</h4>
                <p className="before-example">Alguseks on hea teha sõrmede peal.</p>
                <span className="example">3 + 4 = 3 sõrme + 4 sõrme = 5 sõrme + 2 sõrme = 7 sõrme = 7</span>
                <p className="before-example">Kui liitmine jäeb kümneliste piirile, siis esimesena liitge ühele arvule piisav summa, et jõuaksite kümneni ja siis ülejäänu.</p>
                <span className="example">7+8 = 7+3+5 = 10 + 5 = 15</span>
            </div>

            <div className="guide-div">
                <h4>Kümnelised</h4>
                <p className="before-example">Esmalt jätke meelde mitu kümnelist teil on, siis tehke ühelistega tehe.</p>
                <span className="example">37+35 = (30+30) + (7+5) = 60+12 = 72</span>
            </div>

            <div className="guide-div">
                <h4>Sajalised ja suuremad</h4>
                <p className="before-example">Sajaliste ja suurtemate arvudega tuleb jätta meelde kõige suuremad arvuhulgad, siis järjest väiksemad.</p>
                <span className="example">430+146 = 400+100 + 30+40 + 0+6 = 500 + 70 + 6 = 576</span>
            </div>
        </section>

        <section id="korrutamine">
            <div className='header-container'>
                <h3 className='section-header'>Korrutamine</h3>
            </div>

            <div className="guide-div">
                <h4>Põhiline</h4>
                <p className="before-example">Korrutamine on veidikene raskem liitmine. Vaatame mõnda näidet:</p>
                <span className="example">3·4 = 4+4+4 = 12, samas ka 3·4 = 3+3+3+3 = 12</span>
                <p className="before-example">Üldreegel:</p>
                <span className="example">tegur · tegur = korrutis</span>
                <p className="before-example">Seega näitab üks tegur mitu korda peab teist tegurit liitma iseendaga.</p>
            </div>
        </section>

        <section id="astendamine">
        <div className='header-container'>
                <h3 className='section-header'>Astendamine</h3>
            </div>

            <div className="guide-div">
                <h4>Põhiline</h4>
                <p className="before-example">Astendamine on iseendaga korrutamine. Esmalt vaatame, kuidas astendamine käib:</p>
                <span className="example">3<sup>2</sup> = 3 · 3 = 9</span>
                <span className="example">5<sup>4</sup> = 5 · 5 · 5 · 5 = 625</span>
                <p className="before-example">Üritame nüüd astendada arvu 1. Teeme näiteks järgmise tehte</p>
                <span className="example">1<sup>3</sup> = 1 · 1 · 1 = 1</span>
                <p className="before-example">Märkame, et kui palju me ka ühte iseendaga ei korrutaks, on vastuseks ikka 1. Seega: <b>Üks astmes mis tahes arv on üks!</b></p>
                <p className="before-example">Mis aga juhtuks, kui astendada arvuga 0? Kuidas saaks üldse mingit arvu iseendaga 0 korda korrutada? Selgub, et sellel tehtel on loogiline vastus. Vaatleme järgmisi tehteid:</p>
                <span className="example">6<sup>3</sup> = 6 · 6 · 6 = 216</span><br />
                <span className="example">6<sup>2</sup> = 6 · 6 = 36</span><br />
                <span className="example">6<sup>1</sup> = 6 = 6</span>
                <p className="before-example">Nähtub, et iga kord, kui astendame ühe võrra väiksema arvuga, on vastus 6 korda väiksem. Seega loogiliselt peaks nulliga astendamine olema sisuliselt iseendaga jagamine ehk vastuseks peaks olema üks. <b>Mis tahes arv astmes null on üks!</b></p>
            </div>
        </section>

        <section id="jaguvus">
            <div className='header-container'>
                <h3 className='section-header'>Jaguvusseadused</h3>
            </div>

            <div className="guide-div">
                <table>
                    <thead>
                        <th>Millega jagub</th>
                        <th>Jaguvustunnus</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2</td>
                            <td>
                                <p className="before-example">Arvu üheliste number peab olema paarisarv</p>
                                <span className="example">Paarisarvud on 0, 2, 4, 6 ja 8</span>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>
                                <p className="before-example">Arvu ristsumma peab jaguma kolmega</p>
                                <span className="example">Ristsumma on arvu kõikide numbrite summa</span>
                            </td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>
                                <p className="before-example">Arvu kahest viimasest numbrist moodustunud arv peab jaguma neljaga</p>
                                <span className="example">5<b>24</b> jagub neljaga, sest <b>24</b> jagub neljaga</span>
                            </td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>
                                <p className="before-example">Arvu üheliste number peab olema 0 või 5</p>
                                <span className="example">6320 jagub viiega, sest see lõppeb numbriga 0</span>
                            </td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>
                                <p className="before-example">Arv peab jaguma nii kahe kui kolmega</p>
                                <span className="example">630 jagub kuuega, sest see jagub kahega ja kolmega</span>
                            </td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td>
                                <p className="before-example">Arvu kolmest viimasest numbrist moodustatud arvu ja ülejäänud numbritest moodustatud arvu vahe jagub seitsmega.</p>
                                <span className="example">251 321 jagub 7-ga, sest vahe 321 – 251 = 70 jagub 7-ga</span>
                            </td>
                        </tr>
                        <tr>
                            <td>8</td>
                            <td>
                                <p className="before-example">Arvu kolmest viimasest numbrist moodustatud arv jagub kaheksaga</p>
                                <span className="example">7128 jagub 8-ga, sest 128 jagub 8-ga</span>
                            </td>
                        </tr>
                        <tr>
                            <td>9</td>
                            <td>
                                <p className="before-example">Arvu ristsumma jagub üheksaga</p>
                                <span className="example">2736 jagub 9-ga, sest 2+7+3+6=18 ja 18 jagub 9-ga</span>
                            </td>
                        </tr>
                        <tr>
                            <td>10</td>
                            <td>
                                <p className="before-example">Arvu üheliste number on null</p>
                                <span className="example">8920 jagub 10-ga, sest lõppeb 0-ga</span>
                            </td>
                        </tr>
                        <tr>
                            <td>11</td>
                            <td>
                                <p className="before-example">Arvu kolmest viimasest numbrist moodustatud arvu ja ülejäänud numbritest moodustatud arvu vahe jagub 11-ga</p>
                                <span className="example">211 112 jagub 11-ga, sest vahe 211 – 112 = 99 jagub 11-ga</span>
                            </td>
                        </tr>
                        <tr>
                            <td>12</td>
                            <td>
                                <p className="before-example">Arv jagub nii 3 kui 4-ga</p>
                                <span className="example">624 jagub 12-ga, sest jagub nii 3-ga kui 4-ga</span>
                            </td>
                        </tr>
                        <tr>
                            <td>13</td>
                            <td>
                                <p className="before-example">Arvu kolmest viimasest numbrist moodustatud arvu ja ülejäänud numbritest moodustatud arvu vahe jagub 13-ga</p>
                                <span className="example">32 123 jagub 13-ga, sest 123 – 32 = 91 jagub 13-ga</span>
                            </td>
                        </tr>
                        <tr>
                            <td>18</td>
                            <td>
                                <p className="before-example">Arv jagub nii 2 kui 9-ga</p>
                                <span className="example">2736 jagub 18-ga, sest jagub nii 2-ga kui 9-ga</span>
                            </td>
                        </tr>
                        <tr>
                            <td>25</td>
                            <td>
                                <p className="before-example">Arvu kaks viimast numbrit on nullid või moodustavad arvu 25, 50 või 75</p>
                                <span className="example">218 975 jagub 50-ga, sest lõppeb 75-ga</span>
                            </td>
                        </tr>
                        <tr>
                            <td>50</td>
                            <td>
                                <p className="before-example">Arvu kaks viimast numbrit on nullid või moodustavad arvu 50</p>
                                <span className="example">6 387 250 jagub 50-ga, sest lõppeb 50-ga</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section id="võrdlemine">
            <div className='header-container'>
                <h3 className='section-header'>Võrdlemine</h3>
            </div>

            <p className="before-example">Esmalt tuleb leida korrutamis- või jagamistehe. Seejärel saab oletada, kumb pooltest on suurem. Madalamate tasemete korral tuleb liitmis- ja lahutamistehted välja arvutada. Vaatleme näidet:</p>
            <span className="example">8+9 <span style={{fontSize:"0.8em"}}>?</span> 6:2</span>
            <p className="before-example">8+9 peab olema suurem, sest 6 jagada ükskõik mis arvuga on väiksem kui 8.</p>
            <p className="before-example">Kõrgemates tasemetes saab juba oletada</p>
            <span className="example">17+24 <span style={{fontSize:"0.8em"}}>?</span> 408:24</span>
            <p className="before-example">Mõelda saab näiteks nii:</p>
            <span className="example">17+24 = üle 40</span><br />
            <span className="example">408 : 24  = vähem kui 20, sest 24 · 20 &gt; 408 </span><br />
            <span className="example"><b>Järelikult peab vasak pool olema suurem</b></span>
        </section>

        <section id="kujundid">
            <div className='header-container'>
                <h3 className='section-header'>Kujundid</h3>
            </div>

            <p className="before-example">Mida täpsemalt vaatad, seda vähem näed. Soovitan jälgida kõiki kujundeid kui tervikut, mitte keskenduda igaühele eraldi.</p>
            <img style={{height:"200px"}} src="/assets/guide/kujundid1.png" alt="" />
            <p className="before-example">Esmalt tean, et kujundid on moodustavad ruudu ehk iga külg on võrdne. Seega kui näen silmanurgast, et ülemise rea vasakpoolne, alumise rea vasakpoolne ja keskmine kujund on suurem ja nurgeline, siis tean, et see on ruut. Ehk tunnen mingi eripära järgi kujundi ära.</p>
            <span className="example">Kujundeid kokku 3 · 3 = 9</span><br />
            <span className="example">Hindan ülevaates, et kokku on 3 kujundit. Täpsemalt, näen ruutu üleval vasakul nurgas ja hiljem kahte ruutu all. Seega kokku on ruute 1+2=3.</span><br />

            <img style={{height:"200px"}} src="/assets/guide/kujundid2.png" alt="" />
            <p className="before-example">Raskemates tasemetes tuleb hinnata kujunditest moodustunud ruudu suurust. Seejärel käia silmaga üle kujundite hulk. Piisavalt harjutades õpid silmaga hindama kujundite hulka.</p>
            <span className="example">Kujundeid pildil on 5 · 5 = 25</span><br />
            <span className="example">Otsin nurgelist suurt kujundit ehk ruutu.</span><br />
            <span className="example">Esmalt näen alumistes nurkades kahte ruutu. Hiljem vasakul üleval nurgas kahte ruutu. Viimaks alles paremal keskel kahte.</span><br />
        </section>
    </>;
}