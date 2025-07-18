{{-- Sidebar colapsible estilo Spotify --}}
<div id="spotify-sidebar" 
     class="sidebar-spotify bg-black text-white d-flex flex-column h-100 position-fixed transition-all" 
     style="width: 260px; top: 56px; transition: width 0.3s ease;">
    
    @auth
    {{-- Header con botón de colapso - Solo para usuarios autenticados --}}
    <div class="d-flex justify-content-between align-items-center mb-3 px-3 pt-3">
        <div class="d-flex align-items-center gap-2 sidebar-text" 
             role="button" 
             onclick="toggleSidebar()" 
             title="Contraer Tu biblioteca">
            <i class="bi bi-collection-play fs-5 text-white-50"></i>
            <span class="sidebar-title">Tu biblioteca</span>
        </div>
        <div class="d-flex align-items-center gap-2 sidebar-text">
            <button class="sidebar-header-btn"
                    title="Crear playlist o carpeta">
                <i class="bi bi-plus-lg fs-6"></i>
            </button>
        </div>
    </div>
    @else
    {{-- Header simple para usuarios no autenticados --}}
    <div class="d-flex justify-content-between align-items-center mb-3 px-3 pt-3">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-collection-play fs-5 text-white-50"></i>
            <span class="sidebar-title">Tu biblioteca</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <button class="sidebar-header-btn" title="Crear playlist o carpeta" id="btn-crear-lista-guest">
                <i class="bi bi-plus-lg fs-6"></i>
            </button>
        </div>
    </div>
    @endauth

    @auth
    {{-- Contenido para usuarios autenticados --}}
    {{-- Filtros/Categorías (solo visible cuando expandido) --}}
    <div class="sidebar-content px-3">
        <div class="d-flex gap-2 mb-3 flex-wrap">
            <span class="sidebar-filter active">Reciente</span>
            <span class="sidebar-filter">Agregado recientemente</span>
        </div>

        {{-- Botón "Crear" destacado --}}
        <div class="create-section mb-3">
            <button type="button" title="Crear playlist o carpeta">
                <i class="bi bi-plus-lg"></i>
                Crear playlist
            </button>
        </div>

        {{-- Lista de bibliotecas --}}
        <div class="flex-grow-1 overflow-auto">
            {{-- Items de biblioteca --}}
            <div class="library-item d-flex align-items-center gap-3 p-2 rounded mb-1" role="button" onclick="window.location.href='/me-gusta'">
                <div class="library-icon bg-gradient d-flex align-items-center justify-content-center rounded"
                     style="width: 48px; height: 48px; background: linear-gradient(135deg, #450af5, #c4efd9);">
                    <i class="bi bi-heart-fill text-white"></i>
                </div>
                <div class="library-info flex-grow-1">
                    <div class="library-title">Tus me gusta</div>
                    <div class="library-subtitle text-white-50 small">Playlist • <span id="liked-songs-count">0</span> canciones</div>
                </div>
            </div>

            {{-- Mostrar playlists reales del usuario si está autenticado --}}
            <div id="user-playlists">
                {{-- Se cargarán dinámicamente --}}
            </div>
        </div>
    </div>
    @else
    {{-- Contenido para usuarios no autenticados --}}
    <div class="sidebar-content px-3 flex-grow-1">
        {{-- Crear primera playlist --}}
        <div class="guest-card mb-3 p-4 rounded-3" style="background-color: #1a1a1a;">
            <div class="mb-3">
                <h5 class="fw-bold text-white mb-2">Crea tu primera lista</h5>
                <p class="text-white-50 small mb-3">Es muy fácil, y te echaremos una mano.</p>
                <button class="btn btn-light btn-sm fw-bold px-3 py-2 rounded-pill" id="btn-crear-lista-guest2">
                    Crear lista
                </button>
            </div>
        </div>

        {{-- Encuentra pódcasts --}}
        <div class="guest-card mb-3 p-4 rounded-3" style="background-color: #1a1a1a;">
            <div class="mb-3">
                <h5 class="fw-bold text-white mb-2">Encuentra pódcasts que quieras seguir</h5>
                <p class="text-white-50 small mb-3">Te avisaremos cuando salgan nuevos episodios</p>
                <button class="btn btn-light btn-sm fw-bold px-3 py-2 rounded-pill">
                    Explorar pódcasts
                </button>
            </div>
        </div>

        {{-- Links del footer --}}
        <div class="guest-footer mt-4 pt-4" style="border-top: 1px solid #333;">
            <div class="mb-3">
                <div class="d-flex flex-wrap gap-2 mb-2">
                    <a href="#" class="text-white-50 small text-decoration-none">Legal</a>
                    <a href="#" class="text-white-50 small text-decoration-none">Centro de seguridad y privacidad</a>
                    <a href="#" class="text-white-50 small text-decoration-none">Política de Privacidad</a>
                </div>
                <div class="d-flex flex-wrap gap-2 mb-2">
                    <a href="#" class="text-white-50 small text-decoration-none">Cookies</a>
                    <a href="#" class="text-white-50 small text-decoration-none">Información sobre los anuncios</a>
                    <a href="#" class="text-white-50 small text-decoration-none">Accesibilidad</a>
                </div>
                <div class="mb-3">
                    <a href="#" class="text-white-50 small text-decoration-none">Cookies</a>
                </div>
            </div>
            
            <button class="btn btn-outline-light btn-sm rounded-pill d-flex align-items-center gap-2">
                <i class="bi bi-globe"></i>
                <span>Español de España</span>
            </button>
        </div>
    </div>
    @endauth

    @auth
    {{-- Versión colapsada (solo iconos) - Solo para usuarios autenticados --}}
    <div class="sidebar-collapsed d-none flex-column align-items-center py-3">
        <div class="mb-3" title="Expandir Tu biblioteca">
            <button class="btn btn-sm text-white-50 p-2 rounded-circle d-flex align-items-center justify-content-center"
                    style="width: 48px; height: 48px; background: none; border: none;"
                    onclick="toggleSidebar()">
                <i class="bi bi-collection-play fs-5"></i>
            </button>
        </div>
        <div class="mb-3" title="Tus me gusta">
            <div class="library-icon bg-gradient d-flex align-items-center justify-content-center rounded"
                 style="width: 48px; height: 48px; background: linear-gradient(135deg, #450af5, #c4efd9);">
                <i class="bi bi-heart-fill text-white"></i>
            </div>
        </div>
        <div class="mb-3" title="Crear playlist">
            <div class="library-icon bg-dark d-flex align-items-center justify-content-center rounded"
                 style="width: 48px; height: 48px;">
                <i class="bi bi-plus-lg text-white-50"></i>
            </div>
        </div>
        <div class="mb-3" title="Buscar podcasts">
            <div class="library-icon bg-dark d-flex align-items-center justify-content-center rounded"
                 style="width: 48px; height: 48px;">
                <i class="bi bi-mic-fill text-white-50"></i>
            </div>
        </div>
        <div class="mb-3" title="Explorar">
            <a href="{{ route('explorar') }}" class="btn btn-sm text-white-50 p-2 rounded-circle d-flex align-items-center justify-content-center"
               style="width: 48px; height: 48px; background: none; border: none;">
                <i class="bi bi-inbox fs-5"></i>
            </a>
        </div>
        <div class="mb-3" title="Perfil">
            <div class="library-icon d-flex align-items-center justify-content-center rounded-circle bg-secondary fw-bold text-white"
                 style="width: 48px; height: 48px; font-size: 1.5rem;">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
        </div>
    </div>
    @endauth
