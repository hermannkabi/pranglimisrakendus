import Navbar from "@/Components/Navbar";
import SizedBox from "@/Components/SizedBox";
import { Head } from "@inertiajs/react";

export default function UpdateHistoryPage({auth}){


    const changelog = {
        "05.03.2024": ["See on testuuendus", "Veel mõni uuendatud võimalus", "..."],
        "06.03.2024": ["See on testuuendus", "Veel mõni uuendatud võimalus", "..."],
        "07.03.2024": ["See on testuuendus", "Veel mõni uuendatud võimalus", "..."],
    };

    return <>
        <Head title='Uuenduste ajalugu' />
        {auth.user != null && <Navbar user={auth.user} />}
        <SizedBox height={36} />

        <h2>Uuendused</h2>

        <section>
            {Object.keys(changelog).reverse().map((key)=><div style={{textAlign:"start"}} key={key}>
                <h3 style={{color:"rgb(var(--primary-color))", fontWeight:"bold"}}>{key}</h3>
                <ul>{changelog[key].map((change)=><li style={{color:"grey"}} key={change}>{change}</li>)}</ul>
            </div>)}
        </section>

    </>;
}