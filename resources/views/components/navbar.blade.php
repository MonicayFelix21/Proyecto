{{-- resources/views/components/navbar.blade.php --}}
<nav class="navbar navbar-dark bg-black px-3 py-2 fixed-top" style="z-index:1030;">
  <div class="d-flex align-items-center w-100 gap-3">
    {{-- Logo de inicio --}}
    <a href="{{ route('inicio') }}">
      <img src="{{ asset('imagenes/spotify.png') }}" alt="Spotify" style="height:32px;">
    </a>

    {{-- Botón Home --}}
    <a href="{{ route('inicio') }}"
       class="btn rounded-circle d-flex align-items-center justify-content-center"
       style="width:40px; height:40px; background-color:#2a2a2a;">
      <i class="bi bi-house-door-fill text-white"></i>
    </a>

    {{-- Componente del buscador --}}
    <x-search-box />

    {{-- Links de navegación secundaria --}}
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
