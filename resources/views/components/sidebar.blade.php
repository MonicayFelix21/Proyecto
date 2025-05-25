<div class="bg-black text-white d-flex flex-column p-3 h-100 position-fixed" style="width: 260px; top: 56px;">
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Tu biblioteca</h5>
        <i class="bi bi-plus-lg fs-5"></i>
    </div>

<!-- Bloque: Crear lista -->
<div class="bg-dark rounded p-3 mb-3 position-relative">
    <h6 class="fw-bold">Crea tu primera lista</h6>
    <p class="text-white-50 mb-3">Es muy fácil, y te echaremos una mano.</p>

    <!-- BOTÓN con id -->
    <button id="btn-crear-lista" class="btn btn-light fw-bold px-3 py-2 rounded-pill">Crear lista</button>

    <!-- TOOLTIP oculto -->
    <div id="tooltip-crear-lista"
         class="d-none position-absolute bg-primary text-white p-3 rounded shadow"
         style="width: 260px; top: 100%; left: 50%; transform: translateX(-50%); margin-top: 12px; z-index: 1050;">
        <!-- Flechita visual -->
        <div style="position: absolute; top: -8px; left: 50%; transform: translateX(-50%);
                    width: 0; height: 0; border-left: 8px solid transparent; border-right: 8px solid transparent;
                    border-bottom: 8px solid #0d6efd;"></div>

        <h6 class="fw-bold mb-2">Crear una lista</h6>
        <p class="mb-3">Para crear y compartir listas, inicia sesión.</p>
        <div class="d-flex justify-content-between">
            <button onclick="cerrarTooltip()" class="btn btn-link text-white text-decoration-none p-0">Ahora no</button>
            <a href="{{ route('login') }}" class="btn btn-light fw-bold px-3 py-1 rounded-pill">Iniciar sesión</a>
        </div>
    </div>
</div>
    <!-- Segundo bloque: Podcasts -->
    <div class="bg-dark rounded p-3">
        <h6 class="fw-bold mb-2">Encuentra pódcasts que quieras seguir</h6>
        <p class="small text-white-50">Te avisaremos cuando salgan nuevos episodios</p>
    </div>
</div>
