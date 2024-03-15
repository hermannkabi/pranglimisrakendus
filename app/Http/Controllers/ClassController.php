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
    /**
     * Show the form for creating a new resource.
     */
    public function createKlass($klass_name, $klass_password)
    {
        return Klass::create([
            'klass_id' => (string)Str::uuid(),
            'klass_name' => $klass_name,
            'klass_password' => $klass_password,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'klass_name' => 'required|string|max:256',
            'klass_password' => 'required|string|min:4',
        ]);

        $this->createKlass($request->klass_name, $request->klass_password);

        return Inertia::render('Dashboard/DashboardPage');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $klass_id=null)
    {
        $klass = null;

        if($klass_id==null){
            $klass = Klass::where("klass_id", Auth::user()->klass)->first();
        }else{
            $klass = Klass::where('klass_id', $klass_id)->first();
        }

        // $users = User::where('klass', $klass->klass_id)->where('role', 'student')->get();    
        $leaderboard = app('App\Http\Controllers\LeaderboardController')->getLeaderboardData(User::where("klass", $klass->klass_id)->where("role", "student")->get());
        $teacher = User::where('klass', $klass->klass_id)->where('role', 'teacher')->first(); 
        $stats = $this->overallClassStats($klass->klass_id);  
        return Inertia::render("Classroom/ClassroomPage", ['leaderboard'=>$leaderboard, 'teacher'=>$teacher, "className"=>$klass->klass_name, "stats"=>$stats]);
    }

    /**
     * Show the form for editing the specified resource.
     */
     public function edit($id)
    {
    //     $item = Klass::find($id);
    //     return Inertia::render('ClassroomEditPage', $item);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       //
    }

    public function overallClassStats($klass_id){
        // Total no games
        $total_game_count = 0;
        // Total no points
        $total_points_count = 0;

        $studentsInClass = User::select("id")->where("klass", $klass_id)->where("role", "student")->get();

        // Students count
        $students_count = count($studentsInClass);

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
                $stats = app('App\Http\Controllers\GameController')->getOverallStats($õpilane);

                //Speficied stats - gameMode


                
            }
            
            $students = User::where('klass', $klass)->where('role', 'student')->get();
            $mangud = DB::table('mangs')->where('user_id',$students->id)->orderBy("dt", "desc")->get();

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

        $class = Klass::where("klass_id", $request->klass_id)->where("klass_password", $request->klass_password)->first();

        if($class != null){
            $user = Auth::user();
            $user->klass = $request->klass_id;
            $user->save();

            return redirect()->route("dashboard");

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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $klass_name, $teacher, $all)
    {
        //Delete one class
        DB::table('klass')->where('klass_name',$klass_name)->get()->delete();

        //Delete all classes with that teacher
        if($all==notNullValue()){
            DB::table('klass')->where('teacher', $teacher)->get()->delete();
        }
    }
}
