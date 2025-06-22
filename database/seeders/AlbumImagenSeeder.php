<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlbumImagenSeeder extends Seeder
{
    public function run()
    {
        // Aquí actualizas los registros existentes:
        DB::table('albums')->where('id', 1)->update([
            'imagen' => 'verano.jpg',
        ]);

        DB::table('albums')->where('id', 2)->update([
            'imagen' => 'cosa.jpg',
        ]);

        DB::table('albums')->where('id', 3)->update([
            'imagen' => 'sad.jpg',
        ]);

        // O si prefieres, inserta nuevos:
        /*
        DB::table('albums')->insert([
            'titulo'        => 'Mi Álbum',
            'artista_id'    => 1,
            'reproducciones'=> 0,
            'imagen'        => 'mialbum.jpg',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
        */
    }
}
