<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mang;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Scoreboard
        $koik = DB::table("mang")->get();
        return redirect()->route("scoreboard")->with($koik);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createMang($game_id, $user_id, $score_sum, $experience,$accuracy_sum,$game_count,$last_level,$time,$dt)
    {
        return Mang::create([
            'game_id' => $game_id,
            'user_id' => $user_id,
            'score_sum' => $score_sum,
            'experience' => $experience,
            'accuracy_sum' => $accuracy_sum,
            'game_count' => $game_count,
            'last_level'=> $last_level,
            'time' => $time,
            'dt' => $dt
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'game_id' => 'required|string|max:37',
            'user_id' => 'required|string|max:37',
            'score_sum' => 'required|string|max:37',
            'experience' => 'required|string|max:37',
            'accuracy_sum' => 'required|string|max:37',
            'game_count' => 'required|string|max:37',
            'last_level'=> 'required|string|max:37',
            'time' => 'date_format:H:i',
            'dt' => 'date_format:H:i'
        ]);
        
        $this->createMang($request->game_id,$request->user_id, $request->score_sum, $request->experience, $request->accuracy_sum, $request->game_count, $request->last_level, $request->time,$request->dt);
        $resources = $request->only('game_id', 'user_id', 'score_sum', 'experience', 'accuracy_sum', 'game_count', 'last_level', 'time', 'dt');
        if($resources){
            return redirect()->route("dashboard")->with($resources);
        }
        return redirect()->route("dashboard")->withErrors('Midagi läks valesti!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $user_id)
    {
        //Game history
        $mangud = DB::table('mang')->select($user_id)->get();
        return redirect()->route("history")->with($mangud);
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
     */
    public function update(string $user_id)
    {
        $Mang = Mang::select($user_id);
        $mang = User::where("id",'=',$user_id);
        foreach($Mang as $game){
            $mang -> score_sum += $game["score_sum"];
            $mang -> accuracy_sum += $game['accuracy_sum']/2;
            $mang -> game_count += $game['game_count'];
            $mang -> last_level = $game['last_level'];
            $mang -> time += $game['time'];
            $mang -> dt = $game['dt'];
        };
        
        $mang->save();
        return redirect()->route('dashboard')->with('Mang',$mang);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $user_id)
    {
        $mang = DB::table("Mang")->select($user_id)->get();
        $mang -> delete();

        // $range = Mang::where($user_id)->count();
        // for($count = 0; $count<$range;$count++){
        //     $Mang = Mang::findOrFail($user_id);
        //     $Mang->delete();
        // }
    }
}
