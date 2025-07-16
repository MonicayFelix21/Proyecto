@extends('layouts.app')

@section('title', 'Test Tiempo y Progreso')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="d-flex align-items-center mb-4">
        <h1 class="h3 mb-0">🧪 Test de Tiempo y Progreso</h1>
      </div>
      
      <div class="alert alert-info">
        <strong>Instrucciones:</strong> Esta página te permite verificar que las actualizaciones de tiempo y el seek funcionen correctamente.
      </div>

      {{-- Panel de Estado --}}
      <div class="card bg-dark border-secondary mb-4">
        <div class="card-header">
          <h5 class="mb-0">📊 Estado Actual del Reproductor</h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <p><strong>Spotify Activo:</strong> <span id="spotify-status" class="badge bg-secondary">No</span></p>
              <p><strong>Reproduciendo:</strong> <span id="playing-status" class="badge bg-secondary">No</span></p>
              <p><strong>Canción Actual:</strong> <span id="current-song">Ninguna</span></p>
            </div>
            <div class="col-md-6">
              <p><strong>Tiempo:</strong> <span id="time-display">0:00 / 0:00</span></p>
              <p><strong>Progreso:</strong> <span id="progress-display">0%</span></p>
              <p><strong>Volumen:</strong> <span id="volume-display">80%</span></p>
            </div>
          </div>
        </div>
      </div>

      {{-- Controles de Prueba --}}
      <div class="card bg-dark border-secondary mb-4">
        <div class="card-header">
          <h5 class="mb-0">🎮 Controles de Prueba</h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h6>Spotify</h6>
              <button class="btn btn-success btn-sm mb-2" onclick="checkSpotifyState()">🔍 Verificar Estado</button>
              <button class="btn btn-warning btn-sm mb-2" onclick="seekToPosition(30)">⏭️ Saltar a 30s</button>
              <button class="btn btn-info btn-sm mb-2" onclick="seekToPercentage(50)">📍 Ir al 50%</button>
            </div>
            <div class="col-md-6">
              <h6>Debug</h6>
              <button class="btn btn-secondary btn-sm mb-2" onclick="toggleDebugMode()">🐛 Toggle Debug</button>
              <button class="btn btn-dark btn-sm mb-2" onclick="clearConsole()">🧹 Limpiar Consola</button>
              <button class="btn btn-outline-light btn-sm mb-2" onclick="showPlayerVars()">📋 Variables</button>
            </div>
          </div>
        </div>
      </div>

      {{-- Log de Eventos --}}
      <div class="card bg-dark border-secondary">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">📝 Log de Eventos</h5>
          <button class="btn btn-outline-light btn-sm" onclick="clearLog()">Limpiar</button>
        </div>
        <div class="card-body">
          <div id="event-log" class="border rounded p-3" style="height: 300px; overflow-y: auto; background-color: #1a1a1a; font-family: monospace; font-size: 12px;">
            <div class="text-muted">Log iniciado...</div>
          </div>
        </div>
      </div>

      {{-- Canciones de Prueba --}}
      <div class="card bg-dark border-secondary mt-4">
        <div class="card-header">
          <h5 class="mb-0">🎵 Canciones de Prueba</h5>
        </div>
        <div class="card-body">
          <div class="row">
            @if(auth()->user() && auth()->user()->spotify_token)
              <div class="col-md-4 mb-3">
                <div class="song-card" 
                     data-uri="spotify:track:0VjIjW4GlULA3NkC7c2vSc" 
                     data-titulo="Levitating" 
                     data-artista="Dua Lipa" 
                     data-imagen="{{ asset('imagenes/levitating.jpg') }}">
                  <img src="{{ asset('imagenes/levitating.jpg') }}" alt="Levitating">
                  <h6>Levitating</h6>
                  <div class="artist">🎤 Dua Lipa</div>
                  <div class="play-button">
                    <i class="bi bi-play-fill"></i>
                  </div>
                </div>
              </div>
            @endif
            
            <div class="col-md-4 mb-3">
              <div class="song-card" 
                   data-audio="{{ asset('musica/Levitating.mp3') }}" 
                   data-titulo="Levitating (Local)" 
                   data-artista="Dua Lipa" 
                   data-imagen="{{ asset('imagenes/levitating.jpg') }}">
                <img src="{{ asset('imagenes/levitating.jpg') }}" alt="Levitating Local">
                <h6>Levitating (Local)</h6>
                <div class="artist">🎤 Dua Lipa • Local</div>
                <div class="play-button">
                  <i class="bi bi-play-fill"></i>
                </div>
              </div>
            </div>
            
            <div class="col-md-4 mb-3">
              <div class="song-card" 
                   data-audio="{{ asset('musica/vanish-into-you.mp3') }}" 
                   data-titulo="Vanish Into You" 
                   data-artista="Artista Local" 
                   data-imagen="{{ asset('imagenes/default.png') }}">
                <img src="{{ asset('imagenes/default.png') }}" alt="Vanish Into You">
                <h6>Vanish Into You</h6>
                <div class="artist">🎤 Artista Local</div>
                <div class="play-button">
                  <i class="bi bi-play-fill"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
/* Estilos específicos para la página de test */
.song-card {
  transition: all 0.2s ease;
}

.song-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 20px rgba(0,0,0,0.3);
}

