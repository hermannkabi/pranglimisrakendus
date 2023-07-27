import Navbar from '@/Components/Navbar';
import { Head } from '@inertiajs/react';

export default function Dashboard({auth}) {
    return (
        <>
            <Head title='Töölaud' />
            <Navbar title="Töölaud" user={auth.user}/>
            <section style={{width:"100%"}}>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Cum non sapiente fuga quisquam porro magni ratione, aspernatur repellat vero accusantium, voluptate ipsum praesentium necessitatibus eveniet pariatur delectus est esse placeat?</p>

            </section>
        </>
    );
}
