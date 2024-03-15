<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Klass;

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

    public function createUser($email, $eesnimi, $perenimi, $password, $klass, 
    $googleId, $settings, $remember, $streak){
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
            'settings' => $settings,
            'remember_token' => $remember,
            'profile_pic' =>  "/assets/logo.png",
            'streak' => $streak,
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

        $user = $this->createUser($request->email, $request->eesnimi, $request->perenimi, null, 
    $request->klass, $request->googleid, $request->settings, $request->remember_token, 0);

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
            ],
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

            ]
        );

        $this->createUser($request->email, $request->eesnimi, $request->perenimi, 
        $request->password, $request->klass, null, $request->settings, $request->remember_token, 0);

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

        if(Auth::attempt($credentials, true))
        {
            $request->session()->regenerate();
            return redirect()->intended('dashboard')
                ->withSuccess('You have successfully logged in!');
        }

        return back()->withErrors([
            'email' => 'Vale e-posti aadress/parool',
        ])->onlyInput('email');

    } 

    //TODO: Needs to be reworked
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
            $stats = app("App\Http\Controllers\GameController")->getOverallStats(Auth::id());
            $klass = Auth::user()->klass != null;
            $classData = false;
            if($klass){
                $class = Klass::where("klass_id", Auth::user()->klass)->first();
                $teacher = User::select(["eesnimi", "perenimi"])->where("role", "teacher")->where("klass", Auth::user()->klass)->get();
                $students = User::where("role", "student")->where("klass", Auth::user()->klass)->get();

                $leaderboardData = [];
                $total_count = 0;
                foreach($students as $õp){
                    $õpCount = app('App\Http\Controllers\GameController')->getUserExp($õp->id);
                    $total_count += $õpCount;
                    array_push($leaderboardData, ["user"=>$õp->id, "score"=>$õpCount]);
                }

                usort($leaderboardData, function ($a, $b){return ($a["score"] > $b["score"]) ? -1 : 1;});

                $place = 0;
                $loggedInId = Auth::id();

                for($i = 0; $i<count($leaderboardData); $i++){
                    if($leaderboardData[$i]["user"] == $loggedInId){
                        $place = $i + 1;
                    }
                }

                $classData = ["name"=>$class->klass_name, "teacher"=>$teacher, "studentsCount"=>count($students), "pointsCount"=> $total_count, "myPlace"=>$place];
            }
            return Inertia::render("Dashboard/DashboardPage", ["stats"=>$stats, 'classData'=>$classData])->with(['theme' => 'something']);
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