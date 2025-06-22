{{-- resources/views/home.blade.php --}}
@extends('layouts.app')

@section('title','Home — Mi Spotify')

@push('styles')
<style>
/* sidebar ya viene en tu layout */

.h-scroll {
  display: flex;
  gap: 1rem;
  overflow-x: auto;
  scroll-behavior: smooth;
}
.h-scroll::-webkit-scrollbar { display: none; }
.h-scroll { -ms-overflow-style: none; scrollbar-width: none; }

.card-thumb {
  flex: 0 0 auto;
  width: 200px;
}
.card-thumb img {
  width: 100%;
  border-radius: 4px;
}
.banner-inferior {
  background: linear-gradient(to right, #af2896, #509bf5);
  z-index: 1050;
}
</style>
@endpush

@section('content')
<div class="d-flex">

  {{-- 1) Sidebar --}}
  @include('components.sidebar')

  {{-- 2) Contenido principal --}}
  <div class="flex-grow-1 p-4">

    {{-- Filtros (Todo / Música / Podcasts) --}}
    <div class="mb-4">
      <button class="btn btn-outline-light btn-sm me-2">Todo</button>
      <button class="btn btn-outline-light btn-sm me-2">Música</button>
      <button class="btn btn-outline-light btn-sm">Podcasts</button>
    </div>

    {{-- Sección Recomendados --}}
    <section class="mb-5">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-white mb-0">Recomendados para ti</h4>
        <a href="#" class="text-white-50">Mostrar todos</a>
      </div>
      <div class="h-scroll pb-2">
        @foreach($recomendados as $album)
          <div class="card-thumb text-white">
            <img src="{{ asset('imagenes/albumes/'.$album->imagen) }}" alt="{{ $album->titulo }}">
            <h6 class="mt-2 mb-1">{{ $album->titulo }}</h6>
            <small class="text-white-50">{{ $album->artista->nombre }}</small>
          </div>
        @endforeach
      </div>
    </section>

    {{-- Sección Listas seleccionadas --}}
    <section class="mb-5">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-white mb-0">Listas seleccionadas</h4>
        <a href="#" class="text-white-50">Mostrar todos</a>
      </div>
      <div class="h-scroll pb-2">
        @foreach($listas as $lista)
          <div class="card-thumb text-white">
            <img src="{{ asset('imagenes/albumes/'.$lista->imagen) }}" alt="{{ $lista->titulo }}">
            <h6 class="mt-2 mb-1">{{ $lista->titulo }}</h6>
            <small class="text-white-50">Lista destacada</small>
          </div>
        @endforeach
      </div>
    </section>

  </div>
</div>

{{-- 3) Banner fijo abajo --}}
<div class="banner-inferior fixed-bottom w-100 px-4 py-3 text-white d-flex justify-content-between align-items-center">
  <div>
    <strong>Muestra de Spotify</strong><br>
    Regístrate para disfrutar de canciones y podcasts sin límites, con anuncios ocasionales. No hace falta tarjeta de crédito.
  </div>
  <a href="{{ route('registro') }}" class="btn btn-light text-black fw-bold rounded-pill px-4">
    Registrarte gratis
  </a>
</div>
@endsection
