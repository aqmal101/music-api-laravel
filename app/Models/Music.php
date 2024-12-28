<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Music extends Model
{
    use HasFactory;

    protected $table = 'musics';
    protected $primaryKey = 'id_music';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'judul',
        'artis',
        'file_music',
        'file_album',
    ];

    public function playlists()
    {
        return $this->belongsToMany(
            Playlist::class,
            'playlist_detail',   // Nama tabel pivot
            'id_music',          // Kolom foreign key di tabel pivot untuk Music
            'id_playlist',       // Kolom foreign key di tabel pivot untuk Playlist
            'id_music',          // Primary key di tabel Music
            'id_playlist'        // Primary key di tabel Playlist
        )->withTimestamps();     // Menyertakan kolom timestamps dari tabel pivot
    }
}
