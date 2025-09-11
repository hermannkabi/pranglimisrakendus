<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Klass;
use App\Models\Competition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function adminShow()
    {
        $data = array();

        foreach(Klass::orderBy("klass_name")->get() as $klass){
            array_push($data, ["name"=>$klass->klass_name, "klass"=>$klass, "teacher"=>User::where("id", $klass->teacher_id)->first(), "students"=>User::where("klass", $klass->klass_id)->where("role", "like", "%student%")->orderBy("perenimi", "asc")->get()]);
        }

        $classless = User::where("role", "like", "%student%")->where("klass", null)->orderBy("perenimi", "ASC")->get();
        
        return Inertia::render("Admin/AdminPage", ['data'=>$data, 'students'=>$classless]);
    }


    public function manageCompetitions(Request $request){
        $now = Carbon::now();

        $activeCompetitions = Competition::withCount("participants")->where('dt_start', '<', $now)->where('dt_end', '>', $now)->when(!str_contains($request->user()->role, "admin"), function ($query) use ($request) {$query->where('created_by', $request->user()->id);})->orderBy("dt_end", "ASC")->get();

        $futureCompetitions = Competition::withCount("participants")->where('dt_start', '>', $now)->when(!str_contains($request->user()->role, "admin"), function ($query) use ($request) {$query->where('created_by', $request->user()->id);})->orderBy("dt_start", "ASC")->get();

        $pastCompetitions = Competition::withCount("participants")->where('dt_end', '<', $now)->when(!str_contains($request->user()->role, "admin"), function ($query) use ($request) {$query->where('created_by', $request->user()->id);})->orderBy("dt_end", "DESC")->get();

        return Inertia::render("ManageCompetitions/ManageCompetitionsPage", ["past"=>$pastCompetitions, "present"=>$activeCompetitions, "future"=>$futureCompetitions]);
    }

    public function competitionNew($competition_id=null){
        $competition = $competition_id == null ? null : Competition::findOrFail($competition_id);
        return Inertia::render("NewCompetition/NewCompetitionPage", ["competition"=>$competition]);
    }

    public function competitionDelete(Request $request){
        $competition = Competition::findOrFail($request->competition_id);

        $competition->participants()->detach();

        $competition->delete();

        return 1;
    }

    public function competitionAdd(Request $request){
        $competition = $request->competition_id == null ? null : Competition::findOrFail($request->competition_id);

        $data = $request->except(['competition_id', '_token']);

        if($competition){
            $competition->update($data);
        }else{
            $competition = Competition::create($data);
        }

        return $competition->competition_id;
    }

    public function addParticipants(Request $request, $id){
        $competition = Competition::findOrFail($id);

        $data = array();
        $classes = str_contains($request->user()->role, "admin") ? Klass::orderBy("klass_name")->get() : Klass::where("teacher_id", $request->user()->id)->get();

        foreach($classes as $klass){
            array_push($data, ["name"=>$klass->klass_name, "klass"=>$klass, "students"=>User::where("klass", $klass->klass_id)->where("role", "like", "%student%")->whereDoesntHave('competitions', function ($query) use ($competition) {
                $query->where('competitions.competition_id', $competition->competition_id);
            })->orderBy("perenimi", "asc")->get()]);
        }

        $usersWithoutClass = User::where("klass", null)->where("role", "!=", "guest")->whereDoesntHave('competitions', function ($query) use ($competition) {
            $query->where('competitions.competition_id', $competition->competition_id);
        })->orderBy("perenimi")->get();


        return Inertia::render("CompetitionAddParticipants/CompetitionAddParticipantsPage", ["data"=>$data, "others"=>$usersWithoutClass, "competitionId"=>$competition->competition_id]);
    }

    public function addParticipantsPost(Request $request, $id){
        $competition = Competition::findOrFail($id);

        $competition->participants()->syncWithoutDetaching($request->participants);

        return 1;
    }

    public function removeParticipantsPost(Request $request, $id){
        $competition = Competition::findOrFail($id);

        $competition->participants()->detach($request->participant);

        return 1;
    }
}
