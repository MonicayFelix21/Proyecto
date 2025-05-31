<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Regístrate - Mi Spotify</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

   <style>
    body {
        background-color: #121212;
        color: #fff;
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
        min-height: 100vh;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
    }

    .register-container {
        width: 100%;
        max-width: 480px;
        padding: 2rem;
        background-color: #121212;
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

    /* Estilo unificado para todos los campos */
    input.form-control,
    select.form-control {
        background-color: #121212;
        color: white;
        border: 1px solid #666;
        border-radius: 6px;
        height: 45px;
    }

    input::placeholder {
        color: #b3b3b3;
    }

    input.form-control:focus,
    select.form-control:focus {
        background-color: #121212;
        color: white;
        border-color: white;
        outline: none;
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

    .hidden {
        display: none;
    }

    /* Contraseña con ícono de mostrar/ocultar */
    .input-password-wrapper {
        position: relative;
    }

    .input-password-wrapper input {
        width: 100%;
        padding-right: 40px;
        background-color: #121212;
        border: 1px solid #b3b3b3;
        color: white;
        border-radius: 6px;
        height: 45px;
    }

    .input-password-wrapper input:focus {
        border-color: white;
        outline: none;
        box-shadow: none;
    }

    .toggle-password {
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
        color: #b3b3b3;
        cursor: pointer;
    }

    .requirement {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 6px;
        font-size: 0.95rem;
    }

    .requirement.neutral {
        color: white;
    }

    .requirement.valid {
        color: #1ed760;
    }

    .requirement.invalid {
        color: #e91429;
    }

    /* Eliminar flechas de input number */
    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type=number] {
        -moz-appearance: textfield;
    }

    /* Radios personalizados estilo Spotify */
    .radio-custom {
        display: inline-flex;
        align-items: center;
        cursor: pointer;
        margin: 0.5rem 1rem 0.5rem 0;
        font-weight: 500;
        color: white;
        position: relative;
        padding-left: 28px;
        user-select: none;
    }

    .radio-custom input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    .radio-custom .checkmark {
        position: absolute;
        left: 0;
        top: 2px;
        height: 18px;
        width: 18px;
        border-radius: 50%;
        border: 2px solid white;
        background-color: transparent;
    }

    .radio-custom input:checked ~ .checkmark {
        border-color: #1db954;
        background-color: transparent;
    }

    .radio-custom .checkmark::after {
        content: "";
        position: absolute;
        display: none;
    }

    .radio-custom input:checked ~ .checkmark::after {
        display: block;
    }

    .radio-custom .checkmark::after {
        top: 3px;
        left: 3px;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #1db954;
    }
</style>

</head>
<body>
<div class="register-container text-start">
    <img src="{{ asset('imagenes/spotify.png') }}" alt="Spotify Logo" class="spotify-logo">
<h2 id="titulo-inicial" class="text-center">Regístrate<br>para empezar<br>a escuchar contenido</h2>

    @if($errors->any())
        <div class="alert alert-danger text-start">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('registro') }}">
        @csrf

        {{-- Paso 1: Email --}}
<div id="step-email">
    <div class="mb-3">
        <label for="email" class="form-label fw-bold">Dirección de correo electrónico</label>
<input 
  type="email" 
  name="email" 
  id="email" 
  class="form-control"
  placeholder="nombre@dominio.com" 
  autocomplete="off"
  required 
  oninput="verificarEmail()" 
/>
        <small id="email-feedback" class="text-danger d-none">Este correo ya está registrado.</small>
    </div>
    <button type="button" id="next-btn-email" class="btn btn-green w-100 py-2" onclick="goToStep2()" disabled>Siguiente</button>
</div>


        {{-- Paso 2: Contraseña --}}
        <div id="step-password" class="hidden">
            <div class="mb-4">
                <a href="javascript:void(0)" onclick="backToStep1()" class="text-decoration-none text-white">
                    <i class="bi bi-arrow-left-short fs-3"></i>
                </a>
                <p class="mb-1 mt-2 text-success fw-semibold">Paso 2 de 3</p>
                <h5 class="fw-bold">Crea una contraseña</h5>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-bold">Contraseña</label>
<div class="input-password-wrapper">
    <input type="password" name="password" id="password"
           class="form-control"
           placeholder="Contraseña"
           required oninput="checkPassword()">
    <i class="bi bi-eye-slash toggle-password" id="toggle-icon" onclick="togglePassword()"></i>
