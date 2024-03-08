<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
        return Inertia::render('Register/NewRegisterPage');
    }

    public function registerGoogle(){
        return Inertia::render("Register/RegisterGooglePage");
    }

    public function createUser($email, $eesnimi, $perenimi, $password, $klass, $googleId){
        $teachers = array(
        'andres.talts@real.edu.ee',
        'helen.kaasik@real.edu.ee',
        'helli.juurma@real.edu.ee',
        'kerli.kupits@real.edu.ee',
        'liivi.koss@real.edu.ee',
        'riin.saar@real.edu.ee',
        'villu.raja@real.edu.ee',
        'jaanika.lukk@real.edu.ee',
    

        'aili.hobejarv@real.edu.ee',
        'kadri.pajo@real.edu.ee',
        'kati.kurim@real.edu.ee',
        'kaie.matt@real.edu.ee',
        'lisette.veevali@real.edu.ee',
        'margit.luts@real.edu.ee',
        'reet.libe@real.edu.ee',
        'triinuliis.vahter@real.edu.ee');
        
        return User::create([
            'role' => !in_array($email, $teachers) ? 'student' : 'teacher',
            'email' => $email,
            'eesnimi' => $eesnimi,    
            'perenimi' => $perenimi,    
            'password' => $password == null ? null : Hash::make($password),
            'klass' => $klass,
            'google_id'=> $googleId,

            //User settings
        //TODO: Tee need küpsisteks https://laravel.com/docs/master/requests#cookies , https://stackoverflow.com/questions/45207485/how-to-set-and-get-cookie-in-laravel
            'dark_backround' => false,
            'visible_timer' => true,
            'score_animations' => true,
            'default_time' => 0,
            'color' => 'turquoise',

            //Game information
            'score_sum' => 0,
            'experience' => 0,
            'accuracy_sum' => 0,
            'game_count' => 0,
            'last_level' => 0,
            'last_equation' => 0,
            'time' => 0,
            'dt' => 0,
            'mistakes_tendency' => 0,
            'mistakes_sum' => 0,

            //Quests
            'quests' => 0,
            'quest_type' => 0,
            'completed_quests_sum' => 0,
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

        if(!str_ends_with($request->email, "real.edu.ee")){
            return redirect()->back()->withErrors(["Kasuta oma Reaalkooli e-posti aadressi"]);
        }

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
            'klass' => 'required|string',],
            [
                'eesnimi.required' => 'Palun sisesta enda eesnimi',
                'perenimi.required' => 'Palun sisesta enda perenimi',

                'eesnimi.max' => 'Eesnime pikkus saab olla kuni 250 tähemärki',
                'perenimi.max' => 'Perenime pikkus saab olla kuni 250 tähemärki',

                'email.required' => 'Palun sisesta enda e-posti aadress',
                'email.unique' => 'Sellise e-posti aadressiga konto juba eksisteerib',

                'password.required' => 'Palun sisesta parool',
                'password_confirmation.required' => 'Palun kinnita parool',

                'password.confirmed' => 'Paroolid ei kattu',

                'password.min' => 'Parool peab olema vähemalt 8 tähemärki pikk',

                'klass.required' => 'Palun sisesta klass, kus õpid' 
            ]
        );

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
        return Inertia::render("Login/NewLoginPage");;
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
    public function dashboard(Request $request)
    {
        if(Auth::check())
        {        
            $stats = app("App\Http\Controllers\GameController")->getOverallStats();
            return Inertia::render("Dashboard/DashboardPage", ["stats"=>$stats])->with(['theme' => 'something']);
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