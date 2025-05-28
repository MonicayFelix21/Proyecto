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
            <div class="song-card position-relative" data-audio="{{ asset($cancion->audio) }}">
                <img src="{{ asset($cancion->imagen) }}" alt="Portada">
                <h6>{{ $cancion->titulo }}</h6>
                <div class="artist">
                    @if($cancion->explicito)
                        <span class="badge bg-secondary px-2 py-1">E</span>
                    @endif
                    <span>{{ $cancion->artista }}</span>
                </div>
                <div class="play-button" title="Reproducir {{ $cancion->titulo }}">
                    <i class="bi bi-play-fill"></i>
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Modal flotante si no hay sesión iniciada -->
<div class="modal fade" id="modalLoginPrompt" tabindex="-1" aria-labelledby="modalLoginPromptLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content text-white" style="background: linear-gradient(to right, #af2896, #509bf5);">
            <div class="modal-body d-flex align-items-center p-5">
                <div class="me-4">
                    <img src="{{ asset('imagenes/spotify.png') }}" alt="Spotify" class="img-fluid" style="max-width: 180px;">
                </div>
                <div>
                    <h2 class="fw-bold mb-3">Empieza a escuchar con una cuenta gratis</h2>
                    <div class="d-flex gap-3">
                        <a href="{{ route('registro') }}" class="btn btn-success fw-bold px-4 py-2 rounded-pill">Regístrate gratis</a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light fw-bold px-4 py-2 rounded-pill">Iniciar sesión</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
