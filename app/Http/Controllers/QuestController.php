<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Random\Randomizer;

use App\Models\User;

class QuestController extends Controller
{
    const QUEST_COUNT = 3;

    public function activate($klass, $user){
        $quests = [
            1 =>['liitmine' || 'liitlahutamine' || 1 => [
                    1 => 'Soorita 5 mängu liitmisega',
                ],
                'lahutamine' || 'liitlahutamine' || 2 => [
                    1 => 'Soorita 5 mängu lahutamisega',
                ],
            ],
            5 => []
        
        ];
        $valim = array();
        $kogum = array();
        $array = array();
        $Metsa = random_int(1,$klass);
        for($i=0;$i<QuestController::QUEST_COUNT;$i++){
            $tüüp = random_int(1,4);
            $quest = random_int(1,3);

            array_push($valim, $quests[$Metsa < 5 ? 1:
            ($Metsa < 6 ? [1,5][array_rand([1,5])] : 
            ($Metsa < 7 ? [1,5,6][array_rand([1,5,6])] : 
            [1,5,6,7][array_rand([1,5,6,7])]))][$tüüp][$quest]);

            array_push($kogum, $tüüp);
            array_push($array, $quest);
        }
       
        
        
        $return = User::select($user);
        return 
        $return -> quests = $valim;
        $return -> quest_type = $kogum;
        $return -> quest_num = $array;
        $return -> quest_count_1 = 0;
        $return -> quest_count_2 = 0;
        $return -> quest_count_3 = 0;
    }
    
    public function check($user, $type){
        $one = 0;
        $two = 0;
        $three = 0;
        $quests = User::select($user)->get('quest_num');
        $quest1 = User::select($user)->get('quest_count_1');
        $quest2 = User::select($user)->get('quest_count_2');
        $quest3 = User::select($user)->get('quest_count_3');
        $quest_type = User::select($user)->get('quest_type');
        if(in_array($type, $quest_type)){
            if($quest_type == 1){
                if ($quests == 1){
                    $quest1 ++;
                    $one ++;
                }
                if ($quests == 2){
                    $one == 0 ? $quest1 ++ : $quest2 ++;
                    $one == 0 ? $one ++ : $two ++;
                }
                if ($quests == 3){
                    $one == 0 ? $quest1 ++ : ($two == 0 ? $quest2 ++ : $quest3 ++);
                    $one == 0 ? $one ++ : ($two == 0 ? $two ++ : $three ++);
                }
            }
            if($quest_type == 2){
                if ($quests == 1){
                    $one == 0 ? $quest1 ++ : ($two == 0 ? $quest2 ++ : ($three == 0 ? $quest3 ++ : 0));
                    $one == 0 ? $one ++ : ($two == 0 ? $two ++ : ($three == 0 ? $three ++ : 0));
                }
            }
            
        }
    }
    
    
}
