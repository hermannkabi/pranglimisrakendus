<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Klass;
use App\Models\User;

class AdminController extends Controller
{
    public function adminShow()
    {
        $data = array();

        foreach(Klass::orderBy("klass_name")->get() as $klass){
            array_push($data, ["name"=>$klass->klass_name, "klass"=>$klass, "teacher"=>User::where("id", $klass->teacher_id)->first(), "students"=>User::where("klass", $klass->klass_id)->where("role", "like", "%student%")->orderBy("perenimi", "asc")->get()]);
        }
        
        return Inertia::render("Admin/AdminPage", ['data'=>$data]);
    }
}
