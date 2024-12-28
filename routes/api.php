<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PlaylistController;
use App\Http\Controllers\MusicController;
use App\Http\Controllers\PlaylistDetailController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
    Route::get('/profile', 'profile')->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('playlists', PlaylistController::class);
});

Route::apiResource('musics', MusicController::class);

Route::get('/musics/search', [MusicController::class, 'search']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('playlist/{playlistId}/add-music', [PlaylistDetailController::class, 'addMusicToPlaylist']);
    Route::post('playlist/{playlistId}/remove-music', [PlaylistDetailController::class, 'removeMusicFromPlaylist']);
    Route::get('playlist/{playlistId}/musics', [PlaylistDetailController::class, 'getMusicInPlaylist']);
    Route::post('playlist/{playlistId}/update-music', [PlaylistDetailController::class, 'updateMusicInPlaylist']);
});
