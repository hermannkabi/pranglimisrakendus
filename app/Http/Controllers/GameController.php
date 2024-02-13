<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Mang;

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
    public function create($game_id, $user_id, $score_sum, $accuracy_sum,$game_count,$last_level,$time,$dt)
    {
        return Mang::create([
            'game_id' => $game_id,
            'user_id' => $user_id,
            'score_sum' => $score_sum,
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
    public function destroy(string $id)
    {
        //
    }
}
