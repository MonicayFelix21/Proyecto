@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
  {{-- Filtros (Todo / Música / Podcasts) --}}
  <div class="d-flex gap-2 mb-4">
    <button class="btn btn-outline-light rounded-pill px-3">Todo</button>
    <button class="btn btn-outline-light rounded-pill px-3">Música</button>
    <button class="btn btn-outline-light rounded-pill px-3">Podcasts</button>
  </div>

  {{-- Sección: Recomendados para ti --}}
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold mb-0">Recomendados para ti</h3>
    <a href="#" class="text-white-50 text-decoration-none">Mostrar todos</a>
  </div>
  <div class="row g-4 mb-5">
    @foreach($recomendados as $c)
      <div class="col-6 col-sm-4 col-md-3 col-lg-2">
        <div class="song-card position-relative" data-audio="{{ asset($c->audio) }}">
          <img src="{{ asset($c->imagen) }}" alt="Portada {{ $c->titulo }}">
          <h6>{{ $c->titulo }}</h6>
          <div class="artist"><span>{{ $c->artista }}</span></div>
          <div class="play-button"><i class="bi bi-play-fill"></i></div>
        </div>
      </div>
    @endforeach
  </div>

  {{-- Sección: Álbumes populares --}}
  <div class="d-flex justify-content-between align-items-center mb-3 mt-5">
    <h3 class="fw-bold mb-0">Álbumes populares</h3>
    <a href="#" class="text-white-50 text-decoration-none">Mostrar todos</a>
  </div>
  <div class="row g-4 mb-5">
    @foreach($populares as $a)
      <div class="col-6 col-sm-4 col-md-3 col-lg-2">
        <div class="song-card position-relative">
          <img src="{{ asset('imagenes/albumes/'.$a->imagen) }}"
               alt="Portada {{ $a->titulo }}">
          <h6>{{ $a->titulo }}</h6>
          <div class="artist"><span>{{ $a->artista->nombre }}</span></div>
          <div class="play-button"><i class="bi bi-play-fill"></i></div>
        </div>
      </div>
    @endforeach
  </div>

  {{-- Sección: Artistas destacados --}}
  <div class="d-flex justify-content-between align-items-center mb-3 mt-5">
    <h3 class="fw-bold mb-0">Artistas destacados</h3>
    <a href="#" class="text-white-50 text-decoration-none">Mostrar todos</a>
  </div>
  <div id="carrusel-artistas" class="d-flex flex-nowrap gap-4 px-2" style="overflow-x:auto; scroll-behavior:smooth;">
    @foreach($artistas as $art)
      <div class="text-center" style="flex:0 0 auto; width:160px;">
        <img src="{{ asset($art->imagen) }}"
             alt="{{ $art->nombre }}"
             class="img-fluid rounded-circle mb-2"
             style="width:160px;height:160px;object-fit:cover;">
        <p class="mb-0 fw-semibold text-white">{{ $art->nombre }}</p>
      </div>
    @endforeach
  </div>
@endsection
