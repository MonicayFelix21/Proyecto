<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Artista;

class ArtistaSeeder extends Seeder
{
    public function run()
    {
        $artistas = [
            ['nombre' => 'Bad Bunny', 'imagen' => 'imagenes/artistas/bad.jpg'],
            ['nombre' => 'Lady Gaga', 'imagen' => 'imagenes/artistas/gaga.jpg'],
            ['nombre' => 'Junior H', 'imagen' => 'imagenes/artistas/junior.jpg'],
            ['nombre' => 'Rauw Alejandro', 'imagen' => 'imagenes/artistas/rau.jpg'],
            ['nombre' => 'Dua Lipa', 'imagen' => 'imagenes/artistas/dua.jpg'],
            ['nombre' => 'Tito Double', 'imagen' => 'imagenes/artistas/tito.jpg'],
            ['nombre' => 'Lana Del Rey', 'imagen' => 'imagenes/artistas/lana.jpg'],
            ['nombre' => 'Natanael Cano', 'imagen' => 'imagenes/artistas/nata.jpg'],
        ];

        foreach ($artistas as $artista) {
            Artista::create($artista);
        }
    }
}
