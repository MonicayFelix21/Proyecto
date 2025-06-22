{{-- resources/views/inicio.blade.php --}}
@extends('layouts.app')

@section('title', 'Inicio - Mi Spotify')

@push('styles')
<style>
  /* ====== Carrusel de artistas ====== */
  #carrusel-artistas::-webkit-scrollbar { display: none; }
  #carrusel-artistas { scrollbar-width: none; -ms-overflow-style: none; }
  .grupo-carrusel:hover .btn-scroll { opacity: 1; }
  .btn-scroll { opacity: 0; transition: opacity 0.3s ease; }
</style>
@endpush

@section('content')
  <!-- Canciones en tendencia -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Canciones en tendencia</h3>
    <a href="#" class="text-white-50 text-decoration-none fw-semibold">Mostrar todos</a>
  </div>

  <div class="row g-4 mb-5">
    @foreach($canciones as $cancion)
      <div class="col-6 col-sm-4 col-md-3 col-lg-2">
        <div class="song-card position-relative" data-audio="{{ asset($cancion->audio) }}">
          <img src="{{ asset($cancion->imagen) }}" alt="Portada">
          <h6>{{ $cancion->titulo }}</h6>
          <div class="artist">
            @if($cancion->explicito)
              <span class="badge bg-secondary px-2 py-1">E</span>
            @endif
            <span>{{ $cancion->artista }}</span>
          </div>

          {{-- Si no hay sesión, dispara el modal; si hay sesión, lo capturará el JS de @auth --}}
          <div
            class="play-button"
            title="Reproducir {{ $cancion->titulo }}"
            @guest
              data-bs-toggle="modal"
              data-bs-target="#playModal"
            @endguest
          >
            <i class="bi bi-play-fill"></i>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <!-- Artistas populares -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Artistas populares</h3>
    <a href="{{ route('artistas.index') }}" class="text-white-50 text-decoration-none fw-semibold">
      Mostrar todos
    </a>
  </div>
  <div class="position-relative mx-auto grupo-carrusel" style="max-width: 1150px; overflow: visible;">
    {{-- flechas y carrusel identical al anterior --}}
    <button id="scroll-izquierda" class="btn btn-scroll position-absolute d-flex justify-content-center align-items-center" style="top:50%; left:0; transform: translate(-50%,-50%); background:#1a1a1a; border-radius:50%; width:40px; height:40px; z-index:10;">
      <i class="bi bi-chevron-left text-white fs-5"></i>
    </button>
    <button id="scroll-derecha" class="btn btn-scroll position-absolute d-flex justify-content-center align-items-center" style="top:50%; right:0; transform: translate(50%,-50%); background:#1a1a1a; border-radius:50%; width:40px; height:40px; z-index:10;">
      <i class="bi bi-chevron-right text-white fs-5"></i>
    </button>
    <div id="carrusel-artistas" class="d-flex flex-nowrap gap-4 px-2" style="scroll-behavior: smooth; overflow-x:auto;">
      @foreach($artistas as $artista)
        <div class="text-center" style="flex:0 0 auto; width:160px;">
          <img src="{{ asset($artista->imagen) }}" alt="{{ $artista->nombre }}"
               class="img-fluid rounded-circle mb-2 hover-zoom"
               style="width:160px;height:160px;object-fit:cover;transition:transform 0.3s;">
          <p class="mb-0 fw-semibold text-white">{{ $artista->nombre }}</p>
          <small class="text-white-50">Artista</small>
        </div>
      @endforeach
    </div>
  </div>

  <!-- Álbumes y sencillos populares -->
  <div class="d-flex justify-content-between align-items-center mb-4 mt-5">
    <h3 class="fw-bold mb-0">Álbumes y sencillos populares</h3>
<a href="#" class="text-white-50 text-decoration-none fw-semibold">
  Mostrar todos
</a>
  </div>
  <div class="row g-4 mb-5">
    @foreach($albumes as $album)
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
    @endforeach
  </div>

  {{-- Incluir el partial del modal --}}
  @include('partials.spotify-modal')
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // Carrusel
    document.querySelectorAll('.grupo-carrusel').forEach(grupo => {
      const carr = grupo.querySelector('div[style*="overflow-x"]'),
            izq = grupo.querySelector('.btn-scroll:first-of-type'),
            der = grupo.querySelector('.btn-scroll:last-of-type');
      function act() {
        const max = carr.scrollWidth - carr.clientWidth,
              x   = carr.scrollLeft;
        izq.style.display = (carr.scrollWidth>carr.clientWidth && x>10)             ? 'flex':'none';
        der.style.display = (carr.scrollWidth>carr.clientWidth && x<max-10)        ? 'flex':'none';
      }
      der.onclick = ()=> carr.scrollBy({ left: carr.clientWidth*0.6, behavior:'smooth' });
      izq.onclick = ()=> carr.scrollBy({ left:-carr.clientWidth*0.6, behavior:'smooth' });
      carr.addEventListener('scroll', act);
      window.addEventListener('resize', act);
      setTimeout(act,300);
    });
  });
</script>

@auth
<script>
document.addEventListener('DOMContentLoaded', () => {
  const modalEl = document.getElementById('playModal');
  const bsModal = new bootstrap.Modal(modalEl);

  document.querySelectorAll('.song-card .play-button').forEach(btn => {
    btn.addEventListener('click', () => {
      // 1) Obtener la portada de la song-card
      const card   = btn.closest('.song-card');
      const imgSrc = card.querySelector('img').src;

      // 2) Asignarla al modal
      document.getElementById('playModalImg').src = imgSrc;

      // 3) Mostrar el modal
      bsModal.show();
    });
  });
});
</script>
@endauth
@endpush
