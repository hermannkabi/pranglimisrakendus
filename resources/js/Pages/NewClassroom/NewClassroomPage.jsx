import Navbar from "@/Components/Navbar";
import SizedBox from "@/Components/SizedBox";
import { Head } from "@inertiajs/react";
import "/public/css/preview.css";
import InfoBanner from "@/Components/InfoBanner";



export default function NewClassroomPage({auth, errors}){

    console.log(errors);

    return <>
        <Head title='Uus klass' />
        <Navbar user={auth.user} title="Uus klass" />
        <SizedBox height={36} />
        
        <h2>Uus klass</h2>
        
        <div className="container">
            <div className="preferences">
            {Object.keys(errors).length > 0 && <InfoBanner text={errors[Object.keys(errors)[0]]} />}

                <section>
                    <form method="post" action={route("classStore")} autoComplete="off">
                        <input type="hidden" name="_token" value={window.csrfToken}  />
                        <input type="text" name="klass_name" id="" placeholder="Klassi nimi" required style={{width:"100%", height:"100%", boxSizing:"border-box", marginInline:"-8px"}} />
                        <input autoComplete="new-password" required style={{width:"100%", height:"100%", boxSizing:"border-box", marginInline:"-8px"}} type="password" name="klass_password" placeholder="Parool klassiga ühinemiseks" />
                        <input autoComplete="new-password" required style={{width:"100%", height:"100%", boxSizing:"border-box", marginInline:"-8px"}} type="password" name="klass_password_confirmation" placeholder="Kinnita parool" />
                        
                        <button style={{width:"100%", height:"100%", boxSizing:"border-box", marginInline:"-8px"}} type="submit">Loo klass</button>
                    </form>
                </section>
            </div>
        </div>

    </>;
}