export default function ForgotPasswordPage({auth, token}){

    return <>
        <form action={route("password.update")} method="post">
            <input type="hidden" name="_token" value={window.csrfToken} />
            <input type="hidden" name="token" value={token} />
            <input type="hidden" name="email" value={auth.user.email} />

            <input type="text" name="password" placeholder="Parool" />
            <input type="text" name="password_confirmation" placeholder="Korda" />

            <button type="submit">Saada</button>
        </form>
    </>;
}