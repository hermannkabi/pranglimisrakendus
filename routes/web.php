<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// See on väga halb kood, lihtsalt selleks, et lehed töötaks praegu

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

Route::controller(App\Http\Controllers\ProfileController::class)->group(function() {
    Route::get('/profile', function () {
        return Inertia::render("Profile/ProfilePage");
    })->name("profilePage");

    Route::post('/profile/settings/edit', "settings")->name("settingsAdd");

}) -> middleware(['throttle:6,1', 'auth']);


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
}) -> middleware('throttle:6,1');

//Google login
Route::controller(App\Http\Controllers\GoogleLoginController::class)->group(function() {
    Route::get('/google/redirect', 'redirectToGoogle')->name('google.redirect');
    Route::get('/google/callback', 'handleGoogleCallback')->name('google.callback');
})->middleware('throttle:6,1');

//Email verification
Route::controller(App\Http\Controllers\AuthVerificationController::class)->group(function() {
    Route::get('/email/verify', 'notice')->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', 'verify')->name('verification.verify');
    Route::post('/email/resend', 'resend')->name('verification.resend');
})->middleware(['auth','throttle:6,1']);

//Password reset
Route::controller(App\Http\Controllers\Auth\PasswordResetLinkController::class)->group(function() {
    Route::get('/forgot-password', 'create')->name('password.request');
    Route::get('/forgot-password', 'store')->name('password.email');
})->middleware(['guest', 'throttle:4,1']);

//Password reset form
Route::controller(App\Http\Controllers\Auth\NewPasswordController::class)->group(function() {
    Route::get('/reset-password/{token}', 'create')->name('password.reset');
    Route::get('/reset-password', 'store')->name('password.update');
})->middleware(['guest', 'throttle:4,1']);

//User information
Route::controller(App\Http\Controllers\ProfileController::class)->group(function() {
    Route::post('/user/settings', 'settings')->name('user-settings');
    Route::post('/delete-user',  'destroy')->name('delete-user');
})->middleware('auth');


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
})->name("gameNew")->middleware(['auth', 'throttle: 6,1']);

//Game data
Route::controller(App\Http\Controllers\GameController::class)->group(function() {
    Route::post('/game/store', 'store')->name('gameStore');
    Route::post('/game/update', 'update')->name('gameUpdate');
    Route::get('/game/history', 'show')->name('gameHistory');
    Route::post('/game/scoreboard', 'index')->name('gameScoreboard');
})->middleware('auth');

//Classroom data
Route::controller(App\Http\Controllers\ClassController::class)->group(function (){
    //Route::get('/classroom/edit/{id}', 'edit')->name('classEdit');
    Route::post('/classroom/search', 'index')->name('classSearch');
    Route::get('/classroom/view/{id}', 'show')->name('classShow');
    Route::post('/classroom/join', 'join')->name('classJoin');
    Route::post('/classroom/store', 'store')->name('classStore')->middleware(['role:teacher', 'throttle: 4,1']);
    Route::post('/classroom/delete', 'destroy')->name('classDelete')->middleware('role:teacher');
    Route::post('/classroom/remove}', 'destroy')->name('classRemove')->middleware('role:teacher');
})->middleware('auth');

Route::get('/dashboard/old', function (){
    return Inertia::render("Dashboard/OldDashboardPage");
})->name("dashboard-old");

require __DIR__.'/auth.php';


