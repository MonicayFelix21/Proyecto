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
        Schema::table('users', function (Blueprint $table) {
            // Cambiar las columnas de Spotify a TEXT (más espacio)
            $table->text('spotify_token')->nullable()->change();
            $table->text('spotify_refresh_token')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Revertir a string (si es necesario)
            $table->string('spotify_token')->nullable()->change();
            $table->string('spotify_refresh_token')->nullable()->change();
        });
    }
};
