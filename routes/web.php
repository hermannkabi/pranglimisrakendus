<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExampleFormController;

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


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
