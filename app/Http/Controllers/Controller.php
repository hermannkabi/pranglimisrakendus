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
        $lisand = 1;
        $xkontroll = 0;
        $ykontroll = 0;
        
        do {
            $tase = 1;

            //liitmine
            $xliit = random_int($lisand, $lisand + 2);
            $yliit = random_int($lisand, $lisand + 2);
            
            
            //korrutamine
            $xkor = random_int($lisand, $lisand + 2);
            $ykor = random_int($lisand, $lisand + 2);
            
            
            //lahutamine
            $xlah = random_int($lisand, $lisand + 2);
            $ylah = random_int($lisand, $lisand + 2);
            
            //jagamine (ei tohi == 0)
            $xjag = random_int($lisand, $lisand + 2);
            $yjag = random_int($lisand, $lisand + 2);
            
            
            //lünkamine
            if ($tehe === 'lünkamine'){

                $jarjekord = rand(1, 2);

                if ($jarjekord === 1){
                    $xlünk = "Lünk";
                } else{
                    $ylünk =  "Lünk";
                } 


                $loos = random_int(1, 4);
                if ($loos == 1){
                    if ($xlünk = 'Lünk'){
                        $ylünk = $yliit;
                    } else {
                        $xlünk = $xliit;
                    }
                    array_push($loendlünk, ["operation"=>$xlünk . '+' . $ylünk . " = " . $xliit + $yliit, "answer"=>$xliit + $yliit - (is_string($xlünk) ? $ylünk : $xlünk)]);
                }
                if ($loos == 2){
                    if ($xlünk = 'Lünk'){
                        $ylünk = $ykor;
                    } else {
                        $xlünk = $xkor;
                    }
                    array_push($loendlünk, ["operation"=>$xlünk . '·' . $ylünk . " = " . $xkor * $ykor, "answer"=>$xkor * $ykor / (is_string($xlünk) ? $ylünk : $xlünk)]);
                }
                if ($loos == 3){
                    if ($xlünk = 'Lünk'){
                        $ylünk = $ylah;
                    } else {
                        $xlünk = $xlah;
                    }
                    array_push($loendlünk, ["operation"=>$ylünk . '-' . $xlünk . " = " . $ylah - $xlah, "answer"=>($ylünk == "Lünk" ? $ylah - $xlah + $xlünk : $ylünk - ($ylah - $xlah))]);
                }
                if ($loos == 4){
                    if ($xlünk = 'Lünk'){
                        $ylünk = $yjag;
                        array_push($loendlünk, ["operation"=>$xlünk . ':' . $ylünk . " = " . $xjag, "answer"=>$xjag * $yjag]);
                    } else {
                        $xlünk = $xjag;
                        array_push($loendlünk, ["operation"=>$xlünk * $yjag . ':' . $ylünk . " = " . $xjag, "answer"=>$yjag]);
                    }
                }
            
            }
            
            
            //ascending level system
            if($pop >= 0){
                if ($tehe === 'liitmine' or 'lahutamine'){
                    do {
                        $xliit = $xlah = random_int($lisand, $lisand + 2);
                        $yliit = $ylah = random_int($lisand, $lisand + 2);
                     } while ($xlah == $xkontroll or $ylah == $ykontroll);
                     $xkontroll = $xlah;
                     $ykontroll = $ylah;
                }
                if ($tehe === 'korrutamine' or 'jagamine'){
                    do {
                        $xkor = $xjag = random_int($lisand, $lisand + 2);
                        $ykor = $yjag = random_int($lisand, $lisand + 2);
                    } while ($xkor == $xkontroll or $ykor == $ykontroll);
                    $xkontroll = $xkor;
                    $ykontroll = $ykor;
                $muutuja = 2;
                }
            }
            if($pop >= 5){
                $tase = 2;
                if ($lisand < 10){
                    $lisand = 10;
                }
                if ($tehe === 'liitmine' or 'lahutamine'){
                    $xliit = $xlah= random_int($lisand, 5 + $lisand);
                    $yliit = $ylah= random_int($lisand, 5 + $lisand);
                    do {
                       $xliit = $xlah = random_int($lisand - 2, $lisand + 7);
                       $yliit = $ylah = random_int($lisand - 2, $lisand + 7);
                    } while ($xlah == $xkontroll or $ylah == $ykontroll);
                    $xkontroll = $xlah;
                    $ykontroll = $ylah;
                    $muutuja = 18;
                }
                if ($tehe === 'korrutamine' or 'jagamine'){
                    if ($pop == 6 && $lisand > 14){
                        $lisand = 14;
                        $xkor = $xjag = random_int($lisand - 2, $lisand);
                        $ykor = $yjag = random_int($lisand - 2, $lisand);
                    }
                    $xkor = $xjag = random_int($lisand, $lisand + 2);
                    $ykor = $yjag = random_int($lisand, $lisand + 2);
                    do {
                        $xkor = $xjag = random_int($lisand, $lisand + 2);
                        $ykor = $yjag = random_int($lisand, $lisand + 2);
                    } while ($xkor == $xkontroll or $ykor == $ykontroll);
                    $xkontroll = $xkor;
                    $ykontroll = $ykor;
                    $muutuja = 2;
                }
            }
            if($pop >= 10){
                $tase = 3;
                if ($tehe === 'liitmine' or 'lahutamine'){
                    if ($lisand < 100){
                        $lisand = 100;
                    }
                    $xliit = $xlah =  random_int($lisand, 59 + $lisand);
                    $yliit = $ylah =  random_int($lisand, 59 + $lisand);
                    do {
                        $xliit = $xlah =  random_int($lisand, 59 + $lisand);
                        $yliit = $ylah =  random_int($lisand, 59 + $lisand);
                    } while ($xliit == $xkontroll or $ylah == $ykontroll);
                    $xkontroll = $xliit;
                    $ykontroll = $yliit;
                    $muutuja = 180;
                }
                if ($tehe === 'korrutamine' or 'jagamine'){
                    if ($lisand < 20){
                        $lisand = 20;
                    }
                    $xkor = $xjag = random_int($lisand - 9, $lisand + 9);
                    $ykor = $yjag = random_int($lisand - 9, $lisand + 9);
                    do {
                        $xkor = $xjag = random_int(random_int($lisand - 9, $lisand), random_int($lisand, $lisand + 9));
                        $ykor = $yjag = random_int(random_int($lisand - 9, $lisand), random_int($lisand, $lisand + 9));
                    } while ($xkor == $xkontroll or $ykor == $ykontroll && $xkor = $ykor);
                    $xkontroll = $xkor;
                    $ykontroll = $ykor;
                    $muutuja = 16;
                }
                }
            if($pop >= 15){
                $tase = 4;
                if ($tehe === 'liitmine' or 'lahutamine'){
                    $xliit = $xlah = random_int(1000 + $lisand, 1500 + $lisand);
                    $yliit = $ylah = random_int(1000 + $lisand, 1500 + $lisand);
                    $muutuja = 1800;
                }
                if ($tehe === 'korrutamine' or 'jagamine'){
                    $xkor = $xjag = random_int(100 + $lisand, 250 + $lisand);
                    $ykor = $yjag = random_int(100 + $lisand, 250 + $lisand);
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

        if ($tehe === 'lünkamine'){
            return $loendlünk;
        }
    }
};