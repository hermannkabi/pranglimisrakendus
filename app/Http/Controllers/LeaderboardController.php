<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Mang;
use App\Models\User;
use App\Models\Klass;
use Illuminate\Http\Request;

/**
 * Get and send data for displaying on the leaderboard
*/
class LeaderboardController extends Controller
{
    public function getLeaderboardData($users){

        $data = [];

        foreach($users as $user){
            $kasutaja_mangud = Mang::orderBy("dt", "desc")->select("experience", "dt")->where("user_id", $user->id)->get();
            $kokku = 0;

            foreach($kasutaja_mangud as $mang){
                $kokku += $mang->experience;
            }

            array_push($data, ["user"=>$user, "xp"=>$kokku, "place"=>"0", "playedToday"=> count($kasutaja_mangud) <= 0 ? false : (new DateTime($kasutaja_mangud[0]->dt))->format('d.m.Y') == (new DateTime("today"))->format('d.m.Y')]);
        }

        usort($data, function ($a, $b){
            return $a["xp"] >= $b["xp"] ? -1 : 1;
        });

        //For testing purposes
        //$data = [0=>["user"=>"Hermann", "xp"=>100, "place"=>"0"], 1=>["user"=>"Hermann", "xp"=>90, "place"=>"0"], 2=>["user"=>"Hermann", "xp"=>90, "place"=>"0"], 3=>["user"=>"Hermann", "xp"=>90, "place"=>"0"], 4=>["user"=>"Hermann", "xp"=>89, "place"=>"0"], 5=>["user"=>"Hermann", "xp"=>70, "place"=>"0"], 6=>["user"=>"Hermann", "xp"=>70, "place"=>"0"]];

        foreach($data as $i=>$current){
            $previous = $i > 0 ? $data[$i - 1] : null;
            $next = ($i + 1) < count($data) ? $data[$i + 1] : null;


            if($previous != null){
                if($previous["xp"] == $current["xp"]){
                    $data[$i]["place"] = $previous["place"];
                    continue;
                }
            }

            if($next != null){
                if($next["xp"] == $current["xp"]){
                    $data[$i]["place"] = "T".($i+1);
                    continue;
                }
            }

            $data[$i]["place"] = strval($i+1);
        }

        return $data;
    }
}
