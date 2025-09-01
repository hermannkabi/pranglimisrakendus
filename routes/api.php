<?php

use App\Models\Mang;
use App\Models\User;
use App\Models\Klass;
use App\Models\Competition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/stats', function () {
    $totalUsers = User::count();
    $totalGames = Mang::count();
    $totalPoints = Mang::sum("score_sum");
    $classCount = Klass::count();
    $competitionCount = Competition::count();
    return ["total_users"=>$totalUsers, "total_games"=>$totalGames, "total_points"=>$totalPoints, "class_count"=>$classCount, "competition_count"=>$competitionCount];
});