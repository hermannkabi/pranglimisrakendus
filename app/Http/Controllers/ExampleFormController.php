<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;

class ExampleFormController extends Controller
{
    public function handleForm(Request $request){
        $name = $request->name;
        $message = $request->message;

        return Inertia::render("Welcome/WelcomePage", ['name'=>$name, 'message'=>$message]);
    }
}
