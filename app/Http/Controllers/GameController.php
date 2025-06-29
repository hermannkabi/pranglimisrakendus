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
        $mangud = DB::table('mangs')->where('user_id', $user->id)->orderBy("dt", "desc")->paginate(10);

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
        $mangud = DB::table('mangs')->where('user_id',$user_id == null ? Auth::id() : $user_id)->orderBy("dt", "desc")->get();

        $accuracy_sum = 0;
        //Score as points
        $points_sum = 0;
        //Game time
        $time_sum = 0;
        //Game proficiency
        $proficiency = array(
            "liitmine" => 0,
            "lahutamine" => 0,
            "korrutamine" => 0,
            "jagamine" => 0,
            "astendamine" => 0,
            "juurimine" => 0,
            "astejuurimine" => 0,
            "jaguvus" => 0,
            "murruTaandamine" => 0,
            "v%C3%B5rdlemine" => 0,
            "lünkamine" => 0,
            "kujundid"  => 0,
        );

        $count = sizeof($mangud);

        foreach($mangud as $mang){
            $accuracy_sum += $mang->accuracy_sum;
            $points_sum += $mang->score_sum;
            if(array_key_exists($mang->game, $proficiency)){
                $proficiency[$mang->game] += $mang->experience;
            };
            $time_sum += $mang->time;
        }

        $proficiency_sorted = asort($proficiency);

        //Average accuracy
        $accuracy = $count > 0 ? round($accuracy_sum / $count) : 0;
        //Average time
        $avg_time = $count > 0 ? round($time_sum / $count) : 0;

        //Streak
        $streak = app(ProfileController::class)->viewStreak($user_id);

        $streak_active = User::where("id", $user_id)->first()->streak_active;

        //Send all gathered information to frontend
        return ["total_training_count"=>$count, "accuracy"=>$accuracy, "points"=>$points_sum, 'proficiency'=>$proficiency_sorted, 'streak'=>$streak, "streak_active"=>$streak_active, "average_time"=>$avg_time, "last_active"=>$count == 0 ? "-" : date_format(date_create($mangud->first()->dt), "d.m.Y")];

    }

    //Get all the game details
    public function gameDetails($id){
        $mang = Mang::where("game_id", $id)->first();

        if($mang){
            $manguAutor = User::where("id", $mang->user_id)->first();
            $competition = $mang->competition_id == null ? null : Competition::find($mang->competition_id);
            return Inertia::render("GameDetails/GameDetailsPage", ["game"=>$mang, "playedBy"=>$manguAutor, "competition"=>$competition]);
        }

        abort(404);

        return redirect()->route("dashboard");
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
}
