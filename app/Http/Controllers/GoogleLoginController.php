<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Laravel\Socialite\Facades\Socialite;


class GoogleLoginController extends Controller
{
    public function redirectToGoogle(){
        return Socialite::driver('google')
            ->stateless() //oli soovitatud, võib olla hilisema probleemi põhjustaja
            ->redirect();
    }
    


    public function handleGoogleCallback()
    {
            $googleUser = Socialite::driver('google')->stateless()->user();
            $user = User::where('email', $googleUser->email)->first();
            if(!$user)
            {
                return redirect()->route("registerGoogle", ["email"=>$googleUser->email, "name"=>$googleUser->name, "googleId"=>$googleUser->id]);
            }

            if($user->google_id != $googleUser->id){
                $user->google_id = $googleUser->id;
                $user->save();    
            }
    
            Auth::login($user, true);
    
            return redirect()->intended(RouteServiceProvider::HOME);
    
        // }catch(\Throwable $e){
        //     return redirect()->route("login")->withErrors(["email"=>"Google'ga sisselogimine ebaõnnestus"]);
        // }
    }
}        