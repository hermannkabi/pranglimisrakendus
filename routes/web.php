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

Route::get('/ui', function () {
    return Inertia::render('UI/UIPage');
})->name("ui");

Route::get('/register', function () {
    return Inertia::render('Register/RegisterPage');
})->name("register");

Route::post('/register', function () {
    if (isset($_POST['registration'])) {

        $name = $_POST['name'];
        $famname = $_POST['famname'];
        $email = $_POST['email'];
        $class = $_POST['class'];
        $adminpsw = $_POST['adminpsw'];
        $pwd = $_POST['pwd'];
        $pwdRepeat = $_POST['pwdrepeat'];
    
        require_once '../login/database.php';
        require_once '../login/functions.php';
    
        if (emptyInputSignup($name, $email, $class, $pwd, $pwdRepeat, $result, $famname, $adminpsw) != false) {
            echo 'Error'; // Add more inf
            return Inertia::render('Register/RegisterPage', ["message"=>"Midagi on puudu!"]);

        };
        if (invalidname($name, $result) != false) {
            echo 'Error';// Add more inf
        };
        if (invalidemail($email, $result) != false) {
            echo 'Error';// Add more inf
        };
        if (pwdMatch($pwd,  $pwdRepeat, $result) !== false) {
            echo 'Error';// Add more inf
        };
        if (nameExists($conn, $name, $email) != false) {
            echo 'Error';// Add more inf
        };
        createUser($conn, $name, $email, $class, $pwd);
    } else {
        echo 'Error';// Add more in
    }
    return Inertia::render('Register/RegisterPage');
})->name("register");

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
    
    return Inertia::render("Game/GamePage", ["data" => app('App\Http\Controllers\GameController')->wrapper($mis, str_split($level), $tüüp), "time"=>60*$aeg]);
})->name("gameNew");

require __DIR__.'/auth.php';


