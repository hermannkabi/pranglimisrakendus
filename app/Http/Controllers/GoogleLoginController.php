<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;


class GoogleLoginController extends Controller
{
    public function redirectToGoogle(){
        return Socialite::driver('google')
            ->stateless() //oli soovitatud, võib olla hilisema probleemi põhjustaja
            ->redirect();
    }
    


    public function handleGoogleCallback()
    {
        try{
            $googleUser = Socialite::driver('google')->stateless()->user();
            $user = User::where('email', $googleUser->email)->first();
            if(!$user)
            {
                // Praegu saadab tavalisse registeri, teen mingi hetk ümber -Hermann
                return redirect()->route("register", ["email"=>$googleUser->email, "name"=>$googleUser->name]);
            }
    
            Auth::login($user);
    
            return redirect()->intended(RouteServiceProvider::HOME);
    
        }catch(\Throwable $e){
            return redirect()->route("login")->withErrors(["email"=>"Google'ga sisselogimine ebaõnnestus"]);
        }
    }
}        