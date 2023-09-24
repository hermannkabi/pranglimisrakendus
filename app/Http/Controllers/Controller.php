<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
};

class ArrayController extends Controller
{

    public function array_Gen($x, $y){
        $x = random_int(0,9);     
        $y = random_int(1,10);
        $loend = [] ;
        $pop = 0 ;
    do {
        $loend[$x . '+' . $y] = $x + $y;
        $pop ++;
    }while($pop <=15);
        var_dump($loend);
    }
    
};

