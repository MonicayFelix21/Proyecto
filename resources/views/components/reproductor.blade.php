<div id="reproductor"
     class="fixed-bottom bg-gradient-dark px-0 py-0"
     style="z-index:1050; height: 90px; border-top: 1px solid #282828; backdrop-filter: blur(10px);">

  <div class="container-fluid d-flex justify-content-between align-items-center h-100 px-4">
    
    {{-- INFORMACIÓN DE LA CANCIÓN ACTUAL --}}
    <div class="d-flex align-items-center gap-3" id="info-cancion" style="min-width: 30%; max-width: 30%;">
      <div class="position-relative">
        <img id="img-cancion"
             src="{{ asset('imagenes/default.png') }}"
             alt="Portada"
             class="rounded-2 shadow-sm"
             style="width: 56px; height: 56px; object-fit: cover;">
        <div class="position-absolute top-0 start-0 w-100 h-100 rounded-2 spotify-glow" style="opacity: 0; transition: all 0.3s ease;"></div>
      </div>
      <div class="d-flex flex-column justify-content-center flex-grow-1" style="min-width: 0;">
        <span id="titulo-cancion" class="fw-semibold text-white text-truncate" style="font-size: 14px;">Selecciona una canción</span>
        <span id="artista-cancion" class="text-white-50 text-truncate" style="font-size: 12px;">Ningún artista</span>
      </div>
      <div class="d-flex align-items-center gap-2">
        <i class="bi bi-heart text-white-50 spotify-control-icon" 
           role="button" 
           title="Me gusta"
           id="heart-btn"
           onclick="toggleCurrentTrackHeart()"></i>
        <i class="bi bi-pip text-white-50 spotify-control-icon" role="button" title="Picture in picture"></i>
      </div>
    </div>

    {{-- CONTROLES CENTRALES --}}
    <div class="d-flex flex-column align-items-center justify-content-center" style="min-width: 40%; max-width: 40%;">
      
      {{-- Controles de reproducción --}}
      <div class="d-flex justify-content-center align-items-center gap-4 mb-2">
        <i class="bi bi-shuffle spotify-control-icon" role="button" title="Aleatorio" id="btn-shuffle"></i>
        <i class="bi bi-skip-start-fill spotify-control-icon" role="button" title="Anterior" id="btn-previous"></i>
        
        <button id="btn-reproducir"
                class="btn spotify-play-button border-0 rounded-circle d-flex justify-content-center align-items-center"
                style="width: 32px; height: 32px; background-color: white; color: black; transition: all 0.1s ease;">
          <i class="bi bi-play-fill" style="font-size: 14px; margin-left: 2px;"></i>
        </button>
        
        <i class="bi bi-skip-end-fill spotify-control-icon" role="button" title="Siguiente" id="btn-next"></i>
        <i class="bi bi-repeat spotify-control-icon" role="button" title="Repetir" id="btn-repeat"></i>
      </div>

      {{-- Barra de progreso --}}
      <div class="d-flex align-items-center justify-content-center gap-2 w-100" style="max-width: 700px;">
        <span id="tiempo-actual" class="text-white-50" style="font-size: 11px; min-width: 40px; text-align: right;">0:00</span>
        
        <div class="position-relative flex-grow-1 mx-2">
          <input id="barra-progreso"
                 type="range"
                 min="0" max="100" value="0"
                 class="spotify-progress-bar"
                 style="width: 100%;">
        </div>
        
        <span id="tiempo-total" class="text-white-50" style="font-size: 11px; min-width: 40px; text-align: left;">0:00</span>
      </div>
    </div>

    {{-- CONTROLES DE VOLUMEN Y OPCIONES --}}
    <div class="d-flex align-items-center justify-content-end gap-3" style="min-width: 30%; max-width: 30%;">
      <i class="bi bi-mic text-white-50 spotify-control-icon d-none d-lg-block" role="button" title="Letras"></i>
      <i class="bi bi-list-ul text-white-50 spotify-control-icon d-none d-lg-block" role="button" title="Cola de reproducción"></i>
      <i class="bi bi-pc-display text-white-50 spotify-control-icon d-none d-xl-block" role="button" title="Conectar a un dispositivo"></i>
      
      <div class="d-flex align-items-center gap-2">
        <i id="volume-icon" class="bi bi-volume-up text-white-50 spotify-control-icon" role="button" title="Silenciar"></i>
        <div class="position-relative" style="width: 93px;">
          <input id="barra-volumen"
                 type="range"
                 min="0" max="1" step="0.01" value="0.8"
                 class="spotify-volume-bar"
                 style="width: 100%;">
        </div>
      </div>
      
      <i class="bi bi-arrows-fullscreen text-white-50 spotify-control-icon d-none d-xl-block" role="button" title="Pantalla completa"></i>
    </div>
  </div>
