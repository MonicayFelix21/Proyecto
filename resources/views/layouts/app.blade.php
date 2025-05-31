<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Mi Spotify')</title>
     @stack('styles') 

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
    body {
        background-color: #121212;
    }

    .main-gradient-bg {
    background: linear-gradient(to bottom, #1e1e1e 0%, #121212 100%);
}

.song-card {
    background-color: #181818;
    border-radius: 8px;
    padding: 16px;
    transition: background-color 0.2s ease;
}

.song-card:hover {
    background-color: #282828;
}

.song-card img {
    width: 100%;
    aspect-ratio: 1;
    object-fit: cover;
    border-radius: 4px;
    margin-bottom: 12px;
}

.song-card h6 {
    font-size: 14px;
    font-weight: 700;
    color: white;
    margin-bottom: 4px;
    text-align: left;
}

.song-card .artist {
    font-size: 13px;
    color: #b3b3b3;
    text-align: left;
    display: flex;
    align-items: center;
    gap: 4px;
}
.play-button {
    position: absolute;
    bottom: 16px;
    right: 16px;
    background-color: #1ed760;
    color: black;
    border-radius: 50%;
    width: 44px;
    height: 44px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 24px;
    opacity: 0;
    transform: translateY(10px);
    transition: opacity 0.2s ease, transform 0.2s ease;
    cursor: pointer;
}

.song-card:hover .play-button {
    opacity: 1;
    transform: translateY(0);
}

    .buscador-wrapper {
        background-color: #2a2a2a;
        border-radius: 999px;
        width: 100%;
        max-width: 600px;
        padding: 0.4rem 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: border 0.3s ease;
        border: 2px solid transparent;
    }

    .buscador-wrapper:focus-within {
        border: 2px solid white;
    }

    .search-bar input::placeholder,
    .buscador-wrapper input::placeholder {
        color: #b3b3b3;
        font-weight: 400;
    }

    #sugerencias div:hover {
        background-color: #2a2a2a;
        cursor: pointer;
    }
    