</div>

            </div>

            <div class="mb-4">
                <p class="fw-bold small mb-2">La contraseña debe contener al menos:</p>
                <ul class="list-unstyled mb-0">
                    <li id="req-letter" class="requirement neutral"><i class="bi me-2" id="icon-letter"></i>1 letra</li>
                    <li id="req-special" class="requirement neutral"><i class="bi me-2" id="icon-special"></i>1 número o carácter especial (por ejemplo, "#", "?", "!" o "&")</li>
                    <li id="req-length" class="requirement neutral"><i class="bi me-2" id="icon-length"></i>10 caracteres</li>
                </ul>
            </div>

<button id="submit-btn" type="button" class="btn btn-green w-100 py-2" disabled onclick="goToStep3()">Siguiente</button>
        </div>

        <!-- Paso 3: Perfil -->
<div id="step-profile" class="hidden">
    <div class="mb-4">
        <a href="javascript:void(0)" onclick="backToStep2()" class="text-decoration-none text-white">
            <i class="bi bi-arrow-left-short fs-3"></i>
        </a>
        <p class="mb-1 mt-2 text-success fw-semibold">Paso 3 de 3</p>
        <h5 class="fw-bold">Háblanos de ti</h5>
    </div>

    <div class="mb-3">
        <label for="nombre" class="form-label fw-bold">Nombre</label>
        <small class="d-block mb-1 text-white-50">Este nombre aparecerá en tu perfil</small>
        <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Tu nombre" required>
    </div>

    <div class="mb-3">
        <label class="form-label fw-bold">Fecha de nacimiento</label>
        <small class="d-block mb-1 text-white-50">
            ¿Por qué necesitamos tu fecha de nacimiento?
            <a href="#" class="text-decoration-underline text-white-50">Más información.</a>
        </small>
        <div class="d-flex gap-2">
            <input type="number" name="dia" id="dia" class="form-control" placeholder="dd" min="1" max="31" required>
            <select name="mes" id="mes" class="form-control" required>
                <option value="" disabled selected>Mes</option>
                <option>Enero</option>
                <option>Febrero</option>
                <option>Marzo</option>
                <option>Abril</option>
                <option>Mayo</option>
                <option>Junio</option>
                <option>Julio</option>
                <option>Agosto</option>
                <option>Septiembre</option>
                <option>Octubre</option>
                <option>Noviembre</option>
                <option>Diciembre</option>
            </select>
            <input type="number" name="anio" id="anio" class="form-control" placeholder="aaaa" min="1900" max="2025" required>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label fw-bold">Género</label>
        <small class="d-block mb-1 text-white-50">Usamos tu género para personalizar nuestras recomendaciones.</small>
<label class="radio-custom">
  <input type="radio" name="genero" value="Hombre" required>
  <span class="checkmark"></span>
  Hombre
</label>
<label class="radio-custom">
  <input type="radio" name="genero" value="Mujer" required>
  <span class="checkmark"></span>
  Mujer
</label>
<label class="radio-custom">
  <input type="radio" name="genero" value="Prefiero no responder" required>
  <span class="checkmark"></span>
  Prefiero no responder
</label>

    </div>

    <button type="submit" class="btn btn-green w-100 py-2" onclick="return validateStep3()">Registrarse</button>
</div>
    </form>

    <p class="mt-4 text-white-50 text-center">¿Ya tienes una cuenta? <a href="{{ route('login') }}" class="small-link">Inicia sesión aquí.</a></p>
</div>




<script>
function verificarEmail() {
    const emailInput = document.getElementById('email');
    const email = emailInput.value.trim();
    const feedback = document.getElementById('email-feedback');
    const nextBtn = document.getElementById('next-btn-email');

    if (!email || !email.includes('@')) {
        feedback.classList.add('d-none');
        nextBtn.disabled = true;
        return;
    }

    fetch(`/verificar-email?email=${encodeURIComponent(email)}`)
        .then(response => response.json())
        .then(data => {
            if (data.existe) {
                feedback.classList.remove('d-none');
                nextBtn.disabled = true;
            } else {
                feedback.classList.add('d-none');
                nextBtn.disabled = false;
            }
        })
        .catch(() => {
            feedback.classList.add('d-none');
            nextBtn.disabled = true;
        });
}

