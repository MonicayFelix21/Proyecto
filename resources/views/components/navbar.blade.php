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

{{-- Navegación derecha dinámica --}}
<div class="d-flex align-items-center gap-3 ms-auto">

  @auth
    <a href="#" class="text-white-50 d-flex align-items-center gap-1 text-decoration-none">
      <i class="bi bi-download"></i> Instalar app
    </a>
    <i class="bi bi-bell text-white-50 fs-5"></i>
    <i class="bi bi-people text-white-50 fs-5"></i>

    @php
      $letra = strtoupper(substr(Auth::user()->name, 0, 1));
    @endphp
<div class="dropdown">
  <button class="btn p-0 border-0 bg-transparent dropdown-toggle"
          type="button"
          id="dropdownUsuario"
          data-bs-toggle="dropdown"
          aria-expanded="false">
    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center fw-bold"
         style="width:36px; height:36px;">
      {{ $letra }}
    </div>
  </button>

<ul class="dropdown-menu dropdown-menu-end spotify-menu shadow"
    aria-labelledby="dropdownUsuario">
  <li><a class="dropdown-item" href="#">Cuenta <i class="bi bi-box-arrow-up-right"></i></a></li>
  <li><a class="dropdown-item" href="#">Configurar el plan Familiar <i class="bi bi-box-arrow-up-right"></i></a></li>
  <li><a class="dropdown-item" href="#">Perfil</a></li>
  <li><a class="dropdown-item" href="#">Asistencia <i class="bi bi-box-arrow-up-right"></i></a></li>
  <li><a class="dropdown-item" href="#">Descargar <i class="bi bi-box-arrow-up-right"></i></a></li>
  <li><a class="dropdown-item" href="#">Configuración</a></li>
  <li><hr class="dropdown-divider"></li>
  <li>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button class="dropdown-item text-danger" type="submit">Cerrar sesión</button>
    </form>
  </li>
</ul>

  @else
    <a href="#" class="text-white-50 fw-semibold">Premium</a>
    <a href="#" class="text-white-50 fw-semibold">Asistencia</a>
    <a href="#" class="text-white-50 fw-semibold">Descargar</a>
    <div class="vr"></div>
    <a href="#" class="d-flex align-items-center text-white-50">
      <i class="bi bi-download me-1"></i> Instalar app
    </a>
    <a href="{{ route('registro') }}" class="text-white-50 fw-semibold">Registrarte</a>
    <a href="{{ route('login') }}" class="btn btn-light rounded-pill px-4 fw-bold">Iniciar sesión</a>
  @endauth

</div>

</nav>
