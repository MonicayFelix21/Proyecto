@extends('layouts.app')

@section('title', 'Inicio - Mi Spotify')

@push('styles')
<style>
    #carrusel-artistas::-webkit-scrollbar {
        display: none;
    }

    #carrusel-artistas {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .grupo-carrusel:hover .btn-scroll {
        opacity: 1;
    }

    .btn-scroll {
        opacity: 0;
        transition: opacity 0.3s ease;
    }
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
                <div class="play-button" title="Reproducir {{ $cancion->titulo }}">
                    <i class="bi bi-play-fill"></i>
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Artistas populares -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0">Artistas populares</h3>
    <a href="{{ route('artistas.index') }}" class="text-white-50 text-decoration-none fw-semibold">Mostrar todos</a>
</div>

<!-- Carrusel de artistas -->
<div class="position-relative mx-auto grupo-carrusel"
     style="max-width: 1150px; overflow: visible;">


    <!-- Flecha izquierda -->
    <button id="scroll-izquierda"
            class="btn btn-scroll position-absolute d-flex justify-content-center align-items-center"
            style="top: 50%; left: 0; transform: translate(-50%, -50%);
                   background-color: #1a1a1a; border-radius: 50%; width: 40px; height: 40px; z-index: 10;">
        <i class="bi bi-chevron-left text-white fs-5"></i>
    </button>

    <!-- Flecha derecha -->
    <button id="scroll-derecha"
            class="btn btn-scroll position-absolute d-flex justify-content-center align-items-center"
            style="top: 50%; right: 0; transform: translate(50%, -50%);
                   background-color: #1a1a1a; border-radius: 50%; width: 40px; height: 40px; z-index: 10;">
        <i class="bi bi-chevron-right text-white fs-5"></i>
    </button>

    <!-- Lista horizontal de artistas -->
    <div id="carrusel-artistas"
         class="d-flex flex-nowrap gap-4 px-2"
         style="scroll-behavior: smooth; overflow-x: auto;">
        @foreach($artistas as $artista)
            <div class="text-center" style="flex: 0 0 auto; width: 160px;">
                <img src="{{ asset($artista->imagen) }}" alt="{{ $artista->nombre }}"
                     class="img-fluid rounded-circle mb-2 hover-zoom"
                     style="width: 160px; height: 160px; object-fit: cover; transition: transform 0.3s;">
                <p class="mb-0 fw-semibold text-white">{{ $artista->nombre }}</p>
                <small class="text-white-50">Artista</small>
            </div>
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const carrusel = document.getElementById('carrusel-artistas');
        const btnIzq = document.getElementById('scroll-izquierda');
        const btnDer = document.getElementById('scroll-derecha');

        function actualizarVisibilidadFlechas() {
            const scrollLeft = carrusel.scrollLeft;
            const maxScrollLeft = carrusel.scrollWidth - carrusel.clientWidth;

            // Mostrar flechas solo si el carrusel es más ancho que su contenedor
            const hayScrollHorizontal = carrusel.scrollWidth > carrusel.clientWidth;

            btnIzq.style.display = hayScrollHorizontal && scrollLeft > 10 ? 'flex' : 'none';
            btnDer.style.display = hayScrollHorizontal && scrollLeft < maxScrollLeft - 10 ? 'flex' : 'none';
        }

        // Eventos de scroll y resize
        carrusel.addEventListener('scroll', actualizarVisibilidadFlechas);
        window.addEventListener('resize', actualizarVisibilidadFlechas);

        // Mover a la derecha
        btnDer.addEventListener('click', () => {
            carrusel.scrollBy({ left: 320, behavior: 'smooth' });
        });

        // Mover a la izquierda
        btnIzq.addEventListener('click', () => {
            carrusel.scrollBy({ left: -320, behavior: 'smooth' });
        });

        // Ejecutar tras renderizado completo
        setTimeout(actualizarVisibilidadFlechas, 300);
    });
</script>
@endpush

