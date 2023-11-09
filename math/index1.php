<?php


//Muliplier for smoother transitions \\\ NEEDS WORK
function multyplier($var1 , $var2, $mult, $Max){
    $var2 = round($var2 * $mult);
    $var1 = round($var1 * $mult);
    $mult =+ 0.2;
    if(($var2+$var1) > $Max){
        $var2+$var1 = 0.7 * ($var2+$var1);
    };
    
}
