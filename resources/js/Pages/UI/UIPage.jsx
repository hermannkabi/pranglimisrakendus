import LoadingSpinner from "@/Components/LoadingSpinner";
import LoginHeader from "@/Components/LoginHeader";
import NumberInput from "@/Components/NumberInput";
import { Head } from "@inertiajs/react";

export default function UIPage(){
    return (
        <>
            <Head title="Kasutajaliides" />
            <section>
                <LoginHeader pageName="Kasutajaliides" description="Siin on erinevad kasutajaliidese elemendid" topMargin="20px" />
            </section>
            <section id="btns">
                <section>
                    <h1>Nupud</h1>
                </section>
                <h3>Peamine nupp</h3>
                <button>Peamine nupp</button>
                <button><span className="material-icons">mouse</span> Ikooniga nupp</button>
                <button><LoadingSpinner /> Laadimisega nupp</button>
                <h3>Sekundaarne nupp</h3>
                <button secondary="true">Sekundaarne nupp</button>
                <button secondary="true"><span className="material-icons">mouse</span> Ikooniga nupp</button>
                <button secondary="true"><LoadingSpinner /> Laadimisega nupp</button>
            </section>

            <section>
                <section>
                    <h1>Lingid</h1>
                </section>
                <h3>Tekstisisene link</h3>
                <p>Lorem ipsum dolor sit <a href="">amet</a> consectetur adipisicing elit. A, hic.</p>
                <h3>Eraldiseisev link</h3>
                <a href="" alone="true">Link <span class="material-icons">arrow_forward</span> </a>
                <a href="" alone="true">Link</a>
            </section>
            <section>
                <section>
                    <h1>Sisendiväljad</h1>
                </section>
                <h3>Tekstiväli</h3>
                <input type="text" placeholder="Kirjuta midagi..." />
                <input type="text" placeholder="Muutmatu..." disabled="true" />
                <h3>Numbriväli</h3>
                <NumberInput placeholder="Number siia..." />
                <NumberInput placeholder="Ei tööta..." disabled="true" />
                <h3>Rippmenüü</h3>
                <select>
                    <option>Lorem</option>
                    <option>Ipsum</option>
                    <option>Dolor</option>
                </select>
            </section>
        </>
    );
}