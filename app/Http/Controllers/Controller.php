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

    // KONSTANDID:
    const SAME_NUMBER_REPEAT_COUNT = 1;
    const OPERATION_COUNT = 25;

    const BOTH = "mõlemad";
    const LIITMINE = "liitmine";
    const LAHUTAMINE = "lahutamine";
    const KORRUTAMINE = "korrutamine";
    const JAGAMINE = "jagamine";
    const ASTENDAMINE = "astendamine";
    const JUURIMINE = "juurimine";
    const ASTEJUURIMINE = "astejuurimine";
    const JAGUVUS = "jaguvus";
    const LIHTSUSTAMINE = "lihtsustamine";
    //....

    function gcd ($a, $b) {
        return $b ? GameController::gcd($b, $a % $b) : $a;
    }

    //Op1 = liitmine, korrutamine
    //Op2 = lahutamine, jagamine
    function generateOp($xf, $yf, $mis, $ans, $opnames, $opsymbs, $level, $aeg=1){

        $array = [];
        $check = 0;

        $xold = 0;
        $yold = 0;

        $count = 0;
        
        do{
            $x = $xf();
            $y = $yf();

            if ($x == $y && $mis == GameController::ASTENDAMINE || GameController::JUURIMINE){
                $check ++;

                if ($check > GameController::SAME_NUMBER_REPEAT_COUNT){
                    do{
                        $x = $xf();
                        $y = $yf();
                    } while ($x == $y);
                }
            }

            if ($x == $xold or $y == $yold && $mis == GameController::ASTENDAMINE || GameController::JUURIMINE){
                do{
                    $x = $xf();
                    $y = $yf();
                } while(($x == $xold && $y == $yold) || $x == $y || ($x == $yold && $y == $xold));
            }

            $xold = $x;
            $yold = $y;

            $uusmis = $mis;

            if ($uusmis === GameController::BOTH){
                $uusmis = $opnames[array_rand($opnames)];
            }

            // Liitmine v korrutamine
            if (in_array($uusmis, [GameController::LIITMINE, GameController::KORRUTAMINE])){
                array_push($array, ["operation"=>$x . $opsymbs[0] . ($y < 0 ? "(" . $y . ")" : $y), "answer"=>$ans($x, $y, $uusmis), "level"=>$level]);
            }

            // Lahutamine v jagamine
            if (in_array($uusmis, [GameController::LAHUTAMINE, GameController::JAGAMINE])){
                array_push($array, ["operation"=> ($uusmis == GameController::LAHUTAMINE ? ($x + $y) : ($x * $y)) . $opsymbs[1] . ($y < 0 ? "(" . $y . ")" : $y), "answer"=>$ans($x, $y, $uusmis), "level"=>$level]);
            }

            //Astendamine v juurimine
            if (in_array($uusmis, [GameController::ASTENDAMINE, GameController::JUURIMINE])){
                array_push($array, ["operation"=> ($uusmis == GameController::ASTENDAMINE ? ($x . "EXP" . $y) : ($x**$y . "RAD" . $y) ), "answer"=>$ans($x, $y, $uusmis), "level"=>$level]);
            }

            //Jaguvus
            if ($uusmis === GameController::JAGUVUS){
                $jagub = $ans($x, $y, $uusmis);
                array_push($array, ["operation"=> ($jagub ? (($x * $y) . " ⋮ " . $y) : (($x*$y + ($y - random_int(1, $y - 1)))." ⋮ ".$y)), "answer"=>$jagub, "level"=>$level]);
            }

            //Lihtustamine
            if ($uusmis == GameController::LIHTSUSTAMINE){
                $z = $opnames();
                while ($z * $y == 1 || $x * $z == 1){
                    $z = $opnames();
                }
                array_push($array, ["operation"=> "LIHT(" . ($x * $z) . "/" .($y * $z) . ")" , "answer"=>$ans($x * $z, $y * $z), "level"=>$level]);
            }

            $count ++;
        } while ($count < (GameController::OPERATION_COUNT + (14 * $aeg)));

        return ["array"=>$array];
    }


    // Esialgu 1206-realine funktsioon
    // Vaatame, kui palju vähemaks võtta annab
    // Üritame kirjutada võimalikult DRY koodi

    //Addition and Substraction
    public function liitlah($level, $mis, $tüüp, $aeg){
        $array = [];
        $x = 0;
        $y = 0;
        $tase = 1;
        $count = 0;
        $max = 10;
        $add = 0;
        $add2 = 0;
        $xold = 0;
        $yold = 0;
        $kontroll = 0;


        $opnames = [GameController::LIITMINE, GameController::LAHUTAMINE];
        $opsymbs = ["+", "-"];

        $xvalues = [
            "1"=>[
                "natural"=>function (){return random_int(1, 5);},
                "fraction"=>function (){return random_int(1, 5) + 0.5;},
                "integer"=>function (){return random_int(-5, 5);},
            ],
            "2"=>[
                "natural"=>function (){return random_int(6, 10);},
                "fraction"=>function (){return random_int(6, 10) + 0.5;},
                "integer"=>function (){
                    $randints = [random_int(-10, -6), random_int(6, 10)];
                    return $randints[array_rand($randints)];
                },
            ],
            "3"=>[
                "natural"=>function (){return random_int(11, 30);},
                "fraction"=>function (){return random_int(11, 30) + 0.5;},
                "integer"=>function (){
                    $randints = [random_int(-30, -11), random_int(11, 30)];
                    return $randints[array_rand($randints)];},
            ],
            "4"=>[
                "natural"=>function (){return random_int(31, 100);},
                "fraction"=>function (){return random_int(31, 100) + random_int(1, 9)/10;},
                "integer"=>function (){ 
                    $randints = [random_int(-100, -31), random_int(31, 100)];
                    return $randints[array_rand($randints)];},
            ],
            "5"=>[
                "natural"=>function (){return random_int(101, 500);},
                "fraction"=>function (){return random_int(101, 500) + random_int(1, 99)/100;},
                "integer"=>function (){ 
                    $randints = [random_int(-500, -101), random_int(101, 500)];
                    return $randints[array_rand($randints)];},
            ],
            "A"=>[
                "natural"=>function (){return random_int(501, 1000);},
                "fraction"=>function (){return random_int(501, 1000) + random_int(1, 99)/100;},
                "integer"=>function (){
                    $randints = [random_int(-1000, -501), random_int(501, 1000)];
                    return $randints[array_rand($randints)];},
            ],
            "B"=>[
                "natural"=>function (){return random_int(1000, 10000);},
                "fraction"=>function (){return random_int(1000, 10000) + random_int(1, 99)/100;},
                "integer"=>function (){ 
                    $randints = [random_int(-10000, -1001), random_int(1001, 10000)];
                    return $randints[array_rand($randints)];},
            ],
            "C"=>[
                "natural"=>function (){return random_int(10000, 100000);},
                "fraction"=>function (){return random_int(10000, 100000) + random_int(1, 999)/1000;},
                "integer"=>function (){
                    $randints = [random_int(-100000, -10001), random_int(10001, 100000)];
                    return $randints[array_rand($randints)];},
            ],
            
        ];

        $yvalues = [
            "1"=>[
                "natural"=>function (){return random_int(1, 5);},
                "fraction"=>function (){return random_int(1, 5);},
                "integer"=>function (){return random_int(-5, 5);},
            ],
            "2"=>[
                "natural"=>function (){return random_int(6, 10);},
                "fraction"=>function (){return random_int(6, 10) + 0.5;},
                "integer"=>function (){return (random_int(0, 1) == 1 ? -1 : 1) * random_int(6, 10);},
            ],
            "3"=>[
                "natural"=>function (){return random_int(11, 30);},
                "fraction"=>function (){return random_int(11, 30) + random_int(1, 9)/10;},
                "integer"=>function (){return (random_int(0, 1) == 1 ? -1 : 1) * random_int(11, 30);},
            ],
            "4"=>[
                "natural"=>function (){return random_int(31, 100);},
                "fraction"=>function (){return random_int(31, 100) + random_int(1, 9)/10;},
                "integer"=>function (){return (random_int(0, 1) == 1 ? -1 : 1) * random_int(31, 100);},
            ],
            "5"=>[
                "natural"=>function (){return random_int(101, 500);},
                "fraction"=>function (){return random_int(101, 500) + random_int(1, 99)/100;},
                "integer"=>function (){return (random_int(0, 1) == 1 ? -1 : 1) * random_int(101, 500);},
            ],
            "A"=>[
                "natural"=>function (){return random_int(501, 1000);},
                "fraction"=>function (){return random_int(501, 1000) + random_int(1, 99)/100;},
                "integer"=>function (){return (random_int(0, 1) == 1 ? -1 : 1) * random_int(501, 1000);},
            ],
            "B"=>[
                "natural"=>function (){return random_int(1000, 10000);},
                "fraction"=>function (){return random_int(1000, 10000) + random_int(1, 99)/100;},
                "integer"=>function (){return (random_int(0, 1) == 1 ? -1 : 1) * random_int(1000, 10000);},
            ],
            "C"=>[
                "natural"=>function (){return random_int(10000, 100000);},
                "fraction"=>function (){return random_int(10000, 100000) + random_int(1, 999)/1000;},
                "integer"=>function (){return (random_int(0, 1) == 1 ? -1 : 1) * random_int(10000, 100000);},
            ],
        ];


        //Specific levels

        if($level != "all"){
            $returnData = GameController::generateOp($xvalues[$level][$tüüp], $yvalues[$level][$tüüp], $mis, function ($num1, $num2, $mis){
                return $mis == GameController::LIITMINE ? $num1 + $num2 : $num1;
             }, $opnames, $opsymbs, $level, $aeg);

             return $returnData["array"];
        }

        
    
        //Ascending levels -- Fraction
        if ($level === 'all' && $tüüp == "fraction"){
            do {
                again2:
                $x = random_int($add, 1 + $add) + 0.5;
                $y = random_int($add, 1 + $add);
                if ($count > 5){
                    $x = random_int($add, 1 + $add) + 0.5;
                    $y = random_int($add, 1 + $add) + random_int(1, 9)/10;
                    $tase = 2;
                }
                if ($count > 10){
                    $tase = 3;
                    $max = 30;
                    $x = random_int($add, 4 + $add) + random_int(1, 9)/10;
                    $y = random_int($add, 4 + $add) + random_int(1, 9)/10;
                }
                if ($count > 15){
                    $tase = 4;
                    $max = 100;
                    $x = random_int($add, 14 + $add) + random_int(1, 99)/100;
                    $y = random_int($add, 14 + $add) + random_int(1, 99)/100;
                }
                if ($count > 20){
                    $tase = 5;
                    $max = 500;
                    $x = random_int($add, 80 + $add) + random_int(1, 99)/100;
                    $y = random_int($add, 80 + $add) + random_int(1, 99)/100;
                }
                if ($x == $y){
                    $kontroll ++;
                    if ($kontroll > GameController::SAME_NUMBER_REPEAT_COUNT){
                        goto again2;
                    }
                }
                if (($x == $xold && $y == $yold) || $x == $y || ($x == $yold && $y == $xold)){
                    // Kas see töötab?
                    goto again2;
                }
                $xold = $x;
                $yold = $y;

                $uusmis = $mis == GameController::BOTH ? (random_int(1, 2) == 1 ?  GameController::LIITMINE : GameController::LAHUTAMINE) : $mis;

                if ($uusmis === GameController::LIITMINE){
                    array_push($array, ["operation"=>$x. '+' . $y, "answer"=>$x + $y, "level"=>$tase]);
                }

                if ($uusmis === GameController::LAHUTAMINE){
                    array_push($array, ["operation"=>$x + $y. '-' . $y, "answer"=>$x, "level"=>$tase]);
                }

                $add += $max/5;
                $count ++;
            }while ($count < GameController::OPERATION_COUNT + ($aeg * 14));

            return $array;
        }



        //Ascending levels -- Integer
        if ($level === 'all' && $tüüp === 'integer'){
            do {
                again3:
                $jarl = [random_int($add2 - 1, $add2 + 1), random_int($add -1 ,$add + 1)];
                $x = $jarl[array_rand($jarl)];
                $y = $jarl[array_rand($jarl)];
                $tase = 1;
                if ($count > 5){
                    $tase = 2;
                }
                if ($count > 10){ 
                    $tase = 3;
                    $max = 30;
                    $jarl = [random_int($add2 - 4, $add2 + 4), random_int($add - 4, $add + 4)];
                    $x = $jarl[array_rand($jarl)];
                    $y = $jarl[array_rand($jarl)];
                }
                if ($count > 15){ 
                    $tase = 4;
                    $max = 100;
                    $jarl = [random_int($add2 - 14, $add2 + 14), random_int($add - 14,$add + 14)];
                    $x = $jarl[array_rand($jarl)];
                    $y = $jarl[array_rand($jarl)];
                }
                if ($count > 20){ 
                    $tase = 5;
                    $max = 500;
                    $jarl = [random_int($add2 - 80, $add2 + 30), random_int($add - 30,$add + 80)];
                    $x = $jarl[array_rand($jarl)];
                    $y = $jarl[array_rand($jarl)];
                }
                if ($x == $y){
                    $kontroll ++;
                    if ($kontroll > GameController::SAME_NUMBER_REPEAT_COUNT){
                        goto again3;
                    }
                }


                if (($x == $xold && $y == $yold) || $x == $y || ($x == $yold && $y == $xold)){
                    goto again3;
                }


                $xold = $x;
                $yold = $y;

                $uusmis = $mis;

                if($mis == GameController::BOTH){
                    $uusmis = $opnames[array_rand($opnames)];
                }
                
                if ($uusmis === GameController::LIITMINE){
                    if ($y < 0){
                        $y = -$y;
                        array_push($array, ["operation"=>$x. '-' . $y, "answer"=>$x - $y, "level"=>$tase]);
                    } else {
                    array_push($array, ["operation"=>$x. '+' . $y, "answer"=>$x + $y, "level"=>$tase]);
                    }
                }
                if ($uusmis === GameController::LAHUTAMINE){
                    if ($y < 0){
                        $y = -$y;
                        array_push($array, ["operation"=>$x + $y. '-' . $y, "answer"=>$x, "level"=>$tase]);
                    } else{
                    array_push($array, ["operation"=>$x + $y. '-' . $y, "answer"=>$x, "level"=>$tase]);
                    }
                }

                $add += $max / 5;
                $add2 -= $max / 5;
                $count ++;
            
            } while ($count < GameController::OPERATION_COUNT + ($aeg * 7));

            return $array;
        }


        // Ascending levels -- Natural
        
        if ($level === "all" && $tüüp ==='natural'){
            do{
                again4:
                $x = random_int($add, 1 + $add);
                $y = random_int($add, 1 + $add);

                $tase = 1;
                if ($count > 5){
                    $tase = 2;
                }
                if ($count > 10){
                    $tase = 3;
                    $max = 30;
                    $x = random_int($add, 4 + $add);
                    $y = random_int($add, 4 + $add);
                }
                if ($count > 15){
                    $tase = 4;
                    $max = 100;
                    $x = random_int($add, 14 + $add);
                    $y = random_int($add, 14 + $add);
                }
                if ($count > 20){
                    $tase = 5;
                    $max = 500;
                    $x = random_int($add, 80 + $add);
                    $y = random_int($add, 80 + $add);
                }
                if ($x == $y){
                    $kontroll ++;
                    if ($kontroll > GameController::SAME_NUMBER_REPEAT_COUNT){
                        goto again4;
                    }
                }


                if (($x == $xold && $y == $yold) || $x == $y || ($x == $yold && $y == $xold)){
                    goto again4;
                }

                $xold = $x;
                $yold = $y;

                $uusmis = $mis == GameController::BOTH ? (random_int(1, 2) == 1 ?  GameController::LIITMINE : GameController::LAHUTAMINE) : $mis;

                if ($uusmis === GameController::LIITMINE){
                    array_push($array, ["operation"=>$x. '+' . $y, "answer"=>$x + $y, "level"=>$tase]);
                }
                if ($uusmis === GameController::LAHUTAMINE){
                    array_push($array, ["operation"=>$x + $y. '-' . $y, "answer"=>$x, "level"=>$tase]);
                }

                $add += $max/5;
                $count ++;
            }while ($count < GameController::OPERATION_COUNT + ($aeg * 7));

            return $array;
        }            
    }




    public function korjag($level, $mis, $tüüp, $aeg){
        $array = [];
        $x = 0;
        $y = 0;
        $tase = 1;
        $count = 0;
        $min = 0;
        $max = 0;
        $xmax = 0;
        $xmax2 = 0;
        $ymax = 0;
        $ymax2 = 0;
        $add = 0;
        $add2 = 0;
        $xadd = 0;
        $yadd = 0;
        $xadd2 = 0;
        $yadd2 = 0;
        $xold = 0;
        $yold = 0;
        $check = 0;
        $kontroll = 0;


        $opnames = [GameController::KORRUTAMINE, GameController::JAGAMINE];
        $opsymbs= ["·", ":"];


        $xvalues = [
            "1"=>[
                "natural"=>function (){return random_int(1, 5);},
                "fraction"=>function (){return random_int(1, 5)/10;},
                "integer"=>function (){return random_int(-5, 5);},
            ],
            "2"=>[
                "natural"=>function (){return random_int(1, 5);},
                "fraction"=>function (){return random_int(6, 9);},
                "integer"=>function (){return random_int(-5, 5);},
            ],
            "3"=>[
                "natural"=>function (){return random_int(2, 10);},
                "fraction"=>function (){return random_int(1, 5) + 0.5;},
                "integer"=>function (){
                    $randints = [random_int(-10, -2), random_int(2, 10)];
                    return $randints[array_rand($randints)];},
            ],
            "4"=>[
                "natural"=>function (){return random_int(2, 10);},
                "fraction"=>function (){return random_int(2, 10) + random_int(1, 9)/10;},
                "integer"=>function (){
                    $randints = [random_int(-10, -2), random_int(2, 10)];
                    return $randints[array_rand($randints)];},
            ],
            "5"=>[
                "natural"=>function (){return random_int(2, 10);},
                "fraction"=>function (){return random_int(20, 30) + random_int(1, 9)/10;},
                "integer"=>function (){
                    $randints = [random_int(-20, -11), random_int(11, 20)];
                    return $randints[array_rand($randints)];},
            ],
            "6"=>[
                "natural"=>function (){return random_int(11, 20);},
                "fraction"=>function (){return random_int(31, 100) + random_int(1, 9)/10;},
                "integer"=>function (){
                    $randints = [random_int(-20, -11), random_int(11, 20)];
                    return $randints[array_rand($randints)];},
            ],
            "A"=>[
                "natural"=>function (){return random_int(21, 30);},
                "fraction"=>function (){return random_int(21, 30) + random_int(1, 99)/100;},
                "integer"=>function (){
                    $randints = [random_int(-30, -21), random_int(21, 30)];
                    return $randints[array_rand($randints)];},
            ],
            "B"=>[
                "natural"=>function (){return random_int(31, 100);},
                "fraction"=>function (){return random_int(31, 100) + random_int(1, 999)/1000;},
                "integer"=>function (){
                    $randints = [random_int(-100, -31), random_int(31, 100)];
                    return $randints[array_rand($randints)];},
            ],
            "C"=>[
                "natural"=>function (){return random_int(101, 1000);},
                "fraction"=>function (){return random_int(101, 1000) + random_int(1, 999)/1000;},
                "integer"=>function (){
                    $randints = [random_int(-1000, -101), random_int(101, 1000)];
                    return $randints[array_rand($randints)];},
            ],
            
        ];

        $yvalues = [
            "1"=>[
                "natural"=>function (){return random_int(1, 5);},
                "fraction"=>function (){return random_int(1, 5)/10;},
                "integer"=>function (){return random_int(-5, 5);},
            ],
            "2"=>[
                "natural"=>function (){return random_int(6, 10);},
                "fraction"=>function (){return random_int(6, 9)/10;},
                "integer"=>function (){
                    $randints = [random_int(-10, -6), random_int(6, 10)];
                    return $randints[array_rand($randints)];
                },
            ],
            "3"=>[
                "natural"=>function (){return random_int(6, 10);},
                "fraction"=>function (){return random_int(6, 10);},
                "integer"=>function (){
                    $randints = [random_int(-10, -6), random_int(6, 10)];
                    return $randints[array_rand($randints)];},
            ],
            "4"=>[
                "natural"=>function (){return random_int(11, 20);},
                "fraction"=>function (){return random_int(2, 10);},
                "integer"=>function (){
                    $randints = [random_int(-20, -11), random_int(11, 20)];
                    return $randints[array_rand($randints)];},
            ],
            "5"=>[
                "natural"=>function (){return random_int(101, 500);},
                "fraction"=>function (){return random_int(10, 20) + random_int(1, 9)/10;},
                "integer"=>function (){
                    $randints = [random_int(-500, -101), random_int(101, 500)];
                    return $randints[array_rand($randints)];},
            ],
            "6"=>[
                "natural"=>function (){return random_int(21, 30);},
                "fraction"=>function (){return random_int(21, 30) + random_int(1, 9)/10;},
                "integer"=>function (){
                    $randints = [random_int(-30, -21), random_int(21, 30)];
                    return $randints[array_rand($randints)];},
            ],
            "A"=>[
                "natural"=>function (){return random_int(21, 30);},
                "fraction"=>function (){return random_int(21, 30) + random_int(1, 9)/10;},
                "integer"=>function (){
                    $randints = [random_int(-30, -21), random_int(21, 30)];
                    return $randints[array_rand($randints)];},
            ],
            "B"=>[
                "natural"=>function (){return random_int(31, 100);},
                "fraction"=>function (){return random_int(31, 100) + random_int(1, 9)/10;},
                "integer"=>function (){
                    $randints = [random_int(-100, -31), random_int(31, 100)];
                    return $randints[array_rand($randints)];},
            ],
            "C"=>[
                "natural"=>function (){return random_int(31, 100);},
                "fraction"=>function (){return random_int(31, 100) + random_int(1, 99)/100;},
                "integer"=>function (){
                    $randints = [random_int(-100, -31), random_int(31, 100)];
                    return $randints[array_rand($randints)];},
            ],
        ];

        if($level != "all"){
            $returnData = GameController::generateOp($xvalues[$level][$tüüp], $yvalues[$level][$tüüp], $mis, function ($num1, $num2, $mis){
                return $mis == GameController::KORRUTAMINE ? $num1 * $num2 : $num1;
             }, $opnames, $opsymbs, $level, $aeg);

             return $returnData["array"];
        }
        
        //Ascending levels -- Fraction
        if ($level === 'all' && $tüüp === 'fraction'){
            do {
                again1:
                $x = random_int($add, 1 + $add) + 0.5;
                $y = random_int($add, 1 + $add);
                $max = 3;
                if ($count > 5){
                    $x = random_int($add, 1 + $add) + 0.5;
                    $y = random_int($add, 1 + $add);
                    $max = 5;
                    $tase = 2;
                }
                if ($count > 10){ 
                    if ($check != 1){
                        $add = 6;
                        $check = 1;
                    };
                    $tase = 3;
                    $max = 10;
                    $x = random_int($add, 1 + $add) + random_int(1, 9)/10;
                    $y = random_int($add, 1 + $add);
                }
                if ($count > 15){ 
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
                if ($count > 20){ 
                    if ($check != 1){
                        $add = 2;
                        $add2 = 101;
                        $check = 1;
                    };
                    $tase = 5;
                    $max = 10;
                    $max2 = 500;
                    $x = random_int($add, 2 + $add) + random_int(1, 9)/10;
                    $y = random_int($add2, 100 + $add2) + random_int(1, 9)/10;
                    $add2 += $max2 / 5;
                }
                if ($count > 25){ 
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
                    if ($kontroll > GameController::SAME_NUMBER_REPEAT_COUNT){
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
            
            } while ($count < GameController::OPERATION_COUNT + ($aeg * 14));
            return $array;
        }
            
        
        //Ascending levels -- Integer
        if ($level === 'all' && $tüüp === 'integer'){
            do {
                again:
                $xjarl = [random_int($xadd2 - 2, $xadd2 + 2), random_int($xadd - 2, $xadd + 2)];
                $yjarl = [random_int($yadd2 - 2, $yadd2 + 2), random_int($yadd - 2, $yadd + 2)];
                $x = $xjarl[array_rand($xjarl)];
                $y = $yjarl[array_rand($yjarl)];
                if ($count > 5){
                    if ($check != 1){
                        $xadd = 1;
                        $yadd = 6;
                        $check = 1;
                    };
                    $ymax = 10;
                    $xmax = 5;
                    $xjarl = [random_int($xadd2 - 2, $xadd2 + 1), random_int($xadd - 1, $xadd + 2)];
                    $yjarl = [random_int($yadd2 - 2, $yadd2 + 1), random_int($yadd - 1, $yadd + 2)];
                    $x = $xjarl[array_rand($xjarl)];
                    $tase = 2;
                }
                if ($count > 10){ 
                    if ($check != 1){
                        $xadd = $yadd = 6;
                        $check = 1;
                    };
                    $tase = 3;
                    $xmax = $ymax = 10;
                    $xjarl = [random_int($xadd2 - 2, $xadd2 + 2), random_int($xadd - 2,$xadd + 2)];
                    $x = $xjarl[array_rand($xjarl)];
                    $y = $xjarl[array_rand($xjarl)];
                }
                if ($count > 15){ 
                    if ($check != 1){
                        $yadd = 11;
                        $xadd = 2;
                        $check = 1;
                    };
                    $tase = 4;
                    $xmax = 10;
                    $ymax = 20;
                    $yjarl = [random_int($yadd2 - 2, $yadd2 + 2), random_int($yadd - 2,$yadd + 2)];
                    $xjarl = [random_int($xadd2 - 2, $xadd2 + 2), random_int($xadd - 2,$xadd + 2)];
                    $x = $xjarl[array_rand($xjarl)];
                    $y = $yjarl[array_rand($yjarl)];
                    

                }
                if ($count > 20){ 
                    if ($check != 1){
                        $xadd = 2;
                        $yadd = 101;
                        $check = 1;
                    };
                    $tase = 5;
                    $xmax = 10;
                    $ymax = 500;
                    
                    $xjarl = [random_int($xadd2 - 2, $xadd2 + 2), random_int($xadd - 2,$xadd + 2)];
                    $yjarl = [random_int($yadd2 - 2, $yadd2 + 2), random_int($yadd - 2,$yadd + 2)];
                    $x = $xjarl[array_rand($xjarl)];
                    $y = $yjarl[array_rand($yjarl)];
                    
                }
                if ($count > 25){ 
                    if ($check != 1){
                        $xadd = 11;
                        $yadd = 21;
                        $check = 1;
                    };
                    $tase = 6;
                    $xmax = 20;
                    $ymax = 500;
                    
                    $xjarl = [random_int($xadd2 - 2, $xadd2 + 2), random_int($xadd - 2,$xadd + 2)];
                    $yjarl = [random_int($yadd2 - 2, $yadd2 + 2), random_int($yadd - 2,$yadd + 2)];
                    $x = $xjarl[array_rand($xjarl)];
                    $y = $yjarl[array_rand($yjarl)];
                    
                }
                if ($x == $y){
                    $kontroll ++;
                    if ($kontroll > GameController::SAME_NUMBER_REPEAT_COUNT){
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
                
                $xadd += $xmax / 5;
                $xadd2 = -$xadd;
                $yadd += $ymax / 5;
                $yadd2 = -$yadd;

                $count ++;
            
            } while ($count < 25 + ($aeg * 14));
            return $array;
        }
            
        

        
        //Ascending levels -- Natural
        if ($level === 'all' && $tüüp === 'natural'){
            do {
                again6:
                $x = random_int($add, 1 + $add);
                $y = random_int($add, 1 + $add);
                if ($count > 5){
                    $x = random_int($add - 5, $add - 4);
                    $tase = 2;
                }
                if ($count > 10){ 
                    if ($check != 1){
                        $add = 6;
                        $check = 1;
                    };
                    $tase = 3;
                    $max = 10;
                    $x = random_int($add, 1 + $add);
                    $y = random_int($add, 1 + $add);
                }
                if ($count > 15){ 
                    if ($check != 1){
                        $add = 2;
                        $add2 = 11;
                        $check = 1;
                    };
                    $tase = 4;
                    $max = 10;
                    $max2 = 20;
                    $x = random_int($add, 2 + $add);
                    $y = random_int($add2, 2 + $add2);

                }
                if ($count > 20){ 
                    if ($check != 1){
                        $add = 2;
                        $add2 = 101;
                        $check = 1;
                    };
                    $tase = 5;
                    $max = 10;
                    $max2 = 500;
                    $x = random_int($add, 2 + $add);
                    $y = random_int($add2, 80 + $add2);
                  
                }
                if ($count > 25){ 
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
                    
                }
                if ($x == $y){
                    $kontroll ++;
                    if ($kontroll > GameController::SAME_NUMBER_REPEAT_COUNT){
                        goto again6;
                    }
                }
                if ($x == $xold or $y == $yold){
                    goto again6;
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
                $add2 += $max2 / 5;

                $count ++;
            
            } while ($count < 25 + ($aeg * 14));


            return $array;
        }
        
    }

    //Astenamine - Exponentiation
    public function astendamine($level, $mis, $tüüp, $aeg,){
        $array = [];
        $x = 0;
        $y = 0;
        $tase = 1;
        $count = 0;
        $max = 10;
        $add = 1;
        $xadd = 1;
        $xadd2 = -$xadd;
        $xold = 0;
        $yold = 0;
        $check = 0;
        $xvalues = [];


        $opnames = [GameController::ASTENDAMINE, GameController::JUURIMINE];
        $xvalues = [
            "1"=>[
                "natural"=>function (){return random_int(1, 5);},
                "integer"=>function (){
                    $randints = [random_int(-5, -1), random_int(1, 5)];
                    return $randints[array_rand($randints)];
                },
            ],
            "2"=>[
                "natural"=>function (){return random_int(6, 10);},
                "integer"=>function (){
                    $randints = [random_int(-10, -6), random_int(6, 10)];
                    return $randints[array_rand($randints)];
                },
            ],
            "3"=>[
                "natural"=>function (){return random_int(2, 5);},
                "integer"=>function (){
                    $randints = [random_int(-5, -2), random_int(2, 5)];
                    return $randints[array_rand($randints)];
                },
            ],
            "4"=>[
                "natural"=>function (){return random_int(11, 20);},
                "integer"=>function (){
                    $randints = [random_int(-20, -11), random_int(11, 20)];
                    return $randints[array_rand($randints)];},
            ],
            "5"=>[
                "natural"=>function (){return random_int(6, 10);},
                "integer"=>function (){
                    $randints = [random_int(-10, -6), random_int(6, 10)];
                    return $randints[array_rand($randints)];},
            ],
        ];
        
        $yvalues = [
            "1"=>[
                "natural"=>function (){return random_int(1, 2);},
                "integer"=>function (){return random_int($xvalues[$level][$tüüp] < 3 ? -10 : -2, -2) or random_int(2, $xvalues[$level][$tüüp] < 3 ? 10 : 2);},
            ],
            "2"=>[
                "natural"=>function (){return 2;},
                "integer"=>function (){return -2 or 2;},
            ],
            "3"=>[
                "natural"=>function (){return random_int(3,4);},
                "integer"=>function (){
                    $randints = [random_int($xvalues[$level][$tüüp] < 4 ? -5 : -4, -3), random_int(3, $xvalues[$level][$tüüp] < 4 ? 5 : 4)];
                    return $randints[array_rand($randints)];},
            ],
            "4"=>[
                "natural"=>function (){return 2;},
                "integer"=>function (){return -2 or 2;},
            ],
            "5"=>[
                "natural"=>function (){return 3;},
                "integer"=>function (){return -3 or 3;},
            ],
        ];


        if($level != "all"){
            $returnData = GameController::generateOp($xvalues[$level][$tüüp], $yvalues[$level][$tüüp], $mis, function ($num1, $num2, $mis){
                return $mis == GameController::ASTENDAMINE ? $num1 ** $num2 : $num1;
             }, $opnames, [],  $level, $aeg);

             return $returnData["array"];
        }

        //Ascending levels -- Natural
        if ($level === 'all' && $tüüp === 'natural'){
            do {
                again7:
                if ($check != 1){
                    $max = 5;
                    $check = 1;
                };
                $x = random_int($add, 1 + $add);
                $y = random_int(1, 2);
                if ($count > 10){
                    if ($check != 1){
                        $max = 10;
                        $check = 1;
                    };
                    $tase = 2;
                    $y = 2;
                }
                if ($count > 20){ 
                    if ($check != 1){
                        $add = 1;
                        $max = 5;
                        $check = 1;
                    };
                    $tase = 3;
                    $x = random_int($add, 1 + $add);
                    $y = ($x < 4) ? 6 : 3;
                }
                if ($count > 30){ 
                    if ($check != 1){
                        $add = 11;
                        $max = 20;
                        $check = 1;
                    };
                    $tase = 4;
                    $x = random_int($add, 1 + $add);
                    $y = 2;

                }
                if ($count > 40){ 
                    if ($check != 1){
                        $add = 6;
                        $max = 10;
                        $check = 1;
                    };
                    $tase = 5;
                    $x = random_int($add, 1 + $add);
                    $y = 3;
                }

                $uusmis = $mis === 'mõlemad' ? (random_int(1, 2) == 1 ? GameController::ASTENDAMINE : GameController::JUURIMINE) : $mis;
                
                if ($uusmis === GameController::ASTENDAMINE) {
                    array_push($array, ["operation"=>$x . 'EXP' . $y, "answer"=>$x ** $y, "level"=>$tase]);
                }

                if ($uusmis === GameController::JUURIMINE) {
                    array_push($array, ["operation"=>$x ** $y . 'RAD' . $y, "answer"=>$x, "level"=>$tase]);
                }
                
                $add += $max / 5;
                $count ++;
            
            } while ($count < 50 + ($aeg * 7));

            return $array; 
        }

        //Ascending levels - Integer
        if ($level === 'all' && $tüüp === 'integer'){
            do {
                again8:
                if ($check != 1){
                    $xadd = 1;
                    $xmax = 5;
                    $check = 1;
                };
                $xadd2 = -$xadd;
                $xjarl = [random_int($xadd2 - 1, $xadd2 + 1), random_int($xadd - 1, $xadd + 1)];
                $x = $xjarl[array_rand($xjarl)];
                $y = random_int(-2,-1) || random_int(1,2);
                if ($count > 10){
                    if ($check != 1){
                        $xadd = 2;
                        $xmax = 10;
                        $check = 1;
                    };
                    $xadd2 = -$xadd;
                    $xjarl = [random_int($xadd2 - 1, $xadd2 + 1), random_int($xadd - 1, $xadd + 1)];
                    $x = $xjarl[array_rand($xjarl)];
                    $y = -2 || 2;
                    $tase = 2;
                }
                if ($count > 20){ 
                    if ($check != 1){
                        $xadd = 2;
                        $xmax = 5;
                        $check = 1;
                    };
                    $xadd2 = -$xadd;
                    $tase = 3;
                    $xjarl = [random_int($xadd2 - 1, $xadd2 + 1), random_int($xadd - 1,$xadd + 1)];
                    $x = $xjarl[array_rand($xjarl)];
                    $yjarl = [random_int($x < 3 ? 10 : ($x == 3 ? 5 : 4), 3), random_int($x > -3 ? -10 : ($x == -3 ? -5 : -4), -3)];
                    $y = $yjarl[array_rand($yjarl)];
                }
                if ($count > 30){ 
                    if ($check != 1){
                        $xadd = 2;
                        $xmax = 10;
                        $check = 1;
                    };
                    $xadd2 = -$xadd;
                    $tase = 4;
                    $y = -3 or 3;
                    $xjarl = [random_int($xadd2 - 1, $xadd2 + 1), random_int($xadd - 1,$xadd + 1)];
                    $x = $xjarl[array_rand($xjarl)];
                }
                if ($count > 40){ 
                    if ($check != 1){
                        $xadd = 11;
                        $check = 1;
                        $xmax = 20;
                    };
                    $xadd2 = -$xadd;
                    $tase = 5;
                    $xjarl = [random_int($xadd2 - 2, $xadd2 + 2), random_int($xadd - 2,$xadd + 2)];
                    $y = -2 or 2;
                    $x = $xjarl[array_rand($xjarl)];   
                }
                
                if ($x == $xold or $y == $yold){
                    goto again8;
                }
                $xold = $x;
                $yold = $y;
                    
                $uusmis = $mis === GameController::BOTH ? (random_int(1, 2) == 1 ? GameController::ASTENDAMINE : GameController::JUURIMINE) : $mis;
                
                if ($uusmis === GameController::ASTENDAMINE) {
                    array_push($array, ["operation"=>$x . 'EXP' . $y, "answer"=>$x ** $y, "level"=>$tase]);
                }
                if ($uusmis === GameController::JUURIMINE) {
                    array_push($array, ["operation"=>$x ** $y . 'RAD' . $y, "answer"=>$x, "level"=>$tase]);
                }

                $xmax -= $xadd;
                $xadd += $xmax / 5;
                
                $count ++;
            
            } while ($count < 50 + ($aeg * 7));

            return $array; 
        }
    }


    //lünkamine
    public function lünkamine($level, $aeg){

        // Lisasin A,B,C tasemed, kui tulevikus vaja neid väärtusi kasutada, siis tuleks muuta
        $defaultMaxLiit = ["1"=>10, "2"=>10, "3"=>100, "4"=>500, "5"=>1000, "A"=>9999, "B"=>99999, "C"=>999999];
        $defaultMaxKor = ["1"=>5, "2"=>5, "3"=>10, "4"=>10, "5"=>10, "A"=>30, "B"=>100, "C"=>100];
        $defaultMaxKor2 = ["1"=>5, "2"=>10, "3"=>10, "4"=>20, "5"=>500, "A"=>100, "B"=>100, "C"=>500];

        $x = 0;
        $y = 0;
        $xold = 0;
        $yold = 0;
        $add = 1;
        $add2 = 1;
        $count = 0;
        $loendlünk = [];
        $max = $defaultMaxLiit[$level];
        $max2 = $defaultMaxKor[$level];
        $max22 = $defaultMaxKor2[$level];

        //Ascending levels
        do{

            $xlünk = 0;
            $ylünk = 0;

            // $add += $max/10;
            // $add2 += $max2/10;
            
            $jarjekord = rand(1, 2);
            $jarjekord === 1 ? $xlünk = "Lünk" : $ylünk =  "Lünk";

            $loos = random_int(1, 4);

            $arvud_liitlah = [
                "1"=>function ($add){return random_int($add, 2 + $add);},
                "2"=>function ($add){return random_int($add + 4, 9 + $add);},
                "3"=>function ($add){return random_int($add + 9, 29 + $add);},
                "4"=>function ($add){return random_int($add + 29, 99 + $add);},
                "5"=>function ($add){return random_int($add + 99, 999 + $add);},
                "A"=>function ($add){return random_int($add + 999, 9999 + $add);},
                "B"=>function ($add){return random_int($add + 9999, 99999 + $add);},
                "C"=>function ($add){return random_int($add + 99999, 999999 + $add);},
            ];

            $arvud_korjag_x = [
                "1"=>function ($add){return random_int($add, 4 + $add);},
                "2"=>function ($add){return random_int($add, 4 + $add);},
                "3"=>function ($add){return random_int($add + 5, 9 + $add);},
                "4"=>function ($add){return random_int($add + 1, 9 + $add);},
                "5"=>function ($add){return random_int($add + 1, 8 + $add);},
                "A"=>function ($add){return random_int($add + 20, 29 + $add);},
                "B"=>function ($add){return random_int($add + 29, 99 + $add);},
                "C"=>function ($add){return random_int($add + 100, 499 + $add);},

            ];
            $arvud_korjag_y = [
                "1"=>function ($add){return random_int($add, 4 + $add);},
                "2"=>function ($add){return random_int($add + 5, 9 + $add);},
                "3"=>function ($add){return random_int($add+ 5, 9 + $add);},
                "4"=>function ($add){return random_int($add + 10, 19 + $add);},
                "5"=>function ($add){return random_int($add + 100, 499 + $add);},
                "A"=>function ($add){return random_int($add + 30, 99 + $add);},
                "B"=>function ($add){return random_int($add + 99, 499 + $add);},
                "C"=>function ($add){return random_int($add + 100, 499 + $add);},

            ];

            $x = $loos % 2 == 1 ? $arvud_liitlah[$level]($add) : $arvud_korjag_x[$level]($add2);
            $y = $loos % 2 == 1 ? $arvud_liitlah[$level]($add) : $arvud_korjag_y[$level]($add2);
            if ($loos == 1){
                // Ternary
                if ($xlünk == 'Lünk'){
                    $ylünk = $y;
                } else {
                    $xlünk = $x;
                }
                array_push($loendlünk, ["operation"=>$xlünk . '+' . $ylünk . " = " . $x + $y, "answer"=>$x + $y - (is_string($xlünk) ? $ylünk : $xlünk), "level"=>$level]);
            }
            if ($loos == 2){
                // Ternary
                if ($xlünk == 'Lünk'){
                    $ylünk = $y;
                } else {
                    $xlünk = $x;
                }
                array_push($loendlünk, ["operation"=>$xlünk . '·' . $ylünk . " = " . $x * $y, "answer"=>$x * $y / (is_string($xlünk) ? $ylünk : $xlünk), "level"=>$level]);
            }
            if ($loos == 3){
                // Ternary
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

        }while ($count < 10 + (4 * $aeg));

        return $loendlünk;
    }

    public function jagseadus($level, $tüüp, $aeg){
        $xvalues = [
            "1"=>[
                "natural"=>function (){return random_int(1, 10);},
                "integer"=>function (){return random_int(-10, 10);},
            ],
            "2"=>[
                "natural"=>function (){return random_int(10, 100);},
                "integer"=>function (){
                    $randints = [random_int(-100, -10), random_int(10, 100)];
                    return $randints[array_rand($randints)];
                },
            ],
            "3"=>[
                "natural"=>function (){return random_int(101, 1000);},
                "integer"=>function (){
                    $randints = [random_int(-1000, -101), random_int(101, 1000)];
                    return $randints[array_rand($randints)];
                },
            ],
            "4"=>[
                "natural"=>function (){return random_int(10001, 100000);},
                "integer"=>function (){
                    $randints = [random_int(-100000, -10001), random_int(10001, 100000)];
                    return $randints[array_rand($randints)];},
            ],
            "5"=>[
                "natural"=>function (){return random_int(1001, 10000);},
                "integer"=>function (){
                    $randints = [random_int(-10000, -1001), random_int(1001, 10000)];
                    return $randints[array_rand($randints)];},
            ],
            "A"=>[
                "natural"=>function (){return random_int(1001, 10000);},
                "integer"=>function (){
                    $randints = [random_int(-10000, -1001), random_int(1001, 10000)];
                    return $randints[array_rand($randints)];},
            ],
            "B"=>[
                "natural"=>function (){return random_int(10001, 1000000);},
                "integer"=>function (){
                    $randints = [random_int(-1000000, -10001), random_int(10001, 1000000)];
                    return $randints[array_rand($randints)];},
            ],
            "C"=>[
                "natural"=>function (){return random_int(10001, 1000000);},
                "integer"=>function (){
                    $randints = [random_int(-1000000, -10001), random_int(10001, 1000000)];
                    return $randints[array_rand($randints)];},
            ],
        ];
        $yvalues = [
            "1"=>[
                "natural"=>function (){
                    $randints = [5, 10];
                    return $randints[array_rand($randints)];},
                "integer"=>function (){
                    $randints = [5, 10];
                    return $randints[array_rand($randints)];},
            ],
            "2"=>[
                "natural"=>function (){
                    $randints = [2, 3];
                    return $randints[array_rand($randints)];},
                "integer"=>function (){
                    $randints = [2, 3];
                    return $randints[array_rand($randints)];},
            ],
            "3"=>[
                "natural"=>function (){
                    $randints = [4, 6, 8];
                    return $randints[array_rand($randints)];},
                "integer"=>function (){
                    $randints = [4, 6, 8];
                    return $randints[array_rand($randints)];},
            ],
            "4"=>[
                "natural"=>function (){
                    $randints = [7, 9];
                    return $randints[array_rand($randints)];},
                "integer"=>function (){
                    $randints = [7, 9];
                    return $randints[array_rand($randints)];},
            ],
            "5"=>[
                "natural"=>function (){
                    $randints = [12, 14];
                    return $randints[array_rand($randints)];},
                "integer"=>function (){
                    $randints = [12, 14];
                    return $randints[array_rand($randints)];},
            ],
            "A"=>[
                "natural"=>function (){
                    $randints = [15, 17];
                    return $randints[array_rand($randints)];},
                "integer"=>function (){
                    $randints = [15, 17];
                    return $randints[array_rand($randints)];},
            ],
            "B"=>[
                "natural"=>function (){return 11;},
                "integer"=>function (){return 11;},
            ],
            "C"=>[
                "natural"=>function (){return 13;},
                "integer"=>function (){return 13;},
            ],
        ];
        
        $returnData = GameController::generateOp($xvalues[$level][$tüüp], $yvalues[$level][$tüüp], GameController::JAGUVUS, function ($num1, $num2, $mis){
            // Boolean, mis ütleb, kas vastus on tõene või mitte
            return random_int(0, 1) == 1;
            }, [], [],  $level, $aeg);

        return $returnData["array"];
        

    }
    public function võrdlemine($level, $aeg){
        $array = [];
        $count = 0;
        $xold = 0;
        $yold = 0;
        $check = 0;
        
        

        if ($level === '1'){
            do{
                $x = random_int(1, 10);
                $y = random_int(1, 10);
                $xk = random_int(2, 5);
                $yk = random_int(2, 5);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(0, 9);
                            $y = random_int(1, 10);
                        } while ($x == $y);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(0, 9);
                        $y = random_int(1, 10);
                    } while (($x == $xold && $y == $yold) || $x == $y || ($x == $yold && $y == $xold));
                }
                $xold = $x;
                $yold = $y;
                
                $random  = random_int(1, 4);

                $suva = [
                1=>function($x, $y){return ["op"=> $x . "·" . $y, "ans"=>$x*$y];},
                2=>function($x, $y){return ["op"=> $x * $y . ":" . $y, "ans"=>$x];},
                3=>function($x, $y){return ["op"=> $x . "+" . $y, "ans"=>$x+$y];},
                4=>function($x, $y){return ["op"=> $x + $y. "-" . $y, "ans"=>$x];}
                ];

                $esimene = ($random == 1 || $random == 2) ? $suva[$random]($xk, $yk) : $suva[$random]($x, $y);
                $random = ($random == 1 || $random == 2) ? random_int(3, 4) : random_int(1,2);
                $teine = ($random == 1 || $random == 2) ? $suva[$random]($xk, $yk) : $suva[$random]($x, $y);

                $vastus = $esimene["ans"] > $teine["ans"] ? "left" : ($esimene["ans"] == $teine["ans"] ? "c" : "right");
                array_push($array, ["operation1"=>$esimene["op"], "operation2"=>$teine["op"], "answer"=> $vastus, "level"=>$level]);

                $count ++;
            } while ($count < 10 + ($aeg * 7));    
        }

        if ($level === '2'){
            do{
                $x = random_int(11, 30);
                $y = random_int(11, 30);
                $xk = random_int(5, 10);
                $yk = random_int(11, 20);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(0, 9);
                            $y = random_int(1, 10);
                        } while ($x == $y);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(0, 9);
                        $y = random_int(1, 10);
                    } while (($x == $xold && $y == $yold) || $x == $y || ($x == $yold && $y == $xold));
                }
                $xold = $x;
                $yold = $y;
                
                $random  = random_int(1, 4);

                $suva = [
                1=>function($x, $y){return ["op"=> $x . "·" . $y, "ans"=>$x*$y];},
                2=>function($x, $y){return ["op"=> $x * $y . ":" . $y, "ans"=>$x];},
                3=>function($x, $y){return ["op"=> $x . "+" . $y, "ans"=>$x+$y];},
                4=>function($x, $y){return ["op"=> $x + $y. "-" . $y, "ans"=>$x];}
                ];

                $esimene = ($random == 1 || $random == 2) ? $suva[$random]($xk, $yk) : $suva[$random]($x, $y);
                $random = ($random == 1 || $random == 2) ? random_int(3, 4) : random_int(1,2);
                $teine = ($random == 3 || $random == 4) ? $suva[$random]($xk, $yk) : $suva[$random]($x, $y);

                $vastus = $esimene["ans"] > $teine["ans"] ? "left" : ($esimene["ans"] == $teine["ans"] ? "c" : "right");
                array_push($array, ["operation1"=>$esimene["op"], "operation2"=>$teine["op"], "answer"=> $vastus, "level"=>$level]);

                $count ++;
            } while ($count < 10 + ($aeg * 7));
        }

        if ($level === '3'){
            do{
                $x = random_int(1, 10);
                $y = random_int(1, 10);
                $xk = random_int(2, 5);
                $yk = random_int(2, 5);
                $zk = random_int(2, 5);
                $z = random_int(2, 5);
                if ($x == $y){
                    $check ++;
                    if ($check == 2){
                        do{
                            $x = random_int(0, 9);
                            $y = random_int(1, 10);
                        } while ($x == $y);
                    }
                }
                if ($x == $xold or $y == $yold){
                    do{
                        $x = random_int(0, 9);
                        $y = random_int(1, 10);
                    } while (($x == $xold && $y == $yold) || $x == $y || ($x == $yold && $y == $xold));
                }
                $xold = $x;
                $yold = $y;
                
                $random  = random_int(1, 4);
                $mark = random_int(1, 2) == 1 ? "+" : "-";
                $mark2 = random_int(1, 2) == 1 ? "·" : ":";
                $suva = [
                1=>function($x, $y, $z, $m){

                    return ["op"=> $x . "·" . $y . $m . $z, "ans"=>$x*$y+($m == "-" ? -$z : $z)];},
                2=>function($x, $y, $z, $m){return ["op"=> $x * $y . ":" . $y. $m. $z, "ans"=>$x+($m == "-" ? -$z : $z)];},
                3=>function($x, $y, $z, $m){return ["op"=> $x . "+" . $y . $m . $z, "ans"=>$x+$y*($m == ":" ? round(1/$z, 2) : $z)];},
                4=>function($x, $y, $z, $m){return ["op"=> $x + $y * ($m == ":" ? round(1/$z,2) : $z) . "-" . $y . $m . $z, "ans"=>$x];}
                ];
                
                $esimene = ($random == 1 || $random == 2) ? $suva[$random]($xk, $yk, $zk, $mark) : $suva[$random]($x, $y, $z, $mark2);
                $random = ($random == 1 || $random == 2) ? random_int(3, 4) : random_int(1,2);
                $teine = ($random == 1 || $random == 2) ? $suva[$random]($xk, $yk, $zk, $mark) : $suva[$random]($x, $y, $z, $mark2);

                $vastus = $esimene["ans"] > $teine["ans"] ? "left" : ($esimene["ans"] == $teine["ans"] ? "c" : "right");
                array_push($array, ["operation1"=>$esimene["op"], "operation2"=>$teine["op"], "answer"=> $vastus, "level"=>$level]);

                $count ++;
            }while ($count < 10 + ($aeg * 7)); 
        }; 
    

        return $array;
    }

    public function lihtsustamine($level, $tüüp, $aeg){
        $sama = $sama2 = $count = $max = 0;
        
        $xvalues = [
            "1"=>[
                "natural"=>function (){return random_int(1, $max =5);},
                //"fraction"=>function (){return random_int(1, $max =5);},
            ],
            "2"=>[
                "natural"=>function (){return random_int(1, $max =5);},
                //"fraction"=>function (){return random_int(6, $max =9);},
            ],
            "3"=>[
                "natural"=>function (){return random_int(2, $max =10);},
                //"fraction"=>function (){return random_int(1, $max =5);},
            ],
            "4"=>[
                "natural"=>function (){return random_int(2, $max =10);},
                //"fraction"=>function (){return random_int(2, $max =10);},
            ],
            "5"=>[
                "natural"=>function (){return random_int(2, $max =10);},
                //"fraction"=>function (){return random_int(20, $max =30);},
            ],
            "6"=>[
                "natural"=>function (){return random_int(11, $max =20);},
                //"fraction"=>function (){return random_int(31, $max =100);},
            ],
            "A"=>[
                "natural"=>function (){return random_int(21, $max =30);},
                //"fraction"=>function (){return random_int(21, $max =30);},
            ],
            "B"=>[
                "natural"=>function (){return random_int(31, $max =100);},
                //"fraction"=>function (){return random_int(31, $max =100);},
            ],
            "C"=>[
                "natural"=>function (){return random_int(101, $max =1000);},
                //"fraction"=>function (){return random_int(101, $max =1000);},
            ],
            
        ];

        $x2values = [
            "1"=>[
                "natural"=>function (){return random_int(1, $max =5);},
                //"fraction"=>function (){return random_int(1, $max =5);},
            ],
            "2"=>[
                "natural"=>function (){return random_int(1, $max =5);},
                //"fraction"=>function (){return random_int(6, $max =9);},
            ],
            "3"=>[
                "natural"=>function (){return random_int(2, $max =10);},
                //"fraction"=>function (){return random_int(1, $max =5);},
            ],
            "4"=>[
                "natural"=>function (){return random_int(2, $max =10);},
                //"fraction"=>function (){return random_int(2, $max =10);},
            ],
            "5"=>[
                "natural"=>function (){return random_int(2, $max =10);},
                //"fraction"=>function (){return random_int(20, $max =30);},
            ],
            "6"=>[
                "natural"=>function (){return random_int(11, $max =20);},
                //"fraction"=>function (){return random_int(31, $max =100);},
            ],
            "A"=>[
                "natural"=>function (){return random_int(21, $max =30);},
                //"fraction"=>function (){return random_int(21, $max =30);},
            ],
            "B"=>[
                "natural"=>function (){return random_int(31, $max =100);},
                //"fraction"=>function (){return random_int(31, $max =100);},
            ],
            "C"=>[
                "natural"=>function (){return random_int(101, $max =1000);},
                //"fraction"=>function (){return random_int(101, $max =1000);},
            ],
            
        ];
        $zvalues = [
            "1"=>[
                "natural"=>function (){return random_int(1, 5);},
                //"fraction"=>function (){return random_int(1, 5) + 0.5;},
            ],
            "2"=>[
                "natural"=>function (){return random_int(6, 10);},
                //"fraction"=>function (){return random_int(6, 9) + 0.5;},
            ],
            "3"=>[
                "natural"=>function (){return random_int(6, 10);},
                //"fraction"=>function (){return random_int(6, 10) + 0.5;},
            ],
            "4"=>[
                "natural"=>function (){return random_int(11, 20);},
                //"fraction"=>function (){return random_int(2, 10) + random_int(1, 9)/10;},
            ],
            "5"=>[
                "natural"=>function (){return random_int(101, 500);},
                //"fraction"=>function (){return random_int(10, 20) + random_int(1, 9)/10;},
            ],
            "6"=>[
                "natural"=>function (){return random_int(21, 30);},
                //"fraction"=>function (){return random_int(21, 30) + random_int(1, 9)/10;},
            ],
            "A"=>[
                "natural"=>function (){return random_int(21, 30);},
                //"fraction"=>function (){return random_int(21, 30) + random_int(1, 9)/10;},
            ],
            "B"=>[
                "natural"=>function (){return random_int(31, 100);},
                //"fraction"=>function (){return random_int(31, 100) + random_int(1, 9)/10;},
            ],
            "C"=>[
                "natural"=>function (){return random_int(31, 100);},
                //"fraction"=>function (){return random_int(31, 100) + random_int(1, 99)/100;},
            ],
        ];
        $x = $xvalues[$level][$tüüp];
        $x2 = $x2values[$level][$tüüp];
        $z = $zvalues[$level][$tüüp];

        if($level != "all"){
            $returnData = GameController::generateOp($x, $x2, GameController::LIHTSUSTAMINE, function ($x, $y){
                $gcd = GameController::gcd($x, $y);
                return "(" . ($x / $gcd) . "/" . ($y / $gcd) . ")";
             }, $z, [], $level, $aeg);

             return $returnData["array"];
        }
    }

    public function wrapper($tehe, $tasemed, $tüüp, $aeg){
        $loend = [];
        $koik = $tasemed == [1, 2, 3, 4, 5];
        if ($koik && $tehe != "lünkamine" && $tehe != 'võrdlemine' && $tehe != "jaguvus" && $tehe != GameController::LIHTSUSTAMINE){

            // Funktsionaalseks (DRY)
            // See on copy paste ju
            if($tehe == "liitmine" or $tehe == "lahutamine" or $tehe == "liitlahutamine"){

                if($tehe == "liitlahutamine"){
                    $tehe = "mõlemad";
                }


                $loend[0] = app('App\Http\Controllers\GameController')->liitlah('all', $tehe, $tüüp, $aeg);
            }

            if($tehe == "korrutamine" or $tehe == "jagamine" or $tehe == "korrujagamine"){

                if($tehe == "korrujagamine"){
                    $tehe = "mõlemad";
                }

                $loend[0] = app('App\Http\Controllers\GameController')->korjag("all", $tehe, $tüüp, $aeg);
            }

            if($tehe == "astendamine" or $tehe == "juurimine" or $tehe == "astejuurimine"){
                $loend[0] = app('App\Http\Controllers\GameController')->astendamine("all", $tehe == 'astejuurimine' ? "mõlemad" : $tehe, $tüüp, $aeg);
            }

        }else{
            for ($lugeja = 0; $lugeja < count($tasemed); $lugeja ++){
                if($tehe == "liitmine" or $tehe == "lahutamine" or $tehe == "liitlahutamine"){   
                    $loend[$tasemed[$lugeja]] = app('App\Http\Controllers\GameController')->liitlah($tasemed[$lugeja], $tehe == "liitlahutamine" ? "mõlemad" : $tehe, $tüüp, $aeg);
                }
    
                if($tehe == "korrutamine" or $tehe == "jagamine" or $tehe == "korrujagamine"){    
                    $loend[$tasemed[$lugeja]] = app('App\Http\Controllers\GameController')->korjag($tasemed[$lugeja], $tehe == "korrujagamine" ? "mõlemad" : $tehe, $tüüp, $aeg);
                }
    
                if($tehe == "lünkamine"){
                    $loend[$tasemed[$lugeja]] = app('App\Http\Controllers\GameController')->lünkamine($tasemed[$lugeja], $aeg);
                }

                if($tehe == "võrdlemine"){
                    $loend[$tasemed[$lugeja]] = app('App\Http\Controllers\GameController')->võrdlemine($tasemed[$lugeja], $aeg);
                }

                if($tehe == GameController::ASTENDAMINE or $tehe == GameController::JUURIMINE or $tehe == GameController::ASTEJUURIMINE){    
                    $loend[$tasemed[$lugeja]] = app('App\Http\Controllers\GameController')->astendamine($tasemed[$lugeja], $tehe == "astejuurimine" ? "mõlemad" : $tehe, $tüüp, $aeg);
                }

                if($tehe == GameController::JAGUVUS){
                    $loend[$tasemed[$lugeja]] = app('App\Http\Controllers\GameController')->jagseadus($tasemed[$lugeja], $tüüp, $aeg);
                }

                if($tehe == GameController::LIHTSUSTAMINE){
                    $loend[$tasemed[$lugeja]] = app('App\Http\Controllers\GameController')->lihtsustamine($tasemed[$lugeja], $tüüp, $aeg);
                }
                
            }
        }
        return $loend;
    }
}