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
        $Metsa = random_int(1,$klass);
        for($i=0;$i<QuestController::QUEST_COUNT;$i++){
            $suvaline = random_int(1,4);

            array_push($valim, $quests[$Metsa < 5 ? 1:
            ($Metsa < 6 ? [1,5][array_rand([1,5])] : 
            ($Metsa < 7 ? [1,5,6][array_rand([1,5,6])] : 
            [1,5,6,7][array_rand([1,5,6,7])]))][$suvaline][random_int(1,3)]);

            array_push($kogum, $suvaline);
        }
        
        $return = User::select($user);
        return 
        $return -> quests = $valim;
        $return -> quest_type = $kogum;
    }
    
    public function check($user, $type){
        $quests = User::select($user)->get('quests');
        $quest_type = User::select($user)->get('quest_type');
        if(in_array($type, $quest_type)){
            
        }
    }
    
    
}
