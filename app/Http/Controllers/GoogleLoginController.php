<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Auth\LoginRegisterController;

/**
 * Google login system
 */
class GoogleLoginController extends Controller
{
    public function redirectToGoogle(){
        return Socialite::driver('google')
            ->with(["prompt" => "select_account"])
            ->redirect();
    }
    


    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();
        $user = User::where('email', $googleUser->email)->first();
        if(!$user)
        {
            if(!str_ends_with($googleUser->email, "real.edu.ee")){
                return redirect()->route("login")->withErrors(["Kasuta oma Reaalkooli e-posti aadressi"]);
            }

            $eesnimi = substr($googleUser->name, 0, strrpos($googleUser->name, " "));
            $perenimi = substr($googleUser->name, strrpos($googleUser->name, " ")+1);
            $user = app(LoginRegisterController::class)->createUser($googleUser->email, $eesnimi, $perenimi, null, $googleUser->id, null);
        
            Auth::login($user);

            return redirect()->route("dashboard");
    
        }

        if($user->google_id != $googleUser->id){
            $user->google_id = $googleUser->id;
            $user->markEmailAsVerified();
            $user->save();    
        }

        Auth::login($user, true);

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}        