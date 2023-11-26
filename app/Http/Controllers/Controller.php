<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use PhpParser\Node\Stmt\ElseIf_;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
};

class GameController extends Controller
{

    


    public function array_Gen($tehe){

        $loendliit = [];
        $loendkor = [];
        $loendjag = [];
        $loendlah = [];
        $loendlünk = [];
        $pop = 0;
        $operation_count = 20;
        $lisand = 0;
        
        do {
            $tase = 1;

            //liitmine
            if ($tehe === 'liitmine') {
                $xliit = random_int(0, 2) + $lisand;
                $yliit = random_int(0, 3) + $lisand;
            }
            
            //korrutamine
            if ($tehe === 'korrutamine') {
                $xkor = random_int(0, 2) + $lisand;
                $ykor = random_int(0, 3) + $lisand;
            }
            
            //tehe
            if ($tehe === 'lahutamine') {
                $xlah = random_int(0, 2) + $lisand;
                $ylah = random_int(0, 3) + $lisand;
            }
            
            if ($tehe === 'jagamine') {
                $xjag = random_int(0, 2) + $lisand;
                $yjag = random_int(0, 3) + $lisand;
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

                foreach ($suvalisus as $value){
                    if ($value === 'Lünk'){
                        $xlünk = $value;
                        $ylünk =  random_int(1,10);
                    } ElseIf ($value === 'Tavaline'){
                        $xlünk = random_int(0,9);
                        $ylünk =  $value;
                    } 
                }   

                $loos = random_int(1, 4);
                if ($loos == 1){
                    array_push($loendlünk, ["operation"=>$xlünk . '+' . $ylünk, "answer"=>$xliit + $yliit]);
                }
                if ($loos == 2){
                    array_push($loendlünk, ["operation"=>$xlünk . '·' . $ylünk, "answer"=>$xkor * $ykor]);
                }
                if ($loos == 3){
                    array_push($loendlünk, ["operation"=>$xlünk . '-' . $ylünk, "answer"=>$xlah - $ylah]);
                }
                if ($loos == 4){
                    array_push($loendlünk, ["operation"=>$xlünk . ':' . $ylünk, "answer"=>$xjag / $yjag]);
                }
            }
            
            
            //ascending level system
            if($pop >= 0){
                $muutuja = 2;
            }
            if($pop >= 5){
                $tase = 2;
                $lisand = 10;
                if ($tehe === 'liitmine' or 'lahutamine'){
                    $xkor = random_int($lisand, 5 + $lisand);
                    $ykor = random_int($lisand, 5 + $lisand);
                    $muutuja = 18;
                }
                if ($tehe === 'korrutamine' or 'jagamine'){
                    $xkor = random_int(10, 12) + random_int(0, 3);
                    $xkor = random_int(10, 12) + random_int(1, 3);
                }
            }
            if($pop >= 10){
                $tase = 3;
                if ($tehe === 'liitmine' or 'lahutamine'){
                    $xkor = random_int($lisand, 150 + $lisand);
                    $ykor = random_int($lisand, 150 + $lisand);
                    $muutuja = 180;
                }
                if ($tehe === 'korrutamine' or 'jagamine'){
                    $xkor = random_int($lisand, 5 + $lisand);
                    $ykor = random_int($lisand, 5 + $lisand);
                    $muutuja = 16;
                }
                }
            if($pop >= 15){
                $tase = 4;
                if ($tehe === 'liitmine' or 'lahutamine'){
                    $xkor = random_int(1000 + $lisand, 1500 + $lisand);
                    $ykor = random_int(1000 + $lisand, 1500 + $lisand);
                    $muutuja = 1800;
                }
                if ($tehe === 'korrutamine' or 'jagamine'){
                    $xkor = random_int(100 + $lisand, 250 + $lisand);
                    $ykor = random_int(100 + $lisand, 250 + $lisand);
                    $muutuja = 180;
                }
                }
            if ($pop == 20){

                return redirect()->route('gameEnd');
            
            }

            if ($tehe === 'liitmine') {
                array_push($loendliit, ["operation"=>$xliit. '+' . $yliit, "answer"=>$xliit + $yliit, "level"=>$tase]);
            }

            if ($tehe === 'lahutamine') {
                array_push($loendlah, ["operation"=>$xlah + $ylah . '-' . $ylah, "answer"=>$xlah, "level"=>$tase]);
            }

            if ($tehe === 'korrutamine') {
                array_push($loendkor, ["operation"=>$xkor . '·' . $ykor, "answer"=>$xkor * $ykor, "level"=>$tase]);

            }

            if ($tehe === 'jagamine') {
                array_push($loendjag, ["operation"=>$xjag * $yjag . ':' . $yjag, "answer"=>$xjag, "level"=>$tase]);
            }
            $lisand += $muutuja;
            $pop ++;   
        }while($pop <= ($operation_count - 1));
        
    
        if ($tehe === 'liitmine'){
            return $loendliit;
        }

        if ($tehe === 'lahutamine'){
            return $loendlah;
        }

        if ($tehe === 'jagamine'){
            return $loendjag;
        }

        if ($tehe === 'korrutamine'){
            return $loendkor;
        }
    }
};