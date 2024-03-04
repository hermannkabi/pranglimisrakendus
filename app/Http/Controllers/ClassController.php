<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Klass;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $search)
    {
      
        $tabel =  DB::table('klass')->orderBy($search==null ? 'klass_name' : $search)->take(25);
        
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
            $kasutaja -> joined_klass = $klass_name;
            $kasutaja -> teacher = Klass::find($klass_name) -> get('teacher');
            $kasutaja->save();
            return redirect()->route('classroom/{id}');
        }else{
            return Inertia::render('ClassroomSearch', 'Vale parool.');
        }
        
    }

    public function remove($id, $class) {
        $klass = Klass::find($class);
        $user = User::find($id);
        $klass -> array_pop($klass->student_list, $user->eesnimi . ' ' . $user->perenimi);//võimalik veakoht
        $klass -> save();
        $user -> joined_klass = 'None';
        $user -> teacher = 'None';
        $user -> save();
        return Inertia::render('ClassroomPage', 'Õpilane ' .  $user->eesnimi . ' ' . $user->perenimi . ' ' . 'on edukalt eemaldatud teie klassist.');
        //Viimane rida ülearune - sama funktsiooni saaks kasutada kasutaja eemaldamiseks klassist, kui konto kustub
    }
    /**
     * Show the form for creating a new resource.
     */
    public function createKlass($klass_name, $student_list, $klass_password, $teacher)
    {
        return Klass::create([
            'klass_id' => (string)Str::uuid(),
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
            'klass_name' => 'required|string|max:256',
            'student_list' => 'required|string|max:1000',
            'klass_password' => 'required|string|min:8',
            'teacher' => 'required|string|max:37',
        ]);

        $this->createKlass($request->klass_name, $request->student_list, $request->klass_password, $request->teacher);
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
    public function update(Request $request, string $id)
    {
       //
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
