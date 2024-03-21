<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Random\Randomizer;

use App\Models\User;

class QuestController extends Controller
{
    //const QUEST_COUNT = 3;

    const KOGUM = [
        'liitmine' => 5,
        'lahutamine' => 5,
        'korrutamine' => 4,
        'jagamine' => 4,
    ];
    public function activate(){
        $massiiv = [
            'liitmine',
            'lahutamine',
            'korrutamine',
            'jagamine',
        ];

        
        $võti = array_rand($massiiv, 2);
        $ülesanne1 = $massiiv[$võti[0]];
        $ülesanne2 = $massiiv[$võti[1]];
        $ülesanne3 = $massiiv[$võti[2]];

        //Salvestab Andmebaasi...

        

        $mituÜlesannet1 = QuestController::KOGUM[$ülesanne1];
        $mituÜlesannet2 = QuestController::KOGUM[$ülesanne2];
        $mituÜlesannet3 = QuestController::KOGUM[$ülesanne3];

        //Salvestab Andmebaasi...

        return ['quest'=>[$ülesanne1, $ülesanne2, $ülesanne3], 'measure'=>[$mituÜlesannet1, $mituÜlesannet2, $mituÜlesannet3]];

    }
    
    public function check($tüüp){
        //kui on andmebaasis see ülesanne
            //Lisa juurde kogust
            $kogus = QuestController::KOGUM[$tüüp]; //kogust vaja
            //kui on piisavalt loe tehtuks


    }
    
    
}
