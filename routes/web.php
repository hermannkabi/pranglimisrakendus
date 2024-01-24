<?php

use App\Http\Controllers\ArrayController;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExampleFormController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GoogleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

// See on väga halb kood, lihtsalt selleks, et lehed töötaks praegu

Route::get('/', function () {
    return Inertia::render('Welcome/WelcomePage');
})->name("welcome");

Route::post("/handleForm", [ExampleFormController::class, "handleForm"])->name("saveItem");

Route::get("/handleForm", function (){
    return redirect()->route("welcome");
});

Route::get('/login', function () {
    return Inertia::render('Login/LoginPage');
})->name("login");

Route::post('/login', function () {
    if(isset($_POST["email"])){

        $email = $_POST["email"];
        $parool = $_POST["pwd"];
    
    
        require_once "../login/database.php";
        require_once "../login/functions.php";
    
        if (emptyLogin($email, $parool) !== false){
            //add inf
            return Inertia::render('Login/LoginPage', ["message"=>"Midagi läks valesti!"]);
        }
    
        loginUser($conn, $email, $parool);
    } else {
        return Inertia::render('Login/LoginPage', ["message"=>"Midagi läks valesti!"]);
    }
})-> name('loginForm');
Route::get('/ui', function () {
    return Inertia::render('UI/UIPage');
})->name("ui");

Route::get('/register', function () {
    return Inertia::render('Register/RegisterPage');
})->name("register");

//MySQL database
Route::post('/register', function () {    
    if (isset($_POST['name'])) {

        $name = $_POST['name'];
        $famname = $_POST['famname'];
        $email = $_POST['email'];
        $class = $_POST['class'];
        $pwd = $_POST['pwd'];
        $pwdRepeat = $_POST['pwdrepeat'];


        require_once '../login/functions.php';
        require_once '../login/database.php';

        
        if (emptyInputSignup($name, $famname, $email, $class, $pwd, $pwdRepeat)) {
            return Inertia::render('Register/RegisterPage', ["message"=>"Mõni väli on jäänud täitmata!"]);
        };
        if (invalidname($name)) {
            return Inertia::render('Register/RegisterPage', ["message"=>"Nimi ei ole korrektselt vormistatud!"]);
        };
        if (invalidemail($email)) {
            return Inertia::render('Register/RegisterPage', ["message"=>"E-posti aadress ei ole korrektselt vormistatud!"]);
        };
        if (pwdNoMatch($pwd,  $pwdRepeat)) {
            return Inertia::render('Register/RegisterPage', ["message"=>"Paroolid ei kattu!"]);
        };
        if (nameExists($conn, $name, $email) != false) {
            return Inertia::render('Register/RegisterPage', ["message"=>"Sellise e-postiga kasutaja on juba loodud!"]);
        };

        // Validation checks completed
        createUser($conn, $name, $famname, $email, $class, $pwd);
    } else {
        return Inertia::render('Register/RegisterPage', ["message"=>"Midagi läks valesti!"]);
    }
})->name("registerPost");

Route::get('/dashboard', function (){
    return Inertia::render("Dashboard/DashboardPage");
})->name("dashboard");

Route::get('/dashboard/old', function (){
    return Inertia::render("Dashboard/OldDashboardPage");
})->name("dashboard-old");

Route::get("/profile", function (){
    return Inertia::render("Profile/ProfilePage");
})->name("profilePage");

Route::get("/preview", function (){
    return Inertia::render("GamePreview/GamePreviewPage");
})->name("preview");


route::get('google-login', [GoogleController::class, 'googlepage'])->name('googleLogin');
route::get('google-login/callback', [GoogleController::class, 'googlecallback']);

// Route::get("/game/{tehe}/{aeg}", function ($tehe, $aeg){
//     return Inertia::render("Game/GamePage", ["data" => app('App\Http\Controllers\GameController')->array_Gen($tehe), "time"=>60*$aeg]);
// })->name("game");

Route::get("/game/{level}/{mis}/{aeg}/{tüüp}", function ($level, $mis, $aeg, $tüüp){
    
    return Inertia::render("Game/GamePage", ["data" => app('App\Http\Controllers\GameController')->wrapper($mis, str_split($level), $tüüp, $aeg), "time"=>60*$aeg]);
})->name("gameNew");

require __DIR__.'/auth.php';


