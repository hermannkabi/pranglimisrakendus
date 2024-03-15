<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Klass;
use App\Models\User;
use App\Models\Mang;

class LeaderboardController extends Controller
{
    public function getLeaderboardData($users){

        $data = [];

        foreach($users as $user){
            $kasutaja_mangud = Mang::select("experience")->where("user_id", $user->id)->get();
            $kokku = 0;

            foreach($kasutaja_mangud as $mang){
                $kokku += $mang->experience;
            }

            array_push($data, ["user"=>$user, "xp"=>$kokku]);
        }

        usort($data, function ($a, $b){
            return $a["xp"] >= $b["xp"] ? -1 : 1;
        });

        return $data;
    }
}
