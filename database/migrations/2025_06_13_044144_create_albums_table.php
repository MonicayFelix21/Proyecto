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
    Schema::create('albums', function (Blueprint $table) {
        $table->id();
        $table->string('titulo');
        $table->string('imagen')->nullable();
        $table->foreignId('artista_id')
              ->constrained('artistas')    // asume que tu tabla de artistas se llama 'artistas'
              ->onDelete('cascade');
        $table->unsignedBigInteger('reproducciones')->default(0);
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('albums');
    }
};
