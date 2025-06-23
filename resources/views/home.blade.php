@extends('layouts.app')

@section('title', 'Home')

@section('content')
  {{-- Filtros (Todo / Música / Podcasts) --}}
  <div class="d-flex gap-2 mb-4">
    <button class="btn btn-outline-light rounded-pill px-3">Todo</button>
    <button class="btn btn-outline-light rounded-pill px-3">Música</button>
    <button class="btn btn-outline-light rounded-pill px-3">Podcasts</button>
  </div>

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
