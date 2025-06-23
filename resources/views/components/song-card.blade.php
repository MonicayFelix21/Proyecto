<div class="col-6 col-sm-4 col-md-3 col-lg-2">
  <div class="song-card position-relative"
data-audio="{{ asset($song->audio) }}"
       data-titulo="{{ $song->titulo }}"
       data-artista="{{ $song->artista }}"
       data-imagen="{{ asset($song->imagen) }}">
       
    {{-- Imagen --}}
    <img src="{{ asset($song->imagen) }}" alt="Portada {{ $song->titulo }}"
         class="img-fluid rounded mb-2" style="width:100%; aspect-ratio:1; object-fit:cover;">

    {{-- Título --}}
    <h6 class="text-white">{{ $song->titulo }}</h6>

    {{-- Artista y etiqueta explícito --}}
    <div class="artist d-flex align-items-center gap-1 text-white-50 small">
      @if($song->explicito)
        <span class="badge bg-secondary px-1 py-0">E</span>
      @endif
      <span>{{ $song->artista }}</span>
    </div>


    {{-- Botón de reproducción sin restricción por sesión --}}
    <div class="play-button" title="Reproducir {{ $song->titulo }}">
      <i class="bi bi-play-fill fs-4"></i>
    </div>
  </div>
</div>
