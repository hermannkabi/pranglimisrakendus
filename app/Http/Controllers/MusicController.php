<?php

namespace App\Http\Controllers;

use App\Models\Song;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Playlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class MusicController extends Controller
{
    public function get(){

        $playlists = Playlist::withCount("songs")->get();


        return Inertia::render("Music/MusicHomePage", ["playlists"=>$playlists]);
    }

    public function showPlaylist($link_id){

        $playlist = Playlist::where("link_id", $link_id)->first();

        if(!$playlist){
            return abort(404);
        }

        return $playlist->name;
    }
}