.sidebar {
    height: 100vh;
    overflow-y: auto;
    scrollbar-width: thin;
}
.banner-inferior {
    background: linear-gradient(to right, #af2896, #509bf5);
    z-index: 1050;
}
</style>

</head>
<body class="bg-dark text-white">

<!-- Navbar superior -->
<nav class="navbar navbar-dark bg-black px-3 py-2 fixed-top" style="z-index: 1030;">
    <div class="d-flex align-items-center w-100 gap-3">

        <!-- Logo Spotify -->
        <a href="/inicio">
            <img src="{{ asset('imagenes/spotify.png') }}" alt="Spotify" style="height: 32px;">
        </a>

        <!-- Botón de inicio -->
        <a href="/inicio" class="btn rounded-circle d-flex align-items-center justify-content-center"
           style="width: 40px; height: 40px; background-color: #2a2a2a;">
            <i class="bi bi-house-door-fill text-white"></i>
        </a>

        <!-- Contenedor búsqueda + sugerencias -->
        <div class="position-relative" style="max-width: 600px; width: 100%;">
            <div class="buscador-wrapper">
                
                <!-- Icono de búsqueda -->
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-search text-white fs-5"></i>
                    <input id="busqueda"
                           type="text"
                           class="form-control bg-transparent border-0 text-white p-0"
                           placeholder="¿Qué quieres reproducir?"
                           style="outline: none; box-shadow: none;" />
                </div>

                <!-- Separador y botón -->
                <div class="d-flex align-items-center">
                    <div style="width: 1px; height: 24px; background-color: rgba(255,255,255,0.3);" class="mx-3"></div>
                    <button class="btn p-0" style="width: 30px; height: 30px;">
                        <i class="bi bi-inbox text-white fs-5"></i>
                    </button>
                </div>
            </div>

            <!-- Sugerencias -->
            <div id="sugerencias"
                 class="position-absolute bg-dark text-white rounded mt-1 px-3 py-2 w-100"
                 style="z-index: 1000; display: none;"></div>
        </div>

      <!-- DERECHA: enlaces y botones -->
        <div class="d-flex align-items-center gap-4 ms-auto">
            <a href="#" class="text-white-50 fw-semibold text-decoration-none">Premium</a>
            <a href="#" class="text-white-50 fw-semibold text-decoration-none">Asistencia</a>
            <a href="#" class="text-white-50 fw-semibold text-decoration-none">Descargar</a>
            <div class="vr"></div>
            <a href="#" class="d-flex align-items-center text-white-50 text-decoration-none">
                <i class="bi bi-download me-1"></i> Instalar app
            </a>
<a href="{{ route('registro') }}" class="text-white-50 fw-semibold text-decoration-none">Registrarte</a>
            <a href="{{ route('login') }}" class="btn btn-light rounded-pill px-4 fw-bold">
                Iniciar sesión
            </a>
        </div>

    </div>
</nav>



<div class="d-flex">
    @include('components.sidebar')

<main class="flex-grow-1 p-4 text-white min-vh-100 main-gradient-bg" style="margin-left: 260px; margin-top: 56px;">
        @yield('content')
    </main>
</div>

    <!-- Contenido principal con margen izquierdo -->

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

{{-- 🎧 Reproducción automática de preview de audio al hacer hover --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const cards = document.querySelectorAll('.song-card');
        let audioPlayer = new Audio();

        cards.forEach(card => {
            const audioSrc = card.getAttribute('data-audio');

            card.addEventListener('mouseenter', () => {
                audioPlayer.src = audioSrc;
                audioPlayer.play().catch(() => {});
            });

            card.addEventListener('mouseleave', () => {
                audioPlayer.pause();
                audioPlayer.currentTime = 0;
            });
        });
    });
</script>

{{-- 🔍 Sugerencias AJAX --}}
<script>
    const inputBusqueda = document.getElementById('busqueda');
    const sugerencias = document.getElementById('sugerencias');

    inputBusqueda.addEventListener('input', function () {
        const q = this.value.trim();
        if (q.length < 2) {
            sugerencias.style.display = 'none';
            sugerencias.innerHTML = '';
            return;
        }




        fetch(`/buscar-canciones?q=${encodeURIComponent(q)}`)
            .then(res => res.json())
            .then(data => {
                if (data.length > 0) {
                    sugerencias.style.display = 'block';

        sugerencias.addEventListener('click', function (e) {
    const sugerencia = e.target.closest('.sugerencia');
    if (!sugerencia) return;

    const id = sugerencia.dataset.id;
    sugerencias.style.display = 'none';
    inputBusqueda.value = sugerencia.textContent.trim();

    fetch(`/buscar-cancion?id=${id}`)
        .then(res => res.json())
        .then(cancion => mostrarCancionSeleccionada(cancion));
});

sugerencias.innerHTML = data.map(c => `
    <div class="py-1 border-bottom sugerencia" data-id="${c.id}">
        <strong>${c.titulo}</strong><br><small>${c.artista}</small>
    </div>
`).join('');
                } else {
                    sugerencias.style.display = 'none';
                }
            });
    });

    document.addEventListener('click', (e) => {
        if (!inputBusqueda.contains(e.target) && !sugerencias.contains(e.target)) {
            sugerencias.style.display = 'none';
        }
    });
    
</script>

<script>
    const btn = document.getElementById('btn-crear-lista');
    const tooltip = document.getElementById('tooltip-crear-lista');

    if (btn && tooltip) {
        btn.addEventListener('click', () => {
            tooltip.classList.toggle('d-none');
        });

        window.cerrarTooltip = function () {
            tooltip.classList.add('d-none');
        };

        document.addEventListener('click', function (e) {
            if (!btn.contains(e.target) && !tooltip.contains(e.target)) {
                tooltip.classList.add('d-none');
            }
        });
    }
</script>
<script>
function mostrarCancionSeleccionada(cancion) {
    const contenedor = document.querySelector('.row');
    contenedor.innerHTML = `
        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
            <div class="song-card">
                <img src="/${cancion.imagen}" alt="Portada">
                <h6>${cancion.titulo}</h6>
                <div class="artist">
                    ${cancion.explicito ? '<span class="badge bg-secondary px-2 py-1">E</span>' : ''}
                    <span>${cancion.artista}</span>
                </div>
            </div>
        </div>
    `;
}
</script>

<div class="banner-inferior fixed-bottom w-100 px-4 py-3 text-white d-flex justify-content-between align-items-center">
    <div>
        <strong>Muestra de Spotify</strong><br>
        Regístrate para disfrutar de canciones y podcasts sin límites, con anuncios ocasionales. No hace falta tarjeta de crédito.
    </div>
<a href="{{ route('registro') }}" class="btn btn-light text-black fw-bold rounded-pill px-4">
    Registrarte gratis
</a>
</div>
@stack('scripts') 
</body>
</html>
