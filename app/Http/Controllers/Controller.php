<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
};

class GameController extends Controller
{

    public function array_Gen(){
        $loend = [];
        $pop = 0;
    
        $operation_count = 15;
        
        do {

            $x = random_int(0,9);     
            $y = random_int(1,10);
            $start = microtime(true);
            array_push($loend, ["operation"=>$x . '+' . $y, "answer"=>$x + $y]);
            $pop ++;

            if ($pop == 15){

                return redirect()->route('gameEnd');
            
            }
            $time_elapsed_secs = microtime(true) - $start;   
        }while($pop <= ($operation_count - 1));
        
    

        return Inertia::render("Game/GamePage", ["data"=>$loend]);
    }
    
};

