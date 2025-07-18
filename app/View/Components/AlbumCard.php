<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\Album;          

class AlbumCard extends Component
{
    /**
     * El álbum que se va a mostrar.
     *
     * @var \App\Models\Album
     */
    public Album $album;

    /**
     * Crea una nueva instancia del componente.
     *
     * @param  \App\Models\Album  $album
     */
    public function __construct(Album $album)
    {
        $this->album = $album;
    }

    /**
     * Obtiene la vista del componente.
     */
    public function render()
    {
        return view('components.album-card');
    }
}
