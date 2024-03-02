<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use Laravel\Socialite\Facades\Socialite;


class GoogleLoginController extends Controller
{
    public function redirectToGoogle(){
        return Socialite::driver('google')
            ->Socialite::stateless() //oli soovitatud, võib olla hilisema probleemi põhjustaja
            ->redirect();
    }
    


    public function handleGoogleCallback()
    {
            $googleUser = Socialite::driver('google')->Socialite::stateless()->user();
            $user = User::where('email', $googleUser->email)->first();
            if(!$user)
            {
                // Praegu saadab tavalisse registeri, teen mingi hetk ümber -Hermann
                return redirect()->route("registerGoogle", ["email"=>$googleUser->email, "name"=>$googleUser->name, "googleId"=>$googleUser->id]);
            }

            if($user->google_id != $googleUser->id){
                $user->google_id = $googleUser->id;
                $user->save();    
            }
    
            Auth::login($user);
    
            return redirect()->intended(RouteServiceProvider::HOME);
    
        // }catch(\Throwable $e){
        //     return redirect()->route("login")->withErrors(["email"=>"Google'ga sisselogimine ebaõnnestus"]);
        // }
    }
}        