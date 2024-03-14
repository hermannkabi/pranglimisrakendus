import Navbar from "@/Components/Navbar";
import SizedBox from "@/Components/SizedBox";
import { Head } from "@inertiajs/react";

export default function GameDetailsPage({game, auth}){


    console.log(game);

    return <>
            <Head title="Mäng" />
            <Navbar title="Mäng" user={auth.user} />
            <SizedBox height={36} />

        <h1>{game.game}</h1>
    </>;
}