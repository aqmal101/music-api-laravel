<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('playlists', function (Blueprint $table) {
            $table->id('id_playlist');  
            $table->foreignId('id_user')
                ->constrained('users') 
                ->onDelete('cascade'); 
            $table->string('nama_playlist'); 
            $table->string('url_album')->nullable();
            $table->json('list_music')->nullable(); 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('playlists'); 
    }
};
