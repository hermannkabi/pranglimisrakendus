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
        $teacher = Auth::user()->role == 'teacher';
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

    /**
     * Join function for connecting with a classroom.
     */
    public function join(string $klass_name, $klass_password) {
        //For later, when working with multiple schools
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
            'klass_password' => $klass_password == null ? null : Hash::make($klass_password),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'klass_name' => 'required|string|max:256',
            'klass_password' => 'required|string|min:8',
        ]);

        $this->createKlass($request->klass_name, $request->klass_password);
        $question = $request->input('Answer', 'Jah');
        if($question){
            $this->add();
        }
        $resources = $request->all();
        if($resources){
            return Inertia::render('ClassroomPage',$resources);
        }
        return Inertia::render('DashboardPage', 'Midagi läks valesti!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $klass_id)
    {
        $list = Klass::where('klass_id', $klass_id)->get();
        $users = User::where('klass', $list->klass_name)->where('role', 'student');
        $teacher = User::where('klass', $list->klass_name)->where('role', 'teacher');   
        return ['students'=>$users, 'teacher'=>$teacher];
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
