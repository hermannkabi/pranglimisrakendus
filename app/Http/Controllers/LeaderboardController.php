<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use App\Models\Mang;
use Illuminate\Support\Facades\DB;

/**
 * Get and send data for displaying on the leaderboard
*/
class LeaderboardController extends Controller
{
    public function getLeaderboardData($users)
    {
        $userIds = $users->pluck('id');

// SQL: get XP, rank (descending), and last played date
$leaderboardRaw = DB::table('users')
    ->selectRaw('
        users.id as user_id,
        COALESCE(SUM(mangs.experience), 0) as xp,
        MAX(mangs.dt) as last_played,
        RANK() OVER (ORDER BY COALESCE(SUM(mangs.experience), 0) DESC) as rank
    ')
    ->leftJoin('mangs', 'users.id', '=', 'mangs.user_id')
    ->whereIn('users.id', $userIds)
    ->groupBy('users.id')
    ->get();

// Group entries by rank
$rankGroups = collect($leaderboardRaw)->groupBy('rank')->all();

// Sort rank groups by rank ASC (1 first), and within each group by perenimi
ksort($rankGroups); // rank 1, 2, 3...

$data = [];
foreach ($rankGroups as $rank => $entries) {
    $sorted = $entries->sort(function ($a, $b) use ($users) {
        $userA = $users->firstWhere('id', $a->user_id);
        $userB = $users->firstWhere('id', $b->user_id);
        return strcmp($userA->perenimi, $userB->perenimi);
    });

    $isTied = $entries->count() > 1;
    foreach ($sorted as $entry) {
        $user = $users->firstWhere('id', $entry->user_id);
        $playedToday = $entry->last_played
            ? Carbon::parse($entry->last_played)->isToday()
            : false;

        $data[] = [
            'user' => $user,
            'xp' => $entry->xp,
            'place' => $isTied ? "T{$rank}" : (string)$rank,
            'playedToday' => $playedToday
        ];
    }
}

return $data;
    }
}
