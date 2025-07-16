{{-- resources/views/components/song-card.blade.php --}}
@php
  // Determinar si es pista remota (Spotify) o local (BD)
  $isRemote   = isset($track) && is_array($track);
  $uri        = $isRemote ? "spotify:track:{$track['id']}"                : null;
  $previewUrl = $isRemote ? ($track['preview_url'] ?? null)               : null;
  $audioLocal = ! $isRemote ? asset($song->audio)                         : null;
  $titulo     = $isRemote ? $track['name']                                : $song->titulo;
  $artista    = $isRemote
                  ? implode(', ', array_column($track['artists'], 'name'))
                  : $song->artista;
  $imagen     = $isRemote
                  ? ($track['album']['images'][0]['url'] ?? '')
                  : asset($song->imagen);
  $explicito  = ! $isRemote && $song->explicito;
@endphp

<div class="col-6 col-sm-4 col-md-3 col-lg-2">
  <div class="song-card position-relative"
       @if($uri)        data-uri="{{ $uri }}"        @endif
       @if($previewUrl) data-preview="{{ $previewUrl }}" @endif
       @if($audioLocal) data-audio="{{ $audioLocal }}"   @endif
       data-titulo="{{ $titulo }}"
       data-artista="{{ $artista }}"
       data-imagen="{{ $imagen }}"
  >

    {{-- Imagen --}}
    <img src="{{ $imagen }}"
         alt="Portada {{ $titulo }}"
         class="img-fluid rounded mb-2"
         style="width:100%; aspect-ratio:1; object-fit:cover;">

    {{-- Título --}}
    <h6 class="text-white">{{ $titulo }}</h6>

    {{-- Artista y etiqueta explícito --}}
    <div class="artist d-flex align-items-center gap-1 text-white-50 small">
      @if($explicito)
        <span class="badge bg-secondary px-1 py-0">E</span>
      @endif
      <span>{{ $artista }}</span>
    </div>

    {{-- Botón de reproducción --}}
    <button class="play-button" title="Reproducir {{ $titulo }}">
      <i class="bi bi-play-fill fs-4"></i>
    </button>
  </div>
</div>
