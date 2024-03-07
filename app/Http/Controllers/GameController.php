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
    public function createMang($score_sum, $experience,$accuracy_sum, $game_count, $equation_count,$last_level,$last_equation,$time, $log)
    { 
        return Mang::create([
            'user_id' => Auth::id(),
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
            'score_sum' => 'required|string|max:37',
            'accuracy_sum' => 'required|string|max:37',
            'game_count' => 'required|string|max:37',
            'last_level'=> 'required|string|max:37',
            'last_equation'=>'required|string|max:37',
            'time' => 'required',
            'log' => 'required|string|max:4444',
        ]);
        $this->createMang($request->score_sum, $this->calculateExperience($request->time, $request->accuracy_sum, $request->score_sum, $request->game_count), $request->accuracy_sum, $request->game_count, $request->equation_count, $request->last_level, $request->last_equation, $request->time, 
        $request->log);
        $resources = $request->only('game_id', 'user_id', 'score_sum', 'experience', 'accuracy_sum', 'equation_count', 'last_level', 'last_equation', 'time', 'dt', 'log');

        return;
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        //Game history
        $mangud = DB::table('mangs')->where('user_id',Auth::id())->take(10)->get();
        //Option 1
        return redirect()->route("game_history")->with($mangud);
        //Option 2
        return Inertia::render('GameHistory', $mangud);
    }

    public function getOverallStats(){
        $mangud = DB::table('mangs')->where('user_id',Auth::id())->orderBy("dt", "desc")->get();

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
     * Needs user id and top mistakes that the user makes
     */
    public function update_user(string $user_id, string $mistakes_tendency)
    {
        $Mang = Mang::select($user_id);
        $mang = User::where("id",$user_id);
        foreach($Mang as $game){
            $mang -> score_sum += $game["score_sum"];
            $mang -> accuracy_sum += $game['accuracy_sum']/2;
            $mang -> game_count += $game['game_count'];
            $mang -> last_level = $game['last_level'];
            $mang -> last_equation = $game['last_equation']->where('game_id',$game['last_level']->get('game_id'));
            $mang -> time += $game['time'];
            $mang -> dt = $game['dt'];
            $mang -> mistakes_tendency = $mistakes_tendency;
            $mang -> mistakes_sum += $game['mistakes_sum'];
        };
        
        $mang->save();
        //Option 1
        return redirect()->route('dashboard')->with('Mang',$mang);
        //Option 2
        return Inertia::render('DashboardPage', $mang);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $user_id)
    {
        $mang = DB::table("Mang")->where('user_id', $user_id)->get();
        $mang -> delete();

        // $range = Mang::where($user_id)->count();
        // for($count = 0; $count<$range;$count++){
        //     $Mang = Mang::findOrFail($user_id);
        //     $Mang->delete();
        // }
    }
}
