<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArtistasTable extends Migration
{
    public function up()
    {
        Schema::create('artistas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');              // nombre del artista
            $table->string('imagen');              // ruta o URL de la imagen
            $table->timestamps();                  // created_at y updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('artistas');
    }
}


