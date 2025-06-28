<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Mang;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Competition;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CompetitionController extends Controller
{
    public function view(Request $request, $id){

        $competition = Competition::with(["participants"=> function ($query){$query->orderBy("perenimi");}])->findOrFail($id);
        
        if(count(array_intersect(["teacher", "admin"], explode(",", $request->user()->role))) <= 0 && !$competition->participants()->where('user_id', Auth::id())->exists() && !$competition->public){
            return abort(403);
        }
        
        $attemptsLeft = $competition->attempt_count == 0 ? -1 : $competition->attempt_count - Mang::where("user_id", Auth::id())->where("competition_id", $id)->count();

        $totalGames = Mang::where("competition_id", $id)->count();
        $maxScore = Mang::where("competition_id", $id)->max("experience");
        $gamesToday = Mang::whereDate('dt', Carbon::today())->where("competition_id", $id)->count();

        return Inertia::render("Competition/CompetitionPage", ["competition"=>$competition, "leaderboard"=>$this->getLeaderboard($id, Auth::id()), "participants"=>$competition->participants, "attemptsLeft"=>$attemptsLeft, "totalGames"=>$totalGames, "maxScore"=>$maxScore, "gamesToday"=>$gamesToday]);
    }

    public function getLeaderboard($id, $user){

        $competition = Competition::findOrFail($id);

        $participants = DB::table('competition_user')
            ->leftJoin('mangs', function ($join) use ($id) {
                $join->on('competition_user.user_id', '=', 'mangs.user_id')
                    ->where('mangs.competition_id', '=', $id);
            })
            ->where('competition_user.competition_id', $id)
            ->select(
                'competition_user.user_id',
                DB::raw($competition->attempt_count == 0 ? 'MAX(mangs.experience) as total_score' : 'SUM(mangs.experience) as total_score')
            )
            ->groupBy('competition_user.user_id');

        $leaderboard = DB::table(DB::raw("({$participants->toSql()}) as ranked"))
            ->mergeBindings($participants)
            ->select(
                'user_id',
                'total_score',
                DB::raw('RANK() OVER (ORDER BY (total_score IS NULL),total_score DESC) as rank')
            )
            ->orderBy('rank')
            ->get();

        $rankCounts = [];
        foreach ($leaderboard as $entry) {
            $rankCounts[$entry->rank] = ($rankCounts[$entry->rank] ?? 0) + 1;
        }

        $userIds = $leaderboard->pluck('user_id')->unique()->toArray();

        $users = User::whereIn('id', $userIds)->get()->keyBy('id');

        $leaderboardWithUsers = $leaderboard->map(function ($entry) use ($users, $rankCounts) {
            $prefix = $rankCounts[$entry->rank] > 1 ? 'T' : '';
            
            return [
                'user' => $users->get($entry->user_id),
                'total_score' => $entry->total_score,
                'rank' => $entry->rank,
                'rank_label' => $prefix . $entry->rank,
            ];
        });

        $grouped = $leaderboardWithUsers->groupBy('rank');

        $sorted = $grouped->map(function (Collection $group, $rank) {
            if ($group->count() > 1) {
                return $group->sortBy(function ($entry) {
                    return $entry['user']->perenimi;
                })->values();
            }
            return $group;
        });

        $finalLeaderboard = $sorted->flatten(1);

        return $finalLeaderboard;
    }

    public function getBestRank($user_id){
        return DB::selectOne("
            WITH user_scores AS (
                SELECT
                    cu.competition_id,
                    cu.user_id,
                    CASE
                        WHEN c.attempt_count = 0 THEN MAX(m.experience)
                        ELSE SUM(m.experience)
                    END AS total_score
                FROM competition_user cu
                JOIN competitions c ON c.competition_id = cu.competition_id
                LEFT JOIN mangs m ON m.competition_id = cu.competition_id AND m.user_id = cu.user_id
                WHERE c.dt_end < NOW()
                GROUP BY cu.competition_id, cu.user_id, c.attempt_count
            ),
            competition_ranks AS (
                SELECT
                    competition_id,
                    user_id,
                    total_score,
                    RANK() OVER (PARTITION BY competition_id ORDER BY total_score DESC) AS rank
                FROM user_scores
            ),
            user_best_rank AS (
                SELECT
                    cr.rank,
                    cr.competition_id
                FROM competition_ranks cr
                WHERE cr.user_id = ?
                ORDER BY cr.rank ASC
                LIMIT 1
            )
            SELECT
                ubr.rank,
                ubr.competition_id,
                c.name AS competition_name,
                c.dt_end
            FROM user_best_rank ubr
            JOIN competitions c ON c.competition_id = ubr.competition_id
        ", [$user_id]);
    }

    public function getDashboardCompetition(){
        $now = Carbon::now();
        $user = Auth::user();

        $activeCompetition = $user->competitions()
        ->where('dt_start', '<', $now)
        ->where('dt_end', '>', $now)
        ->first();

        $competitionToShow = null;

        if ($activeCompetition) {
            $competitionToShow = $activeCompetition;
        } else {
            $competitionToShow = $user->competitions()
            ->where('dt_end', '<', $now)
            ->orderByRaw('ABS(TIMESTAMPDIFF(SECOND, dt_end, ?)) ASC', [$now])
            ->first();
        }

        $leaderboard = null;

        if($competitionToShow != null){
            Log::debug($competitionToShow);
            $leaderboard = $this->getLeaderboard($competitionToShow->competition_id, $user->id);
        }

        $userRank = null;

        if($leaderboard){
            $topThree = $leaderboard->take(3);

            $userEntry = $leaderboard->firstWhere('user.id', $user->id);
            $userRank = $userEntry ? $userEntry['rank_label'] : null;
        }

        
        $bestRank = $this->getBestRank($user->id);

        $attemptsLeft = $competitionToShow == null ? null : ($competitionToShow->attempt_count == 0 ? -1 : $competitionToShow->attempt_count - Mang::where("user_id", $user->id)->where("competition_id", $competitionToShow->competition_id)->count());


        return $competitionToShow == null ? null : ["competition"=>$competitionToShow, "attemptsLeft"=>$attemptsLeft, "bestRank"=>$bestRank, "nextCompetition"=>$user->competitions()->where("dt_start", ">", $now)->orderByRaw('ABS(TIMESTAMPDIFF(SECOND, dt_start, ?)) ASC', [$now])->first(), "totalCompetitions"=>$user->competitions->where('dt_end', '<', $now)->count(), "totalParticipants"=>$competitionToShow->participants->count(), "myPlace"=>$userRank, "threeBest"=>$topThree];
    }


    public function competitionsView(){
        // Get upcoming competitions for user and all public competitions

        $now = Carbon::now();

        $myCompetitions = Auth::user()->competitions()->withCount("participants")->where("dt_end", ">", $now)->selectRaw('CASE WHEN dt_start <= ? AND dt_end >= ? THEN true ELSE false END as active', [$now, $now])->orderByDesc("active")->orderBy("dt_start", "ASC")->get();

        $publicCompetitions = Competition::whereDoesntHave('participants', fn($q) => $q->where('user_id', Auth::id()))->withCount("participants")->where("public", true)->where("dt_end", ">", $now)->selectRaw('CASE WHEN dt_start <= ? AND dt_end >= ? THEN true ELSE false END as active', [$now, $now])->orderByDesc("active")->orderBy("dt_start", "ASC")->get();

        return Inertia::render("CompetitionsView/CompetitionsViewPage", ["myCompetitions"=>$myCompetitions, "publicCompetitions"=>$publicCompetitions]);
    }

    public function competitionRemoveSelf(Request $request, $id){
        $competition = Competition::findOrFail($id);

        $competition->participants()->detach($request->user());

        return 1;
    }

    public function competitionJoin($id, $user=null){
        $user = $user ?? Auth::user();
        Competition::findOrFail($id)->participants()->attach($user);
        return 1;
    }

    public function competitionHistory(?string $id=null){
        $user = User::where("id", $id ?? Auth::id())->first();
        $now = Carbon::now();

        if($user != Auth::user() && (in_array("student", explode(",", $user->role)) && count(array_intersect(["teacher", "admin"], explode(",", Auth::user()->role))) <= 0) && $user->klass != Auth::user()->klass){
            return abort(403);
        }

        $ranks = DB::table(DB::raw('(
            SELECT
                m.competition_id,
                m.user_id,
                CASE 
                    WHEN c.attempt_count = 0 THEN MAX(m.experience)
                    ELSE SUM(m.experience)
                END AS total_score,
                RANK() OVER (
                    PARTITION BY m.competition_id
                    ORDER BY 
                        CASE 
                            WHEN c.attempt_count = 0 THEN MAX(m.experience)
                            ELSE SUM(m.experience)
                        END DESC
                ) AS rank
            FROM mangs m
            INNER JOIN competitions c ON c.competition_id = m.competition_id
            WHERE c.dt_end < ?
            GROUP BY m.competition_id, m.user_id, c.attempt_count
        ) as ranked'))
        ->setBindings([$now])
        ->get();

        $tiedRanks = [];
        foreach ($ranks as $r) {
            $key = $r->competition_id . '-' . $r->rank;
            $tiedRanks[$key][] = $r->user_id;
        }

        $userRanks = $ranks->where('user_id', $user->id)->keyBy('competition_id');

        $pastCompetitions = $user->competitions()
            ->where('dt_end', '<', $now)
            ->paginate(10);

        $pastCompetitions->getCollection()->each(function ($competition) use ($userRanks, $tiedRanks) {
            $rankData = $userRanks[$competition->competition_id] ?? null;
            if (!$rankData) {
                $competition->rank_label = null;
                return;
            }

            $key = $competition->competition_id . '-' . $rankData->rank;
            $isTied = isset($tiedRanks[$key]) && count($tiedRanks[$key]) > 1;

            $competition->rank_label = $isTied ? 'T' . $rankData->rank : (string)$rankData->rank;
        });

        $competitionGames = Mang::where("user_id", $user->id)->where("competition_id", "!=", null)->get();
        $competitionPoints = Mang::where("user_id", $user->id)->where("competition_id", "!=", null)->sum("experience");

        $stats = ["competitionCount"=>count($pastCompetitions), "bestRank"=>$this->getBestRank($user->id), "gamesCount"=>count($competitionGames), "competitionPoints"=>$competitionPoints];

        return Inertia::render("CompetitionHistory/CompetitionHistoryPage", ["stats"=>$stats, "competitions"=>$pastCompetitions]);
    }
}
