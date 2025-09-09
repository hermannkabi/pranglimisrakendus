<?php

use Carbon\Carbon;
use App\Models\Mang;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Competition;
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
    $message = optional(DB::table('properties')->where("property", "reaaler_message")->first())->value;
    return Inertia::render('Welcome/WelcomePage', ["users"=>$totalUsers, "games"=>$totalGames, "points"=>$totalPoints, "message"=>$message]);
})->name("welcome");

Route::get('/welcome', function () {
    $totalUsers = User::count();
    $totalGames = Mang::count();
    $totalPoints = Mang::sum("score_sum");
    $message = optional(DB::table('properties')->where("property", "reaaler_message")->first())->value;
    return Inertia::render('Welcome/WelcomePage', ["users"=>$totalUsers, "games"=>$totalGames, "points"=>$totalPoints, "message"=>$message]);
})->middleware('auth');

Route::get('/teaduskonkurss', function () {
    $totalUsers = User::count();
    $totalGames = Mang::count();
    $totalPoints = Mang::sum("score_sum");
    return Inertia::render('Welcome/TeaduskonkurssPage', ["users"=>$totalUsers, "games"=>$totalGames, "points"=>$totalPoints]);
})->middleware('auth');

Route::get("/handleForm", function (){
    return redirect()->route("welcome");
});

Route::controller(App\Http\Controllers\ProfileController::class)->middleware(['auth'])->group(function() {
    Route::get('/profile', "show")->name("profilePage");

    Route::post('/profile/settings/edit', "settings")->name("settingsAdd");
    Route::post('/profile/avatar/upload', 'changeProfilePicture')->name('changeProfilePicture');

    Route::get('/profile/{id}', "showPublic")->name("profilePublic");

    Route::post("/profile/{id}/delete", "deleteUser")->name("deleteUser");
});


//Login and registration
Route::controller(App\Http\Controllers\Auth\LoginRegisterController::class)->group(function() {
    Route::get('/register', 'register')->name('register');

    Route::post('/store', 'store')->name('store');
    Route::post('/store/google', 'storeGoogle')->name('storeGoogle');

    Route::get('/login', 'login')->name('login');
    
    Route::get('/authenticate/guest', 'authenticateGuest')->name('authenticateGuest');

    Route::get('/login/forgot-password', 'forgotPassword')->name('forgotPassword');
    

    
    Route::get('/logout', 'logout')->name('logout');
});

//Google login
Route::controller(App\Http\Controllers\GoogleLoginController::class)->group(function() {
    Route::get('/google/redirect', 'redirectToGoogle')->name('google.redirect');
    Route::get('/google/callback', 'handleGoogleCallback')->name('google.callback');
});

Route::controller(App\Http\Controllers\Auth\LoginRegisterController::class)->group(function () {
    Route::post('/authenticate', 'authenticate')->name('authenticate');
    Route::get('/dashboard', 'dashboard')->name('dashboard');
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
})->name("preview")->middleware(['auth']);

Route::get("/preview/competition/{id}", function ($id){
    $competition = Competition::findOrFail($id);

    if(!$competition->participants()->where('user_id', Auth::id())->exists()){
        return abort(404);
    }

    $now = Carbon::now();

    if ($now->between($competition->dt_start, $competition->dt_end)) {
        $attemptsLeft = $competition->attempt_count == 0 ? -1 : $competition->attempt_count - Mang::where("user_id", Auth::id())->where("competition_id", $id)->count();
        if($attemptsLeft == 0 || ($attemptsLeft == -1 && $competition->attempt_count != 0)) return redirect("/competition/".$id."/view");
        return Inertia::render("GamePreview/GamePreviewPage", ["type"=>explode(",", json_decode($competition->game_data, true)["mis"])[0], "competition"=>$competition, "attemptsLeft"=>$attemptsLeft]);    
    }

    return abort(404);
})->name("preview")->middleware('auth');


Route::post("/preview", function (Request $request){
    $request->validate([
        'mis' => 'required',
        'level' => 'required',
        'tyyp' => 'required',
        'aeg' => 'required',
    ]);

    session(['gameData' => ["mis"=>$request->mis, "level"=>$request->level, "tyyp"=>$request->tyyp, "aeg"=>$request->aeg, "competition_id"=>$request->competition_id]]);
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

    $competition = null;
    if($data["competition_id"] != null){
        $competition = Competition::findOrFail($data["competition_id"]);
    }
    return Inertia::render("Game/GamePage", ["mis"=>$mis, "tyyp"=>$tyyp, "raw_level"=>$level, "data" => app("App\Http\Controllers\MathController")->wrapper($mis, str_split($level), $tyyp, $aeg), "time"=>60*$aeg, "competition"=>$competition]);
})->name("gameNew")->middleware(['auth']);


//Game data
Route::controller(App\Http\Controllers\GameController::class)->middleware(["auth"])->group(function() {
    Route::post('/game/store', 'store')->name('gameStore');

    Route::post('/game/update', 'update')->name('gameUpdate');

    Route::get('/game/history/{id?}', 'show')->name('gameHistory');

    Route::post('/game/scoreboard', 'index')->name('gameScoreboard');
    
    Route::get("game/{id}/details", "gameDetails");

    Route::get("/stats", "showStats")->name("stats");
    Route::get("/stats/{id?}", "showStats")->name("statsPublic");
});

