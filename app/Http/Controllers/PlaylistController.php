<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use Illuminate\Http\Request;

class PlaylistController extends Controller
{
    public function index()
    {
        $playlists = auth()->user()->playlists;

        return response()->json([
            'message' => 'Playlists retrieved successfully',
            'playlists' => $playlists,
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_playlist' => 'required|string',
            'list_music' => 'nullable|array',
            'url_album' => 'nullable|url',
        ]);

        $playlist = auth()->user()->playlists()->create([
            'nama_playlist' => $request->nama_playlist,
            'list_music' => $request->list_music,
            'url_album' => $request->url_album,
        ]);

        return response()->json([
            'message' => 'Playlist created successfully',
            'playlist' => $playlist,
        ], 201);
    }

    public function show($id)
    {
        $playlist = auth()->user()->playlists()->where('id_playlist', $id)->firstOrFail();

        return response()->json([
            'message' => 'Playlist retrieved successfully',
            'playlist' => $playlist,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $playlist = auth()->user()->playlists()->where('id_playlist', $id)->firstOrFail();

        $request->validate([
            'nama_playlist' => 'nullable|string',
            'list_music' => 'nullable|array',
            'url_album' => 'nullable|url',
        ]);

        $playlist->update($request->only(['nama_playlist', 'list_music', 'url_album']));

        return response()->json([
            'message' => 'Playlist updated successfully',
            'playlist' => $playlist,
        ], 200);
    }

    public function destroy($id)
    {
        $playlist = auth()->user()->playlists()->where('id_playlist', $id)->firstOrFail();

        $playlist->delete();

        return response()->json([
            'message' => 'Playlist deleted successfully',
        ], 200);
    }
}
