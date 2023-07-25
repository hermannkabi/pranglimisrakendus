import { Head } from "@inertiajs/react";

export default function WelcomePage({name, message}){
    return (
        <>
            <Head title="Avaleht" />

            <h1>Kõik on hästi!</h1>
            <p>Oled jõudnud Pranglimisrakenduse kodulehele. Installatsioon on õnnestunud!!!</p>
            <br /><br />
            <img src="https://i.pinimg.com/originals/8a/d7/e2/8ad7e27c04f68d43526032f33100f0c1.gif" alt="" />

            <br />
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


                <input type="text" name="name" id="" placeholder="Nimi"/><br /><br />
                <textarea type="text" name="message" placeholder="Sõnum" /><br /><br />
                <button type="submit">Edasta</button>
            </form>
        </>
    )
}