{{-- resources/views/inicio.blade.php --}}
@extends('layouts.app')

@section('title', 'Inicio - Mi Spotify')

@section('content')
  <!-- Canciones en tendencia -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Canciones en tendencia</h3>
    <a href="#" class="text-white-50 text-decoration-none fw-semibold">Mostrar todos</a>
  </div>

  <div class="row g-4 mb-5">
    @foreach($canciones as $cancion)
      <x-song-card :song="$cancion" />
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
    <button id="scroll-izquierda" class="btn btn-scroll position-absolute d-flex justify-content-center align-items-center">
      <i class="bi bi-chevron-left text-white fs-5"></i>
    </button>
    <button id="scroll-derecha" class="btn btn-scroll position-absolute d-flex justify-content-center align-items-center">
      <i class="bi bi-chevron-right text-white fs-5"></i>
    </button>
    <div id="carrusel-artistas" class="d-flex flex-nowrap gap-4 px-2">
      @foreach($artistas as $artista)
        <x-artist-card :artist="$artista" />
      @endforeach
    </div>
  </div>

  <!-- Álbumes y sencillos populares -->
  <div class="d-flex justify-content-between align-items-center mb-4 mt-5">
    <h3 class="fw-bold mb-0">Álbumes y sencillos populares</h3>
    <a href="#" class="text-white-50 text-decoration-none fw-semibold">Mostrar todos</a>
  </div>
  <div class="row g-4 mb-5">
    @foreach($albumes as $album)
      <x-album-card :album="$album" />
    @endforeach
  </div>

  {{-- Partial del modal de reproducción --}}
  @include('partials.spotify-modal')
@endsection
