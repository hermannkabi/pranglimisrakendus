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
    //Lisa "if -> liitmine do liitmine" array_Gen funktsiooniks
    public function array_Gen($tehe){
        $loendliit = [];
        $loendkor = [];
        $loendjag = [];
        $loendlah = [];
        $loendlünk = [];
        $pop = 0;
    
        $operation_count = 15;
        
        do {
            //liitmine
            if ($tehe === 'liitmine') {
                $xliit= random_int(0,9);     
                $yliit= random_int(1,10);
                array_push($loendliit, ["operation"=>$xliit. '+' . $yliit, "answer"=>$xliit + $yliit]);
            }
            
            
            //korrutamine
            if ($tehe === 'korrutamine') {
                $xkor = random_int(0,9);
                $ykor = random_int(1,10);
                array_push($loendkor, ["operation"=>$xkor . '*' . $ykor, "answer"=>$xkor * $ykor]);
            }
            

            //tehe
            if ($tehe === 'lahtuamine') {
                $xlah = random_int(0,9);
                $ylah = random_int(1,10);
                array_push($loendlah, ["operation"=>$xlah . '-' . $ylah, "answer"=>$xlah - $ylah]);
            }
            

            //jagamine
            if ($tehe === 'jagamine') {
                $xjag = random_int(0,9);
                $yjag = random_int(1,10);
                array_push($loendjag, ["operation"=>$xjag . ':' . $yjag, "answer"=>$xjag / $yjag]);
            }
            
            //lünkamine
            if ($tehe === 'lünkamine'){
                $opo = 0;
                $suvalisus = array(
                    'Lünk',
                    'Tavaline',
                );
                do{
                    shuffle($suvalisus);
                    array_push($loendlünk, reset($suvalisus));
                    $opo ++;
                } while ($opo <= $operation_count);
            }
        
            //ascending level system
            if($pop >= 0){
                multyplier($xliit, $yliit, 0.5, 10);
                multyplier($xkor, $ykor, 0.2, 10);
                multyplier($xliit, $yliit, 0.5, 10);
                multyplier($xliit, $yliit, 0.2, 10);
                }
            if($pop >= 5){
                $xliit= random_int(10, 99);
                $xkor= random_int(10, 99);
                $xlah= random_int(10, 99);
                $xjag= random_int(10, 99);
                $yliit= random_int(11, 100);
                $ykor= random_int(11, 100);
                $ylah= random_int(11, 100);
                $yjag= random_int(11, 100);
                multyplier($xliit, $yliit, 0.5, 100);
                multyplier($xkor, $ykor, 0.2, 100);
                multyplier($xliit, $yliit, 0.5, 100);
                multyplier($xliit, $yliit, 0.2, 100);
                }
            if($pop >= 9){
                $xliit= random_int(100, 999);
                $xkor= random_int(100, 999);
                $xlah= random_int(100, 999);
                $xjag= random_int(100, 999);
                $yliit= random_int(101, 1000);
                $ykor= random_int(101, 1000);
                $ylah= random_int(101, 1000);
                $yjag= random_int(101, 1000);
                multyplier($xliit, $yliit, 0.5, 1000);
                multyplier($xkor, $ykor, 0.2, 1000);
                multyplier($xliit, $yliit, 0.5, 1000);
                multyplier($xliit, $yliit, 0.2, 1000);
                }
            if($pop >= 12){
                $xliit= random_int(1000, 9999);
                $xkor= random_int(1000, 9999);
                $xlah= random_int(1000, 9999);
                $xjag= random_int(1000, 9999);
                $yliit= random_int(1001, 10000);
                $ykor= random_int(1001, 10000);
                $ylah= random_int(1001, 10000);
                $yjag= random_int(1001, 10000);
                multyplier($xliit, $yliit, 0.5, 10000);
                multyplier($xkor, $ykor, 0.2, 10000);
                multyplier($xliit, $yliit, 0.5, 10000);
                multyplier($xliit, $yliit, 0.2, 10000);
                }
            if ($pop == 15){

                return redirect()->route('gameEnd');
            
            }

            $pop ++;   
        }while($pop <= ($operation_count - 1));
        
    
        if ($tehe === 'liitmine'){
            return Inertia::render("Game/GamePage", ["data"=>$loendliit]);
        }
        if ($tehe === 'korrutamine'){
            return Inertia::render("Game/GamePage", ["data*"=>$loendkor]);
        }
        if ($tehe === 'lahutamine'){
            return Inertia::render("Game/GamePage", ["data-"=>$loendlah]);
        }
        if ($tehe === 'jagamine'){
            return Inertia::render("Game/GamePage", ["data:"=>$loendjag]);
        }
        if ($tehe === 'lünkamine'){
            return Inertia::render("Game/GamePage", ["datalünk:"=>$loendlünk]);
        }
    }
    
};

