<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExampleFormController;


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

Route::get("/profile", function (){
    return Inertia::render("Profile/ProfilePage");
})->name("profilePage");

Route::get("/preview", function (){
    return Inertia::render("GamePreview/GamePreviewPage");
})->name("preview");

require __DIR__.'/auth.php';
