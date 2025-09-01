<?php

use Carbon\Carbon;
use App\Models\Fox;
use App\Models\User;
use Inertia\Inertia;
use App\Mail\TestMail;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

//VALIMISED

function opensAt($detailed=false){
    $opens_at = intval(DB::table('properties')->where("property", "opens_at")->first()->value);
    $vipAdvantage = intval(DB::table('properties')->where("property", "vip_advantage")->first()->value);
    $vipAdvantageUsed = Application::where("applicant", Auth::id())->where("application_type", "valimised-vip")->where("status", "granted")->exists() || str_contains(Auth::user()->role, "valimised-admin");
    $returnValue = $vipAdvantageUsed ? $opens_at - $vipAdvantage : $opens_at;
    return $detailed ? ["advantage_used"=>time() < $opens_at && time() >= ($opens_at - $vipAdvantage), "opens_at"=>$returnValue] : $returnValue;
}

function closesAt(){
    return intval(DB::table('properties')->where("property", "closes_at")->first()->value);
}

function canChooseSecond($request){
    if(!Application::where("applicant", Auth::id())->where("application_type", "valimised-twofox")->where("status", "granted")->exists()){
        return ["result"=>false];
    }else{
        // Second fox can be chosen only when it is the general election time (not during vip time)
        $vipTime = opensAt(true)["advantage_used"];
        if($vipTime){
            return ["result"=>false, "message"=>"(teine rebane peab olema valitud tavavalimiste ajal, mitte eelisajal)"];
        }else{
            return ["result"=>true];
        }
    }
}

