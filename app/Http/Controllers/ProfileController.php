<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

use App\Models\User;
use App\Models\Klass;
use App\Models\Mang;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }
    /**
     * User cookies for saving information regarding website theme colour and personal preferences.
     */
    public function settings(Request $request){
        $user = $request->user();
        
        $user->settings = $request->settings;
        $user->save();

        return;
    }
    /**
     * Changes avatar to the one suggested by the user.
     */
    public function changeProfilePicture(Request $request){

        $request->validate([
            'image' => 'required|file|max:1000|mimes:jpeg,png,jpg'
        ],
        [
            'image.max' => 'Pilt on liiga suur'
        ]);

        $user = Auth::user();
        $user->profile_pic = $request->file("image");
        $user->save();
        return;
    }

    /**
     * Verify users streak (is used by Console\Kernel.php)
    */
    public function checkStreak($user_id=null){
        $user = $user_id != null ? User::where("id", $user_id)->get() : User::all();

        foreach($user as $j){
            $viimaseManguDt = Mang::select("dt")->where("user_id", $j->id)->orderBy("dt", "desc")->first();
            if($viimaseManguDt){
                if(strtotime($viimaseManguDt["dt"])<(strtotime('now')-86400)){
                    $j->streak = 0;
                }else if($j->streak_active == 0){
                    $j->streak ++;
                    $j->streak_active = 1;
                }
                $j->save();
            }
        }
    }
    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();
        app(ClassController::class)->remove(Auth::id(), true);
        app(GameController::class)->destroy(Auth::id());
        $user->delete();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/login');
    }

    /**
     * Display users qurrent class 
     */
    public function show(){

        $user = Auth::user();
        $klass = null;

        if($user->klass){
            $klass = Klass::where("klass_id", $user->klass)->first()["klass_name"];
        }

        return Inertia::render("Profile/ProfilePage", ["className"=>$klass]);
    }
}
