<?php

namespace App\Http\Controllers;

use DateTime;

use Illuminate\Http\Request; 
use App\Models\Mang;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Klass;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\File;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;


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
            'image' => 'required|file|max:500|mimes:jpeg,png,jpg,gif'
        ],
        [
            'image.max' => 'Pilt on liiga suur, maksimaalne suurus on 500kB',
            'image.mimes' => 'Pildi formaat peab olema üks järgnevatest: jpg, jpeg, png, gif',
        ]);
        $user = Auth::user();

        // Delete all existing profile images - for saving storage space 
        File::cleanDirectory(storage_path("app/public/profile-imgs/".$user->id));

        $path = "public/profile-imgs/" . $user->id;
        $file = $request->image;
        $returnPath = Storage::putFile($path, $file, "public");
        File::chmod(storage_path("app/public/profile-imgs/".$user->id), 0755);

        $user->profile_pic = "/storage/profile-imgs/".$user->id."/".basename($returnPath);
        /** @var \App\Models\User $user **/
        $user->save();
        return;
    }

    /**
     * Verify users streak (is used by Console\Kernel.php)
    */
    public static function checkStreak(){
        $user = User::all();

        foreach($user as $j){
            $viimaseManguDt = Mang::select("dt")->where("user_id", $j->id)->orderBy("dt", "desc")->first();
            
            if($viimaseManguDt == null){
                $j->streak = null;
            }else if(strtotime($viimaseManguDt["dt"])<(strtotime('-1 day'))){
                $j->streak = 0;
            }

            $j->streak_active = 0;
            $j->save();
        }
    }

    public function viewStreak($user_id){
        $user = User::where("id", $user_id)->first();

        if($user){
            $viimane = Mang::where("user_id", $user_id)->orderBy("dt", "DESC")->first();
            if(!$viimane) return 0;
            $viimaseDt = $viimane->dt;

            // Streak on aktiive, kui viimane mäng on tehtud täna
            $diff = abs(date_diff(new DateTime("today"), new DateTime(DateTime::createFromFormat("Y-m-d H:i:s", $viimaseDt)->format("Y-m-d")))->format("%a"));

            $streakActive = $diff == 0;
            $user->streak_active = $streakActive;
            $user->save();

            if($streakActive){
                return $user->streak;
            }else{
                // Kui viimane mäng ei ole tehtud täna, kontrolli, kas see on tehtud eile. Kui ei ole, siis on streak null
                if($diff == 1){
                    return $user->streak;
                }
                if($diff > 1){
                    $user->streak = 0;
                    $user->save();
                    return 0;
                }
            }


        }

        return 0;
    }

    // This function is called after completing a game and is used to set a streak for the player
    public function updateStreak($user_id){
        $user = User::where("id", $user_id)->first();

        if($user){
            $andmed = Mang::where("user_id", $user_id)->orderBy("dt", "DESC")->take(2)->get();
            $viimaseDt = count($andmed) > 0 ? $andmed[0]->dt : null;
            $eelviimaseDt = count($andmed) > 1 ? $andmed[1]->dt : null;

            // Viimase dt ei saa vist null olla, aga ikkagi
            if($eelviimaseDt == null || $viimaseDt == null){
                $user->streak_active = 1;
                $user->streak = 1;
                $user->save();
                return;
            }

            $diff = abs(date_diff(new DateTime(DateTime::createFromFormat("Y-m-d H:i:s", $eelviimaseDt)->format("Y-m-d")), new DateTime(DateTime::createFromFormat("Y-m-d H:i:s", $viimaseDt)->format("Y-m-d")))->format("%a"));

            // Streak on mitteaktiivne, aga ka mitteaegunud, kui selle läbib (ehk saab suurendada)
            if($diff == 1){
                $user->streak_active = 1;
                $user->streak += 1;
                $user->save();
            }else if($diff > 1){
                $user->streak_active = 0;
                // 1 is correct here because we just played a game so it cannot be 0
                $user->streak = 1;
                $user->save();
            }

            return;
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
        app(ClassController::class)->remove(Auth::id(), $user->id);
        app(GameController::class)->destroy(Auth::id());
        $user->delete();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/login');
    }

    /**
     * Display users current class 
     */
    public function show(){

        $user = Auth::user();
        $klass = null;

        if($user->klass){
            $klass = Klass::where("klass_id", $user->klass)->first()["klass_name"];
        }

        return Inertia::render("Profile/ProfilePage", ["className"=>$klass]);
    }

    public function showPublic(Request $request, $id){

        $user = User::where("id", $id)->first();

        // Can be assumed to be non-null
        $logged_in_user = Auth::user();

        $logged_in_klass = Klass::where("klass_id", $logged_in_user->klass)->first();


        $stats = null;
        $lastGames = null;

        $klass = null;

        if($user != null){
            // Kuna mulle siiski tundub, et lasta kõigil näha kasutaja andmeid on ebaõige (eriti nooremate laste puhul), siis teen niimoodi ümber, et näha saavad ainult klassikaaslased ja õpetaja
            
            $klass = Klass::where("klass_id", $user->klass)->first();

            // You can see your own public profile, no matter what
            if($user->id != $logged_in_user->id && !str_contains($logged_in_user, "admin")){
                if(str_contains($user->role, "teacher")){
                    // A teacher can be seen only by their students
                    if($logged_in_klass==null || $logged_in_klass->teacher_id != $user->id){
                        abort(403);
                    }
                }else if(str_contains($user->role, "student")){
                    // A student can be seen by their teacher or their classmates
                    if($klass == null || ($klass->teacher_id != $logged_in_user->id && $klass->klass_id != $logged_in_user->klass)){
                        abort(403);
                    }
                }else{
                    // Dont show guest account
                    abort(404);
                }
            }
            
            
            $stats = app(GameController::class)->getOverallStats($user->id);

            $lastGames = Mang::where('user_id', $user->id)->orderBy("dt", "desc")->take(3)->get();

        }else{
            abort(404);
        }

        $stats["streak"] = app(ProfileController::class)->viewStreak($id);
        $user = User::where("id", $id)->first();

        return Inertia::render("Profile/PublicProfilePage", ["user"=>$user, "stats"=>$stats, "lastGames"=>$lastGames, "klass"=>$klass]);
    }
}