Route::prefix("valimised")->name("valimised.")->middleware(["valimised-time"])->group(function (){

    Route::get("/notverified", function (){if(Auth::user()->email_verified_at != null){return redirect()->route("valimised.dashboard");} return view("notverified");})->middleware(["auth"])->name("notverified");

    Route::get("/apply", function (){
        $applications = Application::where('applicant', Auth::id())
        ->whereIn('application_type', ['valimised-basic', 'valimised-vip', 'valimised-twofox'])->latest()->get();
        return view("apply", ["applications"=>$applications]);
    })->name("apply")->middleware("auth");

    Route::post("/apply", function (Request $request){
        Application::create([
            "applicant"=>Auth::id(),
            "application_type"=>$request->type,
        ]);

        return redirect()->back();
    });

    Route::middleware(['auth', 'notguest'])->group(function () {

        Route::get("", function (){
            return redirect()->route("valimised.dashboard");
        });

        Route::get("/logout", function (){
            Auth::logout();
    
            return redirect()->route("valimised.dashboard");
        })->name("logout");       
    
        Route::get("/logi", function (){
            $path = storage_path('logs/valimised.log');

            if (!File::exists($path)) {
                abort(404);
            }
    
            $file = File::get($path);
            $response = Response::make($file, 200);
            $response->header('Content-Type', 'text/plain');
    
            return $response;    
        })->name("log");

        Route::get('/valimine', function (Request $request) {

            $OPENS_AT = opensAt();
            $CLOSES_AT = closesAt();
        

            if(($OPENS_AT != null && time() < $OPENS_AT)){
                return view("notyetopen", ["opens_at"=>$OPENS_AT]);
            }
    
            if(($CLOSES_AT != null && time() > $CLOSES_AT)){
                return view("notopen");
            }
    
            return view('dashboard', ["foxes"=>Fox::orderBy('last_name')->get()]);
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
            
            $secondAllowed = canChooseSecond($request);
            if($secondAllowed["result"]){
                $firstFox = Fox::where("chosen_by", $userId)->orderBy("updated_at", "DESC")->first();
                Fox::where("chosen_by", $userId)->update(["chosen_by"=>null]);
                if($firstFox) Fox::where("id", $firstFox->id)->update(["chosen_by"=>$userId]);
            }else{
                Fox::where("chosen_by", $userId)->update(["chosen_by"=>null]);
            }
    
            $fox->chosen_by = $userId;
            $fox->save();
            Log::channel("valimised")->info("[". $request->user()->eesnimi . " " . $request->user()->perenimi. "(" . $request->user()->id .")]: Päring õnnestub! Uus rebane on ". $fox->name . "(" . $fox->id .")");

    
            return redirect()->back()->withSuccess($fox->name . " on edukalt valitud! \n". ($secondAllowed["message"] ?? ""));
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

            $secondAllowed = canChooseSecond($request);
            if($secondAllowed["result"]){
                $firstFox = Fox::where("chosen_by", Auth::id())->orderBy("updated_at", "DESC")->first();
                Fox::where("chosen_by", Auth::id())->update(["chosen_by"=>null]);
                if($firstFox) Fox::where("id", $firstFox->id)->update(["chosen_by"=>Auth::id()]);
            }else{
                Fox::where("chosen_by", Auth::id())->update(["chosen_by"=>null]);
            }

            $randomFox->chosen_by = Auth::id();
            $randomFox->save();

            Log::channel("valimised")->info("[". $request->user()->eesnimi . " " . $request->user()->perenimi. "(" . $request->user()->id .")]: Päring õnnestub! Uus rebane on ". $randomFox->name . "(" . $randomFox->id .")");

            return redirect()->back()->withSuccess($randomFox->name . " on edukalt valitud! " . ($secondAllowed->message ?? ""));
        })->name("foxRandom");

        Route::post("/fox/clear/self", function (Request $request){
            Log::channel("valimised")->info("[". $request->user()->eesnimi . " " . $request->user()->perenimi. "(" . $request->user()->id .")]: Päring rebase eemaldamiseks enda kontolt!");
    
            Fox::where("chosen_by", $request->user()->id)->update(["chosen_by"=>null]);
            Log::channel("valimised")->info("[". $request->user()->eesnimi . " " . $request->user()->perenimi. "(" . $request->user()->id .")]: Päring õnnestub! Rebane enda kontolt eemaldatud");
    
            return redirect()->back()->withSuccess("Rebane kontolt eemaldatud!");
        })->name("foxClearSelf");
    
        Route::get("/profile", function (){
            return view("profile", ["name"=>ucwords(Auth::user()->eesnimi . " " . Auth::user()->perenimi), "fox"=>Fox::where("chosen_by", Auth::id())->get()]);
        })->name("profile");
    });
    
    Route::middleware(['auth', 'role:valimised-admin'])->group(function () {
        Route::get("/rebane/lisa", function (){
            return view("addfox");
        })->name("addFox");
    
        Route::post("/fox/add", function (Request $request){
            Log::channel("valimised")->info("[". $request->user()->eesnimi . " " . $request->user()->perenimi. "(" . $request->user()->id .")]: Päring rebase lisamiseks!");
    
            $request->validate([
                "foxnames"=>"required|min:2|string",
            ], [
                "foxnames.required"=>"Rebaste nimed on kohustuslik väli",
                "foxnames.min"=>"Nimi peab olema vähemalt 2 tähemärki",
            ]);

            foreach(explode("\r\n", trim($request->foxnames)) as $foxname){
                $foxname = preg_replace('/\s+/', ' ', $foxname);
                $lastSpacePos = strrpos($foxname, ' ');
                $firstName = substr($foxname, 0, $lastSpacePos);
                $lastName = substr($foxname, $lastSpacePos + 1);

                $fox = Fox::create(["first_name"=>$firstName, "last_name"=>$lastName, "chosen_by"=>null]);
                Log::channel("valimised")->info("[". $request->user()->eesnimi . " " . $request->user()->perenimi. "(" . $request->user()->id .")]: Päring õnnestub! Loodud rebane ". $fox->name . "(" . $fox->id .")");    
            }
    
    
            return redirect()->route("valimised.addFox")->withSuccess("Rebased on lisatud!");
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
    
            $foxes = Fox::orderBy('last_name')->get();
            $data = [];
    
            foreach($foxes as $fox){
                
                $chosen_by = $fox->chosen_by == null ? null : User::where("id", $fox->chosen_by)->first();
                $chosen_by_name = null;
                $chosen_by_email = null;
    
                if($chosen_by){
                    $chosen_by_name = ucwords($chosen_by->eesnimi . " " . $chosen_by->perenimi);
                    $chosen_by_email = $chosen_by->email;
                }
    
                array_push($data, ["id"=>$fox->id, "fox_name"=>$fox->name, "chosen_by_name"=>$chosen_by_name, "chosen_by_email"=>$chosen_by_email]);
            }
    
            return view("foxlist", ["data"=>$data]);
        })->name("foxList");

        Route::get("/halda", function (){

            $data = DB::table('properties')
                ->whereIn('property', ['opens_at', 'closes_at', "test"])
                ->get();

            $opensAt = $data->firstWhere('property', 'opens_at')->value;
            $closesAt = $data->firstWhere('property', 'closes_at')->value;

            $opensAtForInput = $opensAt == null ? "" : Carbon::createFromTimestamp($opensAt)->format('Y-m-d\TH:i');
            $closesAtForInput = $closesAt == null ? "" : Carbon::createFromTimestamp($closesAt)->format('Y-m-d\TH:i');
                    
            $test = $data->firstWhere('property', 'test')->value == 1;


            $applications = Application::with("applicantUser")->whereIn('application_type', ['valimised-basic', 'valimised-vip', 'valimised-twofox'])->whereNotIn('status', ['granted', 'denied'])->get();


            return view("properties", ["opens_at"=>$opensAtForInput, "closes_at"=>$closesAtForInput, "test"=>$test, "applications"=>$applications]);
        })->name("properties");

        Route::post("/halda", function (Request $request){
            $opensAt = $request->opens_at;
            $closesAt = $request->closes_at;

            $opensAtTimestamp = $opensAt == null ? null : Carbon::parse($opensAt)->timestamp;
            $closesAtTimestamp = $closesAt == null ? null : Carbon::parse($closesAt)->timestamp;

            DB::table('properties')
            ->where('property', 'opens_at')
            ->update(['value' => $opensAtTimestamp]);

            DB::table('properties')
            ->where('property', 'closes_at')
            ->update(['value' => $closesAtTimestamp]);

            DB::table('properties')
            ->where('property', 'test')
            ->update(['value' => $request->test == 1]);

            return redirect()->back();

        })->name("propertiesPost");

        Route::post("/results/clear", function (Request $request){
            Fox::query()->update(['chosen_by' => null]);
            Log::channel("valimised")->info("[". $request->user()->eesnimi . " " . $request->user()->perenimi. "(" . $request->user()->id .")]: Lähtestas valimistulemused!");

            return redirect()->back();
        })->name("clearResults");


        Route::post("/application/{application_id}/decide", function (Request $request, $application_id){
            $decision = $request->decision;

            $application = Application::findOrFail($application_id);
            $application->status = $decision ?? "error";
            $application->message = $request->message;

            $application->save();

            return redirect()->back();
        });

        Route::get("/info", function(){return view("admininfo");})->name("admininfo");
    });


    
});