function goToStep2() {
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password'); // Nuevo

    if (emailInput.checkValidity()) {
        // Limpiar campo de contraseña
        if (passwordInput) {
            passwordInput.value = '';
            checkPassword(); // Resetea los requisitos visuales
        }

        document.getElementById('step-email').classList.add('hidden');
        document.getElementById('step-password').classList.remove('hidden');
        document.getElementById('titulo-inicial').style.display = 'none';
    } else {
        emailInput.reportValidity();
    }
}
function backToStep1() {
    const emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.value = '';
        verificarEmail(); // Para que se reinicie el botón y feedback
    }

    document.getElementById('step-password').classList.add('hidden');
    document.getElementById('step-email').classList.remove('hidden');
    document.getElementById('titulo-inicial').style.display = 'block';
}


function goToStep3() {
    document.getElementById('step-password').classList.add('hidden');
    document.getElementById('step-profile').classList.remove('hidden');
    document.getElementById('titulo-inicial').style.display = 'none';
}

function backToStep2() {
    document.getElementById('step-profile').classList.add('hidden');
    document.getElementById('step-password').classList.remove('hidden');
    document.getElementById('titulo-inicial').style.display = 'none';
}

    function togglePassword() {
        const input = document.getElementById('password');
        const icon = document.getElementById('toggle-icon');

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("bi-eye-slash");
            icon.classList.add("bi-eye");
        } else {
            input.type = "password";
            icon.classList.remove("bi-eye");
            icon.classList.add("bi-eye-slash");
        }
    }

    function checkPassword() {
        const password = document.getElementById('password').value;
        const submitBtn = document.getElementById('submit-btn');

        const hasLetter = /[a-zA-Z]/.test(password);
        const hasSpecial = /[\d\W]/.test(password);
        const isLongEnough = password.length >= 10;

        if (password.length === 0) {
            resetToNeutral('req-letter');
            resetToNeutral('req-special');
            resetToNeutral('req-length');
            submitBtn.disabled = true;
        } else {
            updateRequirement('req-letter', hasLetter);
            updateRequirement('req-special', hasSpecial);
            updateRequirement('req-length', isLongEnough);

            submitBtn.disabled = !(hasLetter && hasSpecial && isLongEnough);
        }
    }

    function updateRequirement(id, condition) {
        const el = document.getElementById(id);
        const icon = document.getElementById('icon-' + id.split('-')[1]);

        el.classList.remove('neutral', 'valid', 'invalid');
        el.classList.add(condition ? 'valid' : 'invalid');

        icon.className = 'bi me-2 ' + (condition ? 'bi-check-circle-fill text-success' : 'bi-circle text-secondary');
    }

    function resetToNeutral(id) {
        const el = document.getElementById(id);
        const icon = document.getElementById('icon-' + id.split('-')[1]);

        el.classList.remove('valid', 'invalid');
        el.classList.add('neutral');

        icon.className = 'bi me-2 bi-circle text-white';
    }

    function validateStep3() {
        const nombre = document.getElementById('nombre').value.trim();
        const dia = document.getElementById('dia').value;
        const mes = document.getElementById('mes').value;
        const anio = document.getElementById('anio').value;
        const generoSeleccionado = document.querySelector('input[name="genero"]:checked');

        if (!nombre || !dia || !mes || !anio || !generoSeleccionado) {
            alert('Por favor completa todos los campos antes de continuar.');
            return false;
        }

        return true;
    }
    function resetFormularioRegistro() {
    // Limpiar campos de texto
    document.getElementById('email').value = '';
    document.getElementById('password').value = '';
    document.getElementById('nombre').value = '';
    document.getElementById('dia').value = '';
    document.getElementById('mes').selectedIndex = 0;
    document.getElementById('anio').value = '';

    // Limpiar radios
    const radios = document.querySelectorAll('input[name="genero"]');
    radios.forEach(r => r.checked = false);

    // Ocultar errores
    const feedback = document.getElementById('email-feedback');
    if (feedback) feedback.classList.add('d-none');

    // Desactivar botones
    document.getElementById('next-btn-email').disabled = true;
    document.getElementById('submit-btn').disabled = true;

    // Reset visual
    document.getElementById('step-password').classList.add('hidden');
    document.getElementById('step-profile').classList.add('hidden');
    document.getElementById('step-email').classList.remove('hidden');
    document.getElementById('titulo-inicial').style.display = 'block';

    // Reset de validación visual de contraseña
    resetToNeutral('req-letter');
    resetToNeutral('req-special');
    resetToNeutral('req-length');

    // Focus automático en email
    document.getElementById('email').focus();
}

    window.addEventListener('DOMContentLoaded', () => {
    if (localStorage.getItem('registro_nuevo') === 'true') {
        resetFormularioRegistro();
        localStorage.removeItem('registro_nuevo');
    }
});
</script>

</body>
</html>
