<?php

namespace App\Http\Controllers;

use App\Models\Song;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Playlist;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class MusicController extends Controller
{
    public function get(){

        $playlists = Playlist::where("owner", null)->orWhere("owner", Auth::id())->withCount("songs")->get();

        return Inertia::render("Music/MusicHomePage", ["playlists"=>$playlists]);
    }

    public function showPlaylist($id, $link_id){

        $playlist = Playlist::where("id", $id)->first();

        if(!$playlist){
            return abort(404);
        }

        return Inertia::render("Music/MusicPlaylistPage", ["playlist"=>$playlist, "songs"=>$playlist->songs]);
    }

    public function playlistAddSongs($id, $link_id){
        $playlist = Playlist::where("id", $id)->first();

        $songs = Song::whereDoesntHave('playlists', function ($query) use ($playlist) {
            $query->where('playlist_id', $playlist->id);
        })->get();

        return Inertia::render("Music/MusicPlaylistAddPage", ["playlist"=>$playlist, "songs"=>$songs]);
    }

    public function playlistAddSongsPost(Request $request, $id, $link_id){
        $playlist = Playlist::where("id", $id)->first();

        $request->validate([
            'songs' => 'required',
        ]);

        foreach(explode(";", $request->songs) as $id){
            $playlist->songs()->attach($id);
        }
    }

    public function playlistDelete(Request $request){
        $request->validate([
            'id' => 'required',
        ]);

        $playlist = Playlist::where("id", $request->id)->delete();
    }

    public function create(){
        Log::debug("message");
        $songs = Song::all();

        return Inertia::render("Music/MusicNew", ["songs"=>$songs]);
    }

    public function removeSong(Request $request){
        if(!str_contains($request->user()->role, "music-admin")){
            return abort(403);
        }

        $request->validate([
            'id' => 'required',
        ]);

        $song = Song::where("id", $request->id)->first();

        $path = 'public/music/'.$song->path.'.mp3';

        Storage::delete($path);

        $song->delete();

        return 200;
    }


    public function removeSongFrom(Request $request){
        $request->validate([
            'id' => 'required',
            'playlist' => 'required',
        ]);

        $playlist = Playlist::where("id", $request->playlist)->first();
        if($playlist){
            $playlist->songs()->detach($request->id);
        }


        return $playlist->name;
    }

    public function newSong(){
        return Inertia::render("Music/MusicNewSongPage", ["playlists"=>Playlist::all()]);
    }

    public function newSongPost(Request $request){
        $request->validate([
            'file' => 'required|file|mimes:mp3',
            'title' => 'required',
            'artist' => 'required',
        ]);

        $file = $request->file('file');

        // Generate a unique custom name (e.g., song-123456.mp3)
        $customName = 'song-' . Str::random(10);

        // Store in 'storage/app/public/music/'
        $path = $file->storeAs('music', $customName . '.' . $file->getClientOriginalExtension(), 'public');

        $song = Song::create([
            'title'=>$request->title,
            'artist'=>$request->artist,
            'path'=>$customName,
        ]);

        if($request->playlists){
            $ids = explode(";", $request->playlists);

            foreach($ids as $id){
                $playlist = Playlist::where("id", $id);
                if($playlist){
                    $playlist->songs()->attach($song->id);
                }
            }
        }

        return asset('storage/' . $path);
    }

    public function makeUrlSafe($text) {
        // Convert to lowercase
        $text = strtolower($text);

        $replacements = [
            'ö' => 'o',
            'õ' => 'o',
            'ä' => 'a',
            'ü' => 'u',
        ];
    
        // Replace the special characters first
        $text = strtr($text, $replacements);
    
        // Replace spaces and underscores with hyphens
        $text = preg_replace('/[\s_]+/', '-', $text);
    
        // Remove all non-alphanumeric characters except hyphens
        $text = preg_replace('/[^a-z0-9-]/', '', $text);
    
        // Remove multiple hyphens
        $text = preg_replace('/-+/', '-', $text);
    
        // Trim hyphens from the beginning and end
        return trim($text, '-');
    }

    public function newPlaylistPost(Request $request){
        $request->validate([
            'image' => 'required|image',
            'name' => 'required',
            'songs' => 'required',
        ]);

        $file = $request->file('image');

        // Generate a unique custom name (e.g., song-123456.mp3)
        $customName = 'playlistcover-' . Str::random(10);

        // Store in 'storage/app/public/music/'
        $path = $file->storeAs('music/playlists', $customName . '.' . $file->getClientOriginalExtension(), 'public');

        $playlist = Playlist::create([
            'name'=>$request->name,
            'thumbnail'=>asset("storage/". $path),
            'owner'=>Auth::id(),
            'link_id'=>$this->makeUrlSafe($request->name),
        ]);

        $ids = explode(";", $request->songs);

        foreach($ids as $id){
            $playlist->songs()->attach($id);
        }

        return asset('storage/' . $path);
    }
}
