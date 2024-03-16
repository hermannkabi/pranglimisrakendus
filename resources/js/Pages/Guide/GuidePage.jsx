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

        <section>
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
        </section>

        <section>
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
                    </tbody>
                </table>
            </div>
        </section>
    </>;
}