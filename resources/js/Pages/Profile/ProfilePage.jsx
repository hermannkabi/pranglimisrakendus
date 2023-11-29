import Navbar from "@/Components/Navbar";
import { Head } from "@inertiajs/react";
import "/public/css/profile.css";
import SizedBox from "@/Components/SizedBox";
import NumberInput from "@/Components/NumberInput";


export default function ProfilePage(){
    return (
        <>
            <Head title="Profiil" />
            <Navbar title="Profiil & seaded" />

            <SizedBox height={36} />
            <h2>Minu konto</h2>

            <section>
                <div className="header-container">
                    <h3 className="section-header">Seaded</h3>
                </div>
                <div className="padding-container settings-padding" style={{display:'flex', flexWrap:"wrap"}}>
                    <select style={{width:"100%"}} name="app-theme">
                        <option selected disabled id="default">Rakenduse teema</option>
                        <option value="light">Hele teema</option>
                        <option value="dark">Tume teema</option>
                    </select>
                    <select style={{width:"100%"}} name="app-theme">
                        <option selected disabled id="default">Taimeri nähtavus</option>
                        <option value="light">Taimer nähtav</option>
                        <option value="dark">Taimer peidetud</option>
                    </select>
                    <select style={{width:"100%"}} name="app-theme">
                        <option selected disabled id="default">Vaikimisi mängurežiim</option>
                        <option value="light">Kiiruspõhine</option>
                        <option value="dark">Tehete arvu põhine</option>
                    </select>
                    <NumberInput placeholder="Vaikimisi aeg (min)"/>
                    <SizedBox height="32px" />
                    <button style={{flex:'1', marginInline:"4px"}}>Salvesta seaded</button>
                </div>
            </section>
            <section>
                <div className="header-container">
                    <h3 className="section-header">Profiil</h3>
                </div>
                <div className="" style={{display:'flex', flexWrap:"wrap"}}>
                    <div className="mobile-block" style={{display:"flex", justifyContent:"stretch", width:"100%", gap:"8px"}}>
                        <input type="text" placeholder="Eesnimi" value="Mari" disabled/>
                        <input type="text" placeholder="Perenimi" value="Maasikas" disabled/>
                    </div>
                    <input type="text" placeholder="E-posti aadress" value="mari.maasikas@koolikool.edu.ee" disabled/>
                    <div style={{display:"flex", justifyContent:"stretch", width:"100%", gap:"8px"}}>
                        <input style={{flex:"5"}} type="text" placeholder="Kooli nimi" value="Kooli Põhikool" disabled/>
                        <input type="text" placeholder="Klass" value="9.a klass" style={{minWidth:'100px'}} disabled/>
                    </div>
                    <input type="text" placeholder="Unikaalne kasutaja-ID (UUID)" value={crypto.randomUUID()} disabled />
                    <div className="mobile-block" style={{display:"flex", justifyContent:"stretch", width:"100%", gap:"8px"}}>
                        <button style={{flex:"1", width:'100%', marginLeft:"0px"}}>Muuda parooli</button>
                        <button darkred="true" style={{flex:"1", width:'100%', marginRight:"8px"}} secondary="true" onClick={()=>window.location.href=route("login")}>Logi välja</button>
                    </div>
                    <a alone="" style={{color:"rgb(var(--darkred-color))", marginInline:"auto", marginBlock:"16px"}}>Kustuta konto</a>
                </div>
            </section>
            

            <div className="container">
                <div className="profile">
                    
                </div>

                <div className="settings">
                    
                </div>
            </div>
        </>
    );
}