<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Klass;
use App\Models\User;
use App\Models\Mang;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use DateTime,DateInterval,DatePeriod;

use App\Http\Controllers\GameController;
use App\Http\Controllers\LeaderboardController;


class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $search)
    {
      
        $tabel =  DB::table('klass')->orderBy($search==null ? 'klass_name' : $search)->take(25);
        
        return $tabel;
    }

    /**
     * Join function for connecting with a classroom.
     */
    public function add(){
        $teacher = User::wehre('teacher',Auth::user()->role);
        if($teacher){
            $student = User::where('klass', Auth::user()->klass);
        }
        
        
        foreach($teacher as $õp){
            $student->joined_klass = Auth::user()->klass;
            $student->teacher = $õp;
            $student->save();
        }
        return;

    }

    public function remove(Request $request, $user) {
        if(!$user){
            $user = User::where('id',$request->input('Student', 'id'));
            $user -> klass = null;
            $user -> save();
            return [$user->eesnimi, $user->perenimi]; //For displaying which studend got removed
        } else {
            $mina = User::where('id', Auth::id());
            $mina ->klass = null;
            $mina->save();
        }

       
    }

    public function newClass(){
        return Inertia::render("NewClassroom/NewClassroomPage");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createKlass($klass_name, $klass_password)
    {
        return Klass::create([
            'klass_name' => $klass_name,
            'klass_password' => $klass_password,
            'teacher_id' => Auth::id(),
            'uuid' => (string)Str::uuid(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'klass_name' => 'required|string|max:256',
            'klass_password' => 'required|string|min:4|confirmed',
            'klass_password_confirmation' => 'required|string|min:4',
        
        ], [
            'klass_name.required'=>"Klassi nimi on kohustuslik väli",
            'klass_password.required'=>"Klassi parool on kohustuslik väli",
            'klass_password_confirmation.required'=>"Klassi parool on kohustuslik väli",
            'klass_password.confirmed'=>"Paroolid ei kattu",
            'klass_password.min'=>"Parool peab olema vähemalt 4 tähemärki pikk",
        ]);

        $klass = $this->createKlass($request->klass_name, $request->klass_password);

        return redirect("classroom/" . $klass->uuid . "/view");
    }

    /**
     * Display the specified resource.
     */

    public function show(Request $request, string $uuid=null)
    {
        $klass = null;

        if($uuid==null){
            $klass = Klass::where("klass_id", Auth::user()->klass)->first();
        }else{
            $klass = Klass::where('uuid', $uuid)->first();
        }

        if($klass == null) abort(404);

        // A student should only be able to view their own class
        if($request->user()->role == "student" && $request->user()->klass != $klass->klass_id) abort(403);

        // $users = User::where('klass', $klass->klass_id)->where('role', 'student')->get();    
        $leaderboard = app(LeaderboardController::class)->getLeaderboardData(User::where("klass", $klass->klass_id)->where("role", "student")->get());
        $teacher = User::where('id', $klass->teacher_id)->first(); 
        $stats = $this->overallClassStats($klass->klass_id);  
        $isTeacher = $teacher == null ? false : $request->user()->id == $teacher->id;
        return Inertia::render("Classroom/ClassroomPage", ['uuid'=>$uuid, 'isTeacher'=>$isTeacher, 'leaderboard'=>$leaderboard, 'teacher'=>$teacher, "className"=>$klass->klass_name, "stats"=>$stats]);
    }


    public function edit(Request $request, $id){
        $class = Klass::where("uuid", $id)->first();

        if($class == null){
            abort(404);
            return;
        }

        if($class->teacher_id != $request->user()->id){
            abort(404);
            return;
        }

        $request->validate([
            "klass_name"=>"required|string|max:255",
            "klass_password"=>"required|string|min:4",
        ], [
            "klass_name.required"=>"Klassi nimi on kohustuslik",
            "klass_password.required"=>"Klassi parool on kohustuslik",
            "klass_password.min"=>"Parool peab olema vähemalt 4 tähemärki",
        ]);

        if($request->removed_students != ""){
            $ids = explode(",", $request->removed_students);

            foreach($ids as $id){
                $this->classRemove($id);
            }
        }

        $class->klass_name = $request->klass_name;
        $class->klass_password = $request->klass_password;

        $class->save();

        return redirect("/classroom/".$class->uuid."/view");
    }

    /**
     * Show the form for editing the specified resource.
     */
     public function showEdit(Request $request, $id)
    {
        $class = Klass::where("uuid", $id)->first();

        if($class == null){
            abort(404);
            return;
        }


        if($class->teacher_id != $request->user()->id){
            abort(404);
            return;
        }

        $students = User::select(["eesnimi", "perenimi", "id"])->where("klass", $class->klass_id)->where("role", "student")->get();
        
        return Inertia::render("Classroom/ClassroomEdit", ["klass"=>$class, "students"=>$students]);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       //
    }

    public function overallClassStats($klass_id, $aeg=null /* Time filter for statistics */){
        // Total no games
        $total_game_count = 0;
        // Total no points
        $total_points_count = 0;
        // Points by timestamp
        $points_by_timestamp = [];

        $studentsInClass = User::select("id")->where("klass", $klass_id)->where("role", "student")->get();

        // Students count
        $students_count = count($studentsInClass);

        if($aeg != null){
            $time = $aeg=='week' ? 7 : ($aeg=='month' ? 30 : ($aeg =='year' ? 365 : 1));
            $begin = new DateTime(strtotime('now') - strtotime("-".$time." days"));
            $end = new DateTime('now');

            //Time interval, default value, which is meant for a weak of time, is 1 day
            $interval = DateInterval::createFromDateString($aeg=='month' ? '7 days' : ($aeg=='year' ? '73 days' : ($aeg=='day' ? '2 hours' : '1 day')));
            $period = new DatePeriod($begin, $interval, $end);

            
            $times = array();
            foreach ($period as $dt) {
                $date = $dt->format("Y-m-d H:i:s");
                array_push($times, $date);
            }
        }

        foreach($studentsInClass as $userId){
            $gamesByUser = Mang::where("user_id", $userId->id)->get();
            foreach($gamesByUser as $game){
                $total_points_count += $game->experience;
                $total_game_count ++;
            }
        }

        return ["students"=>$studentsInClass, "studentsCount"=>$students_count, "totalGameCount"=>$total_game_count, "totalPointsCount"=>$total_points_count];
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function classStats($klass, $õpilane /** Õpilase id */, $game, $game_type){
            if($õpilane!=null){
                
                
                //Overall stats of a student
                $stats = app(GameController::class)->getOverallStats($õpilane);

                //Speficied stats - gameMode


                
            }
            
            $students = User::where('klass', $klass)->where('role', 'student')->get();
            $mangud = Mang::where('user_id',$students->id)->orderBy("dt", "desc")->get();

            $accuracy_sum = 0;
            $experience = 0;

            $count = sizeof($mangud);
            foreach($mangud as $games){
                $accuracy_sum += $games->accuracy_sum;
                $experience += $games->experience;
            }
            $accuracy = $count > 0 ? round($accuracy_sum / $count) : 0;
            return [];
        

    }

    public function join(Request $request){
        $request->validate([
            'klass_id' => 'required|string|max:256',
            'klass_password' => 'required|string|min:4',
        ]);

        $class = Klass::where("klass_id", $request->klass_id)->first();

        if($class != null){
            if($class->klass_password==$request->klass_password){
                $user = Auth::user();

                if($user->role == "guest"){
                    return redirect()->back()->withErrors(["Külaliskontoga ei saa klassiga ühineda"]);
                }

                $user->klass = $request->klass_id;

                 /** @var \App\Models\User $user **/
                $user->save();
    
                return redirect()->route("dashboard");
    
            }else{
                return redirect()->back()->withErrors(["Parool ei ole õige"]);
            }

        }else{
            return redirect()->back()->withErrors(["Sellist klassi ei leitud!"]);
        }

    }

    public function showJoin(){

        $user = Auth::user();
        $klass = null;
        if($user->klass != null){
            $klass = Klass::where("klass_id", $user->klass)->first();
        }

        $classes = Klass::all();

        return Inertia::render("JoinClass/JoinClassPage", ["classData"=>$klass, "allClasses"=>$classes]);
    }

    public function joinLink(Request $request, $id){

        $klass = Klass::where("uuid", $id)->first();

        if($klass == null){
            abort(404);
            return;
        }

        $invited_by = User::select(["eesnimi", "perenimi"])->where("id", $klass->teacher_id)->first();

        $invited_by = $invited_by == null ? null : $invited_by->eesnimi . " " . $invited_by->perenimi;

        $current_klass = $request->user()->klass == null ? null : Klass::where("klass_id", $request->user()->klass)->first();


        if($current_klass != null && $current_klass->klass_id == $klass->klass_id){
            return redirect()->route("dashboard");
        }

        return Inertia::render("JoinClass/JoinLinkPage", ["klass"=>$klass, "invited_by"=>$invited_by, "current_klass"=>$current_klass]);
    }

    public function joinLinkPost(Request $request, $id){
        $klass = Klass::where("uuid", $id)->first();

        if($klass != null){
            $user = $request->user();

            $user->klass = $klass->klass_id;

            $user->save();

            return;
        }

        abort(404);
    }

    // Removes the currently authenticated user from their clas
    public function classRemove($id=null){
        $user = $id == null ? Auth::user() : User::where("id", $id)->first();

        if($user != null){
            $user->klass = null;
            
             /** @var \App\Models\User $user **/
            $user->save();

            return;
        }
        
        return;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        
        //Delete one class
        $klass = Klass::where('uuid', $id)->first();

        if($klass->teacher_id == $request->user()->id){
            User::where("klass", $klass->klass_id)->update(["klass"=>null]);
            $klass->delete();
        }else{
            abort(404);
        }

        return;

        // //Delete all classes with that teacher
        // if($all==notNullValue()){
        //     DB::table('klass')->where('teacher', $teacher)->get()->delete();
        // }
    }
}
