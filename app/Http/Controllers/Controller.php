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
            
            

            //ascending level system
            if($pop >= 0){
                $x = random_int(0 ,9);
                $y = random_int(1,10);
                multyplier($x, $y, 0.5, 10);
                }
            if($pop >= 5){
                $x = random_int(10 ,99);
                $y = random_int(11,100);
                multyplier($x, $y, 0.5, 100);
                }
            if($pop >= 9){
                $x = random_int(100,999);
                $y = random_int(101,1000);
                multyplier($x, $y, 0.5, 1000);
                }
            if($pop >= 12){
                $x = random_int(1000,9999);
                $y = random_int(1001,10000);
                multyplier($x, $y, 0.5, 10000);
                }
            if ($pop == 15){

                return redirect()->route('gameEnd');
            
            }
            $time_elapsed_secs = microtime(true) - $start;   
        }while($pop <= ($operation_count - 1));
        
    

        return Inertia::render("Game/GamePage", ["data"=>$loend]);
    }
    
};

