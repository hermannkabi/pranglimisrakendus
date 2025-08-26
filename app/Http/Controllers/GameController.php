<?php

namespace App\Http\Controllers;

use App\Models\Mang;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Klass;
use App\Models\Competition;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class GameController extends Controller
{
    /**
     * Creating a game from given data.
     */
    public function createMang($game, $competition_id, $game_type, $score_sum, $experience,$accuracy_sum, 
    $game_count, $equation_count,$last_level,$last_equation,$time, $log)
    { 
        return Mang::create([
            'user_id' => Auth::id(),
            'game' => $game,
            'competition_id' => $competition_id, 
            'game_type' => $game_type,
            'game_id' => (string)Str::uuid(),
            'score_sum' => $score_sum,
            'game_count' => $game_count,
            'experience' => $experience,
            'accuracy_sum' => $accuracy_sum,
            'equation_count' => $equation_count,
            'last_level'=> $last_level,
            'last_equation' => $last_equation,
            'time' => $time,
            'dt' => date('Y-m-d H:i:s'),
            'log' => $log,
        ]);
    }

    function calculateExperience($time, $accuracy, $score_sum, $game_count, $game){
        return $time == 0 || $accuracy == 0 ? 0 : round(($accuracy*($score_sum + $game_count))/(100*$time/60));
    }

    /**
     * Storing games to DB.
     */
    public function store(Request $request)
    {
        $request->validate([
            'game' =>'required|string|max:37',
            'game_type' => 'required|string|max:37',
            'score_sum' => 'required|string|max:37',
            'accuracy_sum' => 'required|string|max:37',
            'game_count' => 'required|string|max:37',
            'last_level'=> 'required|string|max:37',
            'last_equation'=>'required|string|max:37',
            'time' => 'required',
            'log' => 'required|string',
        ]);
        $mang = $this->createMang($request->game, $request->competition_id, $request->game_type, $request->score_sum, $this->calculateExperience($request->time, $request->accuracy_sum, $request->score_sum, $request->game_count, $request->game), $request->accuracy_sum, $request->game_count, $request->equation_count, $request->last_level, $request->last_equation, $request->time, 
        $request->log);
        app(ProfileController::class)->updateStreak(Auth::id());

        // Forget the session data
        session()->forget('gameData');    
        return;
    }

    /**
     * Display game history.
     */
    public function show(?string $id=null)
    {
        $user = User::where("id", $id ?? Auth::id())->first();
        $klass = Klass::where("klass_id", $user->klass)->first();

        $logged_in_user = Auth::user();
        $logged_in_klass = Klass::where("klass_id", $logged_in_user->klass)->first();


         // You can see your own public profile, no matter what
         // admin too
         if($user->id != $logged_in_user->id && !str_contains($logged_in_user, "admin")){
            if(str_contains($user->role, "teacher")){
                // A teacher can be seen only by their students
                if($logged_in_klass==null || $logged_in_klass->teacher_id != $user->id){
                    abort(403);
                }
            }else if(str_contains($user->role, "student")){
                // A student can be seen by their teacher or their classmates
                if($klass == null || ($klass->teacher_id != $logged_in_user->id && $klass->klass_id != $logged_in_user->klass)){
                    abort(403);
                }
            }else{
                // Dont show guest account
                abort(404);
            }
        }

        //Game history
        $mangud = DB::table('mangs')->where('user_id', $user->id)->orderBy("dt", "desc")->paginate(30);

        return Inertia::render('GameHistory/GameHistoryPage', ["games"=>$mangud, "stats"=>$this->getOverallStats($user->id)]);
    }

    /**
     * Get user information for Game History
     */
    public function getUserExp($user_id){
        $mangud = Mang::where('user_id', $user_id)->get();
        $sumExp = 0;
        
        foreach($mangud as $game){
            $sumExp += $game->experience;
            
        }
        return $sumExp;
    }

    //Show all game related stats like accuracy, game time, points and streak.  
    public function getOverallStats($user_id){
        $mangud = DB::table('mangs')->where('user_id',$user_id ?? Auth::id())->orderBy("dt", "desc")->get();
        $count = $mangud->count();
        $accuracy = round($mangud->avg('accuracy_sum'));
        $avg_time = round($mangud->avg('time'));
        $total_time = $mangud->sum('time');

        $points_sum = $mangud->sum('experience');
        //Streak
        $streak = app(ProfileController::class)->viewStreak($user_id);

        $streak_active = User::where("id", $user_id)->first()->streak_active;

        $total_students = User::where("role", "like", "%student%")->count();
        $total_classes = Klass::count();
        $total_competitions = Competition::count();
        
        //Send all gathered information to frontend
        return ["total_training_count"=>$count, "accuracy"=>$accuracy, "points"=>$points_sum, 'streak'=>$streak, "streak_active"=>$streak_active, "average_time"=>$avg_time, "total_time"=>$total_time, "total_students"=>$total_students, "total_classes"=>$total_classes, "total_competitions"=>$total_competitions];
    }

    //Get all the game details
    public function gameDetails($id){
        $mang = Mang::where("game_id", $id)->first();

        if($mang){
            $manguAutor = User::where("id", $mang->user_id)->first();
            $competition = $mang->competition_id == null ? null : Competition::find($mang->competition_id);
            return Inertia::render("GameDetails/GameDetailsPage", ["game"=>$mang, "playedBy"=>$manguAutor, "competition"=>$competition]);
        }else{
            abort(404);
        }
    }

    //Extra stats (total play time etc)
    // public function getOverallSpecificStats(Request $request){
    //     $mangud = DB::table('mangs')->where('user_id',Auth::id())->orderBy("dt", "desc")->get();

    //     $totalTime = 0;
    //     $mostActiveDay =''; //TODO:
    //     $expTotal = 0;
    //     $mostPlayedGame = '';
    //     $mostPlayedGameType = '';

    //     foreach($mangud as $mang){
    //         //$mainMistake += $mang->;
    //         $totalTime += $mang->time;
    //         $expTotal += $mang->experience;


    //     }
        
    // }

    //Game specific statistics like most popular mistake... 
    public function getOverallSpecificStats(Request $request, $mangud){
        
        foreach($mangud as $game){
            $Mang = Mang::where('game_id', $game)->first()->get();

            //Game statistics
        }

        return Inertia::render('', ['data'=> '',]);
    }
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * 
     * TODO:top mistakes that the user makes
     */

    /**
     * Remove the specified resource from storage.
     */

    //Delete all games(when user is deleted)
    public function destroy(string $user_id)
    {
        $mang = Mang::where('user_id', $user_id);
        $mang -> delete();

    }

    
function getMangStats($userId) {
    $today = Carbon::today();
    $periods = [
        'week' => 7,
        'month' => 30,
        'year' => 365,
    ];

    $stats = [
        'gameCount' => [],
        'gameTypes' => [],
    ];

    foreach ($periods as $key => $days) {
        $startDate = $today->copy()->subDays($days - 1);

        // --- Game counts per day ---
        $countsByDate = Mang::select(DB::raw('DATE(dt) as date'), DB::raw('COUNT(*) as total'))
            ->where('dt', '>=', $startDate->startOfDay())
            ->where('dt', '<=', $today->endOfDay())
            ->where("user_id", $userId)
            ->groupBy(DB::raw('DATE(dt)'))
            ->pluck('total', 'date');

        $dailyCounts = [];
        for ($i = 0; $i < $days; $i++) {
            $date = $startDate->copy()->addDays($i)->toDateString();
            $dailyCounts[] = $countsByDate->get($date, 0);
        }
        $stats['gameCount'][$key] = $dailyCounts;

        // --- Game types percentages ---
        $totalGames = Mang::where('dt', '>=', $startDate->startOfDay())
            ->where('dt', '<=', $today->endOfDay())
            ->where("user_id", $userId)
            ->count();

        if ($totalGames === 0) {
            $stats['gameTypes'][$key] = [];
        } else {
            $gameTypeCounts = Mang::select('game', DB::raw('COUNT(*) as total'))
                ->where('dt', '>=', $startDate->startOfDay())
                ->where('dt', '<=', $today->endOfDay())
                ->where("user_id", $userId)
                ->groupBy('game')
                ->pluck('total', 'game');

            $percentages = [];
            foreach ($gameTypeCounts as $game => $count) {
                $percentages[$game] = round(($count / $totalGames) * 100, 2);
            }
            arsort($percentages);
            $stats['gameTypes'][$key] = $percentages;
        }
    }

    return $stats;
}

    function getPlayedDates($userId) {
        $query = Mang::query();

        $query->where('user_id', $userId);

        // Get distinct dates
        $dates = $query->selectRaw('DATE(dt) as date')
            ->distinct()
            ->orderBy('date', 'asc')
            ->pluck('date'); // returns ['2025-08-07', '2025-08-14', ...]

        // Convert to [day, month, year]
        $formatted = $dates->map(function ($date) {
            $c = Carbon::parse($date);
            return [$c->day, $c->month, $c->year];
        });

        return $formatted->toArray();
    }

    public function showStats($id=null){

        $user = User::where("id", $id ?? Auth::id())->first();
        $logged_in_user = Auth::user();
        $logged_in_klass = Klass::where("klass_id", $logged_in_user->klass)->first();

        if($user != null){            
            $klass = Klass::where("klass_id", $user->klass)->first();

            // You can see your own public profile, no matter what
            if($user->id != $logged_in_user->id && !str_contains($logged_in_user, "admin")){
                if(str_contains($user->role, "teacher")){
                    // A teacher can be seen only by their students
                    if($logged_in_klass==null || $logged_in_klass->teacher_id != $user->id){
                        abort(403);
                    }
                }else if(str_contains($user->role, "student")){
                    // A student can be seen by their teacher or their classmates
                    if($klass == null || ($klass->teacher_id != $logged_in_user->id && $klass->klass_id != $logged_in_user->klass)){
                        abort(403);
                    }
                }else{
                    // Dont show guest account
                    abort(404);
                }
            }
            
            
            $stats = app(GameController::class)->getOverallStats($user->id);

            $lastGames = Mang::where('user_id', $user->id)->orderBy("dt", "desc")->take(3)->get();

        }else{
            abort(404);
        }

        if($id!=null && $user == null) return abort(404);

        $stats = $this->getOverallStats($user->id);

        $gameStats = $this->getMangStats($user->id);

        $stats["gameCount"] = $gameStats["gameCount"];
        $stats["gameTypes"] = $gameStats["gameTypes"];
        $stats["streakDays"] = $this->getPlayedDates($user->id);

        return Inertia::render("Stats/StatsPage", ["stats"=>$stats]);
    }
}
