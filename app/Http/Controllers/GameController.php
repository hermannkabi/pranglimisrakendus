<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mang;
use App\Models\User;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(string $id)
    {
        //
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
    public function update(Request $request, string $game_id)
    {
        $mang = Mang::findOrFail($game_id);

        $mang -> score_sum = $request['score_sum'];
        $mang -> accuracy_sum = $request['accuracy_sum'];
        $mang -> game_count = $request['game_count'];
        $mang -> last_level = $request['last_level'];
        $mang -> time = $request['time'];
        $mang -> dt = $request['dt'];

        $mang->save();

        return redirect()->route('dashboard')->with('Mang',$mang);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $game_id)
    {
        $Mang = Mang::findOrFail($game_id);
        $Mang->delete();
    }
}
