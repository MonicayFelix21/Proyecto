{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>@yield('title', 'Mi Spotify')</title>

  {{-- Bootstrap CSS + Icons --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  {{-- Estilos globales --}}
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
      position: relative;
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
      color: #fff;
      margin-bottom: 4px;
      text-align: left;
    }
    .song-card .artist {
      font-size: 13px;
      color: #b3b3b3;
      display: flex;
      align-items: center;
      gap: 4px;
    }
    .play-button {
      position: absolute;
      bottom: 16px;
      right: 16px;
      background-color: #1ed760;
      color: #000;
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
      padding: .4rem 1rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border: 2px solid transparent;
      transition: border 0.3s ease;
    }
    .buscador-wrapper:focus-within {
      border-color: #fff;
    }
    .buscador-wrapper input::placeholder {
      color: #b3b3b3;
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

  {{-- Styles desde vistas --}}
  @stack('styles')
</head>
<body class="bg-dark text-white">

  {{-- Navbar --}}
  <nav class="navbar navbar-dark bg-black px-3 py-2 fixed-top" style="z-index:1030;">
    <div class="d-flex align-items-center w-100 gap-3">
      <a href="{{ route('inicio') }}">
        <img src="{{ asset('imagenes/spotify.png') }}" alt="Spotify" style="height:32px;">
      </a>
      <a href="{{ route('inicio') }}"
         class="btn rounded-circle d-flex align-items-center justify-content-center"
         style="width:40px; height:40px; background-color:#2a2a2a;">
        <i class="bi bi-house-door-fill text-white"></i>
      </a>

      <div class="position-relative" style="max-width:600px; width:100%;">
        <div class="buscador-wrapper">
          <div class="d-flex align-items-center gap-2">
            <i class="bi bi-search text-white fs-5"></i>
            <input id="busqueda" type="text"
                   class="form-control bg-transparent border-0 text-white p-0"
                   placeholder="¿Qué quieres reproducir?" style="outline:none; box-shadow:none;">
          </div>
          <div class="d-flex align-items-center">
            <div style="width:1px; height:24px; background: rgba(255,255,255,0.3);" class="mx-3"></div>
            <button class="btn p-0" style="width:30px; height:30px;">
              <i class="bi bi-inbox text-white fs-5"></i>
            </button>
          </div>
        </div>
        <div id="sugerencias"
             class="position-absolute bg-dark text-white rounded mt-1 px-3 py-2 w-100"
             style="z-index:1000; display:none;"></div>
      </div>

      <div class="d-flex align-items-center gap-4 ms-auto">
        <a href="#" class="text-white-50 fw-semibold">Premium</a>
        <a href="#" class="text-white-50 fw-semibold">Asistencia</a>
        <a href="#" class="text-white-50 fw-semibold">Descargar</a>
        <div class="vr"></div>
        <a href="#" class="d-flex align-items-center text-white-50">
          <i class="bi bi-download me-1"></i> Instalar app
        </a>
        <a href="{{ route('registro') }}" class="text-white-50 fw-semibold">Registrarte</a>
        <a href="{{ route('login') }}" class="btn btn-light rounded-pill px-4 fw-bold">Iniciar sesión</a>
      </div>
    </div>
  </nav>

  <div class="d-flex">
    @include('components.sidebar')

    <main class="flex-grow-1 p-4 text-white min-vh-100 main-gradient-bg"
          style="margin-left:260px; margin-top:56px;">
      @yield('content')
    </main>
  </div>

  {{-- Banner inferior --}}
  <div class="banner-inferior fixed-bottom w-100 px-4 py-3 text-white d-flex justify-content-between align-items-center">
    <div>
      <strong>Muestra de Spotify</strong><br>
      Regístrate para disfrutar de canciones y podcasts sin límites, con anuncios ocasionales. No hace falta tarjeta de crédito.
    </div>
    <a href="{{ route('registro') }}" class="btn btn-light text-black fw-bold rounded-pill px-4">
      Registrarte gratis
    </a>
  </div>

  {{-- Bootstrap JS --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  {{-- Scripts globales --}}
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // Play on hover
      const cards = document.querySelectorAll('.song-card');
      let player = new Audio();
      cards.forEach(card => {
        const src = card.dataset.audio;
        card.addEventListener('mouseenter', () => {
          player.src = src;
          player.play().catch(() => {});
        });
        card.addEventListener('mouseleave', () => {
          player.pause();
          player.currentTime = 0;
        });
      });

      // Sugerencias de búsqueda
      const input = document.getElementById('busqueda'),
            suger = document.getElementById('sugerencias');
      input.addEventListener('input', () => {
        const q = input.value.trim();
        if (q.length < 2) {
          suger.style.display = 'none';
          return;
        }
        fetch(`/buscar-canciones?q=${encodeURIComponent(q)}`)
          .then(res => res.json())
          .then(data => {
            if (data.length) {
              suger.innerHTML = data.map(c => `
                <div class="py-1 border-bottom sugerencia" data-id="${c.id}">
                  <strong>${c.titulo}</strong><br>
                  <small>${c.artista}</small>
                </div>
              `).join('');
              suger.style.display = 'block';
            } else {
              suger.style.display = 'none';
            }
          });
      });
      document.addEventListener('click', e => {
        if (!input.contains(e.target) && !suger.contains(e.target)) {
          suger.style.display = 'none';
        }
      });
    });
  </script>

  {{-- Scripts desde vistas --}}
  @stack('scripts')
</body>
</html>
