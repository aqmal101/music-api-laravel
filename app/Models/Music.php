<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Music extends Model
{
    use HasFactory;

    protected $table = 'musics';

    protected $primaryKey = 'id_music';

    protected $fillable = [
        'judul',
        'artis',
        'file_music',
        'file_album',
    ];
}
