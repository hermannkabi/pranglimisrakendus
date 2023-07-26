import ApplicationLogo from "@/Components/ApplicationLogo";
import LoginHeader from "@/Components/LoginHeader";
import { Head } from "@inertiajs/react";

export default function WelcomePage({name, message}){
    return (
        <>
            <section>
                <Head title="Avaleht" />
                <LoginHeader topMargin="16px" pageName="Kõik on hästi!" description="Installatsioon on õnnestunud!!!"/>
                <br />
                <img height="200px" src="https://i.pinimg.com/originals/8a/d7/e2/8ad7e27c04f68d43526032f33100f0c1.gif" alt="" />

                </section>

                <br />
            <section>
                <p>Siin saad proovida lihtsat vormi ja näha, kuidas front-end (React.js) back-endiga (Laravel) suhtleb</p>
                {name && message ?
                <>
                    <p>Nimi: <b>{name}</b></p>
                    <p>Sõnum: <b>{message}</b></p> 
                </>:
                <p>Sisesta midagi</p>
                }

                <form action={route('saveItem')} method="post">
                    <input type="hidden" name="_token" value={window.csrfToken} />
                    <input type="text" name="name" id="" placeholder="Nimi"/><br />
                    <textarea type="text" name="message" placeholder="Sõnum" /><br />
                    <button secondary="true" type="submit">Edasta</button>
                </form>
            </section>
            
        </>
    )
}