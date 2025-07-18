{{-- resources/views/home.blade.php --}}
@extends('layouts.app')

@section('title', 'Home')

@section('content')

  @php
    /**
     * Helper inline para resolver URLs:
     * si $path es una URL absoluta, la devuelve tal cual;
     * si no, la convierte con asset().
     */
    function urlResource($path) {
      return filter_var($path, FILTER_VALIDATE_URL)
             ? $path
             : asset($path);
    }
  @endphp

  {{-- 1. Canciones en tendencia --}}
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Canciones en tendencia</h3>
    <a href="#" class="text-white-50 fw-semibold">Mostrar todos</a>
  </div>
  <div class="row g-4 mb-5">
    @foreach($canciones as $c)
      @php
        $img   = urlResource($c->imagen);
        $audio = isset($c->audio) ? urlResource($c->audio) : '';
        $spotifyUri = $c->spotify_uri ?? null;
      @endphp
      <div class="col-6 col-sm-4 col-md-3 col-lg-2">
        <div class="song-card position-relative"
             data-imagen="{{ $img }}"
             data-titulo="{{ $c->titulo }}"
             data-artista="{{ $c->artista }}"
             @if($audio) data-audio="{{ $audio }}" @endif
             @if($spotifyUri) data-uri="{{ $spotifyUri }}" @endif>
          <img src="{{ $img }}"
               alt="Portada {{ $c->titulo }}"
               class="img-fluid rounded mb-2"
               style="width:160px; height:160px; object-fit:cover;">
          <h6 class="text-white">{{ $c->titulo }}</h6>
          <div class="artist d-flex align-items-center gap-1 text-white-50 small">
            @if(!empty($c->explicito))
              <span class="badge bg-secondary px-1 py-0">E</span>
            @endif
            <span>{{ $c->artista }}</span>
          </div>
          <div class="play-button"><i class="bi bi-play-fill fs-4"></i></div>
        </div>
      </div>
    @endforeach
  </div>

  {{-- 2. Artistas destacados --}}
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Artistas destacados</h3>
    <a href="#" class="text-white-50 fw-semibold">Mostrar todos</a>
  </div>
  <div id="carrusel-artistas" class="d-flex flex-nowrap gap-4 px-2 mb-5" style="overflow-x:auto;">
    @foreach($artistas as $a)
      @php $imgArt = urlResource($a->imagen); @endphp
      <div class="text-center" style="flex:0 0 auto; width:160px;">
        <img src="{{ $imgArt }}"
             alt="{{ $a->nombre }}"
             class="img-fluid rounded-circle mb-2"
             style="width:160px; height:160px; object-fit:cover;">
        <p class="mb-0 fw-semibold text-white">{{ $a->nombre }}</p>
      </div>
    @endforeach
  </div>

  {{-- 3. Álbumes populares --}}
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Álbumes populares</h3>
    <a href="#" class="text-white-50 fw-semibold">Mostrar todos</a>
  </div>
  <div class="row g-4 mb-5">
    @foreach($albumes as $al)
      @php $imgAlb = urlResource($al->imagen); @endphp
      <div class="col-6 col-sm-4 col-md-3 col-lg-2">
        <div class="song-card position-relative">
          <img src="{{ $imgAlb }}"
               alt="Portada {{ $al->titulo }}"
               class="img-fluid rounded mb-2"
               style="width:160px; height:160px; object-fit:cover;">
          <h6 class="text-white">{{ $al->titulo }}</h6>
          <p class="text-white-50 small">{{ $al->artista }}</p>
        </div>
      </div>
    @endforeach
  </div>

@endsection
