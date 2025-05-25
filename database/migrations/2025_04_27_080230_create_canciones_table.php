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
        Schema::create('canciones', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->string('artista');
            $table->string('categoria'); // ✅ nueva columna: categoría musical
            $table->string('imagen')->nullable(); // ✅ opcional
            $table->string('audio')->nullable(); // ✅ opcional
            $table->boolean('explicito')->default(false); // ✅ marcar si es contenido explícito
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('canciones');
    }
};
