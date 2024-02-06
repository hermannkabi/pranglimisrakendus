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
            $user = User::create(['name' => $googleUser->name, 'email' => $googleUser->email, 'password' => Hash::make(rand(100000,999999)), 'klass'=> '140.a', 'eesnimi' => 'Hermann', 'perenimi'=> 'Justus']);
        }

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}        