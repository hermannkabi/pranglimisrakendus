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
        $googleUser = Socialite::driver('google')->stateless()->user();
        $user = User::where('email', $googleUser->email)->first();
        // TODO: Lisa vaheleht, kus küsib klassi || küsi seda dahsboardis
        if(!$user)
        {
            return redirect()->route("register", ["email"=>$googleUser->email, "name"=>$googleUser->name]);
        }

        Auth::login($user);

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}        