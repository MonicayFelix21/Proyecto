{{-- resources/views/components/album-card.blade.php --}}
<div class="col-6 col-sm-4 col-md-3 col-lg-2">
  <div class="song-card position-relative">
    <img src="{{ asset('imagenes/albumes/'.$album->imagen) }}"
         alt="Portada {{ $album->titulo }}"
         class="img-fluid rounded mb-2"
         style="width:160px;height:160px;object-fit:cover;">
    <h6>{{ $album->titulo }}</h6>
    <div class="artist"><span>{{ $album->artista->nombre }}</span></div>
    <div class="play-button"><i class="bi bi-play-fill"></i></div>
  </div>
</div>
