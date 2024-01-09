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


// VAJADUSEL MUUDA NIME
class BackupGameController extends Controller
{

    // Esialgu 1206-realine funktsioon
    // Vaatame, kui palju vähemaks võtta annab

    //Addition and Substraction
    public function liitlah($level, $mis, $tüüp){
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
        $kontroll = 0;

        //Specific levels - Fractions
        if ($tüüp === "fraction"){

            // Funktsionaalseks
            // Üldine kuju nt:
            // generateOp(x, y, xold, yold, ans, op, level){}
            // x ja y oleksid siis nt funktsioonid??

            // Pmst if laused saaks ka Mapiks teha

            if ($level === '1'){
                do{
                    $x = random_int(1, 5) + random_int(1, 9)/10;
                    $y = random_int(1, 5) + random_int(1, 9)/10;
                    if ($x == $y){
                        // ?!
                        $check ++;

                        // Konstant?
                        if ($check == 2){
                            do{
                                $x = random_int(1, 5) + random_int(1, 9)/10;
                                $y = random_int(1, 5) + random_int(1, 9)/10;

                                // See ei tööta nii, nagu eeldatud
                                // Do-while töötab seni, kuni tingimus on true
                            } while ($x != $y && $x != $yold && $y != $xold);
                        }
                    }
                    if ($x == $xold or $y == $yold){
                        do{
                            $x = random_int(1, 5) + random_int(1, 9)/10;
                            $y = random_int(1, 5) + random_int(1, 9)/10;

                            // Sama probleem
                        } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
                    }
                    $xold = $x;
                    $yold = $y;

                    // Kui on mõlemad:
                    // $mis = array_rand(["liitmine","lahutamine"]);

                    // Samamoodi Mapiks
                    if ($mis === 'liitmine'){

                        // Võiks ka eraldi funktsioon olla
                        // Töötaks kahe arvuga
                        // addOp(x, op, y, ans, level)
                        array_push($array, ["operation"=>$x. '+' . $y, "answer"=>$x + $y, "level"=>$level]);
                    }
                    if ($mis === 'lahutamine'){
                        array_push($array, ["operation"=>$x + $y. '-' . $y, "answer"=>$x, "level"=>$level]);
                    }

                    // Kui on mõlemad, siis võiks teha enne suvaliseks tehteks (kas liitmine v lahutamine)
                    if ($mis === 'mõlemad'){
                        $random  = random_int(1, 2);
                        if ($random == 1){
                            array_push($array, ["operation"=>$x + $y. '-' . $y, "answer"=>$x, "level"=>$level]);
                        } else {
                            array_push($array, ["operation"=>$x. '+' . $y, "answer"=>$x + $y, "level"=>$level]);
                        }
                    }


                    $count ++;

                    // Konstandid faili algusesse
                } while ($count <= 25); 
            }


            if ($level === '2'){
                do{
                    $x = random_int(6, 10) + random_int(1, 9)/10;
                    $y = random_int(6, 10) + random_int(1, 9)/10;
                    if ($x == $y){
                        $check ++;
                        if ($check == 2){
                            do{
                                $x = random_int(6, 10) + random_int(1, 9)/10;
                                $y = random_int(6, 10) + random_int(1, 9)/10;
                            } while ($x != $y && $x != $yold && $y != $xold);
                        }
                    }
                    if ($x == $xold or $y == $yold){
                        do{
                            $x = random_int(6, 10) + random_int(1, 9)/10;
                            $y = random_int(6, 10) + random_int(1, 9)/10;
                        } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
                    }
                    $xold = $x;
                    $yold = $y;
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
                    $count ++;
                } while ($count <= 25);
                
            }
            if ($level === '3'){
                do {
                    $x = random_int(10, 30) + random_int(1, 9)/10;
                    $y = random_int(11, 29) + random_int(1, 9)/10;
                    if ($x == $y){
                        $check ++;
                        if ($check == 2){
                            do{
                                $x = random_int(10, 30) + random_int(1, 9)/10;
                                $y = random_int(11, 29) + random_int(1, 9)/10;
                            } while ($x != $y && $x != $yold && $y != $xold);
                        }
                    }
                    if ($x == $xold or $y == $yold){
                        do{
                            $x = random_int(10, 30) + random_int(1, 9)/10;
                            $y = random_int(11, 29) + random_int(1, 9)/10;
                        } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
                    }
                    $xold = $x;
                    $yold = $y;
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
                    $count ++;
                } while ($count <= 25);
                
            }
            if ($level === '4'){
                do {
                    $x = random_int(30, 100) + random_int(1, 9)/10;
                    $y = random_int(29, 99) + random_int(1, 9)/10;
                    if ($x == $y){
                        $check ++;
                        if ($check == 2){
                            do{
                                $x = random_int(30, 100) + random_int(1, 9)/10;
                                $y = random_int(29, 100) + random_int(1, 9)/10;
                            } while ($x != $y && $x != $yold && $y != $xold);
                        }
                    }
                    if ($x == $xold or $y == $yold){
                        do{
                            $x = random_int(30, 100) + random_int(1, 9)/10;
                            $y = random_int(29, 99) + random_int(1, 9)/10;
                        } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
                    }
                    $xold = $x;
                    $yold = $y;
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
                    $count ++;
                } while ($count <= 25);
                
            }
            if ($level === '5'){
                do {
                    $x = random_int(100, 500) + random_int(1, 99)/100;
                    $y = random_int(101, 499) + random_int(1, 99)/100;
                    if ($x == $y){
                        $check ++;
                        if ($check == 2){
                            do{
                                $x = random_int(100, 500) + random_int(1, 99)/100;
                                $y = random_int(101, 499) + random_int(1, 99)/100;
                            } while ($x != $y && $x != $yold && $y != $xold);
                        }
                    }
                    if ($x == $xold or $y == $yold){
                        do{
                            $x = random_int(100, 500) + random_int(1, 99)/100;
                            $y = random_int(101, 499) + random_int(1, 99)/100;
                        } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
                    }
                    $xold = $x;
                    $yold = $y;
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
                    $count ++;
                } while ($count <= 25);    
            }

            if ($level === '?'){
                do {
                    $x = random_int(500, 1000) + random_int(1, 99)/100;
                    $y = random_int(500, 1000) + random_int(1, 99)/100;
                    if ($x == $y){
                        $check ++;
                        if ($check == 2){
                            do{
                                $x = random_int(500, 1000) + random_int(1, 99)/100;
                                $y = random_int(500, 1000) + random_int(1, 99)/100;
                            } while ($x != $y && $x != $yold && $y != $xold);
                        }
                    }
                    if ($x == $xold or $y == $yold){
                        do{
                            $x = random_int(500, 1000) + random_int(1, 99)/100;
                            $y = random_int(500, 1000) + random_int(1, 99)/100;
                        } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
                    }
                    $xold = $x;
                    $yold = $y;
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
                    $count ++;
                } while ($count <= 25);    
            }

            if ($level === '!?'){
                do {
                    $x = random_int(1000, 10000) + random_int(1, 99)/100;
                    $y = random_int(1000, 10000) + random_int(1, 99)/100;
                    if ($x == $y){
                        $check ++;
                        if ($check == 2){
                            do{
                                $x = random_int(1000, 10000) + random_int(1, 99)/100;
                                $y = random_int(1000, 10000) + random_int(1, 99)/100;
                            } while ($x != $y && $x != $yold && $y != $xold);
                        }
                    }
                    if ($x == $xold or $y == $yold){
                        do{
                            $x = random_int(1000, 10000) + random_int(1, 99)/100;
                            $y = random_int(1000, 10000) + random_int(1, 99)/100;
                        } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
                    }
                    $xold = $x;
                    $yold = $y;
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
                    $count ++;
                } while ($count <= 25);
                
            }
            if ($level === '!'){
                do {
                    $x = random_int(10000, 100000) + random_int(1, 999)/1000;
                    $y = random_int(10000, 100000) + random_int(1, 999)/1000;
                    if ($x == $y){
                        $check ++;
                        if ($check == 2){
                            do{
                                $x = random_int(10000, 100000) + random_int(1, 999)/1000;
                                $y = random_int(10000, 100000) + random_int(1, 999)/1000;
                            } while ($x != $y && $x != $yold && $y != $xold);
                        }
                    }
                    if ($x == $xold or $y == $yold){
                        do{
                            $x = random_int(10000, 100000) + random_int(1, 999)/1000;
                            $y = random_int(10000, 100000) + random_int(1, 999)/1000;
                        } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
                    }
                    $xold = $x;
                    $yold = $y;
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
                    $count ++;
                } while ($count <= 25);
                
            }
            //Ascending levels
            if ($level === 'all'){
                do {
                    again2:
                    $x = random_int($add, 1 + $add) + random_int(1, 9)/10;
                    $y = random_int($add, 1 + $add) + random_int(1, 9)/10;
                    if ($count >= 5){
                        $tase = 2;
                    }
                    if ($count >= 10){
                        $tase = 3;
                        $max = 30;
                        $x = random_int($add, 4 + $add) + random_int(1, 9)/10;
                        $y = random_int($add, 4 + $add) + random_int(1, 9)/10;
                    }
                    if ($count >= 15){
                        $tase = 4;
                        $max = 100;
                        $x = random_int($add, 14 + $add) + random_int(1, 99)/100;
                        $y = random_int($add, 14 + $add) + random_int(1, 99)/100;
                    }
                    if ($count >= 20){
                        $tase = 5;
                        $max = 500;
                        $x = random_int($add, 80 + $add) + random_int(1, 99)/100;
                        $y = random_int($add, 80 + $add) + random_int(1, 99)/100;
                    }
                    if ($x == $y){
                        $kontroll ++;
                        if ($kontroll == 2){
                            goto again2;
                        }
                    }
                    if ($x == $xold or $y == $yold){
                        // Kas see töötab?
                        goto again2;
                    }
                    $xold = $x;
                    $yold = $y;
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


        // Naturaalarvude puhul täpselt samamoodi

        //Specific levels - Whole numbers
        if ($tüüp === "natural"){
            if ($level === '1'){
                do{
                    $x = random_int(-5, 5);
                    $y = random_int(-5, 5);
                    if ($x == $y){
                        $check ++;
                        if ($check == 2){
                            do{
                                $x = random_int(-5, 5);
                                $y = random_int(-5, 5);
                            } while ($x != $y && $x != $yold && $y != $xold);
                        }
                    }
                    if ($x == $xold or $y == $yold){
                        do{
                            $x = random_int(-5, 5);
                            $y = random_int(-5, 5);
                        } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
                    }
                    
                    $xold = $x;
                    $yold = $y;
                    
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
                    $count ++;
                } while ($count <= 25);
                
            }
            if ($level === '2'){
                do{

                    // Or kindlasti nii ei tööta
                    // Pigem array_rand
                    $x = random_int(-10, -6) or random_int(6, 10);
                    $y = random_int(-10, -6) or random_int(6, 10);
                    if ($x == $y){
                        $check ++;
                        if ($check == 2){
                            do{
                                $x = random_int(-10, -6) or random_int(6, 10);
                                $y = random_int(-10, -6) or random_int(6, 10);
                            } while ($x != $y && $x != $yold && $y != $xold);
                        }
                    }
                    if ($x == $xold or $y == $yold){
                        do{
                            $x = random_int(-10, -6) or random_int(6, 10);
                            $y = random_int(-10, -6) or random_int(6, 10);
                        } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
                    }
                    $xold = $x;
                    $yold = $y;
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
                    $count ++;
                } while ($count <= 25);
                
            }
            if ($level === '3'){
                do {
                    $x = random_int(-30, -10) or random_int(10, 30);
                    $y = random_int(-30, -10) or random_int(10, 30);
                    if ($x == $y){
                        $check ++;
                        if ($check == 2){
                            do{
                                $x = random_int(-30, -10) or random_int(10, 30);
                                $y = random_int(-30, -10) or random_int(10, 30);
                            } while ($x != $y && $x != $yold && $y != $xold);
                        }
                    }
                    if ($x == $xold or $y == $yold){
                        do{
                            $x = random_int(-30, -10) or random_int(10, 30);
                            $y = random_int(-30, -10) or random_int(10, 30);
                        } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
                    }
                    $xold = $x;
                    $yold = $y;
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
                    $count ++;
                } while ($count <= 25);
                
            }
            if ($level === '4'){
                do {
                    $x = random_int(-100, -30) or random_int(30, 100);
                    $y = random_int(-100, -30) or random_int(30, 100);
                    if ($x == $y){
                        $check ++;
                        if ($check == 2){
                            do{
                                $x = random_int(-100, -30) or random_int(30, 100);
                                $y = random_int(-100, -30) or random_int(30, 100);
                            } while ($x != $y && $x != $yold && $y != $xold);
                        }
                    }
                    if ($x == $xold or $y == $yold){
                        do{
                            $x = random_int(-100, -30) or random_int(30, 100);
                            $y = random_int(-100, -30) or random_int(30, 100);
                        } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
                    }
                    $xold = $x;
                    $yold = $y;
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
                    $count ++;
                } while ($count <= 25);
                
            }
            if ($level === '5'){
                do {
                    $x = random_int(-500, -100) or random_int(100,500);
                    $y = random_int(-500, -100) or random_int(100,500);
                    if ($x == $y){
                        $check ++;
                        if ($check == 2){
                            do{
                                $x = random_int(-500, -100) or random_int(100,500);
                                $y = random_int(-500, -100) or random_int(100,500);
                            } while ($x != $y && $x != $yold && $y != $xold);
                        }
                    }
                    if ($x == $xold or $y == $yold){
                        do{
                            $x = random_int(-500, -100) or random_int(100,500);
                            $y = random_int(-500, -100) or random_int(100,500);
                        } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
                    }
                    $xold = $x;
                    $yold = $y;
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
                    $count ++;
                } while ($count <= 25);
                
            }
            if ($level === '?'){
                do {
                    $x = random_int(500, 1000) or random_int(-1000,-500);
                    $y = random_int(500, 1000) or random_int(-1000,-500);
                    if ($x == $y){
                        $check ++;
                        if ($check == 2){
                            do{
                                $x = random_int(500, 1000) or random_int(-1000,-500);
                                $y = random_int(500, 1000) or random_int(-1000,-500);
                            } while ($x != $y && $x != $yold && $y != $xold);
                        }
                    }
                    if ($x == $xold or $y == $yold){
                        do{
                            $x = random_int(500, 1000) or random_int(-1000,-500);
                            $y = random_int(500, 1000) or random_int(-1000,-500);
                        } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
                    }
                    $xold = $x;
                    $yold = $y;
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
                    $count ++;
                } while ($count <= 25);
                
            }
            if ($level === '!?'){
                do {
                    $x = random_int(1000, 10000) or random_int(-10000,-1000);
                    $y = random_int(1000, 10000) or random_int(-10000,-1000);
                    if ($x == $y){
                        $check ++;
                        if ($check == 2){
                            do{
                                $x = random_int(1000, 10000) or random_int(-10000,-1000);
                                $y = random_int(1000, 10000) or random_int(-10000,-1000);
                            } while ($x != $y && $x != $yold && $y != $xold);
                        }
                    }
                    if ($x == $xold or $y == $yold){
                        do{
                            $x = random_int(1000, 10000) or random_int(-10000,-1000);
                            $y = random_int(1000, 10000) or random_int(-10000,-1000);
                        } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
                    }
                    $xold = $x;
                    $yold = $y;
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
                    $count ++;
                } while ($count <= 25);
                
            }
            if ($level === '!'){
                do {
                    $x = random_int(10000, 100000) or random_int(-100000,-10000);
                    $y = random_int(10000, 100000) or random_int(-100000,-10000);
                    if ($x == $y){
                        $check ++;
                        if ($check == 2){
                            do{
                                $x = random_int(10000, 100000) or random_int(-100000,-10000);
                                $y = random_int(10000, 100000) or random_int(-100000,-10000);
                            } while ($x != $y && $x != $yold && $y != $xold);
                        }
                    }
                    if ($x == $xold or $y == $yold){
                        do{
                            $x = random_int(10000, 100000) or random_int(-100000,-10000);
                            $y = random_int(10000, 100000) or random_int(-100000,-10000);
                        } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
                    }
                    $xold = $x;
                    $yold = $y;
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
                    $count ++;
                } while ($count <= 25);
                
            }
            //Ascending levels
            if ($level === 'all'){
                do {
                    again3:
                    $x = random_int($add - 1, 1 + $add);
                    $y = random_int($add - 1, 1 + $add);
                    $tase = 1;

                    // If-laused pigem Mapiks? Aga kas saab töötada?
                    if ($count >= 5){
                        $tase = 2;
                    }
                    if ($count >= 10){ 
                        $tase = 3;
                        $max = 30;
                        $x = random_int($add - 4, 4 + $add);
                        $y = random_int($add - 4, 4 + $add);
                    }
                    if ($count >= 15){ 
                        $tase = 4;
                        $max = 100;
                        $x = random_int($add - 14, 14 + $add);
                        $y = random_int($add - 14, 14 + $add);
    
                    }
                    if ($count >= 20){ 
                        $tase = 5;
                        $max = 500;
                        $x = random_int($add - 80, 80 + $add);
                        $y = random_int($add - 80, 80 + $add);
                    }
                    if ($x == $y){

                        // Loogika?
                        $kontroll ++;
                        if ($kontroll == 2){
                            goto again3;
                        }
                    }


                    if ($x == $xold or $y == $yold){
                        goto again3;
                    }


                    $xold = $x;
                    $yold = $y;

                    // Samamoodi nagu enne
                    if ($mis === 'liitmine'){
                        array_push($array, ["operation"=>$x. '+' . $y, "answer"=>$x + $y, "level"=>$tase]);
                    }
                    if ($mis === 'lahutamine'){
                        array_push($array, ["operation"=>$x + $y. '-' . $y, "answer"=>$x, "level"=>$tase]);
                    }
                    if ($mis === 'mõlemad'){
                        $random  = random_int(1, 2);
                        if ($random == 1){
                            array_push($array, ["operation"=>$x + $y. '-' . $y, "answer"=>$x, "level"=>$tase]);
                        } else {
                            array_push($array, ["operation"=>$x. '+' . $y, "answer"=>$x + $y, "level"=>$tase]);
                        }
                    }


                    $add += $max / 5;
                    $count ++;
                

                    // Konstant
                } while ($count <= 25);
            }


            return $array;
        }


        // Ikka needsamad mõtted
        //Specific levels - Natural numbers
        if ($level === '1'){
            do {
                $x = random_int(1, 5);
                $y = random_int(1, 5);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(1, 5);
                            $y = random_int(1, 5);
                        } while ($x != $y && $x != $yold && $y != $xold);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(1, 5);
                        $y = random_int(1, 5);
                    } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
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
                $x = random_int(5, 10);
                $y = random_int(5, 10);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(5, 10);
                            $y = random_int(5, 10);
                        } while ($x != $y && $x != $yold && $y != $xold);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(5, 10);
                        $y = random_int(5, 10);
                    } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
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
                $x = random_int(10, 30);
                $y = random_int(10, 30);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(10, 30);
                            $y = random_int(10, 30);
                        } while ($x != $y && $x != $yold && $y != $xold);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(10, 30);
                        $y = random_int(10, 30);
                    } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
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
                $x = random_int(30, 100);
                $y = random_int(30, 100);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(30, 100);
                            $y = random_int(30, 100);
                        } while ($x != $y && $x != $yold && $y != $xold);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(30, 100);
                        $y = random_int(30, 100);
                    } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
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
                $x = random_int(100, 500);
                $y = random_int(100, 500);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(100, 500);
                            $y = random_int(100, 500);
                        } while ($x != $y && $x != $yold && $y != $xold);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(100, 500);
                        $y = random_int(100, 500);
                    } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
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
                again4:
                $x = random_int($add, 1 + $add);
                $y = random_int($add, 1 + $add);

                $tase = 1;
                if ($count >= 5){
                    $tase = 2;
                }
                if ($count >= 10){
                    $tase = 3;
                    $max = 30;
                    $x = random_int($add, 4 + $add);
                    $y = random_int($add, 4 + $add);
                }
                if ($count >= 15){
                    $tase = 4;
                    $max = 100;
                    $x = random_int($add, 14 + $add);
                    $y = random_int($add, 14 + $add);
                }
                if ($count >= 20){
                    $tase = 5;
                    $max = 500;
                    $x = random_int($add, 80 + $add);
                    $y = random_int($add, 80 + $add);
                }
                if ($x == $y){
                    $kontroll ++;
                    if ($kontroll == 2){
                        goto again4;
                    }
                }


                if ($x == $xold or $y == $yold){
                    goto again4;
                }

                $xold = $x;
                $yold = $y;

                if ($mis === 'liitmine'){
                    array_push($array, ["operation"=>$x. '+' . $y, "answer"=>$x + $y, "level"=>$tase]);
                }
                if ($mis === 'lahutamine'){
                    array_push($array, ["operation"=>$x + $y. '-' . $y, "answer"=>$x, "level"=>$tase]);
                }
                if ($mis === 'mõlemad'){
                    $random  = random_int(1, 2);
                    if ($random == 1){
                        array_push($array, ["operation"=>$x + $y. '-' . $y, "answer"=>$x, "level"=>$tase]);
                    } else {
                        array_push($array, ["operation"=>$x. '+' . $y, "answer"=>$x + $y, "level"=>$tase]);
                    }
                }
                $add += $max/5;
                $count ++;
            }while ($count <= 25);
        }


        if ($level === '?'){
            do {
                $x = random_int(500, 1000);
                $y = random_int(500, 1000);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(500, 1000);
                            $y = random_int(500, 1000);
                        } while ($x != $y && $x != $yold && $y != $xold);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(500, 1000);
                        $y = random_int(500, 1000);
                    } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
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
                $x = random_int(1000, 10000);
                $y = random_int(1000, 10000);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(1000, 10000);
                            $y = random_int(1000, 10000);
                        } while ($x != $y && $x != $yold && $y != $xold);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(1000, 10000);
                        $y = random_int(1000, 10000);
                    } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
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
                $x = random_int(10000, 100000);
                $y = random_int(10000, 100000);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(10000, 100000);
                            $y = random_int(10000, 100000);
                        } while ($x != $y && $x != $yold && $y != $xold);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(10000, 100000);
                        $y = random_int(10000, 100000);
                    } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
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




    public function korjag($level, $mis, $tüüp){
        $array = [];
        $x = 0;
        $y = 0;
        $tase = 1;
        $count = 0;
        $min = 0;
        $max = 0;
        $max2 = 0;
        $add = 0;
        $add2 = 0;
        $xold = 0;
        $yold = 0;
        $check = 0;
        $kontroll = 0;
        
        //Specific levels - Fractions
        if ($tüüp === "fraction"){


            // Ka siin saab (vist) sedasama funktsiooni üsna edukalt kasutada
            if ($level === '1'){
                do{
                    $x = random_int(1, 5) + random_int(1, 9)/10;
                    $y = random_int(1, 5) + random_int(1, 9)/10;
                    if ($x == $y){
                        $check ++;
                        if ($check == 2){
                            do{
                                $x = random_int(1, 5) + random_int(1, 9)/10;
                                $y = random_int(1, 5) + random_int(1, 9)/10;
                            } while ($x != $y && $x != $yold && $y != $xold);
                        }
                    }
                    if ($x == $xold or $y == $yold){
                        do{
                            $x = random_int(1, 5) + random_int(1, 9)/10;
                            $y = random_int(1, 5) + random_int(1, 9)/10;
                        } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
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
                    $x = random_int(1, 5) + random_int(1, 9)/10;
                    $y = random_int(6, 10) + random_int(1, 9)/10;
                    if ($x == $y){
                        $check ++;
                        if ($check == 2){
                            do{
                                $x = random_int(1, 5) + random_int(1, 9)/10;
                                $y = random_int(6, 10) + random_int(1, 9)/10;
                            } while ($x != $y && $x != $yold && $y != $xold);
                        }
                    }
                    if ($x == $xold or $y == $yold){
                        do{
                            $x = random_int(1, 5) + random_int(1, 9)/10;
                            $y = random_int(6, 10) + random_int(1, 9)/10;
                        } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
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
                    $x = random_int(6, 10) + random_int(1, 9)/10;
                    $y = random_int(6, 10) + random_int(1, 9)/10;
                    if ($x == $y){
                        $check ++;
                        if ($check == 2){
                            do{
                                $x = random_int(6, 10) + random_int(1, 9)/10;
                                $y = random_int(6, 10) + random_int(1, 9)/10;
                            } while ($x != $y && $x != $yold && $y != $xold);
                        }
                    }
                    if ($x == $xold or $y == $yold){
                        do{
                            $x = random_int(6, 10) + random_int(1, 9)/10;
                            $y = random_int(6, 10) + random_int(1, 9)/10;
                        } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
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
                    $x = random_int(1, 10) + random_int(1, 9)/10;
                    $y = random_int(11, 20) + random_int(1, 9)/10;
                    if ($x == $y){
                        $check ++;
                        if ($check == 2){
                            do{
                                $x = random_int(1, 10) + random_int(1, 9)/10;
                                $y = random_int(11, 20) + random_int(1, 9)/10;
                            } while ($x != $y && $x != $yold && $y != $xold);
                        }
                    }
                    if ($x == $xold or $y == $yold){
                        do{
                            $x = random_int(1, 10) + random_int(1, 9)/10;
                            $y = random_int(11, 20) + random_int(1, 9)/10;
                        } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
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
                    $x = random_int(2, 10) + random_int(1, 99)/100;
                    $y = random_int(101, 500) + random_int(1, 99)/100;
                    if ($x == $y){
                        $check ++;
                        if ($check == 2){
                            do{
                                $x = random_int(2, 10) + random_int(1, 99)/100;
                                $y = random_int(101, 500) + random_int(1, 99)/100;
                            } while ($x != $y && $x != $yold && $y != $xold);
                        }
                    }
                    if ($x == $xold or $y == $yold){
                        do{
                            $x = random_int(2, 10) + random_int(1, 99)/100;
                            $y = random_int(101, 500) + random_int(1, 99)/100;
                        } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
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
                    $x = random_int(11, 20) + random_int(1, 99)/100;
                    $y = random_int(21, 30) + random_int(1, 99)/100;
                    if ($x == $y){
                        $check ++;
                        if ($check == 2){
                            do{
                                $x = random_int(11, 20) + random_int(1, 99)/100;
                                $y = random_int(21, 30) + random_int(1, 99)/100;
                            } while ($x != $y && $x != $yold && $y != $xold);
                        }
                    }
                    if ($x == $xold or $y == $yold){
                        do{
                            $x = random_int(11, 20) + random_int(1, 99)/100;
                            $y = random_int(21, 30) + random_int(1, 99)/100;
                        } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
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
                    $x = random_int(11, 30) + random_int(1, 99)/100;
                    $y = random_int(31, 100) + random_int(1, 99)/100;
                    if ($x == $y){
                        $check ++;
                        if ($check == 2){
                            do{
                                $x = random_int(11, 30) + random_int(1, 99)/100;
                                $y = random_int(31, 100) + random_int(1, 99)/100;
                            } while ($x != $y && $x != $yold && $y != $xold);
                        }
                    }
                    if ($x == $xold or $y == $yold){
                        do{
                            $x = random_int(11, 30) + random_int(1, 99)/100;
                            $y = random_int(31, 100) + random_int(1, 99)/100;
                        } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
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
                    $x = random_int(31, 100) + random_int(1, 99)/100;
                    $y = random_int(31, 100) + random_int(1, 99)/100;
                    if ($x == $y){
                        $check ++;
                        if ($check == 2){
                            do{
                                $x = random_int(31, 100) + random_int(1, 99)/100;
                                $y = random_int(31, 100) + random_int(1, 99)/100;
                            } while ($x != $y && $x != $yold && $y != $xold);
                        }
                    }
                    if ($x == $xold or $y == $yold){
                        do{
                            $x = random_int(31, 100) + random_int(1, 99)/100;
                            $y = random_int(31, 100) + random_int(1, 99)/100;
                        } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
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
                    $x = random_int(101, 1000) + random_int(1, 999)/1000;
                    $y = random_int(101, 1000) + random_int(1, 999)/1000;
                    if ($x == $y){
                        $check ++;
                        if ($check == 2){
                            do{
                                $x = random_int(101, 1000) + random_int(1, 999)/1000;
                                $y = random_int(101, 1000) + random_int(1, 999)/1000;
                            } while ($x != $y && $x != $yold && $y != $xold);
                        }
                    }
                    if ($x == $xold or $y == $yold){
                        do{
                            $x = random_int(101, 1000) + random_int(1, 999)/1000;
                            $y = random_int(101, 1000) + random_int(1, 999)/1000;
                        } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
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
            //Ascending levels
            if ($level === 'all'){
                do {
                    again1:
                    $x = random_int($add, 1 + $add) + random_int(1, 9)/10;
                    $y = random_int($add, 1 + $add) + random_int(1, 9)/10;
                    if ($count >= 5){
                        $x = random_int($add - 5, $add - 4) + random_int(1, 9)/10;
                        $tase = 2;
                    }
                    if ($count >= 10){ 
                        if ($check != 1){
                            $add = 6;
                            $check = 1;
                        };
                        $tase = 3;
                        $max = 10;
                        $x = random_int($add, 1 + $add) + random_int(1, 9)/10;
                        $y = random_int($add, 1 + $add) + random_int(1, 9)/10;
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
                        $x = random_int($add, 1 + $add) + random_int(1, 9)/10;
                        $y = random_int($add2, 2 + $add2) + random_int(1, 9)/10;
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
                        $x = random_int($add, 2 + $add) + random_int(1, 99)/100;
                        $y = random_int($add2, 100 + $add2) + random_int(1, 99)/100;
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
                        $x = random_int($add, 2 + $add) + random_int(1, 99)/100;
                        $y = random_int($add2, 2 + $add2) + random_int(1, 99)/100;
                        $add2 += $max2 / 5;
                    }
                    if ($x == $y){
                        $kontroll ++;
                        if ($kontroll == 2){
                            goto again1;
                        }
                    }
                    if ($x == $xold or $y == $yold){
                        goto again1;
                    }
                    $xold = $x;
                    $yold = $y;
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
        //Specific levels - Natural numbers
        if ($tüüp === "natural"){
            if ($level === '1'){
                do{
                    $x = random_int(-5, 5);
                    $y = random_int(-5, 5);
                    if ($x == $y){
                        $check ++;
                        if ($check == 2){
                            do{
                                $x = random_int(-5, 5);
                                $y = random_int(-5, 5);
                            } while ($x != $y && $x != $yold && $y != $xold);
                        }
                    }
                    if ($x == $xold or $y == $yold){
                        do{
                            $x = random_int(-5, 5);
                            $y = random_int(-5, 5);
                        } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
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
                    $x = random_int(-5, 5);
                    $y = random_int(-10, -6) or random_int(6, 10);
                    if ($x == $y){
                        $check ++;
                        if ($check == 2){
                            do{
                                $x = random_int(-5, 5);
                                $y = random_int(-10, -6) or random_int(6, 10);
                            } while ($x != $y && $x != $yold && $y != $xold);
                        }
                    }
                    if ($x == $xold or $y == $yold){
                        do{
                            $x = random_int(-5, 5);
                            $y = random_int(-10, -6) or random_int(6, 10);
                        } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
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
                    $x = random_int(-10, -6) or random_int(6, 10);
                    $y = random_int(-10, -6) or random_int(6, 10);
                    if ($x == $y){
                        $check ++;
                        if ($check == 2){
                            do{
                                $x = random_int(-10, -6) or random_int(6, 10);
                                $y = random_int(-10, -6) or random_int(6, 10);
                            } while ($x != $y && $x != $yold && $y != $xold);
                        }
                    }
                    if ($x == $xold or $y == $yold){
                        do{
                            $x = random_int(-10, -6) or random_int(6, 10);
                            $y = random_int(-10, -6) or random_int(6, 10);
                        } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
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
                    $x = random_int(-10, -6) or random_int(6, 10);
                    $y = random_int(-20, -11) or random_int(11, 20);
                    if ($x == $y){
                        $check ++;
                        if ($check == 2){
                            do{
                                $x = random_int(-10, -6) or random_int(6, 10);
                                $y = random_int(-20, -11) or random_int(11, 20);
                            } while ($x != $y && $x != $yold && $y != $xold);
                        }
                    }
                    if ($x == $xold or $y == $yold){
                        do{
                            $x = random_int(-10, -6) or random_int(6, 10);
                            $y = random_int(-20, -11) or random_int(11, 20);
                        } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
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
                    $x = random_int(2, 10) or random_int(-10,-2);
                    $y = random_int(101, 500) or random_int(-500,-101);
                    if ($x == $y){
                        $check ++;
                        if ($check == 2){
                            do{
                                $x = random_int(2, 10) or random_int(-10,-2);
                                $y = random_int(101, 500) or random_int(-500,-101);
                            } while ($x != $y && $x != $yold && $y != $xold);
                        }
                    }
                    if ($x == $xold or $y == $yold){
                        do{
                            $x = random_int(2, 10) or random_int(-10,-2);
                            $y = random_int(101, 500) or random_int(-500,-101);
                        } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
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
                    $x = random_int(11, 20) or random_int(-20,-11);
                    $y = random_int(21, 30) or random_int(-30,-21);
                    if ($x == $y){
                        $check ++;
                        if ($check == 2){
                            do{
                                $x = random_int(11, 20) or random_int(-20,-11);
                                $y = random_int(21, 30) or random_int(-30,-21);
                            } while ($x != $y && $x != $yold && $y != $xold);
                        }
                    }
                    if ($x == $xold or $y == $yold){
                        do{
                            $x = random_int(11, 20) or random_int(-20,-11);
                            $y = random_int(21, 30) or random_int(-30,-21);
                        } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
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
                    $x = random_int(11, 30) or random_int(-30,-11);
                    $y = random_int(31, 100) or random_int(-100,-31);
                    if ($x == $y){
                        $check ++;
                        if ($check == 2){
                            do{
                                $x = random_int(11, 30) or random_int(-30,-11);
                                $y = random_int(31, 100) or random_int(-100,-31);
                            } while ($x != $y && $x != $yold && $y != $xold);
                        }
                    }
                    if ($x == $xold or $y == $yold){
                        do{
                            $x = random_int(11, 30) or random_int(-30,-11);
                            $y = random_int(31, 100) or random_int(-100,-31);
                        } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
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
                    $x = random_int(31, 100) or random_int(-100,-31);
                    $y = random_int(31, 100) or random_int(-100,-31);
                    if ($x == $y){
                        $check ++;
                        if ($check == 2){
                            do{
                                $x = random_int(31, 100) or random_int(-100,-31);
                                $y = random_int(31, 100) or random_int(-100,-31);
                            } while ($x != $y && $x != $yold && $y != $xold);
                        }
                    }
                    if ($x == $xold or $y == $yold){
                        do{
                            $x = random_int(31, 100) or random_int(-100,-31);
                            $y = random_int(31, 100) or random_int(-100,-31);
                        } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
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
                    $x = random_int(101, 1000) or random_int(-1000,-101);
                    $y = random_int(101, 1000) or random_int(-1000,-101);
                    if ($x == $y){
                        $check ++;
                        if ($check == 2){
                            do{
                                $x = random_int(101, 1000) or random_int(-1000,-101);
                                $y = random_int(101, 1000) or random_int(-1000,-101);
                            } while ($x != $y && $x != $yold && $y != $xold);
                        }
                    }
                    if ($x == $xold or $y == $yold){
                        do{
                            $x = random_int(101, 1000) or random_int(-1000,-101);
                            $y = random_int(101, 1000) or random_int(-1000,-101);
                        } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
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
            //Ascending levels
            if ($level === 'all'){
                do {
                    again:
                    $x = random_int($add - 1, 1 + $add);
                    $y = random_int($add - 1, 1 + $add);
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
                        $x = random_int($add - 1, 1 + $add);
                        $y = random_int($add - 1, 1 + $add);
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
                        $x = random_int($add - 1, 1 + $add);
                        $y = random_int($add2 - 2, 2 + $add2);
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
                        $x = random_int($add - 2, 2 + $add);
                        $y = random_int($add2 - 100, 100 + $add2);
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
                        $x = random_int($add - 2, 2 + $add);
                        $y = random_int($add2 - 2, 2 + $add2);
                        $add2 += $max2 / 5;
                    }
                    if ($x == $y){
                        $kontroll ++;
                        if ($kontroll == 2){
                            goto again;
                        }
                    }
                    if ($x == $xold or $y == $yold){
                        goto again;
                    }
                    $xold = $x;
                    $yold = $y;
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
        //Specific levels - Integers
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
                        } while ($x != $y && $x != $yold && $y != $xold);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(1, 5);
                        $y = random_int(1, 5);
                    } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
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
                        } while ($x != $y && $x != $yold && $y != $xold);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(1, 5);
                        $y = random_int(6, 10);
                    } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
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
                        } while ($x != $y && $x != $yold && $y != $xold);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(6, 10);
                        $y = random_int(6, 10);
                    } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
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
                        } while ($x != $y && $x != $yold && $y != $xold);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(1, 10);
                        $y = random_int(11, 20);
                    } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
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
                        } while ($x != $y && $x != $yold && $y != $xold);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(2, 10);
                        $y = random_int(101, 500);
                    } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
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
                        } while ($x != $y && $x != $yold && $y != $xold);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(11, 20);
                        $y = random_int(21, 30);
                    } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
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
                        } while ($x != $y && $x != $yold && $y != $xold);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(11, 30);
                        $y = random_int(31, 100);
                    } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
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
                        } while ($x != $y && $x != $yold && $y != $xold);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(31, 100);
                        $y = random_int(31, 100);
                    } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
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
                        } while ($x != $y && $x != $yold && $y != $xold);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(101, 1000);
                        $y = random_int(101, 1000);
                    } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
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
                    $x = random_int($add, 1 + $add);
                    $y = random_int($add, 1 + $add);
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

        $defaultMaxLiit = ["1"=>10, "2"=>30, "3"=>100, "4"=>500, "5"=>1000];
        $defaultMaxKor = ["1"=>10, "2"=>20, "3"=>30, "4"=>100, "5"=>1000];

        $x = 0;
        $y = 0;
        $xold = 0;
        $yold = 0;
        $add = 1;
        $add2 = 1;
        $count = 0;
        $loendlünk = [];
        $max = $defaultMaxLiit[$tase];
        $max2 = $defaultMaxKor[$tase];

        //Ascending levels
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

    public function võrdlemine($level, $mis){
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
                        } while ($x != $y && $x != $yold && $y != $xold);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(0, 9);
                        $y = random_int(1, 10);
                    } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
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
                if ($proov2 == $proov1){
                    $random  = random_int(1, 2);
                    if ($random == 1){
                        //liitkorrutamine
                        array_push($array, ["operation1"=>$x . $Garl . $y, "operation2"=>$x1 . $Garl . $y1, "answer"=> "equal", "level"=>$level]);
                    } else {
                        //jagamise ja lahutamise variatsioonid
                        if ($võrd == 3 and $kaspar == 3){
                            $suvaline = random_int(1,2);
                            //lahutamine ja jagamine
                            if ($suvaline == 1) {
                                array_push($array, ["operation"=>$x + $y . $Garl . $y, "operation2"=>$x1 * $y1 . $Garl . $y1,"answer"=>"equal", "level"=>$level]);
                            } else {
                                array_push($array, ["operation"=>$x1 * $y1 . $Garl . $y1, "operation2"=>$x + $y . $Garl . $y,"answer"=>"equal", "level"=>$level]);
                            }
                        }
                        //jagamine ja liitkorrutamine
                        if ($võrd != 3 && $võrd != 2 && $kaspar == 4){
                            $suvaline = random_int(1,2);
                            if ($suvaline == 1) {
                                array_push($array, ["operation"=>$x . $Garl . $y, "operation2"=>$x1 * $y1 . $Garl . $y1,"answer"=>"equal", "level"=>$level]);
                            } else {
                                array_push($array, ["operation"=>$x1 * $y1 . $Garl . $y1, "operation2"=>$x . $Garl . $y,"answer"=>"equal", "level"=>$level]);
                            }
                        }
                        //lahutamine ja liitkorrutamine
                        if ($võrd == 3 && $kaspar != 3 && $kaspar != 4){
                            $suvaline = random_int(1,2);
                            if ($suvaline == 1) {
                                array_push($array, ["operation"=>$x + $y . $Garl . $y, "operation2"=>$x1 . $Garl . $y1,"answer"=>"equal", "level"=>$level]);
                            } else {
                                array_push($array, ["operation"=>$x1 . $Garl . $y1, "operation2"=>$x + $y . $Garl . $y,"answer"=>"equal", "level"=>$level]);
                            }
                        } else {
                            goto uuesti;
                        }
                        
                    }
                }
                $count ++;
            } while ($count <= 10);
            
        }
        if ($level === '2'){
            do{
                $x = random_int(10, 29);
                $y = random_int(11, 30);
                $xjag = random_int(2, 5);
                $yjag = random_int(6, 10);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(10, 29);
                            $y = random_int(10, 29);
                        } while ($x != $y && $x != $yold && $y != $xold);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(10, 29);
                        $y = random_int(10, 29);
                    } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
                }
                $xold = $x;
                $yold = $y;
                if ($xjag == $yjag){
                    $check ++;
                    if ($check == 2){
                        do{
                            $xjag = random_int(2, 5);
                            $yjag = random_int(6, 10);
                        } while ($xjag != $yjag);
                    }
                }
                if ($xjag == $xjagold or $yjag == $yjagold){
                    do{
                        $xjag = random_int(2, 5);
                        $yjag = random_int(6, 10);
                    } while ($xjag != $xjagold or $yjag != $yjagold);
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
                        $proov1 += $xjag * $yjag / $xjag;
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
                        $proov1 *= $xjag * $yjag / $xjag;
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
                        $proov1 += $xjag * $yjag / $xjag;
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
                    $proov2 += $xjag * $yjag;
                    $Garl = '·';
                }
                if ($random == 2 && $võrd != 2){
                    $proov2 += $xjag * $yjag / $yjag;
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
                if ($proov1 == $proov2){
                    $random  = random_int(1, 2);
                    if ($random == 1){
                        //liitkorrutamine
                        array_push($array, ["operation1"=>$x . $Garl . $y, "operation2"=>$x1 . $Garl . $y1, "answer"=> "equal", "level"=>$level]);
                    } else {
                        //jagamise ja lahutamise variatsioonid
                        if ($võrd == 3 and $kaspar == 3){
                            $suvaline = random_int(1,2);
                            //lahutamine ja jagamine
                            if ($suvaline == 1) {
                                array_push($array, ["operation"=>$x + $y . $Garl . $y, "operation2"=>$x1 * $y1 . $Garl . $y1,"answer"=>"equal", "level"=>$level]);
                            } else {
                                array_push($array, ["operation"=>$x1 * $y1 . $Garl . $y1, "operation2"=>$x + $y . $Garl . $y,"answer"=>"equal", "level"=>$level]);
                            }
                        }
                        //jagamine ja liitkorrutamine
                        if ($võrd != 3 && $võrd != 2 && $kaspar == 4){
                            $suvaline = random_int(1,2);
                            if ($suvaline == 1) {
                                array_push($array, ["operation"=>$x . $Garl . $y, "operation2"=>$x1 * $y1 . $Garl . $y1,"answer"=>"equal", "level"=>$level]);
                            } else {
                                array_push($array, ["operation"=>$x1 * $y1 . $Garl . $y1, "operation2"=>$x . $Garl . $y,"answer"=>"equal", "level"=>$level]);
                            }
                        }
                        //lahutamine ja liitkorrutamine
                        if ($võrd == 3 && $kaspar != 3 && $kaspar != 4){
                            $suvaline = random_int(1,2);
                            if ($suvaline == 1) {
                                array_push($array, ["operation"=>$x + $y . $Garl . $y, "operation2"=>$x1 . $Garl . $y1,"answer"=>"equal", "level"=>$level]);
                            } else {
                                array_push($array, ["operation"=>$x1 . $Garl . $y1, "operation2"=>$x + $y . $Garl . $y,"answer"=>"equal", "level"=>$level]);
                            }
                        } else {
                            //goto uuesti;
                        }
                    }
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
                            } while ($x != $y && $x != $yold && $y != $xold);
                        }
                    }
                    if ($x == $xold or $y == $yold){
                        do{
                            $x = random_int(20, 99);
                            $y = random_int(21, 100);
                        } while ($x != $xold && $y != $yold && $x == $y && $x != $yold && $y != $xold);
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
                } while ($count <= 10);
                
            }
        }  
    }

    public function wrapper($tehe, $tasemed, $tüüp){
        $loend = [];
        $koik = $tasemed == [1, 2, 3, 4, 5];
        if ($koik){

            // Funktsionaalseks (DRY)
            if($tehe == "liitmine" or $tehe == "lahutamine" or $tehe == "liitlahutamine"){

                if($tehe == "liitlahutamine"){
                    $tehe = "mõlemad";
                }


                $loend[0] = app('App\Http\Controllers\GameController')->liitlah('all', $tehe, $tüüp);
            }

            if($tehe == "korrutamine" or $tehe == "jagamine" or $tehe == "korrujagamine"){

                if($tehe == "korrujagamine"){
                    $tehe = "mõlemad";
                }

                $loend[0] = app('App\Http\Controllers\GameController')->korjag("all", $tehe, $tüüp);
            }

            if($tehe == "lünkamine"){
                $loend[0] = app('App\Http\Controllers\GameController')->lünkamine("all");
            }
        }

        for ($lugeja = 0; $lugeja < count($tasemed); $lugeja ++){
            if($tehe == "liitmine" or $tehe == "lahutamine" or $tehe == "liitlahutamine"){

                if($tehe == "liitlahutamine"){
                    $tehe = "mõlemad";
                }


                $loend[$tasemed[$lugeja]] = app('App\Http\Controllers\GameController')->liitlah($tasemed[$lugeja], $tehe, $tüüp);
            }

            if($tehe == "korrutamine" or $tehe == "jagamine" or $tehe == "korrujagamine"){

                if($tehe == "korrujagamine"){
                    $tehe = "mõlemad";
                }

                $loend[$tasemed[$lugeja]] = app('App\Http\Controllers\GameController')->korjag($tasemed[$lugeja], $tehe, $tüüp);
            }

            if($tehe == "lünkamine"){
                $loend[$tasemed[$lugeja]] = app('App\Http\Controllers\GameController')->lünkamine($tasemed[$lugeja]);
            }

        }

        return $loend;
    }
}