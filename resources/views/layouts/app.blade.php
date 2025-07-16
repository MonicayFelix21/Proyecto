{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Mi Spotify')</title>

  {{-- Bootstrap CSS + Icons --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/spotify-menu.css') }}">
  <link rel="stylesheet" href="{{ asset('css/spotify-sidebar.css') }}">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <script src="{{ asset('js/carrusel.js') }}"></script>



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
  {{-- Spotify Web Playback SDK --}}
  <script src="https://sdk.scdn.co/spotify-player.js"></script>

 {{-- Scripts globales --}}
  <script>
  document.addEventListener('DOMContentLoaded', () => {
    // AUTOCOMPLETE BÚSQUEDA
    const input = document.getElementById('busqueda'),
          suger = document.getElementById('sugerencias');
    let timer;
    input.addEventListener('input', () => {
      clearTimeout(timer);
      const q = input.value.trim();
      if (q.length < 2) return void (suger.style.display = 'none');
      timer = setTimeout(() => {
        fetch(`{{ route('spotify.search') }}?q=${encodeURIComponent(q)}`)
          .then(r => r.json())
          .then(items => {
            if (!items.length) return void (suger.style.display = 'none');
            suger.innerHTML = items.map(c => `
              <div class="sugerencia" data-id="${c.id}">
                <strong>${c.titulo}</strong><br>
                <small>${c.artista}</small>
              </div>
            `).join('');
            suger.style.display = 'block';
          });
      }, 300);
    });
    document.addEventListener('click', e => {
      if (!input.contains(e.target) && !suger.contains(e.target)) {
        suger.style.display = 'none';
      }
    });
    suger.addEventListener('click', e => {
      const el = e.target.closest('.sugerencia');
      if (!el) return;
      console.log('Track seleccionado:', el.dataset.id);
      suger.style.display = 'none';
    });

    // WEB PLAYBACK SDK
    let spotifyDeviceId = null;
    let spotifyPlayer = null;
    let progressUpdateInterval = null;
    const token = '{{ auth()->user()->spotify_token ?? '' }}';
    
    window.onSpotifyWebPlaybackSDKReady = () => {
      if (!token) return console.warn('No Spotify token');
      const player = new Spotify.Player({
        name: 'Laravel Spotify App',
        getOAuthToken: cb => cb(token),
        volume: 0.8,
      });
      
      spotifyPlayer = player;

      // 1) Al estar listo, guardo device_id y transfiero la sesión
      player.addListener('ready', ({ device_id }) => {
        spotifyDeviceId = device_id;
        console.log('Web Playback listo, device_id:', device_id);

        // transferir la reproducción a este dispositivo (sin iniciar reproducción)
        fetch('https://api.spotify.com/v1/me/player', {
          method: 'PUT',
          body: JSON.stringify({ device_ids: [device_id], play: false }),
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
          }
        });
      });

      // Listener para actualizar el reproductor inferior cuando cambie el estado
      player.addListener('player_state_changed', (state) => {
        if (!state) {
          // Si no hay estado, parar la actualización del progreso
          if (progressUpdateInterval) {
            clearInterval(progressUpdateInterval);
            progressUpdateInterval = null;
          }
          console.log('⚠️ Sin estado de Spotify');
          return;
        }
        
        const track = state.track_window.current_track;
        if (track) {
          // Actualizar reproductor inferior con información de Spotify
          const imgElement = document.getElementById('img-cancion');
          const tituloElement = document.getElementById('titulo-cancion');
          const artistaElement = document.getElementById('artista-cancion');
          const btnPlay = document.getElementById('btn-reproducir');
          const reproductor = document.getElementById('reproductor');
          
          if (imgElement) imgElement.src = track.album.images[0]?.url || '';
          if (tituloElement) tituloElement.textContent = track.name;
          if (artistaElement) artistaElement.textContent = track.artists.map(a => a.name).join(', ');
          
          // Actualizar ID del track actual para favoritos
          window.currentTrackId = track.id;
          
          // Verificar si está en favoritos
          if (track.id) {
            fetch(`/api/spotify/saved-tracks`)
              .then(response => response.json())
              .then(data => {
                if (data.success) {
                  const isInFavorites = data.tracks.some(t => t.id === track.id);
                  const heartBtn = document.getElementById('heart-btn');
                  if (heartBtn) {
                    if (isInFavorites) {
                      heartBtn.className = 'bi bi-heart-fill text-success spotify-control-icon';
                      heartBtn.title = 'Quitar de favoritos';
                    } else {
                      heartBtn.className = 'bi bi-heart text-white-50 spotify-control-icon';
                      heartBtn.title = 'Agregar a favoritos';
                    }
                  }
                }
              })
              .catch(e => console.log('Error verificando favoritos:', e));
          }
          
          // Actualizar variables globales
          window.isSpotifyPlaying = true;
          window.reproduciendo = !state.paused;
          
          if (btnPlay) {
            const playIcon = btnPlay.querySelector('i');
            if (state.paused) {
              playIcon.className = 'bi bi-play-fill';
              playIcon.style.marginLeft = '2px';
              reproductor?.classList.remove('playing');
              
              // Parar actualización de progreso cuando está pausado
              if (progressUpdateInterval) {
                clearInterval(progressUpdateInterval);
                progressUpdateInterval = null;
              }
              console.log('⏸️ Spotify pausado');
            } else {
              playIcon.className = 'bi bi-pause-fill';
              playIcon.style.marginLeft = '0px';
              reproductor?.classList.add('playing');
              
              // Iniciar actualización de progreso en tiempo real
              startProgressUpdate();
              console.log('▶️ Spotify reproduciendo:', track.name);
            }
            btnPlay.style.backgroundColor = '#1db954';
            btnPlay.style.color = 'white';
          }
          
          // Actualizar progreso inicial
          updateSpotifyProgress(state);
        }
      });

      player.connect();
    };
    
    // Función para iniciar la actualización de progreso en tiempo real - OPTIMIZADA
    function startProgressUpdate() {
      // Limpiar intervalo anterior si existe
      if (progressUpdateInterval) {
        clearInterval(progressUpdateInterval);
      }
      
      let lastPosition = -1;
      let consecutiveErrors = 0;
      
      // Actualizar cada 100ms para máxima suavidad
      progressUpdateInterval = setInterval(() => {
        if (spotifyPlayer) {
          spotifyPlayer.getCurrentState().then(state => {
            if (state && !state.paused) {
              // Solo actualizar si la posición ha cambiado significativamente
              if (Math.abs(state.position - lastPosition) > 50) { // 50ms de diferencia
                updateSpotifyProgress(state);
                lastPosition = state.position;
                consecutiveErrors = 0; // Reset error counter
              }
            } else {
              // Si está pausado o no hay estado, parar el intervalo
              clearInterval(progressUpdateInterval);
              progressUpdateInterval = null;
              console.log('🔇 Progreso detenido - sin estado o pausado');
            }
          }).catch(err => {
            consecutiveErrors++;
            console.log('⚠️ Error obteniendo estado Spotify:', err);
            
            // Si hay muchos errores consecutivos, parar el intervalo
            if (consecutiveErrors > 10) {
              clearInterval(progressUpdateInterval);
              progressUpdateInterval = null;
              console.log('❌ Deteniendo actualización por errores consecutivos');
            }
          });
        }
      }, 100); // 100ms = actualización muy suave
      
      console.log('🔄 Iniciada actualización de progreso en tiempo real');
    }
    
    // Función para actualizar el progreso de Spotify - MEJORADA
    function updateSpotifyProgress(state) {
      const tiempoActual = document.getElementById('tiempo-actual');
      const tiempoTotal = document.getElementById('tiempo-total');
      const barraProgreso = document.getElementById('barra-progreso');
      
      // Enviar estado al reproductor para el seek
      if (window.updateSpotifyState) {
        window.updateSpotifyState(state);
      }
      
      if (state.position !== undefined && state.duration) {
        const current = state.position / 1000;
        const duration = state.duration / 1000;
        const progress = Math.min(100, Math.max(0, (current / duration) * 100));
        
        // Actualizar tiempo siempre
        if (tiempoActual) tiempoActual.textContent = formatSpotifyTime(current);
        if (tiempoTotal) tiempoTotal.textContent = formatSpotifyTime(duration);
        
        // Solo actualizar barra si no estamos haciendo seek
        if (barraProgreso && !barraProgreso.dataset.seeking) {
          barraProgreso.value = progress;
          document.documentElement.style.setProperty('--progress', `${progress}%`);
        }
        
        // Debug cada 5 segundos aprox
        if (Math.floor(current) % 5 === 0 && Math.floor(current * 10) % 50 === 0) {
          console.log(`🎵 ${formatSpotifyTime(current)} / ${formatSpotifyTime(duration)} (${progress.toFixed(1)}%)`);
        }
      }
    }

    // 2) Función para reproducir URI en ese device_id
    window.playSpotify = uri => {
      if (!spotifyDeviceId) return console.error('Device ID no disponible');
      fetch(`https://api.spotify.com/v1/me/player/play?device_id=${spotifyDeviceId}`, {
        method: 'PUT',
        body: JSON.stringify({ uris: [uri] }),
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`
        }
      }).then(res => {
        if (!res.ok) console.error('Play error', res.status, res.statusText);
        else console.log('✅ Reproduciendo en Spotify:', uri);
      });
    };
    
    // Función auxiliar para formatear tiempo de Spotify - MEJORADA
    window.formatSpotifyTime = (seconds) => {
      if (!seconds || isNaN(seconds) || seconds < 0) return '0:00';
      const mins = Math.floor(seconds / 60);
      const secs = Math.floor(seconds % 60);
      return `${mins}:${secs.toString().padStart(2, '0')}`;
    };
    
    // Función para limpiar intervalos al cambiar de canción
    window.cleanupSpotifyProgress = () => {
      if (progressUpdateInterval) {
        clearInterval(progressUpdateInterval);
        progressUpdateInterval = null;
        console.log('🧹 Limpieza de intervalos de progreso');
      }
    };
    
    // Función para verificar estado de Spotify
    window.checkSpotifyState = () => {
      if (spotifyPlayer) {
        spotifyPlayer.getCurrentState().then(state => {
          console.log('🎧 Estado actual de Spotify:', state);
          if (state) {
            updateSpotifyProgress(state);
          }
        }).catch(err => {
          console.error('❌ Error verificando estado:', err);
        });
      } else {
        console.log('⚠️ Spotify Player no inicializado');
      }
    };
  });
  </script>

  @unless(request()->is('inicio'))
    <x-reproductor />
  @endunless

  @stack('scripts')
</body>
</html>