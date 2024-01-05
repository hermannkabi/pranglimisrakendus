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
                    } while ($x != $xold or $y != $yold && $x == $y);
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
                $x = random_int(11, 30);
                $y = random_int(11, 30);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(11, 30);
                            $y = random_int(11, 30);
                        } while ($x != $y);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(11, 30);
                        $y = random_int(11, 30);
                    } while ($x != $xold or $y != $yold && $x == $y);
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
                $x = random_int(30, 100);
                $y = random_int(30, 100);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(30, 100);
                            $y = random_int(30, 100);
                        } while ($x != $y);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(30, 100);
                        $y = random_int(30, 100);
                    } while ($x != $xold or $y != $yold && $x == $y);
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
        if ($level === '4'){
            do {
                $x = random_int(100, 500);
                $y = random_int(100, 500);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(100, 500);
                            $y = random_int(100, 500);
                        } while ($x != $y);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(100, 500);
                        $y = random_int(100, 500);
                    } while ($x != $xold or $y != $yold && $x == $y);
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
                $x = random_int(500, 1000);
                $y = random_int(500, 1000);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(500, 1000);
                            $y = random_int(500, 1000);
                        } while ($x != $y);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(500, 1000);
                        $y = random_int(500, 1000);
                    } while ($x != $xold or $y != $yold && $x == $y);
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
        
        if ($level === 'all'){
            do{

                $x = random_int($add, 2 + $add);
                $y = random_int($add, 2 + $add);
                if ($count >= 5){
                    $tase = 2;
                    $max = 100;
                    $x = random_int($add, 18 + $add);
                    $y = random_int($add, 18 + $add);
                }
                if ($count >= 10){
                    $tase = 3;
                    $max = 1000;
                    $x = random_int($add, 180 + $add);
                    $y = random_int($add, 180 + $add);
                }
                if ($count >= 15){
                    $tase = 4;
                    $max = 10000;
                    $x = random_int($add, 1800 + $add);
                    $y = random_int($add, 1800 + $add);
                }
                if ($count >= 20){
                    $tase = 5;
                    $max = 100000;
                    $x = random_int($add, 18000 + $add);
                    $y = random_int($add, 18000 + $add);
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
        if ($level === '?'){
            do {
                $x = random_int(1000, 10000);
                $y = random_int(1000, 10000);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(1000, 10000);
                            $y = random_int(1000, 10000);
                        } while ($x != $y);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(1000, 10000);
                        $y = random_int(1000, 10000);
                    } while ($x != $xold or $y != $yold && $x == $y);
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
        if ($level === '!?'){
            do {
                $x = random_int(10000, 100000);
                $y = random_int(10000, 100000);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(10000, 100000);
                            $y = random_int(10000, 100000);
                        } while ($x != $y);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(10000, 100000);
                        $y = random_int(10000, 100000);
                    } while ($x != $xold or $y != $yold && $x == $y);
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
        if ($level === '!'){
            do {
                $x = random_int(100000, 1000000);
                $y = random_int(100000, 1000000);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(100000, 1000000);
                            $y = random_int(100000, 1000000);
                        } while ($x != $y);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(100000, 1000000);
                        $y = random_int(100000, 1000000);
                    } while ($x != $xold or $y != $yold && $x == $y);
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
        
        
        //Specific levels
        if ($level === '1'){
            do{
                $x = random_int(1, 5);
                $y = random_int(1, 5);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(1, 5);
                            $y = random_int(1, 5);
                        } while ($x != $y);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(1, 5);
                        $y = random_int(1, 5);
                    } while ($x != $xold or $y != $yold && $x == $y);
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
                $x = random_int(1, 5);
                $y = random_int(6, 10);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(1, 5);
                            $y = random_int(6, 10);
                        } while ($x != $y);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(1, 5);
                        $y = random_int(6, 10);
                    } while ($x != $xold or $y != $yold && $x == $y);
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
                $x = random_int(6, 10);
                $y = random_int(6, 10);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(6, 10);
                            $y = random_int(6, 10);
                        } while ($x != $y);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(6, 10);
                        $y = random_int(6, 10);
                    } while ($x != $xold or $y != $yold && $x == $y);
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
                $x = random_int(1, 10);
                $y = random_int(11, 20);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(1, 10);
                            $y = random_int(11, 20);
                        } while ($x != $y);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(1, 10);
                        $y = random_int(11, 20);
                    } while ($x != $xold or $y != $yold && $x == $y);
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
                $x = random_int(2, 10);
                $y = random_int(101, 500);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(2, 10);
                            $y = random_int(101, 500);
                        } while ($x != $y);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(2, 10);
                        $y = random_int(101, 500);
                    } while ($x != $xold or $y != $yold && $x == $y);
                }
                $xold = $x;
                $yold = $y;
                if ($mis === 'korrutamine') {
                    array_push($array, ["operation"=>$x . '·' . $y, "answer"=>$x * $y, "level"=>$level]);
                }
                if ($mis === 'jagamine') {
                    if ($y > $x){
                        $z = $x;
                        $x = $y;
                        $y = $z;
                    };
                    array_push($array, ["operation"=>$x * $y . ':' . $y, "answer"=>$x, "level"=>$level]);
                }
                if ($mis === 'mõlemad'){
                    $random  = random_int(1, 2);
                    if ($random == 1){
                        array_push($array, ["operation"=>$x . '·' . $y, "answer"=>$x * $y, "level"=>$level]);
                    } else {
                        if ($y > $x){
                            $z = $x;
                            $x = $y;
                            $y = $z;
                        };
                        array_push($array, ["operation"=>$x * $y . ':' . $y, "answer"=>$x, "level"=>$level]);
                    }
                }
                $count ++;
            } while ($count <= 25);
            
        }
        if ($level === '6'){
            do {
                $x = random_int(11, 20);
                $y = random_int(21, 30);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(11, 20);
                            $y = random_int(21, 30);
                        } while ($x != $y);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(11, 20);
                        $y = random_int(21, 30);
                    } while ($x != $xold or $y != $yold && $x == $y);
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
        if ($level === '?'){
            do {
                $x = random_int(11, 30);
                $y = random_int(31, 100);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(11, 30);
                            $y = random_int(31, 100);
                        } while ($x != $y);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(11, 30);
                        $y = random_int(31, 100);
                    } while ($x != $xold or $y != $yold && $x == $y);
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
        if ($level === '!?'){
            do {
                $x = random_int(31, 100);
                $y = random_int(31, 100);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(31, 100);
                            $y = random_int(31, 100);
                        } while ($x != $y);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(31, 100);
                        $y = random_int(31, 100);
                    } while ($x != $xold or $y != $yold && $x == $y);
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
        if ($level === '!'){
            do {
                $x = random_int(101, 1000);
                $y = random_int(101, 1000);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(101, 1000);
                            $y = random_int(101, 1000);
                        } while ($x != $y);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(101, 1000);
                        $y = random_int(101, 1000);
                    } while ($x != $xold or $y != $yold && $x == $y);
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
        if ($level === 'all'){
            do {
                $x = random_int($add, 1 + $add);
                $y = random_int($add, 1 + $add);
                if ($count >= 5){
                    $x = random_int($add - 5, $add - 4);
                    $tase = 2;
                }
                if ($count >= 10){ 
                    if ($check != 1){
                        $add = 6;
                        $check = 1;
                    };
                    $tase = 3;
                    $max = 10;
                    $x = random_int($add, 1 + $add);
                    $y = random_int($add, 1 + $add);
                }
                if ($count >= 15){ 
                    if ($check != 1){
                        $add = 6;
                        $add2 = 10;
                        $check = 1;
                    };
                    $tase = 4;
                    $max = 10;
                    $max2 = 20;
                    $x = random_int($add, 1 + $add);
                    $y = random_int($add2, 2 + $add2);
                    $add2 += $max2 / 5;

                }
                if ($count >= 20){ 
                    if ($check != 1){
                        $add = 2;
                        $add2 = 101;
                        $check = 1;
                    };
                    $tase = 5;
                    $max = 10;
                    $max2 = 500;
                    $x = random_int($add, 2 + $add);
                    $y = random_int($add2, 100 + $add2);
                    $add2 += $max2 / 5;
                }
                if ($count >= 25){ 
                    if ($check != 1){
                        $add = 11;
                        $add2 = 21;
                        $check = 1;
                    };
                    $tase = 6;
                    $max = 20;
                    $max2 = 30;
                    $x = random_int($add, 2 + $add);
                    $y = random_int($add2, 2 + $add2);
                    $add2 += $max2 / 5;
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
            
            } while ($count <= 30);
        }
        return $array;

    }
    //lünkamine
    public function lünkamine($level){

        $defaultMaxLiit = ["1"=>10, "2"=>100, "3"=>1000, "4"=>10000, "5"=>100000];
        $defaultMaxKor = ["1"=>10, "2"=>20, "3"=>100, "4"=>1000, "5"=>10000];


        $add = 1;
        $add2 = 1;
        $count = 0;
        $loendlünk = [];
        $max = $defaultMaxLiit[$level];
        $max2 = $defaultMaxKor[$level];

        do{

            $xlünk = 0;
            $ylünk = 0;

            $add += $max/10;
            $add2 += $max2/10;
            
            $jarjekord = rand(1, 2);

            if ($jarjekord === 1){
                $xlünk = "Lünk";
            } else{
                $ylünk =  "Lünk";
            } 


            $loos = random_int(1, 4);
            if ($loos % 2 == 1){
                if ($level == "1"){
                       
                    $x = random_int($add, 2 + $add);
                    $y = random_int($add, 2 + $add); 
                }
                if ($level == "2"){
                    $x = random_int($add, 9 + $add);
                    $y = random_int($add, 9 + $add);
                }
                if ($level == "3"){
                   
                    $x = random_int($add, 90 + $add);
                    $y = random_int($add, 90 + $add);
                }
                if ($level == "4"){
                    
                    $x = random_int($add, 900 + $add);
                    $y = random_int($add, 900 + $add);
                }
                if ($level == "5"){
                  
                    $x = random_int($add, 9000 + $add);
                    $y = random_int($add, 9000 + $add);
                }
            }
            if ($loos % 2 == 0){
                if ($level == "1"){
                   
                    $x = random_int($add2, 2 + $add2);
                    $y = random_int($add2, 2 + $add2);
                }
                if ($level == "2"){
                
                    $x = random_int($add2, 2 + $add2);
                    $y = random_int($add2, 2 + $add2);
                }
                if ($level == "3"){
                 
                    $x = random_int($add2, 8 + $add2);
                    $y = random_int($add2, 8 + $add2);
                }
                if ($level == "4"){
                
                    $x = random_int($add2, 90 + $add2);
                    $y = random_int($add2, 90 + $add2);
                }
                if ($level == "5"){
                   
                    $x = random_int($add2, 900 + $add2);
                    $y = random_int($add2, 900 + $add2);
                }

            }
            if ($loos == 1){
                if ($xlünk == 'Lünk'){
                    $ylünk = $y;
                } else {
                    $xlünk = $x;
                }
                array_push($loendlünk, ["operation"=>$xlünk . '+' . $ylünk . " = " . $x + $y, "answer"=>$x + $y - (is_string($xlünk) ? $ylünk : $xlünk), "level"=>$level]);
            }
            if ($loos == 2){
                if ($xlünk == 'Lünk'){
                    $ylünk = $y;
                } else {
                    $xlünk = $x;
                }
                array_push($loendlünk, ["operation"=>$xlünk . '·' . $ylünk . " = " . $x * $y, "answer"=>$x * $y / (is_string($xlünk) ? $ylünk : $xlünk), "level"=>$level]);
            }
            if ($loos == 3){
                if ($xlünk == 'Lünk'){
                    $ylünk = $y;
                } else {
                    $xlünk = $x;
                }
                if ($y > $x){
                    array_push($loendlünk, ["operation"=>$ylünk . '-' . $xlünk . " = " . $y - $x, "answer"=>($ylünk == "Lünk" ? $y : $x), "level"=>$level]);
                }else{
                    array_push($loendlünk, ["operation"=>$xlünk . '-' . $ylünk . " = " . $x - $y, "answer"=>($ylünk == "Lünk" ? $y : $x), "level"=>$level]);
                }
            }
            if ($loos == 4){
                if ($xlünk == 'Lünk'){
                    $ylünk = $y;
                    array_push($loendlünk, ["operation"=>$xlünk . ':' . $ylünk . " = " . $x, "answer"=>$x * $y, "level"=>$level]);
                } else {
                    $xlünk = $x;
                    array_push($loendlünk, ["operation"=>$xlünk * $y . ':' . $ylünk . " = " . $x, "answer"=>$y, "level"=>$level]);
                }
            }
            $count ++;
        }while ($count < 10);
        return $loendlünk;
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
        $xjagold = 0;
        $yjagold = 0;
        $check = 0;
        

        if ($level === '1'){
            do{
                $x = random_int(1, 9);
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
                    } while ($x != $xold or $y != $yold && $x == $y);
                }
                $xold = $x;
                $yold = $y;
                
                uuesti:
                $random  = random_int(1, 4);
                if ($random == 1){
                    $proov1 = $x * $y;
                    $võrd = 1;
                    $Garl = '·';
                }
                if ($random == 2){
                    $proov1 = $x * $y / $x;
                    $võrd = 2;
                    $Garl = ':';
                }
                if ($random == 3){
                    $proov1 = $x + $y - $x;
                    $võrd = 3;
                    $Garl = '-';
                }
                if ($random == 4) {
                    $proov1 = $x + $y;
                    $võrd = 4;
                    $Garl = '+';
                }
                $x1 = $x;
                $y1 = $y;
                $random  = random_int(1, 4);
                if ($random == 1 && $võrd != 1){
                    $proov2 = $x * $y;
                    $Garl = '·';
                    
                    
                }
                if ($random == 2 && $võrd != 2){
                    $proov2 = $x1 * $y1 / $y1;
                    $Garl = ':';
                    $kaspar = 3;
                    
                }
                if ($random == 3 && $võrd != 3){
                    $proov2 = $x1 + $y1 - $y1;
                    $Garl = '-';
                    $kaspar = 4;
                   
                }
                if ($random == 4 && $võrd != 4) {
                    $proov2 = $x1 + $y1;
                    $Garl = '+';
                   
                }
                
                if ($proov1 > $proov2){
                    $random  = random_int(1, 2);
                    if ($random == 1){
                        //liitkorrutamine
                        array_push($array, ["operation1"=>$x . $Garl . $y, "operation2"=>$x1 . $Garl . $y1, "answer"=> "left", "level"=>$level]);
                    } else {
                        //jagamise ja lahutamise variatsioonid
                        if ($võrd == 3 and $kaspar == 3){
                            $suvaline = random_int(1,2);
                            //lahutamine ja jagamine
                            if ($suvaline == 1) {
                                array_push($array, ["operation"=>$x + $y . $Garl . $y, "operation2"=>$x1 * $y1 . $Garl . $y1,"answer"=>"left", "level"=>$level]);
                            } else {
                                array_push($array, ["operation"=>$x1 * $y1 . $Garl . $y1, "operation2"=>$x + $y . $Garl . $y,"answer"=>"right", "level"=>$level]);
                            }
                        }
                        //jagamine ja liitkorrutamine
                        if ($võrd != 3 && $võrd != 2 && $kaspar == 4){
                            $suvaline = random_int(1,2);
                            if ($suvaline == 1) {
                                array_push($array, ["operation"=>$x . $Garl . $y, "operation2"=>$x1 * $y1 . $Garl . $y1,"answer"=>"left", "level"=>$level]);
                            } else {
                                array_push($array, ["operation"=>$x1 * $y1 . $Garl . $y1, "operation2"=>$x . $Garl . $y,"answer"=>"right", "level"=>$level]);
                            }
                        }
                        //lahutamine ja liitkorrutamine
                        if ($võrd == 3 && $kaspar != 3 && $kaspar != 4){
                            $suvaline = random_int(1,2);
                            if ($suvaline == 1) {
                                array_push($array, ["operation"=>$x + $y . $Garl . $y, "operation2"=>$x1 . $Garl . $y1,"answer"=>"left", "level"=>$level]);
                            } else {
                                array_push($array, ["operation"=>$x1 . $Garl . $y1, "operation2"=>$x + $y . $Garl . $y,"answer"=>"right", "level"=>$level]);
                            }
                        } else {
                            goto uuesti;
                        }
                        
                    }
                }
                if ($proov2 > $proov1){
                    $random  = random_int(1, 2);
                    if ($random == 1){
                        //liitkorrutamine
                        array_push($array, ["operation1"=>$x . $Garl . $y, "operation2"=>$x1 . $Garl . $y1, "answer"=> "right", "level"=>$level]);
                    } else {
                        //jagamise ja lahutamise variatsioonid
                        if ($võrd == 3 and $kaspar == 3){
                            $suvaline = random_int(1,2);
                            //lahutamine ja jagamine
                            if ($suvaline == 1) {
                                array_push($array, ["operation"=>$x + $y . $Garl . $y, "operation2"=>$x1 * $y1 . $Garl . $y1,"answer"=>"right", "level"=>$level]);
                            } else {
                                array_push($array, ["operation"=>$x1 * $y1 . $Garl . $y1, "operation2"=>$x + $y . $Garl . $y,"answer"=>"left", "level"=>$level]);
                            }
                        }
                        //jagamine ja liitkorrutamine
                        if ($võrd != 3 && $võrd != 2 && $kaspar == 4){
                            $suvaline = random_int(1,2);
                            if ($suvaline == 1) {
                                array_push($array, ["operation"=>$x . $Garl . $y, "operation2"=>$x1 * $y1 . $Garl . $y1,"answer"=>"right", "level"=>$level]);
                            } else {
                                array_push($array, ["operation"=>$x1 * $y1 . $Garl . $y1, "operation2"=>$x . $Garl . $y,"answer"=>"left", "level"=>$level]);
                            }
                        }
                        //lahutamine ja liitkorrutamine
                        if ($võrd == 3 && $kaspar != 3 && $kaspar != 4){
                            $suvaline = random_int(1,2);
                            if ($suvaline == 1) {
                                array_push($array, ["operation"=>$x + $y . $Garl . $y, "operation2"=>$x1 . $Garl . $y1,"answer"=>"right", "level"=>$level]);
                            } else {
                                array_push($array, ["operation"=>$x1 . $Garl . $y1, "operation2"=>$x + $y . $Garl . $y,"answer"=>"left", "level"=>$level]);
                            }
                        } else {
                            goto uuesti;
                        }
                        
                    }
                } else {
                    goto uuesti;
                }

                $count ++;
            } while ($count <= 10);
            
        }
        if ($level === '2'){
            do{
                $x = random_int(10, 19);
                $y = random_int(11, 20);
                $xjag = random_int(1, 10);
                $yjag = random_int(1, 10);
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
                    } while ($x != $xold or $y != $yold && $x == $y);
                }
                $xold = $x;
                $yold = $y;
                if ($xjag == $yjag){
                    $check ++;
                    if ($check == 2){
                        do{
                            $xjag = random_int(1, 10);
                            $yjag = random_int(1, 10);
                        } while ($xjag != $yjag);
                    }
                }
                if ($xjag == $xjagold or $yjag == $yjagold){
                    do{
                        $xjag = random_int(10, 19);
                        $yjag = random_int(11, 20);
                    } while ($xjag != $jagxold or $yjag != $yjagold);
                }
                $xjagold = $xjag;
                $yjagold = $yjag;
                
                "uuesti:";
                $random  = random_int(1, 4);
                $ettearvamatu = random_int(1,4);
                if ($ettearvamatu == 1){
                    $Metsa = '+';
                    $proov1 = $x;
                    $proov2 = $y;
                    if ($random == 1){
                        $proov1 += $xjag * $yjag;
                        $võrd = 1;
                        $Garl = '·';
                    }
                    if ($random == 2){
                        $proov1 += $x * $y / $x;
                        $võrd = 2;
                        $Garl = ':';
                    }
                    if ($random == 3){
                        $proov1 += $x + $y - $x;
                        $võrd = 3;
                        $Garl = '-';
                    }
                    if ($random == 4) {
                        $proov1 += $x + $y;
                        $võrd = 4;
                        $Garl = '+';
                    }
                }
                if ($ettearvamatu == 2){
                    $Metsa = '-';
                }
                if ($ettearvamatu == 3){
                    $Metsa = '*';
                    if ($random == 1){
                        $proov1 *= $xjag * $yjag;
                        $võrd = 1;
                        $Garl = '·';
                    }
                    if ($random == 2){
                        $proov1 *= $x * $y / $x;
                        $võrd = 2;
                        $Garl = ':';
                    }
                    if ($random == 3){
                        $proov1 *= $x + $y - $x;
                        $võrd = 3;
                        $Garl = '-';
                    }
                    if ($random == 4) {
                        $proov1 *= $x + $y;
                        $võrd = 4;
                        $Garl = '+';
                    }
                }
                if ($ettearvamatu == 4){
                    $Metsa = ':';
                    if ($random == 1){
                        $proov1 /= $xjag * $yjag;
                        $võrd = 1;
                        $Garl = '·';
                    }
                    if ($random == 2){
                        $proov1 += $x * $y / $x;
                        $võrd = 2;
                        $Garl = ':';
                    }
                    if ($random == 3){
                        $proov1 += $x + $y - $x;
                        $võrd = 3;
                        $Garl = '-';
                    }
                    if ($random == 4) {
                        $proov1 += $x + $y;
                        $võrd = 4;
                        $Garl = '+';
                    }
                }
                
                $x1 = $x;
                $y1 = $y;
                $random  = random_int(1, 4);
                if ($random == 1 && $võrd != 1){
                    $proov2 += $x * $y;
                    $Garl = '·';
                    
                    
                }
                if ($random == 2 && $võrd != 2){
                    $proov2 += $x1 * $y1 / $y1;
                    $Garl = ':';
                    $kaspar = 3;
                    
                }
                if ($random == 3 && $võrd != 3){
                    $proov2 += $x1 + $y1 - $y1;
                    $Garl = '-';
                    $kaspar = 4;
                   
                }
                if ($random == 4 && $võrd != 4) {
                    $proov2 += $x1 + $y1;
                    $Garl = '+';
                   
                }
                
                if ($proov1 > $proov2){
                    $random  = random_int(1, 2);
                    if ($random == 1){
                        //liitkorrutamine
                        array_push($array, ["operation1"=>$x . $Garl . $y, "operation2"=>$x1 . $Garl . $y1, "answer"=> "left", "level"=>$level]);
                    } else {
                        //jagamise ja lahutamise variatsioonid
                        if ($võrd == 3 and $kaspar == 3){
                            $suvaline = random_int(1,2);
                            //lahutamine ja jagamine
                            if ($suvaline == 1) {
                                array_push($array, ["operation"=>$x + $y . $Garl . $y, "operation2"=>$x1 * $y1 . $Garl . $y1,"answer"=>"left", "level"=>$level]);
                            } else {
                                array_push($array, ["operation"=>$x1 * $y1 . $Garl . $y1, "operation2"=>$x + $y . $Garl . $y,"answer"=>"right", "level"=>$level]);
                            }
                        }
                        //jagamine ja liitkorrutamine
                        if ($võrd != 3 && $võrd != 2 && $kaspar == 4){
                            $suvaline = random_int(1,2);
                            if ($suvaline == 1) {
                                array_push($array, ["operation"=>$x . $Garl . $y, "operation2"=>$x1 * $y1 . $Garl . $y1,"answer"=>"left", "level"=>$level]);
                            } else {
                                array_push($array, ["operation"=>$x1 * $y1 . $Garl . $y1, "operation2"=>$x . $Garl . $y,"answer"=>"right", "level"=>$level]);
                            }
                        }
                        //lahutamine ja liitkorrutamine
                        if ($võrd == 3 && $kaspar != 3 && $kaspar != 4){
                            $suvaline = random_int(1,2);
                            if ($suvaline == 1) {
                                array_push($array, ["operation"=>$x + $y . $Garl . $y, "operation2"=>$x1 . $Garl . $y1,"answer"=>"left", "level"=>$level]);
                            } else {
                                array_push($array, ["operation"=>$x1 . $Garl . $y1, "operation2"=>$x + $y . $Garl . $y,"answer"=>"right", "level"=>$level]);
                            }
                        } else {
                            //goto uuesti;
                        }
                        
                    }
                }
                if ($proov2 > $proov1){
                    $random  = random_int(1, 2);
                    if ($random == 1){
                        //liitkorrutamine
                        array_push($array, ["operation1"=>$x . $Garl . $y, "operation2"=>$x1 . $Garl . $y1, "answer"=> "right", "level"=>$level]);
                    } else {
                        //jagamise ja lahutamise variatsioonid
                        if ($võrd == 3 and $kaspar == 3){
                            $suvaline = random_int(1,2);
                            //lahutamine ja jagamine
                            if ($suvaline == 1) {
                                array_push($array, ["operation"=>$x + $y . $Garl . $y, "operation2"=>$x1 * $y1 . $Garl . $y1,"answer"=>"right", "level"=>$level]);
                            } else {
                                array_push($array, ["operation"=>$x1 * $y1 . $Garl . $y1, "operation2"=>$x + $y . $Garl . $y,"answer"=>"left", "level"=>$level]);
                            }
                        }
                        //jagamine ja liitkorrutamine
                        if ($võrd != 3 && $võrd != 2 && $kaspar == 4){
                            $suvaline = random_int(1,2);
                            if ($suvaline == 1) {
                                array_push($array, ["operation"=>$x . $Garl . $y, "operation2"=>$x1 * $y1 . $Garl . $y1,"answer"=>"right", "level"=>$level]);
                            } else {
                                array_push($array, ["operation"=>$x1 * $y1 . $Garl . $y1, "operation2"=>$x . $Garl . $y,"answer"=>"left", "level"=>$level]);
                            }
                        }
                        //lahutamine ja liitkorrutamine
                        if ($võrd == 3 && $kaspar != 3 && $kaspar != 4){
                            $suvaline = random_int(1,2);
                            if ($suvaline == 1) {
                                array_push($array, ["operation"=>$x + $y . $Garl . $y, "operation2"=>$x1 . $Garl . $y1,"answer"=>"right", "level"=>$level]);
                            } else {
                                array_push($array, ["operation"=>$x1 . $Garl . $y1, "operation2"=>$x + $y . $Garl . $y,"answer"=>"left", "level"=>$level]);
                            }
                        } else {
                            //goto uuesti;
                        }
                        
                    }
                } else {
                    //goto uuesti;
                }

                $count ++;
            } while ($count <= 10);
            
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
                    } while ($x != $xold or $y != $yold && $x == $y);
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
                    } while ($x != $xold or $y != $yold && $x == $y);
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
                    } while ($x != $xold or $y != $yold && $x == $y);
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
    }

    public function wrapper($tehe, $tasemed, $tüüp){
        $loend = [];

        for ($lugeja = 0; $lugeja < count($tasemed); $lugeja ++){
            if($tehe == "liitmine" or $tehe == "lahutamine"){
                $loend[$tasemed[$lugeja]] = app('App\Http\Controllers\GameController')->liitlah($tasemed[$lugeja], $tehe, $tüüp);
            }

            if($tehe == "korrutamine" or $tehe == "jagamine"){
                $loend[$tasemed[$lugeja]] = app('App\Http\Controllers\GameController')->korjag($tasemed[$lugeja], $tehe, $tüüp);
            }

            if($tehe == "lünkamine"){
                $loend[$tasemed[$lugeja]] = app('App\Http\Controllers\GameController')->lünkamine($tasemed[$lugeja]);
            }

        }

        return $loend;
    }
}