</div>

{{-- ESTILOS SPOTIFY MEJORADOS --}}
<style>
  /* Fondo gradiente como Spotify */
  .bg-gradient-dark {
    background: linear-gradient(90deg, #000000 0%, #121212 50%, #000000 100%);
    box-shadow: 0 -1px 0 rgba(255,255,255,0.1);
  }

  /* Iconos de control con hover */
  .spotify-control-icon {
    color: #b3b3b3 !important;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.2s ease;
    padding: 8px;
    border-radius: 4px;
  }
  
  .spotify-control-icon:hover {
    color: white !important;
    transform: scale(1.06);
  }

  /* Botón de play principal */
  .spotify-play-button {
    transition: all 0.1s ease !important;
    box-shadow: 0 1px 4px rgba(0,0,0,0.3);
  }
  
  .spotify-play-button:hover {
    transform: scale(1.06) !important;
    background-color: #f0f0f0 !important;
  }
  
  .spotify-play-button:active {
    transform: scale(0.96) !important;
  }

  /* Barra de progreso estilo Spotify - mejorada */
  .spotify-progress-bar {
    -webkit-appearance: none;
    height: 4px;
    border-radius: 2px;
    background: #4f4f4f;
    outline: none;
    transition: all 0.2s ease;
    cursor: pointer;
  }
  
  .spotify-progress-bar::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #1db954;
    cursor: pointer;
    opacity: 0;
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.3);
  }
  
  .spotify-progress-bar:hover::-webkit-slider-thumb,
  .spotify-progress-bar:active::-webkit-slider-thumb,
  .spotify-progress-bar[data-seeking]::-webkit-slider-thumb {
    opacity: 1;
  }
  
  .spotify-progress-bar::-moz-range-thumb {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #1db954;
    cursor: pointer;
    border: none;
    opacity: 0;
    transition: all 0.2s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.3);
  }
  
  .spotify-progress-bar:hover::-moz-range-thumb,
  .spotify-progress-bar:active::-moz-range-thumb,
  .spotify-progress-bar[data-seeking]::-moz-range-thumb {
    opacity: 1;
  }

  .spotify-progress-bar::-webkit-slider-track {
    background: linear-gradient(to right, #1db954 0%, #1db954 var(--progress, 0%), #4f4f4f var(--progress, 0%), #4f4f4f 100%);
    height: 4px;
    border-radius: 2px;
  }

  .spotify-progress-bar:hover {
    background: #535353;
  }
  
  .spotify-progress-bar:active {
    background: #535353;
  }
  
  /* Hacer toda la barra clickeable */
  .spotify-progress-bar {
    width: 100%;
    padding: 8px 0; /* Área de click más grande */
    margin: -8px 0;
  }

  /* Barra de volumen estilo Spotify */
  .spotify-volume-bar {
    -webkit-appearance: none;
    height: 4px;
    border-radius: 2px;
    background: #4f4f4f;
    outline: none;
    transition: all 0.2s ease;
  }
  
  .spotify-volume-bar::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #1db954;
    cursor: pointer;
    opacity: 0;
    transition: all 0.2s ease;
  }
  
  .spotify-volume-bar:hover::-webkit-slider-thumb {
    opacity: 1;
  }
  
  .spotify-volume-bar::-moz-range-thumb {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #1db954;
    cursor: pointer;
    border: none;
    opacity: 0;
    transition: all 0.2s ease;
  }
  
  .spotify-volume-bar:hover::-moz-range-thumb {
    opacity: 1;
  }

  .spotify-volume-bar::-webkit-slider-track {
    background: linear-gradient(to right, #1db954 0%, #1db954 var(--volume, 80%), #4f4f4f var(--volume, 80%), #4f4f4f 100%);
    height: 4px;
    border-radius: 2px;
  }

  .spotify-volume-bar:hover {
    background: #535353;
  }

  /* Efecto glow para la imagen */
  .spotify-glow {
    background: linear-gradient(135deg, #1db954, #1ed760);
    opacity: 0;
    transition: opacity 0.3s ease;
  }

  /* Cuando está reproduciendo */
  .playing .spotify-glow {
    opacity: 0.3 !important;
  }

  /* Animación del botón de play cuando reproduce */
  .playing .spotify-play-button {
    background-color: #1db954 !important;
    color: white !important;
  }

  /* Estados activos */
  .spotify-control-icon.active {
    color: #1db954 !important;
  }

  /* Responsive */
  @media (max-width: 1200px) {
    .d-xl-block { display: none !important; }
  }
  
  @media (max-width: 992px) {
    .d-lg-block { display: none !important; }
    #info-cancion { min-width: 25% !important; max-width: 35% !important; }
    .spotify-control-icon { font-size: 14px; padding: 6px; }
  }
  
  @media (max-width: 768px) {
    #reproductor { height: 80px !important; }
    #info-cancion { min-width: 20% !important; max-width: 40% !important; }
    #img-cancion { width: 48px !important; height: 48px !important; }
    .spotify-control-icon { font-size: 14px; padding: 4px; }
    .spotify-play-button { width: 28px !important; height: 28px !important; }
    .spotify-play-button i { font-size: 12px !important; }
  }
</style>

@auth

@push('scripts')
<script>
  // Variables globales - sincronizadas con el layout
  const audio = new Audio();
  let reproduciendo = false;
  let isSpotifyPlaying = false;
  let currentVolume = 0.8;
  let isMuted = false;
  let previousVolume = 0.8;

  // Hacer variables accesibles globalmente para sincronización
  window.isSpotifyPlaying = isSpotifyPlaying;
  window.reproduciendo = reproduciendo;
  window.currentVolume = currentVolume;

  // Elementos del DOM
  const btnPlay = document.getElementById('btn-reproducir');
  const barraProgreso = document.getElementById('barra-progreso');
  const barraVolumen = document.getElementById('barra-volumen');
  const tiempoActual = document.getElementById('tiempo-actual');
  const tiempoTotal = document.getElementById('tiempo-total');
  const volumeIcon = document.getElementById('volume-icon');
  const reproductor = document.getElementById('reproductor');
  const imgCancion = document.getElementById('img-cancion');
  const tituloCancion = document.getElementById('titulo-cancion');
  const artistaCancion = document.getElementById('artista-cancion');

  // Configuración inicial
  audio.volume = currentVolume;
  barraVolumen.value = currentVolume;

  // Función para formatear tiempo
  function formatTime(seconds) {
    if (!seconds || isNaN(seconds)) return '0:00';
    const mins = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60);
    return `${mins}:${secs.toString().padStart(2, '0')}`;
  }

  // Función para actualizar la UI del reproductor - MEJORADA
  function updatePlayerUI(isPlaying, isSpotify = false) {
    const playIcon = btnPlay.querySelector('i');
    
    // Actualizar variables globales
    reproduciendo = isPlaying;
    isSpotifyPlaying = isSpotify;
    window.reproduciendo = isPlaying;
    window.isSpotifyPlaying = isSpotify;
    
    if (isPlaying) {
      playIcon.className = 'bi bi-pause-fill';
      playIcon.style.marginLeft = '0px';
      reproductor.classList.add('playing');
      
      if (isSpotify) {
        btnPlay.style.backgroundColor = '#1db954';
        btnPlay.style.color = 'white';
      } else {
        btnPlay.style.backgroundColor = 'white';
        btnPlay.style.color = 'black';
      }
    } else {
      playIcon.className = 'bi bi-play-fill';
      playIcon.style.marginLeft = '2px';
      reproductor.classList.remove('playing');
      
      if (isSpotify) {
        btnPlay.style.backgroundColor = '#1db954';
        btnPlay.style.color = 'white';
      } else {
        btnPlay.style.backgroundColor = 'white';
        btnPlay.style.color = 'black';
      }
    }
  }

  // Función para actualizar información de la canción
  function updateTrackInfo(title, artist, image) {
    tituloCancion.textContent = title;
    artistaCancion.textContent = artist;
    imgCancion.src = image;
    
    // Actualizar variable CSS para progreso
    document.documentElement.style.setProperty('--progress', '0%');
  }

  // Función para actualizar barra de progreso
  function updateProgress(current, duration) {
    if (duration && !isNaN(duration)) {
      const progress = (current / duration) * 100;
      barraProgreso.value = progress;
      document.documentElement.style.setProperty('--progress', `${progress}%`);
      tiempoActual.textContent = formatTime(current);
      tiempoTotal.textContent = formatTime(duration);
    }
  }

  // Función para actualizar volumen
  function updateVolume(volume) {
    currentVolume = volume;
    barraVolumen.value = volume;
    audio.volume = volume;
    
    // Actualizar variable CSS para volumen
    const volumePercent = volume * 100;
    document.documentElement.style.setProperty('--volume', `${volumePercent}%`);
    
    // Actualizar icono de volumen
    if (volume === 0 || isMuted) {
      volumeIcon.className = 'bi bi-volume-mute text-white-50 spotify-control-icon';
    } else if (volume < 0.5) {
      volumeIcon.className = 'bi bi-volume-down text-white-50 spotify-control-icon';
    } else {
      volumeIcon.className = 'bi bi-volume-up text-white-50 spotify-control-icon';
    }
  }

  // Control del botón principal de play/pause
  btnPlay?.addEventListener('click', () => {
    if (isSpotifyPlaying) {
      // Control de Spotify
      const action = reproduciendo ? 'pause' : 'play';
      fetch(`https://api.spotify.com/v1/me/player/${action}`, {
        method: 'PUT',
        headers: {
          'Authorization': `Bearer {{ auth()->user()->spotify_token ?? '' }}`
        }
      }).then(res => {
        if (res.ok) {
          reproduciendo = !reproduciendo;
          updatePlayerUI(reproduciendo, true);
        }
      }).catch(e => console.error('Error controlando Spotify:', e));
    } else {
      // Control de audio local
      if (audio.src) {
        if (audio.paused) {
          audio.play().then(() => {
            reproduciendo = true;
            updatePlayerUI(true, false);
          }).catch(e => console.error('Error reproduciendo:', e));
        } else {
          audio.pause();
          reproduciendo = false;
          updatePlayerUI(false, false);
        }
      }
    }
  });

  // Control de volumen
  barraVolumen?.addEventListener('input', (e) => {
    const volume = parseFloat(e.target.value);
    updateVolume(volume);
    isMuted = false;
    
    // Si hay Spotify reproduciéndose, controlar volumen de Spotify
    if (isSpotifyPlaying) {
      const volumePercent = Math.round(volume * 100);
      fetch(`https://api.spotify.com/v1/me/player/volume?volume_percent=${volumePercent}`, {
        method: 'PUT',
        headers: {
          'Authorization': `Bearer {{ auth()->user()->spotify_token ?? '' }}`
        }
      }).catch(e => console.error('Error controlando volumen Spotify:', e));
    }
  });

  // Click en icono de volumen para mutear
  volumeIcon?.addEventListener('click', () => {
    if (!isMuted) {
      previousVolume = currentVolume;
      updateVolume(0);
      isMuted = true;
    } else {
      updateVolume(previousVolume);
      isMuted = false;
    }
  });

  // Control de barra de progreso - MEJORADO para seek instantáneo y preciso
  let isDragging = false;
  let wasPlaying = false;
  let currentSpotifyState = null;
  
  // Función para obtener porcentaje desde posición del mouse
  function getPercentageFromMouseEvent(e, element) {
    const rect = element.getBoundingClientRect();
    const percentage = ((e.clientX - rect.left) / rect.width) * 100;
    return Math.max(0, Math.min(100, percentage));
  }
  
  // Función para actualizar visualmente la barra y tiempo
  function updateProgressUI(percentage) {
    barraProgreso.value = percentage;
    document.documentElement.style.setProperty('--progress', `${percentage}%`);
    
    // Actualizar tiempo mostrado en tiempo real
    if (isSpotifyPlaying && currentSpotifyState) {
      const duration = currentSpotifyState.duration / 1000;
      const currentTime = (percentage / 100) * duration;
      if (tiempoActual) tiempoActual.textContent = formatTime(currentTime);
    } else if (audio.duration) {
      const currentTime = (percentage / 100) * audio.duration;
      if (tiempoActual) tiempoActual.textContent = formatTime(currentTime);
    }
  }
  
  // Función para ejecutar el seek
  function performSeek(percentage) {
    if (isSpotifyPlaying) {
      // Spotify seek
      if (currentSpotifyState && currentSpotifyState.duration) {
        const positionMs = Math.round((percentage / 100) * currentSpotifyState.duration);
        fetch(`https://api.spotify.com/v1/me/player/seek?position_ms=${positionMs}`, {
          method: 'PUT',
          headers: {
            'Authorization': `Bearer {{ auth()->user()->spotify_token ?? '' }}`
          }
        }).then(res => {
          if (res.ok) {
            console.log('✅ Seek Spotify exitoso:', positionMs + 'ms');
          } else {
            console.error('❌ Error en seek Spotify:', res.status);
          }
        }).catch(e => console.error('Error seeking Spotify:', e));
      }
    } else {
      // Audio local seek
      if (audio.duration) {
        audio.currentTime = (percentage / 100) * audio.duration;
        console.log('✅ Seek local exitoso:', audio.currentTime + 's');
      }
    }
  }
  
  // Evento mousedown para iniciar arrastre
  barraProgreso?.addEventListener('mousedown', (e) => {
    isDragging = true;
    wasPlaying = reproduciendo;
    barraProgreso.dataset.seeking = 'true';
    
    // Pausar temporalmente las actualizaciones automáticas de progreso
    if (isSpotifyPlaying) {
      // No pausamos Spotify, solo marcamos que estamos haciendo seek
    }
    
    const percentage = getPercentageFromMouseEvent(e, barraProgreso);
    updateProgressUI(percentage);
  });
  
  // Evento mousemove para arrastrar
  document.addEventListener('mousemove', (e) => {
    if (isDragging) {
      const percentage = getPercentageFromMouseEvent(e, barraProgreso);
      updateProgressUI(percentage);
    }
  });
  
  // Evento mouseup para finalizar arrastre
  document.addEventListener('mouseup', (e) => {
    if (isDragging) {
      isDragging = false;
      delete barraProgreso.dataset.seeking;
      
      const percentage = parseFloat(barraProgreso.value);
      performSeek(percentage);
    }
  });
  
  // Click directo en la barra para seek instantáneo (sin arrastre)
  barraProgreso?.addEventListener('click', (e) => {
    if (isDragging) return; // Evitar conflicto con drag
    
    const percentage = getPercentageFromMouseEvent(e, barraProgreso);
    updateProgressUI(percentage);
    performSeek(percentage);
  });

  // Control de siguiente/anterior con Spotify API
  document.getElementById('btn-next')?.addEventListener('click', function() {
    fetch('https://api.spotify.com/v1/me/player/next', {
      method: 'POST',
      headers: {
        'Authorization': `Bearer {{ auth()->user()->spotify_token ?? '' }}`
      }
    }).catch(e => console.error('Error siguiente Spotify:', e));
  });
  document.getElementById('btn-previous')?.addEventListener('click', function() {
    fetch('https://api.spotify.com/v1/me/player/previous', {
      method: 'POST',
      headers: {
        'Authorization': `Bearer {{ auth()->user()->spotify_token ?? '' }}`
      }
    }).catch(e => console.error('Error anterior Spotify:', e));
  });

  // Eventos de audio local - MEJORADOS para actualización fluida
  audio.addEventListener('loadedmetadata', () => {
    if (!isSpotifyPlaying) {
      updateProgress(0, audio.duration);
    }
  });

  let localProgressInterval = null;

  audio.addEventListener('play', () => {
    if (!isSpotifyPlaying) {
      // Iniciar actualización suave para audio local
      if (localProgressInterval) clearInterval(localProgressInterval);
      localProgressInterval = setInterval(() => {
        if (!audio.paused && !isDragging) {
          updateProgress(audio.currentTime, audio.duration);
        }
      }, 100); // 100ms igual que Spotify para consistencia
    }
  });

  audio.addEventListener('pause', () => {
    if (!isSpotifyPlaying && localProgressInterval) {
      clearInterval(localProgressInterval);
      localProgressInterval = null;
    }
  });

  audio.addEventListener('ended', () => {
    reproduciendo = false;
    isSpotifyPlaying = false;
    window.reproduciendo = false;
    window.isSpotifyPlaying = false;
    updatePlayerUI(false, false);
    
    if (localProgressInterval) {
      clearInterval(localProgressInterval);
      localProgressInterval = null;
    }
  });

  // Cleanup global para audio local
  window.cleanupLocalProgress = () => {
    if (localProgressInterval) {
      clearInterval(localProgressInterval);
      localProgressInterval = null;
      console.log('🧹 Limpieza de intervalos de audio local');
    }
  };
  
  // Función para recibir estado de Spotify desde el layout
  window.updateSpotifyState = (state) => {
    currentSpotifyState = state;
  };

  // Manejo de clicks en tarjetas de canciones
  window.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.song-card').forEach(card => {
      const btn = card.querySelector('.play-button');
      btn?.addEventListener('click', () => {
        const spotifyUri = card.dataset.uri;
        const localAudio = card.dataset.audio;
        const titulo = card.dataset.titulo;
        const artista = card.dataset.artista;
        const imagen = card.dataset.imagen;

        // Si tiene URI de Spotify, usar Web Playback SDK
        if (spotifyUri && window.playSpotify) {
          window.playSpotify(spotifyUri);
          
          // Pausar audio local si está reproduciéndose
          if (!audio.paused) {
            audio.pause();
          }
          
          // Actualizar UI
          isSpotifyPlaying = true;
          reproduciendo = true;
          updateTrackInfo(titulo, artista, imagen);
          updatePlayerUI(true, true);
          
          return;
        }

        // Si es audio local
        if (localAudio) {
          // Pausar Spotify si está reproduciéndose
          if (isSpotifyPlaying) {
            fetch('https://api.spotify.com/v1/me/player/pause', {
              method: 'PUT',
              headers: {
                'Authorization': `Bearer {{ auth()->user()->spotify_token ?? '' }}`
              }
            }).catch(e => console.error('Error pausando Spotify:', e));
          }
          
          isSpotifyPlaying = false;
          
          // Si es una canción diferente
          if (audio.src !== localAudio) {
            audio.pause();
            audio.src = localAudio;
            audio.currentTime = 0;
            
            updateTrackInfo(titulo, artista, imagen);
            
            audio.play().then(() => {
              reproduciendo = true;
              updatePlayerUI(true, false);
            }).catch(e => console.error('Error reproduciendo:', e));
          } else {
            // Toggle de la misma canción
            if (audio.paused) {
              audio.play().then(() => {
                reproduciendo = true;
                updatePlayerUI(true, false);
              });
            } else {
              audio.pause();
              reproduciendo = false;
              updatePlayerUI(false, false);
            }
          }
        }
      });
    });
  });

  // Inicializar volumen
  updateVolume(currentVolume);
  
  // Variable global para track actual
  window.currentTrackId = null;
  
  // Función para toggle del corazón del track actual
  window.toggleCurrentTrackHeart = function() {
    if (!window.currentTrackId) {
      console.warn('No hay track actual para agregar a favoritos');
      return;
    }
    const heartBtn = document.getElementById('heart-btn');
    if (!heartBtn) return;
    fetch('/api/spotify/toggle-saved-track', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
      },
      body: JSON.stringify({ track_id: window.currentTrackId })
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        if (data.is_saved) {
          heartBtn.className = 'bi bi-heart-fill text-success spotify-control-icon';
          heartBtn.title = 'Quitar de favoritos';
          showLikeToast('¡Agregado a Tus me gusta!', true);
        } else {
          heartBtn.className = 'bi bi-heart text-white-50 spotify-control-icon';
          heartBtn.title = 'Agregar a favoritos';
          showLikeToast('Quitado de Tus me gusta', false);
        }
        console.log(`✅ Track ${data.action} ${data.is_saved ? 'a' : 'de'} favoritos`);
      }
    })
    .catch(error => {
      console.error('Error al cambiar favorito:', error);
    });
  };

  // Función para mostrar toast de feedback
  function showLikeToast(message, isLiked) {
    const toastEl = document.getElementById('likeToast');
    const toastMsg = document.getElementById('likeToastMsg');
    toastMsg.textContent = message;
    toastEl.classList.remove('bg-success', 'bg-danger');
    toastEl.classList.add(isLiked ? 'bg-success' : 'bg-danger');
    const toast = new bootstrap.Toast(toastEl);
    toast.show();
  }
</script>
@endpush
@endauth

<!-- TOAST DE FEEDBACK PARA LIKE/UNLIKE -->
<div aria-live="polite" aria-atomic="true" class="position-fixed bottom-0 end-0 p-3" style="z-index: 2000;">
  <div id="likeToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="1800">
    <div class="d-flex">
      <div class="toast-body" id="likeToastMsg">
        ¡Agregado a Tus me gusta!
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Cerrar"></button>
    </div>
  </div>
</div>







