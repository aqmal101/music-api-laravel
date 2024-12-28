<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'nama_playlist',
        'img_playlist',
    ];

    protected $primaryKey = 'id_playlist';
    public $incrementing = true;
    protected $keyType = 'int';

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function musics()
    {
        return $this->belongsToMany(
            Music::class,
            'playlist_detail',   // Nama tabel pivot
            'id_playlist',       // Kolom foreign key di tabel pivot untuk Playlist
            'id_music',          // Kolom foreign key di tabel pivot untuk Music
            'id_playlist',       // Primary key di tabel Playlist
            'id_music'           // Primary key di tabel Music
        )->withTimestamps();     // Menyertakan kolom timestamps dari tabel pivot
    }
};
