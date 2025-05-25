<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(#121212, #000);
            color: #fff;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        }

        .login-container {
            max-width: 400px;
            margin: auto;
            margin-top: 100px;
            background-color: #181818;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
        }

        .spotify-logo {
            display: block;
            margin: 0 auto 1rem;
            height: 40px;
        }

        .spotify-heading {
            text-align: center;
            font-weight: 700;
            font-size: 24px;
            letter-spacing: -0.5px;
            margin-bottom: 2rem;
        }

        .input-spotify {
            background-color: #121212;
            color: #fff;
            border: 1px solid #555;
            border-radius: 4px;
            padding: 12px 16px;
            transition: border 0.3s ease, box-shadow 0.3s ease;
            box-shadow: none;
            outline: none;
        }

        .input-spotify:hover {
            border: 1px solid white;
        }

        .input-spotify:focus,
        .input-spotify:active {
            background-color: #121212 !important;
            color: #fff;
            border: 1px solid #555 !important;
            box-shadow: none !important;
            outline: none;
        }

        .input-spotify::placeholder {
            color: #b3b3b3;
            font-weight: 400;
        }

.btn-green {
    background-color: #1ed760;
    color: #000;
    font-weight: 700;
    padding: 12px 24px;
    border: none;
    border-radius: 999px;
    transition: background-color 0.3s ease;
}

.btn-green:hover {
    background-color: #1fdf64;
    color: #000;
}

        a {
            color: #1db954;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .toggle-password {
            color: #b3b3b3;
            font-size: 1.2rem;
            cursor: pointer;
            z-index: 2;
            right: 0.75rem;
        }

        .toggle-password:hover {
            color: white;
        }
    </style>
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
    <a href="{{ route('registro') }}">Suscríbete a Spotify</a>

        </div>
    </div>

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
