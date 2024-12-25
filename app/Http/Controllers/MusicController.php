<?php

namespace App\Http\Controllers;

use App\Models\Music;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MusicController extends Controller
{
    public function index(Request $request)
    {
        // Ambil query parameter untuk pencarian
        $search = $request->query('search');

        $musics = Music::query()
            ->when($search, function ($query, $search) {
                $query->where('judul', 'like', '%' . $search . '%')
                    ->orWhere('artis', 'like', '%' . $search . '%');
            })
            ->get()
            ->map(function ($music) {
                $music->file_music_url = url(Storage::url($music->file_music));
                $music->file_album_url = $music->file_album ? url(Storage::url($music->file_album)) : null;

                unset($music->file_music, $music->file_album);

                return $music;
            });

        return response()->json([
            'message' => 'Music list retrieved successfully',
            'status' => 'success',
            'data' => $musics,
        ]);
    }

    public function search(Request $request)
    {
        // Validasi input pencarian
        $request->validate([
            'search' => 'required|string|min:3', // Minimal 3 karakter
        ]);

        // Ambil input pencarian
        $search = $request->input('search');

        // Query pencarian
        $musics = Music::query()
            ->where('judul', 'like', '%' . $search . '%')
            ->orWhere('artis', 'like', '%' . $search . '%')
            ->get()
            ->map(function ($music) {
                $music->file_music_url = url(Storage::url($music->file_music));
                $music->file_album_url = $music->file_album ? url(Storage::url($music->file_album)) : null;

                unset($music->file_music, $music->file_album);

                return $music;
            });

        return response()->json([
            'message' => $musics->isEmpty() ? 'No results found for your search' : 'Music search results',
            'status' => 'success',
            'data' => $musics,
        ]);
    }


    public function store(Request $request)
    {
        // Validasi input dari pengguna
        $request->validate([
            'judul' => 'required|string|max:255',
            'artis' => 'required|string|max:255',
            'file_music' => 'required|file|mimes:mp3,wav|max:10240',
            'file_album' => 'nullable|file|mimes:jpeg,png,jpg|max:5120',
        ]);

        // Proses upload file musik
        $fileMusicPath = $request->file('file_music')->store('music_files', 'public');

        // Proses upload file album (jika ada)
        $fileAlbumPath = $request->hasFile('file_album')
            ? $request->file('file_album')->store('album_files', 'public')
            : null;

        // Simpan data musik ke database
        $music = Music::create([
            'judul' => $request->judul,
            'artis' => $request->artis,
            'file_music' => $fileMusicPath,
            'file_album' => $fileAlbumPath,
        ]);

        return response()->json([
            'message' => 'Music created successfully',
            'status' => 'success',
            'data' => $music,
        ], 201);
    }

    public function show($id)
    {
        // Ambil data musik berdasarkan ID
        $music = Music::findOrFail($id);
        $music->file_music_url = url(Storage::url($music->file_music));
        $music->file_album_url = $music->file_album ? url(Storage::url($music->file_album)) : null;

        return response()->json([
            'message' => 'Music retrieved successfully',
            'status' => 'success',
            'data' => $music,
        ]);
    }

    public function update(Request $request, $id)
    {
        // Ambil data musik berdasarkan ID
        $music = Music::findOrFail($id);

        // Validasi input dari pengguna
        $request->validate([
            'judul' => 'string|max:255',
            'artis' => 'string|max:255',
            'file_music' => 'file|mimes:mp3,wav|max:10240',
            'file_album' => 'nullable|file|mimes:jpeg,png,jpg|max:5120',
        ]);

        // Update file_music jika diunggah
        if ($request->hasFile('file_music')) {
            if ($music->file_music) {
                Storage::disk('public')->delete($music->file_music);
            }
            $fileMusicPath = $request->file('file_music')->store('music_files', 'public');
            $music->file_music = $fileMusicPath;
        }

        // Update file_album jika diunggah
        if ($request->hasFile('file_album')) {
            if ($music->file_album) {
                Storage::disk('public')->delete($music->file_album);
            }
            $fileAlbumPath = $request->file('file_album')->store('album_files', 'public');
            $music->file_album = $fileAlbumPath;
        }

        // Update data lainnya
        $music->judul = $request->judul ?? $music->judul;
        $music->artis = $request->artis ?? $music->artis;
        $music->save();

        return response()->json([
            'message' => 'Music updated successfully',
            'status' => 'success',
            'data' => $music,
        ]);
    }

    public function destroy($id)
    {
        // Ambil data musik berdasarkan ID
        $music = Music::findOrFail($id);

        // Hapus file musik dan album jika ada
        if ($music->file_music) {
            Storage::disk('public')->delete($music->file_music);
        }

        if ($music->file_album) {
            Storage::disk('public')->delete($music->file_album);
        }

        // Hapus data musik dari database
        $music->delete();

        return response()->json([
            'message' => 'Music deleted successfully',
            'status' => 'success',
        ]);
    }
}