#event-log {
  color: #00ff00;
}

.log-entry {
  margin-bottom: 2px;
  padding: 2px 0;
  border-bottom: 1px solid #333;
}

.log-timestamp {
  color: #888;
  font-size: 10px;
}

.log-spotify {
  color: #1db954;
}

.log-local {
  color: #ff6b6b;
}

.log-seek {
  color: #4ecdc4;
}

.log-error {
  color: #ff4757;
}
</style>

<script>
let debugMode = false;
const eventLog = document.getElementById('event-log');

// Función para agregar al log
function addToLog(message, type = 'info') {
  const timestamp = new Date().toLocaleTimeString();
  const logEntry = document.createElement('div');
  logEntry.className = `log-entry log-${type}`;
  logEntry.innerHTML = `<span class="log-timestamp">[${timestamp}]</span> ${message}`;
  eventLog.appendChild(logEntry);
  eventLog.scrollTop = eventLog.scrollHeight;
}

// Función para actualizar el estado en pantalla
function updateStatusDisplay() {
  document.getElementById('spotify-status').textContent = window.isSpotifyPlaying ? 'Sí' : 'No';
  document.getElementById('spotify-status').className = `badge ${window.isSpotifyPlaying ? 'bg-success' : 'bg-secondary'}`;
  
  document.getElementById('playing-status').textContent = window.reproduciendo ? 'Sí' : 'No';
  document.getElementById('playing-status').className = `badge ${window.reproduciendo ? 'bg-success' : 'bg-secondary'}`;
  
  const tiempoActual = document.getElementById('tiempo-actual')?.textContent || '0:00';
  const tiempoTotal = document.getElementById('tiempo-total')?.textContent || '0:00';
  document.getElementById('time-display').textContent = `${tiempoActual} / ${tiempoTotal}`;
  
  const barraProgreso = document.getElementById('barra-progreso');
  const progress = barraProgreso ? Math.round(barraProgreso.value) : 0;
  document.getElementById('progress-display').textContent = `${progress}%`;
  
  const barraVolumen = document.getElementById('barra-volumen');
  const volume = barraVolumen ? Math.round(barraVolumen.value * 100) : 80;
  document.getElementById('volume-display').textContent = `${volume}%`;
  
  const tituloCancion = document.getElementById('titulo-cancion')?.textContent || 'Ninguna';
  document.getElementById('current-song').textContent = tituloCancion;
}

// Funciones de test
function checkSpotifyState() {
  addToLog('🔍 Verificando estado de Spotify...', 'spotify');
  if (window.checkSpotifyState) {
    window.checkSpotifyState();
  } else {
    addToLog('❌ Función checkSpotifyState no disponible', 'error');
  }
}

function seekToPosition(seconds) {
  addToLog(`⏭️ Intentando saltar a ${seconds} segundos...`, 'seek');
  const barraProgreso = document.getElementById('barra-progreso');
  if (!barraProgreso) {
    addToLog('❌ Barra de progreso no encontrada', 'error');
    return;
  }
  
  if (window.isSpotifyPlaying) {
    // Simular click en posición específica para Spotify
    const duration = 180; // Asumimos 3 minutos como ejemplo
    const percentage = (seconds / duration) * 100;
    barraProgreso.value = percentage;
    
    // Trigger manual seek
    if (window.performSpotifySeek) {
      window.performSpotifySeek(seconds * 1000);
    }
  } else {
    const audio = document.querySelector('audio');
    if (audio && audio.duration) {
      audio.currentTime = seconds;
      addToLog(`✅ Audio local saltado a ${seconds}s`, 'local');
    } else {
      addToLog('❌ No hay audio local reproduciéndose', 'error');
    }
  }
}

function seekToPercentage(percent) {
  addToLog(`📍 Saltando al ${percent}%...`, 'seek');
  const barraProgreso = document.getElementById('barra-progreso');
  if (barraProgreso) {
    barraProgreso.value = percent;
    barraProgreso.dispatchEvent(new Event('change'));
  }
}

function toggleDebugMode() {
  debugMode = !debugMode;
  addToLog(`🐛 Debug mode: ${debugMode ? 'ON' : 'OFF'}`, 'info');
  
  // Redirigir console.log si debug está activo
  if (debugMode) {
    const originalLog = console.log;
    console.log = function(...args) {
      addToLog(args.join(' '), 'info');
      originalLog.apply(console, args);
    };
  }
}

function clearConsole() {
  console.clear();
  addToLog('🧹 Consola limpiada', 'info');
}

function clearLog() {
  eventLog.innerHTML = '<div class="text-muted">Log limpiado...</div>';
}

function showPlayerVars() {
  addToLog('📋 Variables del reproductor:', 'info');
  addToLog(`- isSpotifyPlaying: ${window.isSpotifyPlaying}`, 'info');
  addToLog(`- reproduciendo: ${window.reproduciendo}`, 'info');
  addToLog(`- currentVolume: ${window.currentVolume}`, 'info');
}

// Actualizar estado cada segundo
setInterval(updateStatusDisplay, 1000);

// Log inicial
document.addEventListener('DOMContentLoaded', () => {
  addToLog('🚀 Sistema de test iniciado', 'info');
  addToLog('ℹ️ Usa los controles para probar el reproductor', 'info');
  updateStatusDisplay();
});
</script>
@endsection
