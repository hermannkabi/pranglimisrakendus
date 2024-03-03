<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Klass;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($search)
    {
      
        $tabel = $search==false ? DB::table('klass')->orderBy('klass_name') : DB::table('klass')->orderBy($search);
        
        return Inertia::render("ClassroomSearch", $tabel);
    }

    /**
     * Join function for connecting with a classroom.
     */
    public function join(string $klass_name, $klass_password, $id) {
        $klasspwd = Klass::find($klass_name)->get('klass_password');
        if($klass_password==$klasspwd){
            $klass = Klass::find($klass_name);
            array_push($klass -> student_list,$id ->eesnimi . ' ' . $id->perenimi);
            $klass->save();
            $kasutaja = User::find($id);
            $kasutaja -> klass = $klass_name;
            $kasutaja -> teacher = Klass::find($klass_name) -> get('teacher');
            $kasutaja->save();
            return redirect()->route('classroom/{id}');
        }else{
            return Inertia::render('ClassroomSearch', 'Vale parool.');
        }
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createKlass($klass_id, $klass_name, $student_list, $klass_password, $teacher)
    {
        return Klass::create([
            'klass_id' => $klass_id,
            'klass_name' => $klass_name,
            'student_list' => $student_list,
            'klass_password' => $klass_password,
            'teacher' => $teacher,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'klass_id' => 'required|string|max:37',
            'klass_name' => 'required|string|max:256',
            'student_list' => 'required|string|max:1000',
            'klass_password' => 'required|string|min:8',
            'teacher' => 'required|string|max:37',
        ]);

        $this->createKlass($request->klass_id, $request->klass_name, $request->student_list, $request->klass_password, $request->teacher);
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
        $list = Klass::where('klass_id', $klass_id)->get('student_list');
        return Inertia::render('ClassroomPage', $list);
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
    public function update(Request $request, string $id, $add)
    {
        // $klass = Klass::find($id);
        // if($add){
        //     $klass->student_list=$request->input('name');

        // }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $klass_name, $user_id, $all)
    {
        DB::table('klass')->where('klass_name',$klass_name)->get()->delete();

        if($all){
            DB::table('klass')->select($user_id)->get()->delete();
        }
    }
}
