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

                $tase = ($pop % 5) + 1;

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
                    array_push($loendlünk, ["operation"=>$xlünk . '+' . $ylünk . " = " . $xliit + $yliit, "answer"=>$xliit + $yliit - (is_string($xlünk) ? $ylünk : $xlünk), "level"=>$tase]);
                }
                if ($loos == 2){
                    if ($xlünk = 'Lünk'){
                        $ylünk = $ykor;
                    } else {
                        $xlünk = $xkor;
                    }
                    array_push($loendlünk, ["operation"=>$xlünk . '·' . $ylünk . " = " . $xkor * $ykor, "answer"=>$xkor * $ykor / (is_string($xlünk) ? $ylünk : $xlünk), "level"=>$tase]);
                }
                if ($loos == 3){
                    if ($xlünk = 'Lünk'){
                        $ylünk = $ylah;
                    } else {
                        $xlünk = $xlah;
                    }
                    array_push($loendlünk, ["operation"=>$ylünk . '-' . $xlünk . " = " . $ylah - $xlah, "answer"=>($ylünk == "Lünk" ? $ylah - $xlah + $xlünk : $ylünk - ($ylah - $xlah)), "level"=>$tase]);
                }
                if ($loos == 4){
                    if ($xlünk = 'Lünk'){
                        $ylünk = $yjag;
                        array_push($loendlünk, ["operation"=>$xlünk . ':' . $ylünk . " = " . $xjag, "answer"=>$xjag * $yjag, "level"=>$tase]);
                    } else {
                        $xlünk = $xjag;
                        array_push($loendlünk, ["operation"=>$xlünk * $yjag . ':' . $ylünk . " = " . $xjag, "answer"=>$yjag, "level"=>$tase]);
                    }
                }
            
            }
            
            
            //ascending level system
            if($pop >= 0){
                if ($tehe === 'liitmine' or 'lahutamine'){
                    do {
                        $xliit = $xlah = random_int($lisand -2, $lisand + 2);
                        $yliit = $ylah = random_int($lisand -2, $lisand + 2);
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
        }while($pop <= 20);
        
    
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



    //Addition and Substraction
    public function liitlah($level, $mis){
        $array = [];
        $x = 0;
        $y = 0;
        $tase = 1;
        $count = 0;
        $min = 0;
        $max = 10;
        $add = 0;
        $xold = 0;
        $yold = 0;
        $check = 0;

        
        //Specific levels
        if ($level === '1'){
            do {
                $x = random_int(0, 9);
                $y = random_int(1, 10);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(0, 9);
                            $y = random_int(1, 10);
                        } while ($x != $y);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(0, 9);
                        $y = random_int(1, 10);
                    } while ($x != $xold or $y != $yold);
                }
                $xold = $x;
                $yold = $y;
                if ($mis === 'liitmine') {
                    array_push($array, ["operation"=>$x. '+' . $y, "answer"=>$x + $y, "level"=>$level]);
                }
                if ($mis === 'lahutamine') {
                    array_push($array, ["operation"=>$x + $y . '-' . $y, "answer"=>$x, "level"=>$level]);
                }
                if ($mis === 'mõlemad'){
                    $random  = random_int(1, 2);
                    if ($random == 1){
                        array_push($array, ["operation"=>$x. '+' . $y, "answer"=>$x + $y, "level"=>$level]);
                    } else {
                        array_push($array, ["operation"=>$x + $y . '-' . $y, "answer"=>$x, "level"=>$level]);
                    }
                }
                $count ++;
            }while ($count <= 25);
            
            
        } 
        if ($level === '2'){
            do {
                $x = random_int(10, 99);
                $y = random_int(11, 100);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(10, 99);
                            $y = random_int(11, 100);
                        } while ($x != $y);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(10, 99);
                        $y = random_int(11, 100);
                    } while ($x != $xold or $y != $yold);
                }
                $xold = $x;
                $yold = $y; 
                if ($mis === 'liitmine') {
                    array_push($array, ["operation"=>$x. '+' . $y, "answer"=>$x + $y, "level"=>$level]);
                }
                if ($mis === 'lahutamine') {
                    array_push($array, ["operation"=>$x + $y . '-' . $y, "answer"=>$x, "level"=>$level]);
                }
                if ($mis === 'mõlemad'){
                    $random  = random_int(1, 2);
                    if ($random == 1){
                        array_push($array, ["operation"=>$x. '+' . $y, "answer"=>$x + $y, "level"=>$level]);
                    } else {
                        array_push($array, ["operation"=>$x + $y . '-' . $y, "answer"=>$x, "level"=>$level]);
                    }
                }
                $count ++;
            } while ($count <= 25);
            
        }
        if ($level === '3'){
            do {
                $x = random_int(100, 999);
                $y = random_int(101, 1000);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(100, 999);
                            $y = random_int(101, 1000);
                        } while ($x != $y);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(100, 999);
                        $y = random_int(101, 1000);
                    } while ($x != $xold or $y != $yold);
                }
                $xold = $x;
                $yold = $y; 
                if ($mis === 'liitmine') {
                    array_push($array, ["operation"=>$x. '+' . $y, "answer"=>$x + $y, "level"=>$level]);
                }
                if ($mis === 'lahutamine') {
                    array_push($attay, ["operation"=>$x + $y . '-' . $y, "answer"=>$x, "level"=>$level]);
                }
                if ($mis === 'mõlemad'){
                    $random  = random_int(1, 2);
                    if ($random == 1){
                        array_push($array, ["operation"=>$x. '+' . $y, "answer"=>$x + $y, "level"=>$level]);
                    } else {
                        array_push($array, ["operation"=>$x + $y . '-' . $y, "answer"=>$x, "level"=>$level]);
                    }
                }
                $count ++;
            } while ($count <= 25);
            
        }
        if ($level === '4'){
            do {
                $x = random_int(1000, 9999);
                $y = random_int(1001, 10000);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(1000, 9999);
                            $y = random_int(1001, 10000);
                        } while ($x != $y);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(1000, 9999);
                        $y = random_int(1001, 10000);
                    } while ($x != $xold or $y != $yold);
                }
                $xold = $x;
                $yold = $y;
                if ($mis === 'liitmine') {
                    array_push($array, ["operation"=>$x. '+' . $y, "answer"=>$x + $y, "level"=>$level]);
                }
                if ($mis === 'lahutamine') {
                    array_push($array, ["operation"=>$x + $y . '-' . $y, "answer"=>$x, "level"=>$level]);
                }
                if ($mis === 'mõlemad'){
                    $random  = random_int(1, 2);
                    if ($random == 1){
                        array_push($array, ["operation"=>$x. '+' . $y, "answer"=>$x + $y, "level"=>$level]);
                    } else {
                        array_push($array, ["operation"=>$x + $y . '-' . $y, "answer"=>$x, "level"=>$level]);
                    }
                }
                $count ++;
            } while ($count <= 25);
            
        }
        if ($level === '5'){
            do {
                $x = random_int(10000, 99999);
                $y = random_int(10001, 100000);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(10000, 99999);
                            $y = random_int(10001, 100000);
                        } while ($x != $y);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(10000, 99999);
                        $y = random_int(10001, 100000);
                    } while ($x != $xold or $y != $yold);
                }
                $xold = $x;
                $yold = $y;
                if ($mis === 'liitmine') {
                    array_push($array, ["operation"=>$x. '+' . $y, "answer"=>$x + $y, "level"=>$level]);
                }
                if ($mis === 'lahutamine') {
                    array_push($array, ["operation"=>$x + $y . '-' . $y, "answer"=>$x, "level"=>$level]);
                }
                if ($mis === 'mõlemad'){
                    $random  = random_int(1, 2);
                    if ($random == 1){
                        array_push($array, ["operation"=>$x. '+' . $y, "answer"=>$x + $y, "level"=>$level]);
                    } else {
                        array_push($array, ["operation"=>$x + $y . '-' . $y, "answer"=>$x, "level"=>$level]);
                    }
                }
                $count ++;
            } while ($count <= 25);
            
        }

        // Ascending levels
        
        if ($level === 'a'){
            do{

                $x = random_int($add, 2 + $add);
                $y = random_int($add, 2 + $add);
                if ($count >= 5){
                    $tase = 2;
                    $max = 100;
                    $x = random_int($min + $add, 18 + $add);
                    $y = random_int($min + $add, 18 + $add);
                }
                if ($count >= 10){
                    $tase = 3;
                    $max = 1000;
                    $x = random_int($min + $add, 180 + $add);
                    $y = random_int($min + $add, 180 + $add);
                }
                if ($count >= 15){
                    $tase = 4;
                    $max = 10000;
                    $x = random_int($min + $add, 1800 + $add);
                    $y = random_int($min + $add, 1800 + $add);
                }
                if ($count >= 20){
                    $tase = 5;
                    $max = 100000;
                    $x = random_int($min + $add, 18000 + $add);
                    $y = random_int($min + $add, 18000 + $add);
                }
                
                if ($mis === 'liitmine'){
                    array_push($array, ["operation"=>$x. '+' . $y, "answer"=>$x + $y, "level"=>$level]);
                }
                if ($mis === 'lahutamine'){
                    array_push($array, ["operation"=>$x + $y. '-' . $y, "answer"=>$x, "level"=>$level]);
                }
                if ($mis === 'mõlemad'){
                    $random  = random_int(1, 2);
                    if ($random == 1){
                        array_push($array, ["operation"=>$x + $y. '-' . $y, "answer"=>$x, "level"=>$level]);
                    } else {
                        array_push($array, ["operation"=>$x. '+' . $y, "answer"=>$x + $y, "level"=>$level]);
                    }
                }
                $add += $max/5;
                $count ++;
            }while ($count <= 25);
        }
        

        return $array;
    }

    public function korjag($level, $mis){
        $array = [];
        $x = 0;
        $y = 0;
        $tase = 1;
        $count = 0;
        $min = 0;
        $max = 0;
        $add = 0;
        $xold = 0;
        $yold = 0;
        $check = 0;
        
        if ($level === '1' && $level === '2' && $level === '3' && $level === '4' && $level === '5'){
            goto koik;
        }
        //Specific levels
        if ($level === '1'){
            do{
                $x = random_int(0, 9);
                $y = random_int(1, 10);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(0, 9);
                            $y = random_int(1, 10);
                        } while ($x != $y);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(0, 9);
                        $y = random_int(1, 10);
                    } while ($x != $xold or $y != $yold);
                }
                $xold = $x;
                $yold = $y;
                
                if ($mis === 'korrutamine') {
                    array_push($array, ["operation"=>$x . '·' . $y, "answer"=>$x * $y, "level"=>$level]);
                }
                if ($mis === 'jagamine') {
                    array_push($array, ["operation"=>$x * $y . ':' . $y, "answer"=>$x, "level"=>$level]);
                }
                if ($mis === 'mõlemad'){
                    $random  = random_int(1, 2);
                    if ($random == 1){
                        array_push($array, ["operation"=>$x . '·' . $y, "answer"=>$x * $y, "level"=>$level]);
                    } else {
                        array_push($array, ["operation"=>$x * $y . ':' . $y, "answer"=>$x, "level"=>$level]);
                    }
                }
                $count ++;
            } while ($count <= 25);
            
        }
        if ($level === '2'){
            do{
                $x = random_int(10, 19);
                $y = random_int(11, 20);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(10, 19);
                            $y = random_int(11, 20);
                        } while ($x != $y);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(10, 19);
                        $y = random_int(11, 20);
                    } while ($x != $xold or $y != $yold);
                }
                $xold = $x;
                $yold = $y;
                if ($mis === 'korrutamine') {
                    array_push($array, ["operation"=>$x . '·' . $y, "answer"=>$x * $y, "level"=>$level]);
                }
                if ($mis === 'jagamine') {
                    array_push($array, ["operation"=>$x * $y . ':' . $y, "answer"=>$x, "level"=>$level]);
                }
                if ($mis === 'mõlemad'){
                    $random  = random_int(1, 2);
                    if ($random == 1){
                        array_push($array, ["operation"=>$x . '·' . $y, "answer"=>$x * $y, "level"=>$level]);
                    } else {
                        array_push($array, ["operation"=>$x * $y . ':' . $y, "answer"=>$x, "level"=>$level]);
                    }
                }
                $count ++;
            } while ($count <= 25);
            
        }
        if ($level === '3'){
            do {
                $x = random_int(20, 99);
                $y = random_int(21, 100);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(20, 99);
                            $y = random_int(21, 100);
                        } while ($x != $y);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(20, 99);
                        $y = random_int(21, 100);
                    } while ($x != $xold or $y != $yold);
                }
                $xold = $x;
                $yold = $y;
                if ($mis === 'korrutamine') {
                    array_push($array, ["operation"=>$x . '·' . $y, "answer"=>$x * $y, "level"=>$level]);
                }
                if ($mis === 'jagamine') {
                    array_push($array, ["operation"=>$x * $y . ':' . $y, "answer"=>$x, "level"=>$level]);
                }
                if ($mis === 'mõlemad'){
                    $random  = random_int(1, 2);
                    if ($random == 1){
                        array_push($array, ["operation"=>$x . '·' . $y, "answer"=>$x * $y, "level"=>$level]);
                    } else {
                        array_push($array, ["operation"=>$x * $y . ':' . $y, "answer"=>$x, "level"=>$level]);
                    }
                }
                $count ++;
            } while ($count <= 25);
            
        }
        if ($level === '4'){
            do {
                $x = random_int(100, 999);
                $y = random_int(101, 1000);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(100, 999);
                            $y = random_int(101, 1000);
                        } while ($x != $y);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(100, 999);
                        $y = random_int(101, 1000);
                    } while ($x != $xold or $y != $yold);
                }
                $xold = $x;
                $yold = $y;
                if ($mis === 'korrutamine') {
                    array_push($array, ["operation"=>$x . '·' . $y, "answer"=>$x * $y, "level"=>$level]);
                }
                if ($mis === 'jagamine') {
                    array_push($array, ["operation"=>$x * $y . ':' . $y, "answer"=>$x, "level"=>$level]);
                }
                if ($mis === 'mõlemad'){
                    $random  = random_int(1, 2);
                    if ($random == 1){
                        array_push($array, ["operation"=>$x . '·' . $y, "answer"=>$x * $y, "level"=>$level]);
                    } else {
                        array_push($array, ["operation"=>$x * $y . ':' . $y, "answer"=>$x, "level"=>$level]);
                    }
                }
                $count ++;
            } while ($count <= 25);
            
        }
        if ($level === '5'){
            do {
                $x = random_int(1000, 9999);
                $y = random_int(1001, 10000);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(1000, 9999);
                            $y = random_int(1001, 10000);
                        } while ($x != $y);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(1000, 9999);
                        $y = random_int(1001, 10000);
                    } while ($x != $xold or $y != $yold);
                }
                $xold = $x;
                $yold = $y;
                if ($mis === 'korrutamine') {
                    array_push($array, ["operation"=>$x . '·' . $y, "answer"=>$x * $y, "level"=>$level]);
                }
                if ($mis === 'jagamine') {
                    array_push($array, ["operation"=>$x * $y . ':' . $y, "answer"=>$x, "level"=>$level]);
                }
                if ($mis === 'mõlemad'){
                    $random  = random_int(1, 2);
                    if ($random == 1){
                        array_push($array, ["operation"=>$x . '·' . $y, "answer"=>$x * $y, "level"=>$level]);
                    } else {
                        array_push($array, ["operation"=>$x * $y . ':' . $y, "answer"=>$x, "level"=>$level]);
                    }
                }
                $count ++;
            } while ($count <= 25);
            
        }

        koik:
        //Ascending levels
        if ($level === '1' && $level === '2' && $level === '3' && $level === '4' && $level === '5'){
            do {
                $x = random_int($min += $add, 2 + $add);
                $y = random_int($min += $add, 2 + $add);
                if ($count >= 5){
                    $tase = 2;
                }
                if ($count >= 10){ 
                    $tase = 3;
                    $max = 100;
                    $x = random_int($min += $add, 16 + $add);
                    $y = random_int($min += $add, 16 + $add);

                }
                if ($count >= 15){ 
                    $tase = 4;
                    $max = 1000;
                    $x = random_int($min += $add, 180 + $add);
                    $y = random_int($min += $add, 180 + $add);

                }
                if ($count >= 20){ 
                    $tase = 5;
                    $max = 10000;
                    $x = random_int($min += $add, 1800 + $add);
                    $y = random_int($min += $add, 1800 + $add);

                }
                if ($mis === 'korrutamine') {
                    array_push($array, ["operation"=>$x . '·' . $y, "answer"=>$x * $y, "level"=>$tase]);
                }
                if ($mis === 'jagamine') {
                    array_push($array, ["operation"=>$x * $y . ':' . $y, "answer"=>$x, "level"=>$tase]);
                }
                if ($mis === 'mõlemad'){
                    $random  = random_int(1, 2);
                    if ($random == 1){
                        array_push($array, ["operation"=>$x . '·' . $y, "answer"=>$x * $y, "level"=>$tase]);
                    } else {
                        array_push($array, ["operation"=>$x * $y . ':' . $y, "answer"=>$x, "level"=>$tase]);
                    }
                }
                $add += $max / 5;
                $count ++;
            
            } while ($count <= 25);
        }
        return $array;

    }

    public function võrdlemine($level){
        $array = [];
        $array2 = [];
        $x = 0;
        $y = 0;
        $tase = 1;
        $count = 0;
        $min = 0;
        $max = 0;
        $add = 0;
        $xold = 0;
        $yold = 0;
        $check = 0;

        if ($level === '1'){
            do{
                $x = random_int(0, 9);
                $y = random_int(1, 10);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(0, 9);
                            $y = random_int(1, 10);
                        } while ($x != $y);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(0, 9);
                        $y = random_int(1, 10);
                    } while ($x != $xold or $y != $yold);
                }
                $xold = $x;
                $yold = $y;
                
                uuesti:
                $random  = random_int(1, 4);
                if ($random == 1){
                    $proov1 = $x * $y;
                    $võrd = 1;
                }
                if ($random == 2){
                    $proov1 = $x * $y / $x;
                    $võrd = 2;
                }
                if ($random == 3){
                    $proov1 = $x + $y - $x;
                    $võrd = 3;
                }
                if ($random == 4) {
                    $proov1 = $x + $y;
                    $võrd = 4;
                }
                $random  = random_int(1, 4);
                if ($random == 1 && $võrd != 1){
                    $proov2 = $x * $y;
                    
                }
                if ($random == 2 && $võrd != 2){
                    $proov2 = $x * $y / $y;
                    
                }
                if ($random == 3 && $võrd != 3){
                    $proov2 = $x + $y - $y;
                   
                }
                if ($random == 4 && $võrd != 4) {
                    $proov2 = $x + $y;
                   
                }
                
                if ($proov1 > $proov2){
                    $random  = random_int(1, 2);
                    if ($random == 1){
                        array_push($array, ["operation"=>$x . '·' . $y, "answer"=>$x * $y, "level"=>$level]);
                    } 
                    if ($random == 2){
                        array_push($array, ["operation"=>$x * $y . ':' . $y, "answer"=>$x, "level"=>$level]);
                    }
                }
                if ($proov2 > $proov1){

                } else {
                    goto uuesti;
                }

                $count ++;
            } while ($count <= 25);
            
        }
        if ($level === '2'){
            do{
                $x = random_int(10, 19);
                $y = random_int(11, 20);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(10, 19);
                            $y = random_int(11, 20);
                        } while ($x != $y);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(10, 19);
                        $y = random_int(11, 20);
                    } while ($x != $xold or $y != $yold);
                }
                $xold = $x;
                $yold = $y;
                if ($mis === 'korrutamine') {
                    array_push($array, ["operation"=>$x . '·' . $y, "answer"=>$x * $y, "level"=>$level]);
                }
                if ($mis === 'jagamine') {
                    array_push($array, ["operation"=>$x * $y . ':' . $y, "answer"=>$x, "level"=>$level]);
                }
                if ($mis === 'mõlemad'){
                    $random  = random_int(1, 2);
                    if ($random == 1){
                        array_push($array, ["operation"=>$x . '·' . $y, "answer"=>$x * $y, "level"=>$level]);
                    } else {
                        array_push($array, ["operation"=>$x * $y . ':' . $y, "answer"=>$x, "level"=>$level]);
                    }
                }
                $count ++;
            } while ($count <= 25);
            
        }
        if ($level === '3'){
            do {
                $x = random_int(20, 99);
                $y = random_int(21, 100);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(20, 99);
                            $y = random_int(21, 100);
                        } while ($x != $y);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(20, 99);
                        $y = random_int(21, 100);
                    } while ($x != $xold or $y != $yold);
                }
                $xold = $x;
                $yold = $y;
                if ($mis === 'korrutamine') {
                    array_push($array, ["operation"=>$x . '·' . $y, "answer"=>$x * $y, "level"=>$level]);
                }
                if ($mis === 'jagamine') {
                    array_push($array, ["operation"=>$x * $y . ':' . $y, "answer"=>$x, "level"=>$level]);
                }
                if ($mis === 'mõlemad'){
                    $random  = random_int(1, 2);
                    if ($random == 1){
                        array_push($array, ["operation"=>$x . '·' . $y, "answer"=>$x * $y, "level"=>$level]);
                    } else {
                        array_push($array, ["operation"=>$x * $y . ':' . $y, "answer"=>$x, "level"=>$level]);
                    }
                }
                $count ++;
            } while ($count <= 25);
            
        }
        if ($level === '4'){
            do {
                $x = random_int(100, 999);
                $y = random_int(101, 1000);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(100, 999);
                            $y = random_int(101, 1000);
                        } while ($x != $y);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(100, 999);
                        $y = random_int(101, 1000);
                    } while ($x != $xold or $y != $yold);
                }
                $xold = $x;
                $yold = $y;
                if ($mis === 'korrutamine') {
                    array_push($array, ["operation"=>$x . '·' . $y, "answer"=>$x * $y, "level"=>$level]);
                }
                if ($mis === 'jagamine') {
                    array_push($array, ["operation"=>$x * $y . ':' . $y, "answer"=>$x, "level"=>$level]);
                }
                if ($mis === 'mõlemad'){
                    $random  = random_int(1, 2);
                    if ($random == 1){
                        array_push($array, ["operation"=>$x . '·' . $y, "answer"=>$x * $y, "level"=>$level]);
                    } else {
                        array_push($array, ["operation"=>$x * $y . ':' . $y, "answer"=>$x, "level"=>$level]);
                    }
                }
                $count ++;
            } while ($count <= 25);
            
        }
        if ($level === '5'){
            do {
                $x = random_int(1000, 9999);
                $y = random_int(1001, 10000);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(1000, 9999);
                            $y = random_int(1001, 10000);
                        } while ($x != $y);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(1000, 9999);
                        $y = random_int(1001, 10000);
                    } while ($x != $xold or $y != $yold);
                }
                $xold = $x;
                $yold = $y;
                if ($mis === 'korrutamine') {
                    array_push($array, ["operation"=>$x . '·' . $y, "answer"=>$x * $y, "level"=>$level]);
                }
                if ($mis === 'jagamine') {
                    array_push($array, ["operation"=>$x * $y . ':' . $y, "answer"=>$x, "level"=>$level]);
                }
                if ($mis === 'mõlemad'){
                    $random  = random_int(1, 2);
                    if ($random == 1){
                        array_push($array, ["operation"=>$x . '·' . $y, "answer"=>$x * $y, "level"=>$level]);
                    } else {
                        array_push($array, ["operation"=>$x * $y . ':' . $y, "answer"=>$x, "level"=>$level]);
                    }
                }
                $count ++;
            } while ($count <= 25);
            
        }
    }

    public function wrapper($tehe, $tasemed){
        $loend = [];

        for ($lugeja = 0; $lugeja < count($tasemed); $lugeja ++){
            if($tehe == "liitmine" or $tehe == "lahutamine"){
                $loend[$tasemed[$lugeja]] = app('App\Http\Controllers\GameController')->liitlah($tasemed[$lugeja], $tehe);
            }

            if($tehe == "korrutamine" or $tehe == "jagamine"){
                $loend[$tasemed[$lugeja]] = app('App\Http\Controllers\GameController')->korjag($tasemed[$lugeja], $tehe);
            }
        }

        return $loend;
    }
}