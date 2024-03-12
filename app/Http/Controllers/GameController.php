<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mang;
use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Str;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($search)
    {
        //Scoreboard
        /* $koik = DB::table("mangs")->chunk(50, function(Collection $mangud){
            foreach($mangud as $game){
                DB::table("users")->where('id',$game->user_id);
            }
        }); */
        $koik = DB::table('users')->where('id', Mang::get('user_id'))->orderBy($search == null ? 'score_sum' : $search)
        ->simplePaginate(25);
        // Option 1
        return redirect()->route("scoreboard")->with($koik);
        //Option 2
        return Inertia::render('scoreboard',$koik);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createMang($game, $game_type, $score_sum, $experience,$accuracy_sum, $game_count, $equation_count,$last_level,$last_equation,$time, $log)
    { 
        return Mang::create([
            'user_id' => Auth::id(),
            'game' => $game,
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

    function calculateExperience($time, $accuracy, $score_sum, $game_count){
        // (accuracy * (game count + score_sum))/time (min)
        return $time == 0 || $accuracy == 0 ? 0 : round(($accuracy*($score_sum + $game_count))/(100*$time/60));
    }

    /**
     * Store a newly created resource in storage.
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
            'log' => 'required|string|max:4444',
        ]);
        $this->createMang($request->game, $request->game_type, $request->score_sum, $this->calculateExperience($request->time, $request->accuracy_sum, $request->score_sum, $request->game_count), $request->accuracy_sum, $request->game_count, $request->equation_count, $request->last_level, $request->last_equation, $request->time, 
        $request->log);
        //$resources = $request->only('game_id', 'user_id', 'game', 'game_type', 'score_sum', 'experience', 'accuracy_sum', 'equation_count', 'last_level', 'last_equation', 'time', 'dt', 'log');

        return;
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //Game history
        $mangud = DB::table('mangs')->where('user_id',Auth::id())->orderBy("dt", "desc")->paginate(10);

        return Inertia::render('GameHistory/GameHistoryPage', ["games"=>$mangud]);
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

    //Dashboard stats
    public function getOverallStats($user_id){
        $mangud = DB::table('mangs')->where('user_id',$user_id == null ? Auth::id() : $user_id)->orderBy("dt", "desc")->get();

        $accuracy_sum = 0;
        $points_sum = 0;


        $count = sizeof($mangud);
        foreach($mangud as $mang){
            $accuracy_sum += $mang->accuracy_sum;
            $points_sum += $mang->score_sum;

        }

        $accuracy = $count > 0 ? round($accuracy_sum / $count) : 0;

        return ["total_training_count"=>$count, "accuracy"=>$accuracy, "points"=>$points_sum, "last_active"=>$count == 0 ? "-" : date_format(date_create($mangud->first()->dt), "d.m.Y")];

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
    public function getOverallSpecificStats(Request $request, $mangud){
        // arvuta listi $mangud statistika
        
        foreach($mangud as $game){
            $Mang = Mang::where('game_id', $game)->first()->get();

            //Game statistics
        }

        return Inertia::render('', ['data'=> '',]);
    }
    //Data for gamemode or gametype 
    public function getSpecificStats($õpilane, $gameMode, $gameType){
        $Mode = 0;
        $Type = 0;
        if($õpilane!=null){
            $student = User::find($õpilane)->get();
            $mangudStudent = DB::table('mangs')->where('user_id',$student->id)->orderBy("dt", "desc")->get();
            $Mode = $mangudStudent->where('game', $gameMode);
            $Type = $mangudStudent->where('game_type', $gameType);
        }else{
            $mangud = DB::table('mangs')->where('user_id', Auth::id())->orderBy("dt", "desc")->get();
            foreach($mangud as $g){
                if($gameType==null){
                    $g->where('game',$gameMode)->orderBy("dt", "desc")->get();

                }else{
                    
                }
            }
        }

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
    public function destroy(string $user_id)
    {
        $mang = Mang::where('user_id', $user_id);
        $mang -> delete();

    }
}
