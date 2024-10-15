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

        foreach(Klass::all() as $klass){
            array_push($data, ["name"=>$klass->klass_name, "teacher"=>User::where("id", $klass->teacher_id)->first(), "students"=>User::where("klass", $klass->id)->where("role", "like", "%student%")->get()]);
        }
        
        return Inertia::render("Welcome/WelcomePage");
    }
}
