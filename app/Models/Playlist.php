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
        'url_album',
        'list_music',
    ];

    protected $casts = [
        'list_music' => 'array', 
    ];

    protected $primaryKey = 'id_playlist';
    
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