</div>

{{-- Estilos específicos para el sidebar --}}
<style>
/* Estilos para tarjetas de invitado */
.guest-card {
    transition: background-color 0.2s ease;
}

.guest-card:hover {
    background-color: #232323 !important;
}

.guest-card .btn-light {
    background-color: #fff;
    border: none;
    color: #000;
    font-weight: 600;
}

.guest-card .btn-light:hover {
    background-color: #f0f0f0;
    transform: scale(1.02);
}

.guest-footer a:hover {
    color: #fff !important;
}

.guest-footer .btn-outline-light {
    border-color: #727272;
    color: #fff;
    font-size: 12px;
    font-weight: 600;
}

.guest-footer .btn-outline-light:hover {
    background-color: #727272;
    border-color: #727272;
}

/* Responsive */
@media (max-width: 768px) {
    #spotify-sidebar {
        width: 72px !important;
    }
    
    .sidebar-content {
        display: none !important;
    }
    
    .sidebar-collapsed {
        display: flex !important;
    }
    
    .sidebar-text {
        display: none !important;
    }
}
</style>

<script>
@auth
// Función para toggle del sidebar - Solo para usuarios autenticados
function toggleSidebar() {
    const sidebar = document.getElementById('spotify-sidebar');
    const main = document.querySelector('main');
    
    sidebar.classList.toggle('collapsed');
    
    // Ajustar el margen del contenido principal
    if (sidebar.classList.contains('collapsed')) {
        main.style.marginLeft = '72px';
        localStorage.setItem('sidebarCollapsed', 'true');
    } else {
        main.style.marginLeft = '260px';
        localStorage.setItem('sidebarCollapsed', 'false');
    }
}

