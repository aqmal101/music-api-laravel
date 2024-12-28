<?php

namespace App\Http\Controllers;

use App\Models\Playlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Tambahkan ini untuk menggunakan Storage

class PlaylistController extends Controller
{
    public function index()
    {
        // Mendapatkan semua playlist milik pengguna yang terautentikasi
        $playlists = auth()->user()->playlists;

        return response()->json([
            'message' => 'Playlists retrieved successfully',
            'status' => 'success',
            'data' => $playlists,
        ], 200);
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_playlist' => 'required|string',
            'img_playlist' => 'nullable|file|mimes:jpeg,jpg,png,gif|max:2048', // Validasi file gambar
        ]);

        // Proses penyimpanan file gambar
        if ($request->hasFile('img_playlist')) {
            $path = $request->file('img_playlist')->store('public/images'); // Simpan ke storage
            $imgPlaylistUrl = asset(str_replace('public/', 'storage/', $path)); // Buat URL lengkap
        } else {
            $imgPlaylistUrl = null;
        }

        // Membuat playlist baru
        $playlist = auth()->user()->playlists()->create([
            'nama_playlist' => $request->nama_playlist,
            'img_playlist' => $imgPlaylistUrl,
        ]);

        return response()->json([
            'message' => 'Playlist created successfully',
            'status' => 'success',
            'data' => $playlist,
        ], 201);
    }

    public function show($id)
    {
        $playlist = auth()->user()->playlists()->where('id_playlist', $id)->firstOrFail();
    
        $playlist->img_playlist_url = $playlist->img_playlist ? url(Storage::url($music->img_playlist)) : null;
    
        return response()->json([
            'message' => 'Playlist retrieved successfully',
            'data' => $playlist,
        ], 200);
    }

    public function update(Request $request, $id)
{
    // Retrieve the playlist based on the provided ID
    $playlist = auth()->user()->playlists()->where('id_playlist', $id)->firstOrFail();

    // Validate the request data
    $validated = $request->validate([
        'nama_playlist' => 'nullable|string',
        'img_playlist' => 'nullable|file|mimes:jpeg,jpg,png,gif|max:2048',
    ]);

    // Check if a new image is uploaded
    if ($request->hasFile('img_playlist')) {
        // Delete the old image from storage if it exists
        if (!empty($playlist->img_playlist)) {
            $oldImagePath = str_replace(asset('storage/'), 'public/', $playlist->img_playlist);
            if (Storage::exists($oldImagePath)) {
                Storage::delete($oldImagePath);
            }
        }

        // Store the new image
        $path = $request->file('img_playlist')->store('public/images');
        $playlist->img_playlist = asset(str_replace('public/', 'storage/', $path));
    }

    // Update the name of the playlist if provided
    if ($request->filled('nama_playlist')) {
        $playlist->nama_playlist = $validated['nama_playlist'];
    }

    // Save the updated playlist
    $playlist->save();

    // Reload the playlist to ensure changes are reflected
    $playlist->refresh();

    // Return the updated playlist as JSON response
    return response()->json([
        'message' => 'Playlist updated successfully',
        'data' => $playlist,
    ], 200);
}

    

    public function destroy($id)
    {
        // Mendapatkan playlist berdasarkan ID
        $playlist = auth()->user()->playlists()->where('id_playlist', $id)->firstOrFail();

        // Menghapus playlist
        $playlist->delete();

        return response()->json([
            'message' => 'Playlist deleted successfully',
        ], 200);
    }
}
