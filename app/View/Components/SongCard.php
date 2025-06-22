<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SongCard extends Component
{
    public $song;

    /**
     * Crea una nueva instancia del componente.
     *
     * @param  \App\Models\Cancion  $song
     */
    public function __construct($song)
    {
        $this->song = $song;
    }

    public function render()
    {
        return view('components.song-card');
    }
}
