@extends('layouts.app')

@section('title', 'Inicio - Mi Spotify')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Canciones en tendencia</h3>
    <a href="#" class="text-white-50 text-decoration-none fw-semibold">Mostrar todos</a>
</div>

<div class="row g-4 mb-5">
    @foreach($canciones as $cancion)
<div class="col-6 col-sm-4 col-md-3 col-lg-2">
    <div class="song-card">
        <img src="{{ asset($cancion->imagen) }}" alt="Portada">
        <h6>{{ $cancion->titulo }}</h6>
        <div class="artist">
            @if($cancion->explicito)
                <span class="badge bg-secondary px-2 py-1">E</span>
            @endif
            <span>{{ $cancion->artista }}</span>
        </div>
    </div>
</div>

    @endforeach
</div>

@endsection
