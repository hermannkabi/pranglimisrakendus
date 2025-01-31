<?php

use App\Models\Mang;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

Route::get('/welcome', function () {
    $totalUsers = User::count();
    $totalGames = Mang::count();
    $totalPoints = Mang::sum("score_sum");
    return Inertia::render('Welcome/WelcomePage', ["users"=>$totalUsers, "games"=>$totalGames, "points"=>$totalPoints]);
})->middleware('auth');

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

Route::post("/preview", function (Request $request){
    $request->validate([
        'mis' => 'required',
        'level' => 'required',
        'tyyp' => 'required',
        'aeg' => 'required',
    ]);

    session(['gameData' => ["mis"=>$request->mis, "level"=>$request->level, "tyyp"=>$request->tyyp, "aeg"=>$request->aeg]]);
})->name("previewPost")->middleware('auth');


//Game part of Reaaler
Route::get("/game", function (){

    $data = session()->get("gameData");
    if(!$data){
        return redirect()->route("dashboard");
    }
    $mis = $data["mis"];
    $level = $data["level"];
    $tyyp = $data["tyyp"];
    $aeg = $data["aeg"];

    $aeg = min(10, $aeg);
    return Inertia::render("Game/GamePage", ["mis"=>$mis, "tyyp"=>$tyyp, "raw_level"=>$level, "data" => app("App\Http\Controllers\MathController")->wrapper($mis, str_split($level), $tyyp, $aeg), "time"=>60*$aeg]);
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


    Route::get('/classroom/{id}/edit', 'showEdit')->name('classEdit')->middleware(['role:teacher;admin']);
    Route::post('/classroom/{id}/edit', 'edit')->name('classEditPost')->middleware(['role:teacher;admin']);


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

Route::controller(App\Http\Controllers\AdminController::class)->middleware(["auth", "role:admin"])->group(function (){
    Route::get("/admin", "adminShow")->name("admin");
    Route::get("/competition/new", "competitionNew")->name("competitionNew");
});


Route::prefix("muusika")->controller(App\Http\Controllers\MusicController::class)->middleware(["auth"])->group(function (){
    Route::get("/", "get")->name("music");

    Route::get("/{link_id}", "showPlaylist")->name("playlist");

    Route::get("/kuulamiskava/uus", "get")->name("musicNew");
});


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