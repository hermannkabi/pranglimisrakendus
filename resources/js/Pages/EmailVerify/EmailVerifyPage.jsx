export default function EmailVerifyPage(){
    return <>
    <form action={route("verification.resend")} method="post">
        <input type="hidden" name="_token" value={window.csrfToken} />
        <button type="submit">Kinnita e-posti aadress</button>
    </form>
    </>;
}