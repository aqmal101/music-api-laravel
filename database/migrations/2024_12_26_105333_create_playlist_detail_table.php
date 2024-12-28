<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('playlist_detail', function (Blueprint $table) {
            $table->id('id_playlist_detail');
            $table->unsignedBigInteger('id_playlist');
            $table->foreign('id_playlist')->references('id_playlist')->on('playlists')->onDelete('cascade');
            $table->unsignedBigInteger('id_music');
            $table->foreign('id_music')->references('id_music')->on('musics')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('playlist_detail');
    }
};
