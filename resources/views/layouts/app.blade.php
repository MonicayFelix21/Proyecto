{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>@yield('title', 'Mi Spotify')</title>

  {{-- Bootstrap CSS + Icons --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/spotify-menu.css') }}">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <script src="{{ asset('js/carrusel.js') }}"></script>
<input id="barra-progreso" type="range" min="0" max="100" value="0" />



@if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
  @vite([
    'resources/css/app.css',
    'resources/css/home.css',   
    'resources/js/app.js',
  ])
@else
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <link rel="stylesheet" href="{{ asset('css/home.css') }}"> 
  <script src="{{ asset('js/app.js') }}" defer></script>
@endif
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

 {{-- 1) Navbar como componente --}}
  <x-navbar />

  <div class="d-flex">
    {{-- 2) Sidebar como componente --}}
    <x-sidebar />

    {{-- 3) Contenido principal --}}
    <main
      class="flex-grow-1 p-4 text-white min-vh-100 main-gradient-bg"
      style="margin-left:260px; margin-top:56px;"
    >
      @yield('content')
    </main>
  </div>

      <x-footer />

  {{-- 4) Banner inferior como componente --}}
@guest
  <x-banner-footer />
@endguest


  {{-- Bootstrap JS --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  {{-- Scripts globales --}}
  <script>
    document.addEventListener('DOMContentLoaded', () => {
     
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
@unless (request()->is('inicio'))
  <x-reproductor />
@endunless
  @stack('scripts')

</body>
</html>
