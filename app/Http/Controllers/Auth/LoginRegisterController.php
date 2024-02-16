<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;

class LoginRegisterController extends Controller
{
    /**
     * Instantiate a new LoginRegisterController instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except([
            'logout', 'dashboard'
        ]);
    }

    /**
     * Display a registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function register()
    {
        return Inertia::render('Register/RegisterPage');
    }

    public function registerGoogle(){
        return Inertia::render("Register/RegisterGooglePage");
    }

    public function createUser($email, $eesnimi, $perenimi, $password, $klass, $googleId){
        return User::create([
            'email' => $email,
            'eesnimi' => $eesnimi,    
            'perenimi' => $perenimi,    
            'password' => $password == null ? null : Hash::make($password),
            'klass' => $klass,
            'google_id'=> $googleId,
        ]);
    }


    public function storeGoogle(Request $request){
        $request->validate([
            'eesnimi' => 'required|string|max:250',
            'perenimi' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:users',
            'klass' => 'required|string|max:6',
            'googleid' => 'required',
        ]);

        $user = $this->createUser($request->email, $request->eesnimi, $request->perenimi, null, $request->klass, $request->googleid);

        Auth::login($user);

        return redirect()->route("dashboard");
    }


    /**
     * Store a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'eesnimi' => 'required|string|max:250',
            'perenimi' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:users',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required|min:8',
            'klass' => 'required|string|max:6',
        ]);

        $this->createUser($request->email, $request->eesnimi, $request->perenimi, $request->password, $request->klass, null);

        $credentials = $request->only('email', 'password');
        if(Auth::attempt($credentials)){
            $request->session()->regenerate();
            return redirect()->route("dashboard");
        }

        return redirect()->route("register")->withErrors(['email' => 'Midagi läks valesti!']);
    }

    /**
     * Display a login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        return Inertia::render("Login/LoginPage");;
    }

    /**
     * Authenticate the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if(Auth::attempt($credentials))
        {
            $request->session()->regenerate();
            return redirect()->intended('dashboard')
                ->withSuccess('You have successfully logged in!');
        }

        return back()->withErrors([
            'email' => 'Vale e-posti aadress/parool',
        ])->onlyInput('email');

    } 


    public function authenticateGuest(Request $request){
        Auth::login(User::where("id", "999999")->first());

        return redirect()->route("dashboard");
    }
    
    /**
     * Display a dashboard to authenticated users.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        if(Auth::check())
        {
            return Inertia::render("Dashboard/DashboardPage");
        }
        
        return redirect()->route('login')
            ->onlyInput('email');
    } 
    
    /**
     * Log out the user from application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')
            ->withSuccess('You have logged out successfully!');;
    }    

}