// Mantener estado del sidebar al recargar - Solo para usuarios autenticados
document.addEventListener('DOMContentLoaded', function() {
    const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
    const sidebar = document.getElementById('spotify-sidebar');
    const main = document.querySelector('main');
    
    if (isCollapsed) {
        sidebar.classList.add('collapsed');
        main.style.marginLeft = '72px';
    }
    
    // Cargar playlists del usuario si está autenticado
    loadUserPlaylists();
    
    // Event listeners para filtros
    document.querySelectorAll('.sidebar-filter').forEach(filter => {
        filter.addEventListener('click', function() {
            document.querySelectorAll('.sidebar-filter').forEach(f => f.classList.remove('active'));
            this.classList.add('active');
        });
    });
});

// Cargar playlists reales del usuario
function loadUserPlaylists() {
    fetch('/api/spotify/playlists?limit=10')
        .then(response => response.json())
        .then data => {
            if (data.success && data.playlists.length > 0) {
                const container = document.getElementById('user-playlists');
                const playlistsHtml = data.playlists.map(playlist => `
                    <div class="library-item d-flex align-items-center gap-3 p-2 rounded mb-1" role="button"
                         onclick="playPlaylist('${playlist.uri}')">
                        <div class="library-icon rounded overflow-hidden"
                             style="width: 48px; height: 48px;">
                            <img src="${playlist.image || '/imagenes/default.png'}" 
                                 alt="${playlist.name}"
                                 style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <div class="library-info flex-grow-1">
                            <div class="library-title text-truncate">${playlist.name}</div>
                            <div class="library-subtitle text-white-50 small">
                                Playlist • ${playlist.tracks_total} canciones
                            </div>
                        </div>
                    </div>
                `).join('');
                
                container.innerHTML = playlistsHtml;
            }
        })
        .catch(error => console.log('Error cargando playlists:', error));
}

function playPlaylist(uri) {
    if (window.playSpotify) {
        window.playSpotify(uri);
    }
}
@else
// Para usuarios no autenticados, solo ajustar el margen
document.addEventListener('DOMContentLoaded', function() {
    const main = document.querySelector('main');
    if (main) {
        main.style.marginLeft = '260px';
    }
    // Modal para crear lista (solo invitados)
    var btnCrearLista1 = document.getElementById('btn-crear-lista-guest');
    var btnCrearLista2 = document.getElementById('btn-crear-lista-guest2');
    var modal = document.getElementById('modal-login');
    var btnCancelar = document.getElementById('modal-cancelar');
    [btnCrearLista1, btnCrearLista2].forEach(function(btn) {
        if (btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                if (modal) modal.style.display = 'block';
            });
        }
    });
    if (btnCancelar && modal) {
        btnCancelar.addEventListener('click', function() {
            modal.style.display = 'none';
        });
        modal.addEventListener('click', function(e) {
            if (e.target === modal) modal.style.display = 'none';
        });
    }
});
@endauth
</script>

{{-- MODAL para crear lista (solo invitados) --}}
<div id="modal-login" class="modal" tabindex="-1" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.3);">
  <div id="modal-login-content" style="position:absolute; left:60px; top:120px; background:#fff; color:#000; border-radius:12px; max-width:340px; min-width:280px; box-shadow:0 8px 32px rgba(0,0,0,0.18); padding:1.5rem 1.5rem 1rem 1.5rem;">
    <!-- Flecha -->
    <div style="position:absolute; left:-16px; top:32px; width:0; height:0; border-top:12px solid transparent; border-bottom:12px solid transparent; border-right:16px solid #fff;"></div>
    <h5 class="fw-bold mb-2" style="color:#000; font-size:1.1rem;">Crear una lista</h5>
    <p class="mb-4" style="color:#000; font-size:0.98rem;">Para crear y compartir listas, inicia sesión.</p>
    <div class="d-flex justify-content-end gap-2 align-items-center mt-3">
      <button id="modal-cancelar" class="btn btn-link px-2" style="color:#000; font-weight:600; text-decoration:none;">Ahora no</button>
      <a href="{{ route('login') }}" class="btn btn-light px-3 fw-bold" style="border-radius:20px; font-weight:700;">Iniciar sesión</a>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    fetch('/api/spotify/liked-songs-count')
        .then(res => res.json())
        .then(data => {
            if (data.count !== undefined) {
                document.getElementById('liked-songs-count').textContent = data.count;
            }
        });
});
</script>
@endpush
