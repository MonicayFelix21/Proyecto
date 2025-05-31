<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <link href="{{ asset('css/spotify-style.css') }}" rel="stylesheet">


   
</head>
<body>
    <div class="login-container text-center">
        <!-- Logo -->
        <img src="{{ asset('imagenes/spotify.png') }}" alt="Spotify Logo" class="spotify-logo">

        <!-- Título -->
        <h3 class="spotify-heading">Inicia sesión en Spotify</h3>

        @if($errors->any())
            <div class="alert alert-danger text-start">{{ $errors->first() }}</div>
        @endif

        <!-- Formulario -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3 text-start">
                <label for="email" class="form-label">Correo electrónico o nombre de usuario</label>
                <input type="email" name="email" id="email"
                       class="form-control input-spotify"
                       placeholder="Correo electrónico o nombre de usuario"
                       required autofocus>
            </div>

<div class="mb-3 text-start">
    <label for="password" class="form-label">Contraseña</label>
    <div class="position-relative">
        <input type="password" name="password" id="password"
               class="form-control input-spotify pe-5"
               placeholder="Contraseña"
               required>
        <i class="bi bi-eye-slash toggle-password"
           style="position: absolute; top: 50%; right: 1rem; transform: translateY(-50%); font-size: 1.2rem; color: #b3b3b3; cursor: pointer;"></i>
    </div>
</div>


            <button type="submit" class="btn btn-green w-100 mt-3">Iniciar sesión</button>
        </form>

        <div class="mt-4 text-center small">
            <a href="#">¿Se te ha olvidado la contraseña?</a><br>
    <span class="text-white-50">¿No tienes cuenta?</span>
<a href="{{ route('registro') }}" id="enlace-suscripcion">Suscríbete a Spotify</a>

        </div>
    </div>
<script>
    document.getElementById('enlace-suscripcion')?.addEventListener('click', function () {
        localStorage.setItem('registro_nuevo', 'true');
    });
</script>

    <!-- Mostrar/ocultar contraseña -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const togglePassword = document.querySelector('.toggle-password');
            const passwordField = document.getElementById('password');

            togglePassword.addEventListener('click', () => {
                const isPassword = passwordField.type === 'password';
                passwordField.type = isPassword ? 'text' : 'password';
                togglePassword.classList.toggle('bi-eye');
                togglePassword.classList.toggle('bi-eye-slash');
            });
        });
    </script>
</body>
</html>
