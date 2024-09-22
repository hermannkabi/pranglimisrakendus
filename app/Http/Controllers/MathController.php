<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class MathController extends Controller
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
    const MURRUTAANDAMINE = "murruTaandamine";
    const VÕRDLEMINE = "võrdlemine";
    const LÜNKAMINE = "lünkamine";
    const MULTIOPERAND = "multioperand";
    const ROMAN = "roman";
    const BOTS = "bots";
    const KUJUNDID = "kujundid";
    const SUVALISUS = "suvalisus";
    //....

    function gcd ($a, $b) {
        return $b ? MathController::gcd($b, $a % $b) : $a;
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
            $uusmis = $mis;

            if ($uusmis === MathController::BOTH){
                $uusmis = $opnames[array_rand($opnames)];
            }

            $x = $uusmis == MathController::ASTENDAMINE || $uusmis == MathController::JUURIMINE ?  $xf($uusmis) : $xf();
            $y = $uusmis == MathController::ASTENDAMINE || $uusmis == MathController::JUURIMINE ?  $yf($uusmis) : $yf();            ;    
            

            if ($x == $y && !($uusmis == MathController::ASTENDAMINE || $uusmis == MathController::JUURIMINE)){
                $check ++;

                if ($check > MathController::SAME_NUMBER_REPEAT_COUNT){
                    do{
                        $x = $xf($uusmis);
                        $y = $yf($uusmis);
                    } while ($x == $y);
                }
            }

            if (($x == $xold || $y == $yold) && !($uusmis == MathController::ASTENDAMINE || $uusmis == MathController::JUURIMINE)){
                do{
                    $x = $xf($uusmis);
                    $y = $yf($uusmis);
                } while(($x == $xold && $y == $yold) || $x == $y || ($x == $yold && $y == $xold));
            }

            $xold = $x;
            $yold = $y;

            $xans = $x;
            $yans = $y;

            $sum = $x + $y;
            $prod = $x * $y;

            if($roman){
                $x = $this -> numberToRoman($x);
                $y = $this -> numberToRoman($y);
                $sum = $this -> numberToRoman($xans + $yans);
                $prod = $this -> numberToRoman($xans * $yans);

            }


            // Liitmine v korrutamine
            if (in_array($uusmis, [MathController::LIITMINE, MathController::KORRUTAMINE])){
                array_push($array, ["operation"=>$x . $opsymbs[0] . ($yans < 0 ? "(" . $y . ")" : $y), "answer"=>$ans($xans, $yans, $uusmis), "level"=>$level]);
            }

            // Lahutamine v jagamine
            if (in_array($uusmis, [MathController::LAHUTAMINE, MathController::JAGAMINE])){
                array_push($array, ["operation"=> ($uusmis == MathController::LAHUTAMINE ? ($sum) : ($prod)) . $opsymbs[1] . ($yans < 0 ? "(" . $y . ")" : $y), "answer"=>$ans($xans, $yans, $uusmis), "level"=>$level]);
            }

            //Astendamine v juurimine
            if (in_array($uusmis, [MathController::ASTENDAMINE, MathController::JUURIMINE])){

                // See tähendab, et on murdudega juurimine/astendamine
                if($opsymbs){
                    $x3 = $xf($uusmis);
                    array_push($array, ["operation"=> ($uusmis == MathController::ASTENDAMINE ? ("(".$x."/".$x3.")" . "EXP" . $y) : ("(".$x3**$y."/".$x**$y.")" . "RAD" . $y)), "answer"=>$ans($xans, $yans, $x3, $uusmis), "level"=>$level]);
                }else{
                    array_push($array, ["operation"=> ($uusmis == MathController::ASTENDAMINE ? ($x . "EXP" . $y) : ($y > 0 ? ($x**$y . "RAD" . $y) : ('1'. '/' . $x**abs($y). "RAD" . $y)) ), "answer"=>$ans($xans, $yans, null, $uusmis), "level"=>$level]);
                }
            }

            //Jaguvus
            if ($uusmis === MathController::JAGUVUS){
                $jagub = $ans($x, $y, $uusmis);
                array_push($array, ["operation"=> ($jagub ? (($x * $y) . " ⋮ " . $y) : (($x*$y + ($y - random_int(1, $y - 1)))." ⋮ ".$y)), "answer"=>$jagub, "level"=>$level]);
            }

            //Lihtustamine
            if ($uusmis == MathController::MURRUTAANDAMINE){
                $z = $opnames();
                while ($z * $y == 1 || $x * $z == 1){
                    $z = $opnames();
                }
                array_push($array, ["operation"=> "LIHT(" . ($x * $z) . "/" .($y * $z) . ")" , "answer"=>$ans($x * $z, $y * $z), "level"=>$level]);
            }

            //Multioperand
            if ($uusmis == MathController::MULTIOPERAND){
                array_push($array, ["operation"=> $x , "answer"=>$ans($x, $y), "level"=>$level]);
            }

            //Bots
            if ($uusmis == MathController::BOTS){
                array_push($array, ["operation"=> $x[-1],"answer"=>$ans($x[1],$x[0], 1), "level"=>$level]);
            }

            $count ++;
        } while ($count < (MathController::OPERATION_COUNT + (14 * $aeg)));

        return ["array"=>$array];
    }


    // Esialgu 1206-realine funktsioon
    // Vaatame, kui palju vähemaks võtta annab
    // Üritame kirjutada võimalikult DRY koodi

    //Addition and Substraction
    public function liitlah($level, $mis, $tüüp, $aeg, $random=false){
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


        $opnames = [MathController::LIITMINE, MathController::LAHUTAMINE];
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
            $returnData = MathController::generateOp($xvalues[$level][$tüüp == "roman" ? "natural" : $tüüp], $yvalues[$level][$tüüp == "roman" ? "natural" : $tüüp], $mis, function ($num1, $num2, $mis){
                return $mis == MathController::LIITMINE ? $num1 + $num2 : $num1;
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
                    if ($kontroll > MathController::SAME_NUMBER_REPEAT_COUNT){
                        goto again2;
                    }
                }
                if (($x == $xold && $y == $yold) || $x == $y || ($x == $yold && $y == $xold)){
                    // Kas see töötab?
                    goto again2;
                }
                $xold = $x;
                $yold = $y;

                $uusmis = $mis == MathController::BOTH ? (random_int(1, 2) == 1 ?  MathController::LIITMINE : MathController::LAHUTAMINE) : $mis;

                if ($uusmis === MathController::LIITMINE){
                    array_push($array, ["operation"=>$x. '+' . $y, "answer"=>$x + $y, "level"=>$tase]);
                }

                if ($uusmis === MathController::LAHUTAMINE){
                    array_push($array, ["operation"=>$x + $y. '-' . $y, "answer"=>$x, "level"=>$tase]);
                }

                $add += $max/5;
                $count ++;
            }while ($random ? $count < 1 : $count < MathController::OPERATION_COUNT + ($aeg*14));

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
                    if ($kontroll > MathController::SAME_NUMBER_REPEAT_COUNT){
                        goto again3;
                    }
                }


                if (($x == $xold && $y == $yold) || $x == $y || ($x == $yold && $y == $xold)){
                    goto again3;
                }


                $xold = $x;
                $yold = $y;

                $uusmis = $mis;

                if($mis == MathController::BOTH){
                    $uusmis = $opnames[array_rand($opnames)];
                }
                
                if ($uusmis === MathController::LIITMINE){
                    if ($y < 0){
                        $y = -$y;
                        array_push($array, ["operation"=>$x. '-' . $y, "answer"=>$x - $y, "level"=>$tase]);
                    } else {
                    array_push($array, ["operation"=>$x. '+' . $y, "answer"=>$x + $y, "level"=>$tase]);
                    }
                }
                if ($uusmis === MathController::LAHUTAMINE){
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
            
            } while ($random ? $count < 1 : $count < MathController::OPERATION_COUNT + ($aeg*14));

            return $array;
        }


        // Ascending levels -- Natural
        
        if ($level === "all" && ($tüüp ==='natural' || $tüüp == "roman")){
            do{
                again4:
                $x = random_int($add, 3 + $add);
                $y = random_int($add, 3 + $add);

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
                    if ($kontroll > MathController::SAME_NUMBER_REPEAT_COUNT){
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

                $uusmis = $mis == MathController::BOTH ? (random_int(1, 2) == 1 ?  MathController::LIITMINE : MathController::LAHUTAMINE) : $mis;

                if ($uusmis === MathController::LIITMINE){
                    array_push($array, ["operation"=>$x. '+' . $y, "answer"=>$xans + $yans, "level"=>$tase]);
                }
                if ($uusmis === MathController::LAHUTAMINE){
                    array_push($array, ["operation"=>$sum. '-' . $y, "answer"=>$xans, "level"=>$tase]);
                }

                $add += $max/5;
                $count ++;
            }while ($random ? $count < 1 : $count < MathController::OPERATION_COUNT + ($aeg*14));

            return $array;
        }            
    }




    public function korjag($level, $mis, $tüüp, $aeg, $random=false){
        $array = [];
        $x = 0;
       
        $tase = 1;
        $count = 0;
        $max = 0;
        $xmax = 5;
        $ymax = 5;
        $max2 = 0;
        $add = 1;
        $add2 = 0;
        $xadd = 1;
        $yadd = 1;
        $xadd2 = 0;
        $yadd2 = -1;
        $xold = 0;
        $yold = 0;
        $check = 0;
        $kontroll = 0;


        $opnames = [MathController::KORRUTAMINE, MathController::JAGAMINE];
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
                "integer"=>function (){ 
                    $randints = [random_int(-5, -1), random_int(1, 5)];
                    return $randints[array_rand($randints)];
                },
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
            $returnData = MathController::generateOp($xvalues[$level][$tüüp == "roman" ? "natural" : $tüüp], $yvalues[$level][$tüüp == "roman" ? "natural" : $tüüp], $mis, function ($num1, $num2, $mis){
                return $mis == MathController::KORRUTAMINE ? $num1 * $num2 : $num1;
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
                    if ($kontroll > MathController::SAME_NUMBER_REPEAT_COUNT){
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
            
            } while ($random ? $count < 1 : $count < MathController::OPERATION_COUNT +  ($aeg*14));
            return $array;
        }
            
        
        //Ascending levels -- Integer
        if ($level === 'all' && $tüüp === 'integer'){
            do {
                again:
                $xjarl = [random_int($xadd2 - 2, $xadd2 + 1), random_int($xadd - 1, $xadd + 2)];
                $yjarl = [random_int($yadd2 - 2, $yadd2 == 0 ? -1 : $yadd), random_int($yadd == 0 ? 1 : $yadd, $yadd + 2)];
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
                    $tase = 2;
                }
                if ($count > 10){ 
                    if ($check != 1){
                        $xadd = $yadd = 6;
                        $check = 1;
                    };
                    $tase = 3;
                    $xmax = $ymax = 10;
                    $xjarl = [random_int($xadd2 - 3, $xadd2 + 3), random_int($xadd - 3,$xadd + 3)];
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
                    $yjarl = [random_int($yadd2 - 4, $yadd2 + 4), random_int($yadd - 4,$yadd + 4)];
                    $xjarl = [random_int($xadd2 - 4, $xadd2 + 4), random_int($xadd - 4,$xadd + 4)];
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
                    if ($kontroll > MathController::SAME_NUMBER_REPEAT_COUNT){
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
            
            } while ($count < 30 + ($aeg * 14));
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
                    if ($kontroll > MathController::SAME_NUMBER_REPEAT_COUNT){
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
            
            } while ($random ? $count < 1 : $count < MathController::OPERATION_COUNT + ($aeg*14));


            return $array;
        }
        
    }

    //Astenamine - Exponentiation
    public function astendamine($level, $mis, $tüüp, $aeg, $random=false){
        $xvalues = [];

        $opnames = [MathController::ASTENDAMINE, MathController::JUURIMINE];
        
        $xvalues = [
            "1"=>[
                "natural"=>function (){return random_int(1, 5);},
                "fraction"=>function ($uusmis=null){
                    $randints = $uusmis == MathController::JUURIMINE ? [1, random_int(2, 5)] : [random_int(-5, -1), random_int(1, 5)];
                    return $randints[array_rand($randints)];}
            ],
            "2"=>[
                "natural"=>function (){return random_int(6, 10);},
                "fraction"=>function ($uusmis=null){
                    $randints = $uusmis == MathController::JUURIMINE ? [6, random_int(7, 10)] : [random_int(-10, -6), random_int(6, 10)];
                    return $randints[array_rand($randints)];}
            ],
            "3"=>[
                "natural"=>function (){return random_int(2, 5);},
                "fraction"=>function ($uusmis=null){
                    $randints = $uusmis == MathController::JUURIMINE ? [2, random_int(2, 5)] : [random_int(-5, -2), random_int(2, 5)];
                    return $randints[array_rand($randints)];}
            ],
            "4"=>[
                "natural"=>function (){return random_int(11, 20);},
                "fraction"=>function ($uusmis=null){
                    $randints = $uusmis == MathController::JUURIMINE ? [11, random_int(12, 20)] : [random_int(-20, -11), random_int(11, 20)];
                    return $randints[array_rand($randints)];}
            ],
            "5"=>[
                "natural"=>function (){return random_int(6, 10);},
                "fraction"=>function ($uusmis=null){
                    $randints = $uusmis == MathController::JUURIMINE ? [6, random_int(7, 10)] : [random_int(-10, -6), random_int(6, 10)];
                    return $randints[array_rand($randints)];}
            ],
            "A"=>[
                "natural"=>function (){return random_int(1, 9)*10 + 5;},
                "fraction"=>function ($uusmis=null){
                    $randints = $uusmis == MathController::JUURIMINE ? [15, random_int(2, 9)*10 + 5] : [random_int(-9, -1)*10 -5, random_int(1, 9)*10+5];
                    return $randints[array_rand($randints)];}
            ],
            "B"=>[
                "natural"=>function (){return random_int(4,9);},
                "fraction"=>function ($uusmis=null){
                    $randints = $uusmis == MathController::JUURIMINE ? [4, random_int(5, 9)] : [random_int(-9, -4), random_int(4, 9)];
                    return $randints[array_rand($randints)];}
            ],
            "C"=>[
                "natural"=>function (){return random_int(11,19);},
                "fraction"=>function ($uusmis=null){
                    $randints = $uusmis == MathController::JUURIMINE ? [11, random_int(12, 19)] : [random_int(-19, -11), random_int(11, 19)];
                    return $randints[array_rand($randints)];},
            ],
            
        ];
        $x1 = $xvalues[$level][$tüüp]();
        $yvalues = [
            
            "1"=>[
                "natural"=>function () use ($mis){return ($mis == MathController::JUURIMINE || $mis === "mõlemad")  ? 2 : random_int(0, 2);},
                "fraction"=>function ($uusmis=null) use ($mis, $x1){
                    $randints = [random_int(-2, 2)];

                    //$randints = [random_int(abs($x1) < 3 ? -8 : -2, abs($x1) < 3 ? 8 : 2)];   
                    return ($uusmis ?? $mis) == MathController::JUURIMINE && $x1==2 ? random_int(3,6) : 
                    (($uusmis ?? $mis) == MathController::JUURIMINE && $x1==3 ? random_int(2,4) : 
                    (($uusmis ?? $mis) == MathController::JUURIMINE ? 2 : $randints[array_rand($randints)]));},
            ],
            "2"=>[
                "natural"=>function (){return 2;},
                "fraction"=>function ($uusmis=null) use ($mis){
                    $randints = [random_int(-2, 2)];  
                    return ($uusmis ?? $mis) == MathController::JUURIMINE ? 2 : $randints[array_rand($randints)];},
            ],
            "3"=>[
                "natural"=>function () use ($x1){return random_int(3,$x1==2 ? 8 : ($x1==3 ? 5 : 4));},
                "fraction"=>function ($uusmis=null) use($mis, $x1){
                    $randints = [random_int(-2, 2)];  
                    return ($uusmis ?? $mis) == MathController::JUURIMINE && $x1==2 ? random_int(3,8) : 
                    (($uusmis ?? $mis) == MathController::JUURIMINE && $x1==3 ? random_int(2,5) : 
                    (($uusmis ?? $mis) == MathController::JUURIMINE ? random_int(3,4) : $randints[array_rand($randints)]));},
            ],
            "4"=>[
                "natural"=>function (){return 2;},
                "fraction"=>function ($uusmis=null) use ($mis){
                    $randints = [random_int(-2, 2)];  
                    return ($uusmis ?? $mis) == MathController::JUURIMINE ? 2 : $randints[array_rand($randints)];},
            ],
            "5"=>[
                "natural"=>function () use ($x1){return $x1 == 10 ? random_int(4,6) : 3;},
                "fraction"=>function ($uusmis=null) use ($mis, $x1){
                    $randints = [random_int(-3, 3)];  
                    return ($uusmis ?? $mis) == MathController::JUURIMINE && $x1==10 ? random_int(4,6) : (($uusmis ?? $mis) == MathController::JUURIMINE ? 3 : $randints[array_rand($randints)]);},
            ],
            "A"=>[
                "natural"=>function (){return 2;},
                "fraction"=>function ($uusmis=null) use ($mis){
                    $randints = [random_int(-2, 2)];  
                    return ($uusmis ?? $mis) == MathController::JUURIMINE ? 2 : $randints[array_rand($randints)];},
            ],
            "B"=>[
                "natural"=>function (){return 4;},
                "fraction"=>function ($uusmis=null) use ($mis){
                    $randints = [random_int(-4, 4)];  
                    return ($uusmis ?? $mis) == MathController::JUURIMINE ? 4 : $randints[array_rand($randints)];},
            ],
            "C"=>[
                "natural"=>function (){return 3;},
                "fraction"=>function ($uusmis=null) use ($mis){
                    $randints = [random_int(-3, 3)];  
                    return ($uusmis ?? $mis) == MathController::JUURIMINE ? 3 : $randints[array_rand($randints)];},
            ],
            
        ];
        $y1 = $yvalues[$level][$tüüp]();


        
        // num1 - x1
        // num2 - y
        // num3 - x2 (kui on)
        $returnData = MathController::generateOp($xvalues[$level][$tüüp], $yvalues[$level][$tüüp], $mis, function ($num1, $num2, $num3, $mis){
            if($mis == MathController::ASTENDAMINE){
                if($num3 != null){
                    return ($num1/$num3)**$num2;
                }
                
                return $num1 ** $num2;
            }else{
                if($num3 != null){
                    return abs($num3/$num1);
                }
                return abs($num1); 
            }

        }, $opnames, $tüüp == "fraction",  $level, $aeg, null, $random);

        return $returnData["array"];
        
    }


    //lünkamine
    public function lünkamine($level, $aeg, $random=false){

        // Lisasin A,B,C tasemed, kui tulevikus vaja neid väärtusi kasutada, siis tuleks muuta
        // $defaultMaxLiit = ["1"=>10, "2"=>10, "3"=>100, "4"=>500, "5"=>1000, "A"=>9999, "B"=>99999, "C"=>999999];
        // $defaultMaxKor = ["1"=>5, "2"=>5, "3"=>10, "4"=>10, "5"=>10, "A"=>30, "B"=>100, "C"=>100];
        // $defaultMaxKor2 = ["1"=>5, "2"=>10, "3"=>10, "4"=>20, "5"=>500, "A"=>100, "B"=>100, "C"=>500];

        $x = 0;
        $y = 0;
        $add = 1;
        $add2 = 1;
        $add22 = 1;
        $count = 0;
        $loendlünk = [];
        // $max = $defaultMaxLiit[$level];
        // $max2 = $defaultMaxKor[$level];
        // $max22 = $defaultMaxKor2[$level];

        //Ascending levels
        do{

            $xlünk = 0;
            $ylünk = 0;

            // $add += $max/10;
            // $add2 += $max2/10;
            // $add22 += $max22/10;
            
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
            $y = $loos % 2 == 1 ? $arvud_liitlah[$level]($add) : $arvud_korjag_y[$level]($add22);
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

        }while ($random ? $count < 1 : $count < MathController::OPERATION_COUNT + ($aeg*14));

        return $loendlünk;
    }
    public function multioperand($level, $tüüp, $aeg){ //TODO:
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
        $yvalues = [ //Võiks olla korrujagamisarvud
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
        $astekogum = ['1'=>3, '2'=>3, '3'=>4, '4'=>4];
        $aste = $astekogum[$level];
        $returnData = MathController::generateOp($xvalues[$level][$tüüp], $yvalues[$level][$tüüp], MathController::MULTIOPERAND, function ($num1, $num2,$num1kor, $num2kor) use ($aste){
            $kogum = [$num1.'+'.$num2, $num1+$num2.'-'.$num2, $num1kor.'*'.$num2kor, $num1kor*$num2kor.':'.$num2kor];
            $valik = array_fill(0, $aste, $kogum[$suvakogum = array_rand(range(1,4))]);//$suvanorm - Kogum numbritest, antud juhul tehtemärkidest
            $margid = ['+','-'];
            $suva = array_fill(1, $aste-1, $margid[array_rand($margid)]);
            for($i=0, $番号=0;$i<$aste;$i++){ //番号 - number
                if ($i%2==1) {
                    $valik =array_splice($valik, $i, 0, $suva[$番号]); // Kogum arvudest ja tehetest
                    $番号++;
                }
            }
            return [$valik, $suvakogum];
            }, [], [],  $level, $aeg, null);

        return $returnData["array"];
    }
    public function jagseadus($level, $tüüp, $aeg, $random=false){
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
        
        $returnData = MathController::generateOp($xvalues[$level][$tüüp], $yvalues[$level][$tüüp], MathController::JAGUVUS, function (){
            // Boolean, mis ütleb, kas vastus on tõene või mitte
            return random_int(0, 1) == 1;
            }, [], [],  $level, $aeg, null, $random);

        return $returnData["array"];
        

    }
    public function võrdlemine($level, $aeg, $randomOp=false){
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
            } while ($randomOp ? $count < 1 : $count < MathController::OPERATION_COUNT + ($aeg*14));    
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
            } while ($randomOp ? $count < 1 : $count < MathController::OPERATION_COUNT + ($aeg*14));
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
            }while ($randomOp ? $count < 1 : $count < MathController::OPERATION_COUNT + ($aeg*14)); //TODO:
        }; 
    

        return $array;
    }

    public function murruTaandamine($level, $tüüp, $aeg, $random=false){
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
            $returnData = MathController::generateOp($x, $x2, MathController::MURRUTAANDAMINE, function ($x, $y){
                $gcd = MathController::gcd($x, $y);
                return "(" . ($x / $gcd) . "/" . ($y / $gcd) . ")";
             }, $z, [], $level, $aeg, null, $random);

             return $returnData["array"];
        }
    }

    public function kujundid($level, $tüüp, $aeg, $random=false){
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
    
        }while($random ? $count < 1 : $count < MathController::OPERATION_COUNT + ($aeg*14));

        return $array;
    }


    // type = klassidevaheline/kõik kõikide vastu, klass = raskusaste vastavalt vanusegrupile
    public function võistlus($type, $klass) { 
        $count = 0;
        do{

            $tase = 1;  
            $AEG = 40; //sek
            $count++;
        }while($count < 3);
       
        
        
    }

    public function teisendamine($level, $mis, $tüüp, $aeg) {



    }





    //["liitmine"=>["level"=>2, "tüüp"=>naturaalarvud], "lahutamine"=>5]
    public function random($tehted, $aeg, $level, $tüüp){
        //$count=0;
        //do{
        $suvaline = array_rand($tehted);
        $ans = $this->wrapper($tehted[$suvaline], $tehted[$suvaline][$level], $tehted[$suvaline][$tüüp], $aeg);
        //$count++;
        //}while($count < MathController::OPERATION_COUNT + ($aeg*14));
        return $ans;
        
    }

    public function bots($level, $tehe, $tasemed, $tüüp, $aeg){
        $raskus = [
            "300" => [function ($min, $kadu){
                $min = 0.1;
                $kadu = random_int(1, 15)/100;
                $kiirus = 10;
                return array($min, $kadu, $kiirus);
            }],
            //...

        ];
        $this->wrapper($tehe, $tasemed, $tüüp, $aeg);

        $returnData = MathController::generateOp($raskus[$level], [], MathController::BOTS, function($kadu, $min, $botcheck){
            $accuracy = 1 - ($botcheck > $min ? $kadu : 0);
            $botcheck = $accuracy;
            return $accuracy;
        }, [], [],  $level, $aeg, null);

        return $returnData["array"];
    }






    public function wrapper($tehe, $tasemed, $tüüp, $aeg, $suvalisus=false, $competitions){
        $loend = [];
        $koik = $tasemed == [1, 2, 3, 4, 5];
        $types_without_all = ["lünkamine", "võrdlemine", "jaguvus", "murruTaandamine", "kujundid", "juurimine", "astejuurimine", "astendamine"];
        if ($koik && !in_array($tehe, $types_without_all)){

            // Funktsionaalseks (DRY)
            // See on copy paste ju
            if($tehe == MathController::LIITMINE || $tehe == MathController::LAHUTAMINE || $tehe == "liitlahutamine"){

                if($tehe == "liitlahutamine"){
                    $tehe = "mõlemad";
                }


                $loend[0] = $this->liitlah('all', $tehe, $tüüp, $aeg);
            }

            if($tehe == MathController::KORRUTAMINE || $tehe == MathController::JAGAMINE || $tehe == "korrujagamine"){

                if($tehe == "korrujagamine"){
                    $tehe = "mõlemad";
                }

                $loend[0] = $this->korjag("all", $tehe, $tüüp, $aeg);
            }

            if($tehe == MathController::ASTENDAMINE || $tehe == MathController::JUURIMINE|| $tehe == "astejuurimine"){
                $loend[0] = $this->astendamine("all", $tehe == 'astejuurimine' ? "mõlemad" : $tehe, $tüüp, $aeg);
            }

        }else{
            for ($lugeja = 0; $lugeja < count($tasemed); $lugeja ++){
                if($tehe == MathController::LIITMINE || $tehe == MathController::LAHUTAMINE || $tehe == "liitlahutamine"){   
                    $loend[$tasemed[$lugeja]] = $this->liitlah($tasemed[$lugeja], $tehe == "liitlahutamine" ? "mõlemad" : $tehe, $tüüp, $aeg, $competitions);
                }
    
                if($tehe == MathController::KORRUTAMINE || $tehe == MathController::JAGAMINE || $tehe == "korrujagamine"){    
                    $loend[$tasemed[$lugeja]] = $this->korjag($tasemed[$lugeja], $tehe == "korrujagamine" ? "mõlemad" : $tehe, $tüüp, $aeg, $competitions);
                }
    
                if($tehe == MathController::LÜNKAMINE){
                    $loend[$tasemed[$lugeja]] = $this->lünkamine($tasemed[$lugeja], $aeg, $competitions);
                }

                if($tehe == MathController::VÕRDLEMINE){
                    $loend[$tasemed[$lugeja]] = $this->võrdlemine($tasemed[$lugeja], $aeg, $competitions);
                }

                if($tehe == MathController::ASTENDAMINE || $tehe == MathController::JUURIMINE || $tehe == MathController::ASTEJUURIMINE){    
                    $loend[$tasemed[$lugeja]] = $this->astendamine($tasemed[$lugeja], $tehe == "astejuurimine" ? "mõlemad" : $tehe, $tüüp, $aeg, $competitions);
                }

                if($tehe == MathController::JAGUVUS){
                    $loend[$tasemed[$lugeja]] = $this->jagseadus($tasemed[$lugeja], $tüüp, $aeg);
                }

                if($tehe == MathController::MURRUTAANDAMINE){
                    $loend[$tasemed[$lugeja]] = $this->murruTaandamine($tasemed[$lugeja], "natural", $aeg);
                }

                if($tehe == MathController::KUJUNDID){
                    $loend[$tasemed[$lugeja]] = $this->kujundid($tasemed[$lugeja], $tüüp, $aeg);
                }
                
            }
        }
        
        if($suvalisus){
            for ($lugeja = 0; $lugeja < count($tehe['level']); $lugeja ++){
            $loend[$tasemed[$lugeja]] = $this->random($tasemed, $aeg);
            }
        }
        return $loend;
    }
}