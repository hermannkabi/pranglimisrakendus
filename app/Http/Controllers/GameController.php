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
        ->take(25);
        // Option 1
        return redirect()->route("scoreboard")->with($koik);
        //Option 2
        return Inertia::render('scoreboard',$koik);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createMang($score_sum, $experience,$accuracy_sum,$equation_count,$last_level,$last_equation,$time,$dt, $log)
    { 
        return Mang::create([
            'user_id' => Auth::id(),
            'game_id' => (string)Str::uuid(),
            'score_sum' => $score_sum,
            'experience' => $experience,
            'accuracy_sum' => $accuracy_sum,
            'equation_count' => $equation_count,
            'last_level'=> $last_level,
            'last_equation' => $last_equation,
            'time' => $time,
            'dt' => $dt,
            'log' => $log,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'score_sum' => 'required|string|max:37',
            'experience' => 'required|string|max:37',
            'accuracy_sum' => 'required|string|max:37',
            'game_count' => 'required|string|max:37',
            'last_level'=> 'required|string|max:37',
            'last_equation'=>'required|string|max:37',
            'time' => 'required',
            'dt' => 'required',
            'log' => 'required|string|max:4444',
        ]);
        $this->createMang($request->score_sum, $request->experience, $request->accuracy_sum, $request->equation_count, $request->last_level, $request->last_equation, $request->time,$request->dt, 
        $request->log);
        $resources = $request->only('game_id', 'user_id', 'score_sum', 'experience', 'accuracy_sum', 'equation_count', 'last_level', 'last_equation', 'time', 'dt', 'log');
        if($resources){
            return Inertia::render('DashboardPage', $resources);    //($resources);
        }
        //Option 1
        return redirect()->route("dashboard")->withErrors('Midagi läks valesti!');
        //Option 2
        return Inertia::render('DashboardPage')->withErrors('Midagi läks valesti!');
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
