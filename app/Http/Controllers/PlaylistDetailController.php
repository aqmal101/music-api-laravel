<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use Illuminate\Http\Request;

class PlaylistDetailController extends Controller
{

    /**
     * Get all music in a playlist.
     */
    public function getMusicInPlaylist($playlistId)
    {
        $playlist = Playlist::with('musics')->findOrFail($playlistId);

        return response()->json([
            'playlist_id' => $playlistId,
            'playlist_name' => $playlist->nama_playlist,
            'musics' => $playlist->musics,
        ], 200);
    }


    /**
     * Add multiple music tracks to a playlist.
     */
    public function addMusicToPlaylist(Request $request, $playlistId)
    {
        $request->validate([
            'id_music' => 'required|array',
            'id_music.*' => 'exists:musics,id_music', // Validasi setiap ID dalam array
        ]);

        $playlist = Playlist::findOrFail($playlistId);

        // Attach multiple music tracks to playlist
        $playlist->musics()->attach($request->id_music);

        return response()->json([
            'message' => 'Music added to playlist successfully.',
            'playlist_id' => $playlistId,
            'music_ids' => $request->id_music,
        ], 200);
    }

    /**
     * Remove multiple music tracks from a playlist.
     */
    public function removeMusicFromPlaylist(Request $request, $playlistId)
    {
        $request->validate([
            'id_music' => 'required|array',
            'id_music.*' => 'exists:musics,id_music', // Validasi setiap ID dalam array
        ]);

        $playlist = Playlist::findOrFail($playlistId);

        // Detach multiple music tracks from playlist
        $playlist->musics()->detach($request->id_music);

        return response()->json([
            'message' => 'Music removed from playlist successfully.',
            'playlist_id' => $playlistId,
            'music_ids' => $request->id_music,
        ], 200);
    }

    

    /**
     * Update music in a playlist (add multiple tracks without detaching others).
     */
    public function updateMusicInPlaylist(Request $request, $playlistId)
    {
        $request->validate([
            'id_music' => 'required|array',
            'id_music.*' => 'exists:musics,id_music', // Validasi setiap ID dalam array
        ]);

        $playlist = Playlist::findOrFail($playlistId);

        // Sync multiple music tracks without detaching others
        $playlist->musics()->syncWithoutDetaching($request->id_music);

        return response()->json([
            'message' => 'Music updated in playlist successfully.',
            'playlist_id' => $playlistId,
            'music_ids' => $request->id_music,
        ], 200);
    }
}
