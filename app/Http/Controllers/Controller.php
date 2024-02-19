<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Nette\Utils\Random;
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
    const MULTIOPERAND = "multioperand";
    const ROMAN = "roman";
    const BOTS = "bots";
    //....

    function gcd ($a, $b) {
        return $b ? GameController::gcd($b, $a % $b) : $a;
    }
    function numberToRoman($number) {
        if($number == 0){
            return "0";
        }
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }

    //Op1 = liitmine, korrutamine
    //Op2 = lahutamine, jagamine
    function generateOp($xf, $yf, $mis, $ans, $opnames, $opsymbs, $level, $aeg=1, $roman){

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

            if ($x == $xold || $y == $yold && $mis == GameController::ASTENDAMINE || GameController::JUURIMINE){
                do{
                    $x = $xf();
                    $y = $yf();
                } while(($x == $xold && $y == $yold) || $x == $y || ($x == $yold && $y == $xold));
            }

            $xold = $x;
            $yold = $y;

            $xans = $x;
            $yans = $y;
            $uusmis = $mis;

            $sum = $x + $y;
            $prod = $x * $y;

            if($roman){
                $x = $this -> numberToRoman($x);
                $y = $this -> numberToRoman($y);
                $sum = $this -> numberToRoman($xans + $yans);
                $prod = $this -> numberToRoman($xans * $yans);

            }

            if ($uusmis === GameController::BOTH){
                $uusmis = $opnames[array_rand($opnames)];
            }

            // Liitmine v korrutamine
            if (in_array($uusmis, [GameController::LIITMINE, GameController::KORRUTAMINE])){
                array_push($array, ["operation"=>$x . $opsymbs[0] . ($yans < 0 ? "(" . $y . ")" : $y), "answer"=>$ans($xans, $yans, $uusmis), "level"=>$level]);
            }

            // Lahutamine v jagamine
            if (in_array($uusmis, [GameController::LAHUTAMINE, GameController::JAGAMINE])){
                array_push($array, ["operation"=> ($uusmis == GameController::LAHUTAMINE ? ($sum) : ($prod)) . $opsymbs[1] . ($yans < 0 ? "(" . $y . ")" : $y), "answer"=>$ans($xans, $yans, $uusmis), "level"=>$level]);
            }

            //Astendamine v juurimine
            if (in_array($uusmis, [GameController::ASTENDAMINE, GameController::JUURIMINE])){
                array_push($array, ["operation"=> ($uusmis == GameController::ASTENDAMINE ? ($x . "EXP" . $y) : ($x**$y . "RAD" . $y) ), "answer"=>$ans($xans, $yans, $uusmis), "level"=>$level]);
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

            //Multioperand
            if ($uusmis == GameController::MULTIOPERAND){
                array_push($array, ["operation"=> $x , "answer"=>$ans($x, $y), "level"=>$level]);
            }

            //Bots
            if ($uusmis == GameController::BOTS){
                array_push($array, ["answer"=>$ans($x[1],$x[0], 1), "level"=>$level]);
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
            $returnData = GameController::generateOp($xvalues[$level][$tüüp == "roman" ? "natural" : $tüüp], $yvalues[$level][$tüüp == "roman" ? "natural" : $tüüp], $mis, function ($num1, $num2, $mis){
                return $mis == GameController::LIITMINE ? $num1 + $num2 : $num1;
             }, $opnames, $opsymbs, $level, $aeg, $tüüp == "roman");

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
        
        if ($level === "all" && ($tüüp ==='natural' || $tüüp == "roman")){
            do{
                again4:
                $x = random_int($add, 2 + $add);
                $y = random_int($add, 2 + $add);

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

                $xans = $x;
                $yans = $y;

                $sum = $x + $y;

                if($tüüp == "roman"){
                    $x = $this -> numberToRoman($x);
                    $y = $this -> numberToRoman($y);
                    $sum = $this -> numberToRoman($xans + $yans);
                }

                $uusmis = $mis == GameController::BOTH ? (random_int(1, 2) == 1 ?  GameController::LIITMINE : GameController::LAHUTAMINE) : $mis;

                if ($uusmis === GameController::LIITMINE){
                    array_push($array, ["operation"=>$x. '+' . $y, "answer"=>$xans + $yans, "level"=>$tase]);
                }
                if ($uusmis === GameController::LAHUTAMINE){
                    array_push($array, ["operation"=>$sum. '-' . $y, "answer"=>$xans, "level"=>$tase]);
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
        $max2 = 0;
        $add = 1;
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
                "fraction"=>function (){return random_int(31, 100) + random_int(1, 99)/100;},
                "integer"=>function (){
                    $randints = [random_int(-100, -31), random_int(31, 100)];
                    return $randints[array_rand($randints)];},
            ],
            "C"=>[
                "natural"=>function (){return random_int(101, 1000);},
                "fraction"=>function (){return random_int(101, 1000) + random_int(1, 99)/100;},
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
                "fraction"=>function (){return random_int(31, 100) + random_int(1, 9)/10;},
                "integer"=>function (){
                    $randints = [random_int(-100, -31), random_int(31, 100)];
                    return $randints[array_rand($randints)];},
            ],
        ];

        if($level != "all"){
            $returnData = GameController::generateOp($xvalues[$level][$tüüp == "roman" ? "natural" : $tüüp], $yvalues[$level][$tüüp == "roman" ? "natural" : $tüüp], $mis, function ($num1, $num2, $mis){
                return $mis == GameController::KORRUTAMINE ? $num1 * $num2 : $num1;
             }, $opnames, $opsymbs, $level, $aeg, $tüüp == "roman");

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
                if ($x == $xold || $y == $yold){
                    goto again1;
                }
                $xold = $x;
                $yold = $y;
                if ($mis === 'mõlemad'){
                    $mis = random_int(1, 2) == 1 ? "korrutamine" : "jagamine";
                }
                if ($mis === 'korrutamine') {
                    array_push($array, ["operation"=>$x . '·' . $y, "answer"=>$x * $y, "level"=>$tase]);
                }
                if ($mis === 'jagamine') {
                    array_push($array, ["operation"=>$x * $y . ':' . $y, "answer"=>$x, "level"=>$tase]);
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
                if ($x == $xold || $y == $yold){
                    goto again;
                }
                $xold = $x;
                $yold = $y;

                if ($mis === 'mõlemad'){
                    $mis = random_int(1, 2) == 1 ? "korrutamine" : "jagamine";
                }
                if ($mis === 'korrutamine') {
                    array_push($array, ["operation"=>$x . '·' . $y, "answer"=>$x * $y, "level"=>$tase]);
                }
                if ($mis === 'jagamine') {
                    array_push($array, ["operation"=>$x * $y . ':' . $y, "answer"=>$x, "level"=>$tase]);
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
        if ($level === 'all' && ($tüüp === 'natural' || $tüüp == "roman")){
            do {
                again6:
                $max = 5;

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
                if ($x == $xold || $y == $yold){
                    goto again6;
                }
                $xold = $x;
                $yold = $y;

                $xans = $x;
                $yans = $y;

                if($tüüp == "roman"){
                    $x = $this -> numberToRoman($x);
                    $y = $this -> numberToRoman($y);
                }

                if ($mis === 'mõlemad'){
                    $mis = random_int(1, 2) == 1 ? "korrutamine" : "jagamine"; 
                }

                if ($mis === 'korrutamine') {
                    array_push($array, ["operation"=>$x . '·' . $y, "answer"=>$xans * $yans, "level"=>$tase]);
                }
                if ($mis === 'jagamine') {
                    array_push($array, ["operation"=>$x * $y . ':' . $y, "answer"=>$xans, "level"=>$tase]);
                }
                
                $add += $max / 5;
                $add2 += $max2 / 5;

                $count ++;
            
            } while ($count < 25 + ($aeg * 14));


            return $array;
        }
        
    }

    //Astenamine - Exponentiation
    public function astendamine($level, $mis, $tüüp, $aeg){
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
            "A"=>[
                "natural"=>function (){return random_int(21, 29);},
                "integer"=>function (){
                    $randints = [random_int(-29, -21), random_int(21, 29)];
                    return $randints[array_rand($randints)];},
            ],
            "B"=>[
                "natural"=>function (){return random_int(4,9);},
                "integer"=>function (){
                    $randints = [random_int(-9, -4), random_int(4, 9)];
                    return $randints[array_rand($randints)];},
            ],
            "C"=>[
                "natural"=>function (){return random_int(11,19);},
                "integer"=>function (){return 
                    $randints = [random_int(-19, -11), random_int(11, 19)];
                    return $randints[array_rand($randints)];},
            ],
            
        ];
        $x1 = $xvalues[$level][$tüüp]();
        $yvalues = [
            
            "1"=>[
                "natural"=>function () use ($mis){return $mis == GameController::JUURIMINE  ? 2 : random_int(0, 2);},
                "integer"=>function () use ($x1, $mis){
                    return $mis == GameController::JUURIMINE ? 2 : $randints = [random_int($x1 < 3 ? -10 : -2, [0, -2][array_rand([0, -2])]), random_int([0, 2][array_rand([0, 2])], $x1 < 3 ? 10 : 2)];
                    return $randints[array_rand($randints)];},
            ],
            "2"=>[
                "natural"=>function (){return 2;},
                "integer"=>function (){return [2, -2][array_rand([2, -2])];},
            ],
            "3"=>[
                "natural"=>function (){return random_int(3,4);},
                "integer"=>function () use($x1){
                    $randints = [random_int($x1 < 4 ? -5 : -4, -3), random_int(3, $x1 < 4 ? 5 : 4)];
                    return $randints[array_rand($randints)];},
            ],
            "4"=>[
                "natural"=>function (){return 2;},
                "integer"=>function (){return [2, -2][array_rand([2, -2])];},
            ],
            "5"=>[
                "natural"=>function (){return 3;},
                "integer"=>function (){return [3, -3][array_rand([3, -3])];},
            ],
            "A"=>[
                "natural"=>function (){return 2;},
                "integer"=>function (){return [2, -2][array_rand([2, -2])];},
            ],
            "B"=>[
                "natural"=>function (){return 4;},
                "integer"=>function (){return [4, -4][array_rand([4, -4])];},
            ],
            "C"=>[
                "natural"=>function (){return 3;},
                "integer"=>function (){return [3, -3][array_rand([3, -3])];},
            ],
            
        ];
        $y1 = $xvalues[$level][$tüüp]();


        
        $returnData = GameController::generateOp($xvalues[$level][$tüüp], $yvalues[$level][$tüüp], $mis, function ($num1, $num2, $mis) use ($x1, $y1){
            return $mis == GameController::ASTENDAMINE && $y1 < 0 ? 1/($num1 ** abs($num2)) : ($mis == GameController::JUURIMINE && $y1 < 0 ? 1/$num1 : ($mis == GameController::ASTENDAMINE ? $num1 ** $num2 : abs($num1)));
        }, $opnames, [],  $level, $aeg, null);

        return $returnData["array"];
        
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
    public function multioperand($level, $tüüp, $aeg){
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
                "natural"=>function (){return random_int(1, 8);},
                "integer"=>function (){return random_int(-8, 8);},
            ],
            "2"=>[
                "natural"=>function (){return random_int(10, 88);},
                "integer"=>function (){
                    $randints = [random_int(-88, -10), random_int(10, 88)];
                    return $randints[array_rand($randints)];
                },
            ],
            "3"=>[
                "natural"=>function (){return random_int(101, 888);},
                "integer"=>function (){
                    $randints = [random_int(-888, -101), random_int(101, 888)];
                    return $randints[array_rand($randints)];
                },
            ],
            "4"=>[
                "natural"=>function (){return random_int(10001, 88888);},
                "integer"=>function (){
                    $randints = [random_int(-88888, -10001), random_int(10001, 88888)];
                    return $randints[array_rand($randints)];},
            ],
            "5"=>[
                "natural"=>function (){return random_int(1001, 8888);},
                "integer"=>function (){
                    $randints = [random_int(-8888, -1001), random_int(1001, 8888)];
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
        $returnData = GameController::generateOp($xvalues[$level][$tüüp], $yvalues[$level][$tüüp], GameController::MULTIOPERAND, function ($num1, $num2){
            $mis = random_int(1,4);
            return $mis == 1 ? $num1 + $num2 : ($mis == 2 ? $num1 * $num2 : $num1);
            }, [], [],  $level, $aeg, null);

        return $returnData["array"];
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
        
        $returnData = GameController::generateOp($xvalues[$level][$tüüp], $yvalues[$level][$tüüp], GameController::JAGUVUS, function (){
            // Boolean, mis ütleb, kas vastus on tõene või mitte
            return random_int(0, 1) == 1;
            }, [], [],  $level, $aeg, null);

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
                if ($x == $xold || $y == $yold){
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
                if ($x == $xold || $y == $yold){
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
                if ($x == $xold || $y == $yold){
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
            ],
            "2"=>[
                "natural"=>function (){return random_int(1, $max =5);},
            ],
            "3"=>[
                "natural"=>function (){return random_int(2, $max =10);},
            ],
            "4"=>[
                "natural"=>function (){return random_int(2, $max =10);},
            ],
            "5"=>[
                "natural"=>function (){return random_int(2, $max =10);},
            ],
            "6"=>[
                "natural"=>function (){return random_int(11, $max =20);},
            ],
            "A"=>[
                "natural"=>function (){return random_int(21, $max =30);},
            ],
            "B"=>[
                "natural"=>function (){return random_int(31, $max =100);},
            ],
            "C"=>[
                "natural"=>function (){return random_int(101, $max =1000);},
            ],
            
        ];

        $x2values = [
            "1"=>[
                "natural"=>function (){return random_int(1, $max =5);},
            ],
            "2"=>[
                "natural"=>function (){return random_int(1, $max =5);},
            ],
            "3"=>[
                "natural"=>function (){return random_int(2, $max =10);},
            ],
            "4"=>[
                "natural"=>function (){return random_int(2, $max =10);},
            ],
            "5"=>[
                "natural"=>function (){return random_int(2, $max =10);},
            ],
            "6"=>[
                "natural"=>function (){return random_int(11, $max =20);},
            ],
            "A"=>[
                "natural"=>function (){return random_int(21, $max =30);},
            ],
            "B"=>[
                "natural"=>function (){return random_int(31, $max =100);},
            ],
            "C"=>[
                "natural"=>function (){return random_int(101, $max =1000);},
            ],
            
        ];
        $zvalues = [
            "1"=>[
                "natural"=>function (){return random_int(1, 5);},
            ],
            "2"=>[
                "natural"=>function (){return random_int(6, 10);},
            ],
            "3"=>[
                "natural"=>function (){return random_int(6, 10);},
            ],
            "4"=>[
                "natural"=>function (){return random_int(11, 20);},
            ],
            "5"=>[
                "natural"=>function (){return random_int(101, 500);},
            ],
            "6"=>[
                "natural"=>function (){return random_int(21, 30);},
            ],
            "A"=>[
                "natural"=>function (){return random_int(21, 30);},
            ],
            "B"=>[
                "natural"=>function (){return random_int(31, 100);},
            ],
            "C"=>[
                "natural"=>function (){return random_int(31, 100);},
            ],
        ];
        $x = $xvalues[$level][$tüüp];
        $x2 = $x2values[$level][$tüüp];
        $z = $zvalues[$level][$tüüp];

        if($level != "all"){
            $returnData = GameController::generateOp($x, $x2, GameController::LIHTSUSTAMINE, function ($x, $y){
                $gcd = GameController::gcd($x, $y);
                return "(" . ($x / $gcd) . "/" . ($y / $gcd) . ")";
             }, $z, [], $level, $aeg, null);

             return $returnData["array"];
        }
    }

    public function kujundid($level, $tüüp, $aeg){
        $array = [];
        $count = 0;
        $tasemax = ["1"=>9, "2"=>16, "3"=>25, "4"=>36, "5"=>49];
        $max = $tasemax[$level];
        do{ 
            $suvaline = array();
            $random_kujund = random_int(1,3);
            $random_color = ($tüüp == 'color' or $tüüp == 'all') ? random_int(1,3) : null;
            $random_size = ($tüüp == 'size' or $tüüp == 'all') ? random_int(1,3) : null;
            $anscount = 0;
            for ($x = 0; $x < $max; $x++){
                $random_kujund2 = random_int(1,3);
                $random_color2 = ($tüüp == 'color' or $tüüp == 'all') ? random_int(1,3) : null;
                $random_size2 = ($tüüp == 'size' or $tüüp == 'all') ? random_int(1,3) : null;

                if($random_kujund2 == $random_kujund && $random_color2 == $random_color && $random_size2 == $random_size){
                    $anscount ++;
                }
                array_push($suvaline, ["shape"=>$random_kujund2, "color"=>$random_color2, "size"=>$random_size2]);
                
            };
            array_push($array, ["operation"=> $suvaline, "answer"=>["ans"=>$anscount, "shape"=>$random_kujund, 'color'=>$random_color, 'size'=>$random_size], "level"=>$level]);
            $count ++;
    
        }while($count < 10 + ($aeg * 7));

        return $array;
    }

    public function bots($level, $tehe, $tasemed, $tüüp, $aeg){
        $raskus = [
            "300" => [function ($min, $kadu){
                $min = 0.1;
                $kadu = random_int(1, 15)/100;
                return array($min, $kadu);
            }],
            //...

        ];
        app("App\Http\Controllers\Controller")->wrapper($tehe, $tasemed, $tüüp, $aeg);

        $returnData = GameController::generateOp($raskus[$level], [], GameController::BOTS, function($kadu, $min, $botcheck){
            $accuracy = 1 - ($botcheck > $min ? $kadu : 0);
            $botcheck = $accuracy;
            return $accuracy;
        }, [], [],  $level, $aeg, null);

        return $returnData["array"];
    }

    public function wrapper($tehe, $tasemed, $tüüp, $aeg){
        $loend = [];
        $koik = $tasemed == [1, 2, 3, 4, 5];
        $types_without_all = ["lünkamine", "võrdlemine", "jaguvus", "lihtsustamine", "kujundid", "juurimine", "astejuurimine", "astendamine"];
        if ($koik && !in_array($tehe, $types_without_all)){

            // Funktsionaalseks (DRY)
            // See on copy paste ju
            if($tehe == "liitmine" || $tehe == "lahutamine" || $tehe == "liitlahutamine"){

                if($tehe == "liitlahutamine"){
                    $tehe = "mõlemad";
                }


                $loend[0] = app('App\Http\Controllers\GameController')->liitlah('all', $tehe, $tüüp, $aeg);
            }

            if($tehe == "korrutamine" || $tehe == "jagamine" || $tehe == "korrujagamine"){

                if($tehe == "korrujagamine"){
                    $tehe = "mõlemad";
                }

                $loend[0] = app('App\Http\Controllers\GameController')->korjag("all", $tehe, $tüüp, $aeg);
            }

            if($tehe == "astendamine" || $tehe == "juurimine" || $tehe == "astejuurimine"){
                $loend[0] = app('App\Http\Controllers\GameController')->astendamine("all", $tehe == 'astejuurimine' ? "mõlemad" : $tehe, $tüüp, $aeg);
            }

        }else{
            for ($lugeja = 0; $lugeja < count($tasemed); $lugeja ++){
                if($tehe == "liitmine" || $tehe == "lahutamine" || $tehe == "liitlahutamine"){   
                    $loend[$tasemed[$lugeja]] = app('App\Http\Controllers\GameController')->liitlah($tasemed[$lugeja], $tehe == "liitlahutamine" ? "mõlemad" : $tehe, $tüüp, $aeg);
                }
    
                if($tehe == "korrutamine" || $tehe == "jagamine" || $tehe == "korrujagamine"){    
                    $loend[$tasemed[$lugeja]] = app('App\Http\Controllers\GameController')->korjag($tasemed[$lugeja], $tehe == "korrujagamine" ? "mõlemad" : $tehe, $tüüp, $aeg);
                }
    
                if($tehe == "lünkamine"){
                    $loend[$tasemed[$lugeja]] = app('App\Http\Controllers\GameController')->lünkamine($tasemed[$lugeja], $aeg);
                }

                if($tehe == "võrdlemine"){
                    $loend[$tasemed[$lugeja]] = app('App\Http\Controllers\GameController')->võrdlemine($tasemed[$lugeja], $aeg);
                }

                if($tehe == GameController::ASTENDAMINE || $tehe == GameController::JUURIMINE || $tehe == GameController::ASTEJUURIMINE){    
                    $loend[$tasemed[$lugeja]] = app('App\Http\Controllers\GameController')->astendamine($tasemed[$lugeja], $tehe == "astejuurimine" ? "mõlemad" : $tehe, $tüüp, $aeg);
                }

                if($tehe == GameController::JAGUVUS){
                    $loend[$tasemed[$lugeja]] = app('App\Http\Controllers\GameController')->jagseadus($tasemed[$lugeja], $tüüp, $aeg);
                }

                if($tehe == GameController::LIHTSUSTAMINE){
                    $loend[$tasemed[$lugeja]] = app('App\Http\Controllers\GameController')->lihtsustamine($tasemed[$lugeja], "natural", $aeg);
                }

                if($tehe == 'kujundid'){
                    $loend[$tasemed[$lugeja]] = app('App\Http\Controllers\GameController')->kujundid($tasemed[$lugeja], $tüüp, $aeg);
                }
                
            }
        }
        return $loend;
    }
}