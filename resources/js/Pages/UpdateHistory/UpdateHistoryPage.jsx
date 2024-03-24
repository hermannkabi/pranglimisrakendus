import Navbar from "@/Components/Navbar";
import SizedBox from "@/Components/SizedBox";
import { Head } from "@inertiajs/react";

export default function UpdateHistoryPage({auth}){


    const changelog = {
        "25.07.2023":["boldSellel kuupäeval sai rakenduse programmeerimine ametlikult alguse", "Varasemalt olid tehtud esmased versioonid kasutajaliidese disainist (alates 29.06.2023)", "Samuti oli juba toimunud I kohtumine õpetajatega (16.06.2023)", "Ilmselgelt ei olnud sel ajal rakendusel nime ega logo, nende asemel kasutati sõna 'Pranglimisrakendus' ja logona halli kasti kirjaga 'LOGO'"], 
        "26.07.2023":["Esimese vaatena valmis sisselogimisleht", "Tõsi, mingit funktsionaalsust sel polnud"],
        "27.07.2023":["Jätkub front-endi kiire areng", "Valmib esialgne registreerimisleht, algab töölaua esialgse versiooni areng"],
        "31.07.2023":["Töölaua esialgne vaade on nüüd valmis"],
        "01.08.2023":["Esialgne profiili & seadete vaade"],
        "07.08.2023":["Valmib esimene versioon mängu eelvaatest"],
        "08.08.2023":["boldEsimene back-end arendus", "Sellega testitakse pranglimisfunktsionaalsuse loomist (täpsemalt liitmist)"],
        "09.08.2023":["Jätkub töö back-endi kallal"],
        "17.08.2023":["Esialgne mänguvaate front-end", "Sellega saab praegu teha koodi sissekirjutatud tehteid", "Back-endis genereeritud päris tehetega läheb veel aega"],
        "19.08.2023":["Esialgne vaade mängu lõpetamisele", "Loomulikult pole sellel praegu funktsionaalsust"],
        "08.09.2023":["Esialgne katsetus Google sisselogimissüsteemi loomiseks", "Uuendatud tervitusleht", "Kui varasemalt oli seal palju asju, näiteks vormi testimise võimalus, meem ja teksti, siis nüüd muutus see väga lihtsaks", "Kõik, mis seal kuvati, oli logo placeholder, sõna 'Pranglimisrakendus' ja link sisselogimislehele (mis ei töötanud veel)", "Muide, see leht jäi rakendusse veel väga pikaks ajaks", "Kooli algusega seoses hakkasid arendajad tihti koos peale tunde arendama (paarisprogrammeerimine)"],
        "09.09.2023":["Murdude renderdamise ja sisestamise põhiline esimene versioon", "Märksa hiljem tehakse seoses juurimisega pool tööd täiesti ümber"],
        "24.09.2023":["boldNüüd sai esimesi back-endis genereeritud tehteid ka veebilehel testida","Jätkuvalt toimuvad katsetused Google'i süsteemiga integreerimiseks", "Tagantjärele tarkusena on need mõttetud", "Hiljem lahendame probleemi tunduvalt lihtsamalt"],
        "26.09.2023-29.10.2023":["Toimuvad pidevad väikesed uuendused", "Uuendatakse nii sisselogimissüsteemi kui pranglimisosa"],
        "08.11.2023":["boldTasemete lisamise võimalus", "Suure uuendusena lisatakse võimalus valida tasemeid, milles tehteid teha", "Varasemalt läksid tehted lihtsalt (niivõrd-kuivõrd) ühtlaselt üles"],
        "12.11.2023":["boldEsimene mängutüüp lisaks põhitehetele – lünkamine"],
        "13.11.2023-16.11.2023":["Väga palju uuendusi nii front-endis kui algoritmis"],
        "26.11.2023":["boldAlgas kasutajaliidese ümberarendus", "Rakendus sai lihtsama ja ühtlasema välimuse", "Kui varasemalt kasutati fonte Dela Gothic One ja Kanit, siis nüüd läks rakendus ühe fondi - Lexend peale üle", "Samuti uuenes märgatavalt töölaua välimus - varasemalt oli see väga tihe ja segamini, nüüd aga palju parem", "Sarnane töölaud on rakenduses siiamaani"],
        "27.11.2023":["Uuendused nii front-endis kui algoritmis", "Muuhulgas saab lünkamine front-end toe", "Lisandus ka võimalus tehteid vahele jätta"],
        "28.11.2023":["Kohtumine matemaatikaõpetajate Riin Saare ja Helli Juurmaga", "Kasutajaliides sai kiitust, seda kirjeldati kui pehmet ja kutsuvat", "Pakuti välja idee 'Katkesta' nupu jaoks", "Sel ajal olid tasemed väga ebaühtlased ja korrapäratud, millega sellest saati tööle hakati"],
        "29.11.2023":["Punkte saab nüüd tasemepõhiselt, lisaks võetakse aja kulumise eest aega maha", "Esialgne statistika (seda loomulikult kuhugi ei salvestatud)", "Muudatused ja parandused tehtealgoritmis"],
        "30.11.2023":["Algoritmi, mängu eelvaate kasutajaliidese väike muudatus", "Pärast mängu saab nüüd täpset stastikat (sellist, nagu praegugi)", "Vaadates GitHubi, oli tegus päev :)"],
        "03.12.2023":["Algas töö võrdlemise loomisega", "Tasemete valiku võimalus front-endis", "Kommenteeritud kood ja palju veaparandusi"],
        "05.12.2023":["Lünkamine on valmis!"],
        "06.12.2023":["Esialgne front-end tugi võrdlemisele"],
        "10.12.2023":["Parandatud võrdlemise loogika ja front-end välimus"],
        "12.12.2023":["Kohtumine matemaatikaõpetajate Riin Saare ja Helli Juurmaga", "Praegu läks iga sekund punkte vähemaks, aga tuli idee muuta seda nii, et pärast vastamist saaks lihtsalt vähem punkte", "Valmis tabel korrutamise/jagamise tasemete kohta (mis piiridesse tehted jääma peavad)", "Mõte 'ulmetasemetest' - sellest said hiljem tähega tasemed", "Tulevikus peaks kontosse panema lennu numbri, mitte klassi"],
        "13.12.2023":["Kuna rakendus oli piisavalt valmis, et õpilastega katsetada, oli oluline see veebi üles panna", "Kahjuks arendjatel seda kogemust sisuliselt polnud, seega raisati palju aega mõttetute asjade proovimiseks"],
        "27.12.2023":["boldKohtumiselt saadud lisatasemete idee sai teoks – põhitehted said tähega tasemed"],
        "28.12.2023-09.01.2024":["Väga palju väikeseid parandusi", "Muu hulgas valmis valideerimisega registreerimisvaade"],
        "10.01.2024":["Luuakse funktsioon seadete salvestamiseks", "Hetkel on seadmepõhine (localstorage)", "Jällegi, tihe päev, nagu GitHubist näeb!"],
        "11.01.2024-12.01.2024":["Jälle palju back-end koodi parandusi ja uuendusi"],
        "14.01.2024":["Mobiilisõbralikkust suurendavad uuendused", "Näiteks saab murru aktiivset osa vahetada sellele vajutades"],
        "15.01.2024-17.01.2024":["Järjekordselt palju uut", "Palju parandusi liitmisele/lahutamisele, lünkamisele ja üldisele algoritmile", "Loodi 404 leht, võimalus keelata punktianimatsioon jne"],
        "18.01.2024":["boldVõrdlemise esimene töötav versioon on nüüd valmis!"],
        "19.01.2024":["Parandatud võrdlemise kasutajaliide, kiirstatistika (localstorage-põhine)"],
        "20.01.2024-01.02.2024":["Jällegi tihe parandusperiood", "Palju ideid uute mängutüüpide jaoks", "Parandused rakenduse UI/UX-s", "Proovitakse veel kord sisselogimissüsteemi tööle saada"],
        "03.02.2024":["boldValmib uus mängutüüp – kujundite kokkulugemine"],
        "04.02.2024-06.02.2024":["Töö käib sisselogimise kallal"],
        "09.02.2024":["Kohtumine matemaatikaõpetajate Riin Saare ja Helli Juurmaga", "Nüüdseks on valminud sisselogimissüsteem ja põhilised mängutüübid", "Valmis on: võrdlemine, astendamine (väikeste vigadega), murru taandamine, kujundite kokkulugemine, jaguvusseadused"],
        "10.02.2024":["boldPranglimisrakendus jõuab veebi", "Saime lehe kooli serverile üles", "Hetkel on see küll serverita, aga see muutub peagi"],
        "11.02.2024-12.02.2024":["boldAlguse saab uus mängutüüp – astendamine/juurimine", "Algul on probleeme nt juurimisega (tuleb nullis ja esimene juur jne)"],
        "13.02.2024":["Esialgne kaitse arvutiga lahendamise vastu", "Paraku näritakse see kiiresti läbi..."],
        "14.02.2024":["Valentinipäev möödub väikeste uuendustega back-endis"],
        "19.02.2024":["boldKujundite kokkulugemine saab märkimisväärse uuenduse – nüüd ka värvi ja suurusega!"],
        "20.02.2024":["boldTallinna Reaalkool kuulutab välja konkursi rakenduse nime ja logo loomiseks", "boldAlgab õpilastega testimine", "Täna testib 5.AB klass", "Tuvastati probleem lehe tõlkimisel", "Tehti ettepanek rohkemate värvide võimaldamiseks", "Modernne välimus on positiivset tagasisidet saanud", "Õpetaja Villu Raja rõhutab edetabeli olulisust motivatsiooniallikana"],
        "21.02.2024":["Täna testib 6.AB klass", "Tõlkimisega tekkinud probleem on lahendatud", "Idee luua filter mängu tagasisidesse õigete/valede vastuste vaatamiseks", "Juuremärk peaks paranema", "Vead juurimisel", "Ka neile meeldiks mitu värvivalikut"],
        "22.02.2024":["Eelmainitud filter on valminud"],
        "26.02.2024":["boldRakendus saab uue ja parema registratsioonivoo ja sisselogimisvaate", "Uuendus on muuhulgas inspireeritud Google'i süsteemi uuest välimusest"],
        "28.02.2024":["boldMitme värvi valik saab teoks", "Esialgu saab valida kümne põhivärvi vahel"],
        "29.02.2024-05.03.2024":["Uuendused back-endis, keskenduvad põhiliselt kontole", "Lihtsad parandused ja uuendused", "Automaatselt salvestuvad seaded (varem oli selleks nupp)"],
        "07.03.2024":["boldKonto saab viimaks funktsionaalsuse – statistika salvestatakse nüüd kontopõhiselt", "See on väga pikalt töös olnud uuendus", "Õpetajakonto korral näeb ta profiilil vastavat märget"],
        "08.03.2024":["boldPärast pikka päeva koolis valmib ka seadete salvestamine kontole", "Nagu öeldud, oli see varasemalt seadmepõhine", "Samuti uuendatakse natuke töölaual oleva statistika kuvamist"],
        "09.03.2024":["Uuendatud suurte pranglimisnuppude välimus"],
        "10.03.2024":["Uus seade klaviatuuri ümberpööramiseks (nii, nagu on kalkulaatoritel)"],
        "14.03.2024":["boldPranglimisrakendus saab nime – Reaaler", "Nime ja logo konkursi tulemused on avalikustatud", "Kuna logo vajab digitaliseerimist, ei ole need veel lehele üles pandud - seda teeme lähiajal", "Plaanime lisaks nime ja logo uuendamisele siis ka mõne teise suurema muudatuse teha", "boldMängude ajalugu võimaldab näha kõiki varasemalt mängitud kordi", "Lisaks saab oma paremate mängude linke teistele vaatamiseks jagada", "Ja lõpetuseks, head pii-päeva"],
        "15.03.2024":["boldStreak süsteem motiveerib igapäevaselt pranglima", "boldSamuti lisandus võimalus vaadata enda klassi andmeid ja edetabelit", "Kui sul veel klassi pole, saad selle profiililt lisada", "boldSamuti valmib esialgne koduleht", "Väga tihe päev täis põnevaid uuendusi", ],
        "16.03.2024":["Uuendatud konto loomise flow – enam ei sisesta kasutaja kontot luues klassi, vaid selle saab lisada pärast profiili vaates", "Profiili vaatesse lisatud nupp kas klassiga liitumiseks või oma staatuse muutmiseks (saab end klassist maha võtta ja teise klassi minna)", "Oleme arvestanud ka inimestega, kes ei taha klassis avalikult osaleda, st klassis olemine ei ole rakenduse poole pealt kohustuslik", "Parandatud liidese viga, kus murrud renderdati kohati liiga väikeselt", "Semantilised parandused töölaual ja teised väiksemad parandused"],
        "21.03.2024":["boldProfiilipildi tugi on lõpuks valmis", "Kui algselt oli kõigi profiilipildiks Reaalkooli logo, siis nüüd saab igaüks seda soovi korral vahetada", "Nii muudame Reaaleri veelgi isikupärasemaks"],
        "22.03.2024":["boldValmib esialgne õpetaja vaade", "Praeguseks hetkeks saab õpetaja klasse luua, vaadata, muuta (vajadusel ka õpilasi lisada/eemaldada) ning kustutada", "Samuti saab nüüd klassiga liituda lingi kaudu, st õpetaja saab saata lingi nt klassilisti", "Lisaks uuendasin navigatsiooniriba välimust mobiilil, kuna rakendusse lisandus hiljuti profiilipilt", "boldValmib ka avalik profiili vaade, seda saab vaadata edetabelis nime peale vajutades"],
        "23.03.2024":["Uueneb põhiliselt front-end", "boldTäiesti uuenenud profiilivaade väärtustab enam visuaalset lihtsust ja ilu", "Selle asemel, et kasutada muust rakendusest erinevaid klassi ja kooli silte, uuendati vaade ühtsemaks teistega", "Lisaks sai väikese kasutajaliidesliku maiuspala ka avalik profiili vaade", "Rakendus muutus uuendusega andmekaitsesõbralikumaks – kui enne nägi (lingi olemasolul) avalikku profiili kõik sisselogitud kasutajad, siis nüüd ainult need, kes on kasutaja klassikaaslased või õpetaja (õpetaja kontot näevad ainult tema õpilased)", "Erinevatesse loenditesse (nt edetabel, õpetaja vaates tema klassid jne) lisatud info juhuks, kui see on tühi"],
        "24.03.2024":["Töölaua vaatesse lisatud pisut värvi – näiteks muutub klassi edetabeli koha värv vastavalt kohale (esikolmiku jaoks)", "Seadete kasutajaliides ühtlustatud ülejäänud profiilivaatega", "Streak-süsteemi veaparandused", "Uuendatud töölaua vaate paigutus, nüüd on arvutamine (mis on tihti kasutaja soov rakendusse sisenedes) tõstetud esikohale", "Mõned veaparandused ka arvutusalgoritmi töös"]
    };

    return <>
        <Head title='Uuenduste ajalugu' />
        {auth.user != null && <Navbar user={auth.user} />}
        <SizedBox height={36} />

        <h2>Uuendused</h2>

        <section>
            {Object.keys(changelog).reverse().map((key)=><div style={{textAlign:"start"}} key={key}>
                <h3 style={{color:"rgb(var(--primary-color))", fontWeight:"bold"}}>{key}</h3>
                <ul>{changelog[key].map((change)=><li style={{color:"grey", fontWeight:change.startsWith("bold") ? "bold" : "normal"}} key={change}>{change.startsWith("bold") ? change.substring(4) : change}</li>)}</ul>
            </div>)}
        </section>

    </>;
}