<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Inertia\Inertia;

use App\Models\Klass;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use App\Http\Controllers\GameController;

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
     */
    public function register()
    {
        return Inertia::render('Register/NewRegisterPage');
    }
    public function registerGoogle(){
        return Inertia::render("Register/RegisterGooglePage");
    }

    public function forgotPassword(){
        return Inertia::render("Login/ResetPasswordPage");
    }

    public function createUser($email, $eesnimi, $perenimi, $password, $googleId, $remember){
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
            'klass' => null,
            'google_id'=> $googleId,
            'settings' => null,
            'remember_token' => $remember,
            'profile_pic' =>  "/assets/logo.png",
            'streak' => 0,
        ]);
    }

    


    public function storeGoogle(Request $request){
        $request->validate([
            'eesnimi' => 'required|string|max:250',
            'perenimi' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:users',
            'googleid' => 'required',
        ]);


        $user = $this->createUser($request->email, $request->eesnimi, $request->perenimi, null, $request->googleid, $request->remember_token);

        Auth::login($user);

        return redirect()->intended("dashboard");
    }


    /**
     * Store a new user.
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

        $this->createUser($request->email, $request->eesnimi, $request->perenimi, $request->password, null, $request->remember_token);

        $credentials = $request->only('email', 'password');
        if(Auth::attempt($credentials)){
            $request->session()->regenerate();
            return redirect()->intended("dashboard");
        }

        return redirect()->route("register")->withErrors(['email' => 'Midagi läks valesti!']);
    }

    /**
     * Display a login form.
     */
    public function login()
    {
        return Inertia::render("Login/NewLoginPage");;
    }

    /**
     * Authenticate the user.
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
     */
    public function dashboard(Request $request)
    {
        if(Auth::check())
        {        
            $stats = app(GameController::class)->getOverallStats(Auth::id());
            $klass = Auth::user()->klass != null;
            $classData = false;
            $teacherData = null;
            if($klass && Auth::user()->role == "student"){
                $class = Klass::where("klass_id", Auth::user()->klass)->first();
                $teacher = User::select(["eesnimi", "perenimi", "id"])->where("id", $class->teacher_id)->get();
                $students = User::where("role", "student")->where("klass", Auth::user()->klass)->get();

                $total_count = 0;

                $leaderboardData = app("App\Http\Controllers\LeaderboardController")->getLeaderboardData($students);

                $filtered = (array_filter($leaderboardData, function ($row){return $row["user"]->id == Auth::id();}));
                
                Log::debug($filtered);
                $place = $filtered[array_keys($filtered)[0]]["place"];
                foreach($leaderboardData as $dataRow){
                    $total_count += $dataRow["xp"];
                }

                $classData = ["name"=>$class->klass_name, "teacher"=>$teacher, "studentsCount"=>count($students), "pointsCount"=> $total_count, "myPlace"=>$place, "uuid"=>$class->uuid];
            }

            if(Auth::user()->role == "teacher"){
                $teacherData = [];
                $classes = Klass::select(["klass_name", "uuid"])->where("teacher_id", Auth::id())->get();
                foreach($classes as $class){
                    array_push($teacherData, $class);
                }
            }

            return Inertia::render("Dashboard/DashboardPage", ["stats"=>$stats, "teacherData"=>$teacherData, 'classData'=>$classData])->with(['theme' => 'something']);
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
        return redirect()->route('welcome');
    }    

}