<?php

// app/View/Components/ArtistCard.php
namespace App\View\Components;
use Illuminate\View\Component;

class ArtistCard extends Component
{
    public $artist;
    public function __construct($artist) { $this->artist = $artist; }
    public function render() { return view('components.artist-card'); }
}

// app/View/Components/AlbumCard.php
namespace App\View\Components;
use Illuminate\View\Component;

class AlbumCard extends Component
{
    public $album;
    public function __construct($album) { $this->album = $album; }
    public function render() { return view('components.album-card'); }
}
