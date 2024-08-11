<?php

use App\Models\Fox;
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
    return Inertia::render('Welcome/WelcomePage');
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
    Route::get('/register/google', 'registerGoogle')->name('registerGoogle');

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

Route::get("/preview", function (){
    return Inertia::render("GamePreview/GamePreviewPage");
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


//VALIMISED

function opensAt(){
    $opens_at = intval(DB::table('properties')->where("property", "opens_at")->first()->value);
    return in_array(Auth::user()->role, ["valimised-vip", "valimised-admin"]) ? $opens_at - 5 : $opens_at;
}

function closesAt(){
    return intval(DB::table('properties')->where("property", "closes_at")->first()->value);
}

Route::prefix("valimised")->name("valimised.")->group(function (){

    Route::get("/notverified", function (){if(Auth::user()->email_verified_at != null){return redirect()->route("valimised.dashboard");} return view("notverified");})->middleware(["auth"])->name("notverified");

    Route::middleware(['auth', 'notguest'])->group(function () {

        Route::get("", function (){
            return redirect()->route("valimised.dashboard");
        });

        Route::get("/logout", function (){
            Auth::logout();
    
            return redirect()->route("valimised.dashboard");
        })->name("logout");       
    
    
        Route::get('/valimine', function () {

            $OPENS_AT = opensAt();
            $CLOSES_AT = closesAt();
        

            if(($OPENS_AT != null && time() < $OPENS_AT)){
                return view("notyetopen", ["opens_at"=>$OPENS_AT]);
            }
    
            if(($CLOSES_AT != null && time() > $CLOSES_AT)){
                return view("notopen");
            }
    
            return view('dashboard', ["foxes"=>Fox::orderBy('name')->get()]);
        })->name("dashboard");
    
        Route::post("/rebane/vali", function (Request $request){

            Log::channel("valimised")->info("[". $request->user()->eesnimi . " " . $request->user()->perenimi. "(" . $request->user()->id .")]: Päring rebase valimiseks käsitsi");

            $OPENS_AT = opensAt();
            $CLOSES_AT = closesAt();

            if(($OPENS_AT != null && time() < $OPENS_AT) || ($CLOSES_AT != null && time() > $CLOSES_AT)){
                return view("notopen");
            }
    
            $request->validate([
                "id"=>"required",
            ], [
                "id.required"=>"Midagi läks valesti",
            ]);
    
            $fox = Fox::where("id", $request->id)->first();

            $userId = Auth::id();
    
            if($fox == null){
                Log::channel("valimised")->info("[". $request->user()->eesnimi . " " . $request->user()->perenimi. "(" . $request->user()->id .")]: Päring ebaõnnestub: rebast ei leitud!");
                return redirect()->back()->with("error", "Midagi läks valesti!");
            }

            Log::channel("valimised")->info("[". $request->user()->eesnimi . " " . $request->user()->perenimi. "(" . $request->user()->id .")]: Tuvastatud valik on ". $fox->name . "(" . $fox->id .")");
    
            if($fox->chosen_by != null){
                Log::channel("valimised")->info("[". $request->user()->eesnimi . " " . $request->user()->perenimi. "(" . $request->user()->id .")]: Päring ebaõnnestub: rebane juba valitud!");

                return redirect()->back()->with("error", "See rebane on paraku juba valitud!");
            }
    
            Fox::where("chosen_by", $userId)->update(["chosen_by"=>null]);
    
            $fox->chosen_by = $userId;
            $fox->save();
            Log::channel("valimised")->info("[". $request->user()->eesnimi . " " . $request->user()->perenimi. "(" . $request->user()->id .")]: Päring õnnestub! Uus rebane on ". $fox->name . "(" . $fox->id .")");

    
            return redirect()->back()->withSuccess($fox->name . " on edukalt valitud!");
        })->name("chooseFox");

        Route::post("fox/random", function (Request $request){
            Log::channel("valimised")->info("[". $request->user()->eesnimi . " " . $request->user()->perenimi. "(" . $request->user()->id .")]: Päring rebase valimiseks juhuslikult");

            $OPENS_AT = opensAt();
            $CLOSES_AT = closesAt();

            if(($OPENS_AT != null && time() < $OPENS_AT) || ($CLOSES_AT != null && time() > $CLOSES_AT)){
                return view("notopen");
            }

            $randomFox = Fox::where("chosen_by", null)->inRandomOrder()->first();
            if($randomFox == null){
                Log::channel("valimised")->info("[". $request->user()->eesnimi . " " . $request->user()->perenimi. "(" . $request->user()->id .")]: Juhusliku rebase valik ebaõnnestub!");
                return redirect()->back()->with("error", "Kõik rebased on juba valitud!");
            }

            Log::channel("valimised")->info("[". $request->user()->eesnimi . " " . $request->user()->perenimi. "(" . $request->user()->id .")]: Juhuslik rebane valitud! Valitud rebane on ". $randomFox->name . "(" . $randomFox->id .")");

            $currentFox = Fox::where("chosen_by", Auth::id())->first();

            if($currentFox != null){
                $currentFox->chosen_by = null;
                $currentFox->save();
            }

            $randomFox->chosen_by = Auth::id();
            $randomFox->save();

            Log::channel("valimised")->info("[". $request->user()->eesnimi . " " . $request->user()->perenimi. "(" . $request->user()->id .")]: Päring õnnestub! Uus rebane on ". $randomFox->name . "(" . $randomFox->id .")");

            return redirect()->back()->withSuccess($randomFox->name . " on edukalt valitud!");
        })->name("foxRandom");
    
        Route::get("/profile", function (){
            return view("profile", ["name"=>ucwords(Auth::user()->eesnimi . " " . Auth::user()->perenimi), "fox"=>Fox::where("chosen_by", Auth::id())->first()]);
        })->name("profile");
    });

    Route::middleware(['auth', 'role:valimised-admin'])->group(function () {
        Route::get("/rebane/lisa", function (){
            return view("addfox");
        })->name("addFox");
    
        Route::post("/fox/add", function (Request $request){
            Log::channel("valimised")->info("[". $request->user()->eesnimi . " " . $request->user()->perenimi. "(" . $request->user()->id .")]: Päring rebase lisamiseks!");
    
            $request->validate([
                "name"=>"required|min:4|string",
            ], [
                "name.required"=>"Nimi on kohustuslik väli",
                "name.min"=>"Nimi peab olema vähemalt 4 tähemärki",
            ]);
    
            $fox = Fox::create(["name"=>$request->name, "instagram"=>$request->instagram, "facebook"=>$request->facebook, "chosen_by"=>null]);
            Log::channel("valimised")->info("[". $request->user()->eesnimi . " " . $request->user()->perenimi. "(" . $request->user()->id .")]: Päring õnnestub! Loodud rebane ". $fox->name . "(" . $fox->id .")");
    
            return redirect()->route("valimised.addFox")->withSuccess("Rebane on lisatud!");
        })->name("addFoxPost");
    
        Route::post("/fox/delete", function (Request $request){
            Log::channel("valimised")->info("[". $request->user()->eesnimi . " " . $request->user()->perenimi. "(" . $request->user()->id .")]: Päring rebase kustutamiseks!");

            $request->validate([
                "id"=>"required",
            ], [
                "id.required"=>"Midagi läks valesti",
            ]);
    
            $fox = Fox::where("id", $request->id)->first();
            $fox->delete();

            Log::channel("valimised")->info("[". $request->user()->eesnimi . " " . $request->user()->perenimi. "(" . $request->user()->id .")]: Päring õnnestub! Kustutatud rebane ". $fox->name . "(" . $fox->id .")");
    
            return redirect()->back()->withSuccess("Rebane eemaldatud!");
        })->name("foxDelete");

        Route::post("/fox/clear", function (Request $request){
            Log::channel("valimised")->info("[". $request->user()->eesnimi . " " . $request->user()->perenimi. "(" . $request->user()->id .")]: Päring rebaselt jumala eemaldamiseks!");

            $request->validate([
                "id"=>"required",
            ], [
                "id.required"=>"Midagi läks valesti",
            ]);
    
            $fox = Fox::where("id", $request->id)->first();
            $fox->update(["chosen_by"=>null]);
            Log::channel("valimised")->info("[". $request->user()->eesnimi . " " . $request->user()->perenimi. "(" . $request->user()->id .")]: Päring õnnestub! Valimiseks on avatud rebane ". $fox->name . "(" . $fox->id .")");
    
            return redirect()->back()->withSuccess("Rebane valimiseks saadaval!");
        })->name("foxClear");
    
        Route::get("/nimekiri", function (){
    
            $foxes = Fox::orderBy('name')->get();
            $data = [];
    
            foreach($foxes as $fox){
                
                $chosen_by = $fox->chosen_by == null ? null : User::where("id", $fox->chosen_by)->first();
                $chosen_by_name = null;
                $chosen_by_email = null;
    
                if($chosen_by){
                    $chosen_by_name = ucwords($chosen_by->eesnimi . " " . $chosen_by->perenimi);
                    $chosen_by_email = $chosen_by->email;
                }
    
                array_push($data, ["fox_name"=>$fox->name, "chosen_by_name"=>$chosen_by_name, "chosen_by_email"=>$chosen_by_email]);
            }
    
            return view("foxlist", ["data"=>$data]);
        })->name("foxList");
    });
    
});


require __DIR__.'/auth.php';