//Classroom data
Route::controller(App\Http\Controllers\ClassController::class)->middleware(["auth"])->group(function (){
    Route::get('/classroom/{id}/view/', 'show')->name('classShow');

    Route::get('/classroom/join', 'showJoin')->name('classJoin');
    Route::post('/classroom/join', 'join')->name('join');

    Route::get('/classroom/{id}/join', 'joinLink')->name('joinLink');
    Route::post('/classroom/{id}/join', 'joinLinkPost')->name('joinLinkPost')->middleware(['role:student']);

    Route::get('/classroom/{id}/edit', 'showEdit')->name('classEdit')->middleware(['role:teacher;admin']);
    Route::post('/classroom/{id}/edit', 'edit')->name('classEditPost')->middleware(['role:teacher;admin']);

    Route::get('/classroom/{id}/share', 'share')->name('classShare');

    Route::post('/classroom/remove/{id}', 'classRemove')->name('classRemove');

    Route::get('/classroom/new', 'newClass')->name('newClass')->middleware(['role:teacher']);
    Route::post('/classroom/new', 'store')->name('classStore')->middleware(['role:teacher']);

    Route::post('/classroom/{id}/delete', 'destroy')->name('classDelete')->middleware('role:teacher');

    Route::get("/classroom/{id}/export", "exportClass")->name("exportClass");
    Route::get('/classroom/all', 'showAll')->name('classAll')->middleware('role:teacher');
});

//Competition routes
Route::controller(App\Http\Controllers\CompetitionController::class)->middleware(["auth"])->group(function (){
    Route::get('/competition/{id}/view', 'view');
    Route::get('/competitions/view', 'competitionsView')->name("competitionsView");
    Route::post('/competition/{id}/remove/self', 'competitionRemoveSelf')->name("competitionRemoveSelf");
    Route::post('/competition/{id}/join', 'competitionJoin')->name("competitionJoin");
    Route::get('/competition/history/{id?}', 'competitionHistory')->name("competitionHistory");
    Route::get('/competition/{id}/view/{user_id}', 'competitionProfile');
    Route::get('/competition/{id}/export', 'exportCompetition')->middleware(["role:teacher;admin"]);

});


Route::get('/dashboard/old', function (){
    return Inertia::render("Dashboard/OldDashboardPage");
})->name("dashboard-old");

Route::get('/how-to-play', function (){
    return Inertia::render("Guide/GuidePage");
})->name("guide");

Route::controller(App\Http\Controllers\AdminController::class)->middleware(["auth", "role:admin"])->group(function (){
    Route::get("/students/manage", "adminShow")->name("admin");
    Route::get("/competitions/manage", "manageCompetitions")->name("manageCompetitions");
    Route::get("/competition/new", "competitionNew")->name("competitionNew");
    Route::post("/competition/new", "competitionAdd")->name("competitionNewPost");
    Route::post("/competition/delete", "competitionDelete")->name("competitionDelete");
    Route::get("/competition/{competition_id}/edit", "competitionNew")->name("competitionEdit");
    Route::get("/competition/{competition_id}/participants/add", "addParticipants")->name("addParticipants");
    Route::post("/competition/{competition_id}/participants/add", "addParticipantsPost")->name("addParticipantsPost");
    Route::post("/competition/{competition_id}/participants/remove", "removeParticipantsPost")->name("removeParticipantsPost");

});


Route::prefix("muusika")->controller(App\Http\Controllers\MusicController::class)->middleware(["auth", "music-auth"])->group(function (){
    Route::get("/", "get")->name("music");

    Route::get("/{id}/{link_id}", "showPlaylist")->name("playlist");

    Route::get("/{id}/{link_id}/lisa", "playlistAddSongs")->name("playlistAddSongs");
    Route::post("/{id}/{link_id}/lisa", "playlistAddSongsPost")->name("playlistAddSongs");

    Route::get("/uus-kava", "create")->name("musicNew");
    Route::post("/uus-kava", "newPlaylistPost")->name("musicNewPlaylistPost");
    Route::post("/kuulamiskava/kustuta", "playlistDelete")->name("deletePlaylist");

    Route::get("/teos-uus", "newSong")->middleware("role:music-admin")->name("musicNewSong");
    Route::post("/teos-uus", "newSongPost")->middleware("role:music-admin")->name("musicNewSongPost");

    Route::post("/teos/kustuta", "removeSong")->middleware("role:music-admin")->name("musicRemoveSong");
    Route::post("/teos/eemalda", "removeSongFrom")->name("musicRemoveSongFrom");

    Route::get("/autor", "artistSongs")->name("artistSongs");
});

Route::get("/muusika/keelatud", function (Request $request){
    if(!($request->user()->role == "guest" || ($request->user()->email_verified_at == null && $request->user()->google_id == null))){
        return redirect("/muusika");
    }
     return Inertia::render("Music/MusicNotAllowedPage");
})->name("muusikaKeelatud");



Route::get("/down", function (){
    
    if(!in_array("admin", explode(",", Auth::user()->role))){
        abort(404);
        return;
    }

    Artisan::call("down --render='errors::503' --secret='1630542a-246b-4b66-afa1-dd72a4c43515'");
    return "Rakendus on maas. Kasuta koodi 1630542a-246b-4b66-afa1-dd72a4c43515, et vaadata.";
});

Route::get("/up", function (){
    
    if(!in_array("admin", explode(",", Auth::user()->role))){
        abort(404);
        return;
    }

    Artisan::call("up");
    return "Rakendus on taas avalikult nähtav.";
});

require __DIR__.'/auth.php';