<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Regístrate - Mi Spotify</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
            color: #fff;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .register-container {
            max-width: 400px;
            width: 100%;
            padding: 2rem;
        }

        .spotify-logo {
            display: block;
            margin: 0 auto 1rem;
            height: 40px;
        }

        h2 {
            text-align: center;
            font-weight: 700;
            font-size: 24px;
            margin-bottom: 1.5rem;
        }

        .form-control {
            background-color: #121212;
            color: white;
            border: 1px solid #666;
            border-radius: 4px;
        }

        .form-control::placeholder {
            color: #b3b3b3;
        }

        .form-control:focus {
            background-color: #121212;
            color: white;
            border-color: #1db954;
            box-shadow: none;
        }

        .btn-green {
            background-color: #1db954;
            color: black;
            border-radius: 999px;
            font-weight: bold;
        }

        .btn-green:hover {
            background-color: #1ed760;
        }

        .small-link {
            font-size: 0.9rem;
            color: #1db954;
        }
    </style>
</head>
<body>
    <div class="register-container text-center">
        <img src="{{ asset('imagenes/spotify.png') }}" alt="Spotify Logo" class="spotify-logo">
        <h2>Regístrate<br>para empezar<br>a escuchar <br>contenido</h2>

        @if($errors->any())
            <div class="alert alert-danger text-start">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('registro') }}">
            @csrf
            <div class="mb-3 text-start">
                <label for="email" class="form-label fw-bold">Dirección de correo electrónico</label>
                <input type="email" name="email" id="email"
                       class="form-control"
                       placeholder="nombre@dominio.com"
                       required>
            </div>

            <div class="mb-3 text-start">
                <label for="password" class="form-label fw-bold">Contraseña</label>
                <input type="password" name="password" id="password"
                       class="form-control"
                       placeholder="Contraseña"
                       required>
            </div>

            <button type="submit" class="btn btn-green w-100 py-2">Siguiente</button>
        </form>

        <p class="mt-4 text-white-50">¿Ya tienes una cuenta? <a href="{{ route('login') }}" class="small-link">Inicia sesión aquí.</a></p>
    </div>
</body>
</html>