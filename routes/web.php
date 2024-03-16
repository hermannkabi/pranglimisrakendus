<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if(Auth::check()){
        return redirect()->route("dashboard");
    }
    return Inertia::render('Welcome/WelcomePage');
})->name("welcome");

Route::post("/handleForm", [App\Http\Controllers\ExampleFormController::class, "handleForm"])->name("saveItem");

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
    Route::post('/profile/avatar', 'changeProfilePicture')->name('changeProfilePicture');

});


//Login and registration
Route::controller(App\Http\Controllers\Auth\LoginRegisterController::class)->group(function() {
    Route::get('/register', 'register')->name('register');
    Route::get('/register/google', 'registerGoogle')->name('registerGoogle');

    Route::post('/store', 'store')->name('store');
    Route::post('/store/google', 'storeGoogle')->name('storeGoogle');

    Route::get('/login', 'login')->name('login');
    Route::post('/authenticate', 'authenticate')->name('authenticate');
    Route::get('/authenticate/guest', 'authenticateGuest')->name('authenticateGuest');

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
    Route::get('/email/verify', 'notice')->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', 'verify')->name('verification.verify');
    Route::post('/email/resend', 'resend')->name('verification.resend');
});

//Password reset
Route::controller(App\Http\Controllers\Auth\PasswordResetLinkController::class)->middleware(['guest'])->group(function() {
    Route::get('/forgot-password', 'create')->name('password.request');
    Route::get('/forgot-password', 'store')->name('password.email');
});

//Password reset form
Route::controller(App\Http\Controllers\Auth\NewPasswordController::class)->middleware(['guest'])->group(function() {
    Route::get('/reset-password/{token}', 'create')->name('password.reset');
    Route::get('/reset-password', 'store')->name('password.update');
});

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

Route::get("/preview", function (){
    return Inertia::render("GamePreview/GamePreviewPage");
})->name("preview")->middleware('auth');

//Game part of PRANGLIMISRAKENDUS
Route::get("/game/{level}/{mis}/{aeg}/{tüüp}", function ($level, $mis, $aeg, $tüüp){
    $aeg = min(10, $aeg);
    return Inertia::render("Game/GamePage", ["data" => app('App\Http\Controllers\MathController')->wrapper($mis, str_split($level), $tüüp, $aeg), "time"=>60*$aeg]);
})->name("gameNew")->middleware(['auth']);

//Game data
Route::controller(App\Http\Controllers\GameController::class)->middleware(["auth"])->group(function() {
    Route::post('/game/store', 'store')->name('gameStore');

    Route::post('/game/update', 'update')->name('gameUpdate');

    Route::get('/game/history', 'show')->name('gameHistory');

    Route::post('/game/scoreboard', 'index')->name('gameScoreboard');
    
    Route::get("game/{id}/details", "gameDetails");
});

//Classroom data
Route::controller(App\Http\Controllers\ClassController::class)->middleware(["auth"])->group(function (){
    Route::post('/classroom/search', 'index')->name('classSearch');

    Route::get('/classroom/view/', 'show')->name('classShow');

    Route::get('/classroom/join', 'showJoin')->name('classJoin');
    Route::post('/classroom/join', 'join')->name('join');

    Route::post('/classroom/remove', 'classRemove')->name('classRemove');

    Route::post('/classroom/store', 'store')->name('classStore')->middleware(['teacher']);

    Route::post('/classroom/delete', 'destroy')->name('classDelete')->middleware('teacher');
});

Route::get('/dashboard/old', function (){
    return Inertia::render("Dashboard/OldDashboardPage");
})->name("dashboard-old");

Route::get('/how-to-play', function (){
    return Inertia::render("Guide/GuidePage");
})->name("guide");

require __DIR__.'/auth.php';


