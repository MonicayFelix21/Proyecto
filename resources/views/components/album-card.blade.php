{{-- resources/views/components/album-card.blade.php --}}

@php
  use Illuminate\Support\Str;

  // Determinar la URL de la imagen: remota o local
  $src = Str::startsWith($album->imagen, ['http://', 'https://'])
         ? $album->imagen
         : asset('imagenes/albumes/' . $album->imagen);

  // Resolver nombre de artista (string o modelo)
  $artistName = is_object($album->artista)
                ? $album->artista->nombre
                : $album->artista;
@endphp

<div class="col-6 col-sm-4 col-md-3 col-lg-2">
  <div class="song-card position-relative">
    <img src="{{ $src }}"
         alt="Portada {{ $album->titulo }}"
         class="img-fluid rounded mb-2"
         style="width:160px;height:160px;object-fit:cover;">
    <h6>{{ $album->titulo }}</h6>
    <div class="artist"><span>{{ $artistName }}</span></div>
    <div class="play-button"><i class="bi bi-play-fill"></i></div>
  </div>
</div>

