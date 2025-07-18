@extends('layouts.app')

@section('title', 'Prueba de Reproducción')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2 class="text-white mb-4">
                <i class="fas fa-play-circle me-2"></i>
                Prueba de Reproducción Spotify
            </h2>

            @if(auth()->user()->isSpotifyPremium())
                <div class="alert alert-success">
                    <h5><i class="fas fa-crown me-2"></i>¡Cuenta Premium Detectada!</h5>
                    <p>Haz clic en cualquier botón verde para reproducir música con Spotify.</p>
                </div>

                <div class="row g-4">
                    <!-- Canción de prueba 1 -->
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                        <div class="song-card position-relative"
                             data-uri="spotify:track:4u7EnebtmKWzUH433cf5Qv"
                             data-titulo="Bohemian Rhapsody"
                             data-artista="Queen"
                             data-imagen="https://i.scdn.co/image/ab67616d0000b273ce4f1737bc8a646c8c4bd25a">
                            <img src="https://i.scdn.co/image/ab67616d0000b273ce4f1737bc8a646c8c4bd25a"
                                 alt="Bohemian Rhapsody"
                                 class="img-fluid rounded mb-2"
                                 style="width:100%; aspect-ratio:1; object-fit:cover;">
                            <h6 class="text-white">Bohemian Rhapsody</h6>
                            <div class="artist text-white-50 small">Queen</div>
                            <button class="play-button" title="Reproducir Bohemian Rhapsody">
                                <i class="bi bi-play-fill fs-4"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Canción de prueba 2 -->
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                        <div class="song-card position-relative"
                             data-uri="spotify:track:4VqPOruhp5EdPBeR92t6lQ"
                             data-titulo="Uptown Funk"
                             data-artista="Mark Ronson ft. Bruno Mars"
                             data-imagen="https://i.scdn.co/image/ab67616d0000b273dbb3dd82da45b7d7f31b1b42">
                            <img src="https://i.scdn.co/image/ab67616d0000b273dbb3dd82da45b7d7f31b1b42"
                                 alt="Uptown Funk"
                                 class="img-fluid rounded mb-2"
                                 style="width:100%; aspect-ratio:1; object-fit:cover;">
                            <h6 class="text-white">Uptown Funk</h6>
                            <div class="artist text-white-50 small">Mark Ronson ft. Bruno Mars</div>
                            <button class="play-button" title="Reproducir Uptown Funk">
                                <i class="bi bi-play-fill fs-4"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Canción de prueba 3 -->
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                        <div class="song-card position-relative"
                             data-uri="spotify:track:7qiZfU4dY1lWllzX7mPBI3"
                             data-titulo="Shape of You"
                             data-artista="Ed Sheeran"
                             data-imagen="https://i.scdn.co/image/ab67616d0000b273ba5db46f4b838ef6027e6f96">
                            <img src="https://i.scdn.co/image/ab67616d0000b273ba5db46f4b838ef6027e6f96"
                                 alt="Shape of You"
                                 class="img-fluid rounded mb-2"
                                 style="width:100%; aspect-ratio:1; object-fit:cover;">
                            <h6 class="text-white">Shape of You</h6>
                            <div class="artist text-white-50 small">Ed Sheeran</div>
                            <button class="play-button" title="Reproducir Shape of You">
                                <i class="bi bi-play-fill fs-4"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Canción local de prueba -->
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                        <div class="song-card position-relative"
                             data-audio="{{ asset('musica/Levitating.mp3') }}"
                             data-titulo="Levitating (Local)"
                             data-artista="Dua Lipa"
                             data-imagen="{{ asset('imagenes/levitating.jpg') }}">
                            <img src="{{ asset('imagenes/levitating.jpg') }}"
                                 alt="Levitating Local"
                                 class="img-fluid rounded mb-2"
                                 style="width:100%; aspect-ratio:1; object-fit:cover;">
                            <h6 class="text-white">Levitating (Local)</h6>
                            <div class="artist text-white-50 small">Dua Lipa</div>
                            <button class="play-button" title="Reproducir Levitating Local">
                                <i class="bi bi-play-fill fs-4"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mt-5">
                    <h4 class="text-white mb-3">Instrucciones:</h4>
                    <div class="card bg-dark border-secondary">
                        <div class="card-body">
                            <ol class="text-white-50">
                                <li>Haz clic en cualquier botón verde <i class="bi bi-play-fill text-success"></i></li>
                                <li>Las canciones con URI de Spotify se reproducirán en el Web Player</li>
                                <li>La canción local se reproducirá con el reproductor HTML5</li>
                                <li>Mira el reproductor en la parte inferior de la página</li>
                                <li>El icono del reproductor inferior debe cambiar a <i class="fab fa-spotify text-success"></i> para Spotify</li>
                            </ol>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-warning">
                    <h5><i class="fas fa-exclamation-triangle me-2"></i>Spotify Premium Requerido</h5>
                    <p>Para probar la reproducción completa, necesitas una cuenta Premium de Spotify.</p>
                    <a href="{{ route('spotify.account.status') }}" class="btn btn-success">Verificar Estado</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
