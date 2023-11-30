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

Route::get("/game/{tehe}/{aeg}", function ($tehe, $aeg){
    return Inertia::render("Game/GamePage", ["data" => app('App\Http\Controllers\GameController')->array_Gen($tehe), "time"=>60*$aeg]);
})->name("game");
Route::get("/game/{level}/{mis}/{aeg}", function ($level, $mis, $aeg){
    return Inertia::render("Game/GamePage", ["data" => app('App\Http\Controllers\GameController')->liitlah($level, $mis), "time"=>60*$aeg]);
})->name("game");
Route::get("/game/{level}/{mis}/{aeg}", function ($level, $mis, $aeg){
    return Inertia::render("Game/GamePage", ["data" => app('App\Http\Controllers\GameController')->korjag($level, $mis), "time"=>60*$aeg]);
})->name("game");

require __DIR__.'/auth.php';


