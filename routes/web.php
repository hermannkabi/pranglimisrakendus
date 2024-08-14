<?php

use App\Models\Mang;
use App\Models\User;
use Inertia\Inertia;
use App\Mail\TestMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    if(Auth::check()){
        return redirect()->route("dashboard");
    }

    $totalUsers = User::count();
    $totalGames = Mang::count();
    $totalPoints = Mang::sum("score_sum");
    return Inertia::render('Welcome/WelcomePage', ["users"=>$totalUsers, "games"=>$totalGames, "points"=>$totalPoints]);
})->name("welcome");

Route::get("/handleForm", function (){
    return redirect()->route("welcome");
});

Route::get('/profile', function () {
    return Inertia::render("Profile/ProfilePage");
})->name("profilePage")->middleware('auth');

Route::controller(App\Http\Controllers\ProfileController::class)->middleware(['auth'])->group(function() {
    Route::get('/profile', "show")->name("profilePage");

    //Route::get('/checkstreak', "checkStreak")->name("checkstreak");

    Route::post('/profile/settings/edit', "settings")->name("settingsAdd");
    Route::post('/profile/avatar/upload', 'changeProfilePicture')->name('changeProfilePicture');

    Route::get('/profile/{id}', "showPublic")->name("profilePublic");

});


//Login and registration
Route::controller(App\Http\Controllers\Auth\LoginRegisterController::class)->group(function() {
    Route::get('/register', 'register')->name('register');

    Route::post('/store', 'store')->name('store');
    Route::post('/store/google', 'storeGoogle')->name('storeGoogle');

    Route::get('/login', 'login')->name('login');
    Route::post('/authenticate', 'authenticate')->name('authenticate');
    Route::get('/authenticate/guest', 'authenticateGuest')->name('authenticateGuest');

    Route::get('/login/forgot-password', 'forgotPassword')->name('forgotPassword');
    

    Route::get('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/logout', 'logout')->name('logout');
});

//Google login
Route::controller(App\Http\Controllers\GoogleLoginController::class)->group(function() {
    Route::get('/google/redirect', 'redirectToGoogle')->name('google.redirect');
    Route::get('/google/callback', 'handleGoogleCallback')->name('google.callback');
});

//Email verification
Route::controller(App\Http\Controllers\AuthVerificationController::class)->middleware(['auth'])->group(function() {
    Route::get('/email/verify/{id}/{hash}', 'verify')->name('verification.verify');
    Route::post('/email/resend', 'resend')->name('verification.resend');
});

// //Password reset form
// Route::controller(App\Http\Controllers\Auth\PasswordResetLinkController::class)->middleware(['guest'])->group(function() {
//     Route::get('/reset/password/{token}', 'create')->name('passwordReset');
//     Route::post("/password/reset", 'store')->name("passwordReset");
// });

//User information
Route::controller(App\Http\Controllers\ProfileController::class)->middleware('auth')->group(function() {
    Route::post('/user/settings', 'settings')->name('user-settings');
    Route::post('/delete-user',  'destroy')->name('delete-user');
});

Route::get('/ui', function () {
    return Inertia::render('UI/UIPage');
})->name("ui");

Route::get('/changelog', function () {
    return Inertia::render('UpdateHistory/UpdateHistoryPage');
})->name("changelog");

Route::get("/preview/{type}", function ($type){
    $supportedTypes = ["liitmine", "lahutamine", "korrutamine", "jagamine", "liitlahutamine", "korrujagamine", "astendamine", "juurimine", "astejuurimine", "võrdlemine", "lünkamine", "murruTaandamine", "kujundid", "jaguvus"];
    if(!in_array($type, $supportedTypes)) abort(404);
    return Inertia::render("GamePreview/GamePreviewPage", ["type"=>$type]);
})->name("preview")->middleware('auth');

//Game part of PRANGLIMISRAKENDUS
Route::get("/game/{level}/{mis}/{aeg}/{tüüp}", function ($level, $mis, $aeg, $tüüp){
    $aeg = min(10, $aeg);
    return Inertia::render("Game/GamePage", ["data" => app("App\Http\Controllers\MathController")->wrapper($mis, str_split($level), $tüüp, $aeg), "time"=>60*$aeg]);
})->name("gameNew")->middleware(['auth']);

//Game data
Route::controller(App\Http\Controllers\GameController::class)->middleware(["auth"])->group(function() {
    Route::post('/game/store', 'store')->name('gameStore');

    Route::post('/game/update', 'update')->name('gameUpdate');

    Route::get('/game/history/{id?}', 'show')->name('gameHistory');

    Route::post('/game/scoreboard', 'index')->name('gameScoreboard');
    
    Route::get("game/{id}/details", "gameDetails");
});

//Classroom data
Route::controller(App\Http\Controllers\ClassController::class)->middleware(["auth"])->group(function (){
    Route::post('/classroom/search', 'index')->name('classSearch');

    Route::get('/classroom/{id}/view/', 'show')->name('classShow');

    Route::get('/classroom/join', 'showJoin')->name('classJoin');
    Route::post('/classroom/join', 'join')->name('join');

    Route::get('/classroom/{id}/join', 'joinLink')->name('joinLink');
    Route::post('/classroom/{id}/join', 'joinLinkPost')->name('joinLinkPost')->middleware(['role:student']);


    Route::get('/classroom/{id}/edit', 'showEdit')->name('classEdit')->middleware(['role:teacher']);
    Route::post('/classroom/{id}/edit', 'edit')->name('classEditPost')->middleware(['role:teacher']);


    Route::post('/classroom/remove/{id}', 'classRemove')->name('classRemove');


    Route::get('/classroom/new', 'newClass')->name('newClass')->middleware(['role:teacher']);
    Route::post('/classroom/new', 'store')->name('classStore')->middleware(['role:teacher']); // See ei tootanud mul??

    Route::post('/classroom/{id}/delete', 'destroy')->name('classDelete')->middleware('role:teacher');

    Route::get('/classroom/all', 'showAll')->name('classAll')->middleware('role:teacher');

});

Route::get('/dashboard/old', function (){
    return Inertia::render("Dashboard/OldDashboardPage");
})->name("dashboard-old");

Route::get('/how-to-play', function (){
    return Inertia::render("Guide/GuidePage");
})->name("guide");

Route::get("/down", function (){
    
    $id = Auth::id();
    
    // Minu ja Jarli ID-d (5 on Jarli arvutis tema id)
    if($id != 1000003 && $id != 9 && $id != 5){
        abort(404);
        return;
    }

    Artisan::call("down --render='errors::503' --secret='1630542a-246b-4b66-afa1-dd72a4c43515'");
    return "Rakendus on maas. Kasuta koodi 1630542a-246b-4b66-afa1-dd72a4c43515, et vaadata.";
});

Route::get("/up", function (){

    $id = Auth::id();
    
    if($id != 1000003 && $id != 9 && $id != 5){
        abort(404);
        return;
    }

    Artisan::call("up");
    return "Rakendus on taas avalikult nähtav.";
});



require __DIR__.'/auth.php';


