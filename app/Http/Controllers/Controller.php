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

        //Muliplier for smoother transitions \\\ NEEDS WORK
        function multyplier($var1 , $var2, $mult, $Max){
            $var2 = round($var2 * $mult);
            $var1 = round($var1 * $mult);
            $mult =+ $mult ** 2;
            if(($var2+$var1) > $Max){
                $var2+$var1 = 0.7 * ($var2+$var1);
            }
        }
        function Multiplier($var1, $var2, $varmin, $varmax, $tase){
            $full = 0;
            if ($full >= 1){
                $full = 0;
            }
            $tasemearv = 5;
            if ($tase > 1 && $tase < 4){
                $tasemearv = 4;
            }
            if ($tase == 4){
                $tasemearv = 3;
            }

            $varmin1 = (1.2 + $full) * ($varmax - $varmin);
            $var1 = random_int($varmin + $varmin * $full,$varmin1);
            if ($var1 >= 0.8 * $varmax){
                $var2 = $var1 -  random_int(1,$varmin1);
            }
            if ($var1 <= 1.2 * $varmin){
                $var2 = $var1 +  random_int(1, $varmin1);
            }
            $var2 = (1 +- 0.2) * $var1;
            $full += ($varmax - $varmin) / $tasemearv;
            $full += 1 / $tasemearv;
            
        }
        //Test
        function mult($var1, $var2, $tase, $tasemearv){
            $suurus = 10;
            $algsuurus = 0 + $var1;
            if ($tase == 2){
                $suurus = 20;
            }
            if ($tase == 3){
                $suurus = 100;
                
            }
            if ($tase == 4){
                $suurus = 1000;
            }
            $var1 += 2 * ($suurus - $algsuurus * $tasemearv) / pow($tasemearv, 2);
            $var2 = $var1 +- 0.1*$suurus;
            $tasemearv =- 1;
            return $var1 && $var2 && $tasemearv;
        }
        function MULTI($tase, $tasemearv){
            $suurus = 10;
            global $xkor;
            global $ykor;
            $algsuurus = 0 + $xkor;
            if ($tase == 2){
                $suurus = 20;
            }
            if ($tase == 3){
                $suurus = 100;
                
            }
            if ($tase == 4){
                $suurus = 1000;
            }
            $xkor += 2 * round($suurus - $algsuurus * $tasemearv) / pow($tasemearv, 2);
            $ykor = $xkor+- 0.1*$suurus;
            $tasemearv =- 1;
        }

        $loendliit = [];
        $loendkor = [];
        $loendjag = [];
        $loendlah = [];
        $loendlünk = [];
        $pop = 0;

    
        // muuda ajale tundlikuks $operation_count ja $pop ($aeg * $pop)
        $operation_count = 15;
        
        do {

            $tase = 1;

            //liitmine
            if ($tehe === 'liitmine') {
                $xliit= random_int(0,9);     
                $yliit= random_int(1,10);
            }
            
            
            //korrutamine

            if ($tehe === 'korrutamine') {
                $xkor = random_int(0,9);
                $ykor = random_int(1,10);
                
            }
            

            //tehe
            if ($tehe === 'lahutamine') {
                $xlah = random_int(0,9);
                $ylah = random_int(1,10);
            }
            

            //jagamine, lisa korrutamise tehe ja pööra see ümber, muutes selle täisarvuliseks jagamistehteks
            if ($tehe === 'jagamine') {
                $xjag = random_int(0,9);
                $yjag = random_int(1,10);
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
                if ($tehe === 'liitmine'){
                    multyplier($xliit, $yliit, 0.5, 10);
                }
                if ($tehe === 'korrutamine'){
                    mult($xkor, $ykor, $tase, 5);
                }
                if ($tehe === 'lahutamine'){
                    $xlah= random_int(1, 10);
                    $ylah= random_int(1, 10);
                    multyplier($xlah, $ylah, 0.5, 10);

                }
                if ($tehe === 'jagamine'){
                    $xjag= random_int(0, 9);
                    $yjag= random_int(1, 10);
                    multyplier($xjag, $yjag, 0.2, 10);
                }
                }
            if($pop >= 5){
                $tase = 2;
                if ($tehe === 'liitmine'){
                    $xliit= random_int(10, 99);
                    $yliit= random_int(11, 100);
                    multyplier($xliit, $yliit, 0.5, 100);
                }
                if ($tehe === 'korrutamine'){
                    $xkor= random_int(10, 20);
                    $ykor= random_int(10, 20);
                    mult($xkor, $ykor, $tase, 4);
                }
                if ($tehe === 'lahutamine'){
                    $xlah= random_int(10, 99);
                    $ylah= random_int(11, 100);
                    multyplier($xlah, $ylah, 0.5, 100);

                }
                if ($tehe === 'jagamine'){
                    $xjag= random_int(10, 99);
                    $yjag= random_int(11, 100);
                    multyplier($xjag, $yjag, 0.2, 100);
                }
            }
            if($pop >= 9){
                $tase = 3;

                if ($tehe === 'liitmine'){
                    $xliit= random_int(100, 999);
                    $yliit= random_int(101, 1000);
                    multyplier($xliit, $yliit, 0.5, 1000);
                }
                if ($tehe === 'korrutamine'){
                    $xkor= random_int(20, 100);
                    $ykor= random_int(20, 100);
                    mult($xkor, $ykor, $tase, 3);
                }
                if ($tehe === 'lahutamine'){
                    $xlah= random_int(100, 999);
                    $ylah= random_int(101, 1000);
                    multyplier($xlah, $ylah, 0.5, 1000);

                }
                if ($tehe === 'jagamine'){
                    $xjag= random_int(100, 999);
                    $yjag= random_int(101, 1000);
                    multyplier($xjag, $yjag, 0.2, 1000);
                }
                }
            if($pop >= 12){
                $tase = 4;

                if ($tehe === 'liitmine'){
                    $xliit= random_int(1000, 9999);
                    $yliit= random_int(1001, 10000);
                    multyplier($xliit, $yliit, 0.5, 10000);
                }
                if ($tehe === 'korrutamine'){
                    $xkor= random_int(101, 999);
                    $ykor= random_int(101, 999);
                    Multiplier($xkor, $ykor, 100, 1000, 4);
                }
                if ($tehe === 'lahutamine'){
                    $xlah= random_int(1000, 9999);
                    $ylah= random_int(1001, 10000);
                    multyplier($xlah, $ylah, 0.5, 10000);

                }
                if ($tehe === 'jagamine'){
                    $xjag= random_int(1000, 9999);
                    $yjag= random_int(1001, 10000);
                    multyplier($xjag, $yjag, 0.2, 10000);
                }
                


                }
            if ($pop == 15){

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

