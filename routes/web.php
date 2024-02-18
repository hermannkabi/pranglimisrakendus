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
Route::get('/google/redirect', [App\Http\Controllers\GoogleLoginController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/google/callback', [App\Http\Controllers\GoogleLoginController::class, 'handleGoogleCallback'])->name('google.callback');


//Email verification
Route::controller(App\Http\Controllers\AuthVerificationController::class)->group(function() {
    Route::get('/email/verify', 'notice')->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', 'verify')->name('verification.verify');
    Route::post('/email/resend', 'resend')->name('verification.resend');
});


//Password reset
Route::get('/forgot-password', [App\Http\Controllers\Auth\PasswordResetLinkController::class, 'create'
])->middleware('guest')->name('password.request');

Route::post('/forgot-password', [App\Http\Controllers\Auth\PasswordResetLinkController::class, 'store'
])->middleware('guest')->name('password.email');

//Password reset form
Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\NewPasswordController::class, 'create'
])->middleware('guest')->name('password.reset');

Route::post('/reset-password', [App\Http\Controllers\Auth\NewPasswordController::class, 'store'
])->middleware('guest')->name('password.update');

//User deletion
Route::post('/delete-user', [App\Http\Controllers\ProfileController::class, 'destroy'
])->middleware('auth')->name('delete-user');



Route::get('/ui', function () {
    return Inertia::render('UI/UIPage');
})->name("ui");

Route::get("/preview", function (){
    return Inertia::render("GamePreview/GamePreviewPage");
})->name("preview")->middleware('auth');

//Game part of PRANGLIMISRAKENDUS
Route::get("/game/{level}/{mis}/{aeg}/{tüüp}", function ($level, $mis, $aeg, $tüüp){
    $aeg = min(10, $aeg);
    return Inertia::render("Game/GamePage", ["data" => app('App\Http\Controllers\GameController')->wrapper($mis, str_split($level), $tüüp, $aeg), "time"=>60*$aeg]);
})->name("gameNew")->middleware('auth');

//Game data
Route::controller(App\Http\Controllers\GameController::class)->group(function() {
    Route::post('/game/store', 'store')->name('gameStore');
    Route::post('/game/update', 'update')->name('gameUpdate');
})->middleware('auth');


Route::get('/dashboard/old', function (){
    return Inertia::render("Dashboard/OldDashboardPage");
})->name("dashboard-old");

require __DIR__.'/auth.php';


