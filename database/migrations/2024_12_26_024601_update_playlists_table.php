<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('playlists', function (Blueprint $table) {
            // Hapus kolom yang tidak diperlukan
            $table->dropColumn('list_music');
            $table->dropColumn('url_album');

            // Tambahkan kolom baru
            $table->string('img_playlist')->nullable(); // Kolom untuk gambar playlist
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('playlists', function (Blueprint $table) {
            // Kembalikan perubahan
            $table->json('list_music')->nullable();
            $table->string('url_album')->nullable();

            // Hapus kolom yang ditambahkan
            $table->dropColumn('img_playlist');
        });
    }
};
