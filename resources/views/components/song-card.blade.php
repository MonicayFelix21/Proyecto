{{-- resources/views/components/song-card.blade.php --}}
<div class="col-6 col-sm-4 col-md-3 col-lg-2">
  <div class="song-card position-relative" data-audio="{{ asset($song->audio) }}">
    <img src="{{ asset($song->imagen) }}" alt="Portada {{ $song->titulo }}">
    <h6>{{ $song->titulo }}</h6>
    <div class="artist">
      @if($song->explicito)
        <span class="badge bg-secondary px-2 py-1">E</span>
      @endif
      <span>{{ $song->artista }}</span>
    </div>
    <div
      class="play-button"
      title="Reproducir {{ $song->titulo }}"
      @guest
        data-bs-toggle="modal"
        data-bs-target="#playModal"
      @endguest
    >
      <i class="bi bi-play-fill"></i>
    </div>
  </div>
</div>
