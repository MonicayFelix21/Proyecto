@extends('layouts.app')

@section('title', 'Diagnóstico del Sistema')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <h1 class="h3 mb-4">🔍 Diagnóstico del Sistema Spotify</h1>
      
      <div class="alert alert-info">
        <strong>Instrucciones:</strong> Esta página verifica que todas las funcionalidades estén funcionando correctamente.
      </div>

      {{-- Tests automáticos --}}
      <div class="card bg-dark border-secondary mb-4">
        <div class="card-header">
          <h5 class="mb-0">🧪 Tests Automáticos</h5>
        </div>
        <div class="card-body">
          <button class="btn btn-primary mb-3" onclick="runAllTests()">🚀 Ejecutar Todos los Tests</button>
          <div id="test-results"></div>
        </div>
      </div>

      {{-- Tests manuales --}}
      <div class="card bg-dark border-secondary mb-4">
        <div class="card-header">
          <h5 class="mb-0">👆 Tests Manuales</h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h6>APIs de Lectura</h6>
              <button class="btn btn-outline-light btn-sm mb-2 d-block" onclick="testAPI('/api/spotify/recently-played')">
                🕒 Test Historial
              </button>
              <button class="btn btn-outline-light btn-sm mb-2 d-block" onclick="testAPI('/api/spotify/recommendations?seed_genres=pop')">
                🎯 Test Recomendaciones
              </button>
              <button class="btn btn-outline-light btn-sm mb-2 d-block" onclick="testAPI('/api/spotify/saved-tracks')">
                💝 Test Favoritos
              </button>
              <button class="btn btn-outline-light btn-sm mb-2 d-block" onclick="testAPI('/api/spotify/top/tracks')">
                📊 Test Top Tracks
              </button>
              <button class="btn btn-outline-light btn-sm mb-2 d-block" onclick="testAPI('/api/spotify/playlists')">
                📋 Test Playlists
              </button>
              <button class="btn btn-outline-light btn-sm mb-2 d-block" onclick="testAPI('/api/spotify/queue')">
                🎵 Test Cola
              </button>
            </div>
            <div class="col-md-6">
              <h6>Funciones del Reproductor</h6>
              <button class="btn btn-outline-success btn-sm mb-2 d-block" onclick="testPlayback()">
                ▶️ Test Reproducción
              </button>
              <button class="btn btn-outline-warning btn-sm mb-2 d-block" onclick="testSeek()">
                ⏭️ Test Seek
              </button>
              <button class="btn btn-outline-info btn-sm mb-2 d-block" onclick="testVolume()">
                🔊 Test Volumen
              </button>
              <button class="btn btn-outline-light btn-sm mb-2 d-block" onclick="testHeart()">
                💖 Test Favoritos
              </button>
              <button class="btn btn-outline-secondary btn-sm mb-2 d-block" onclick="testControls()">
                🎮 Test Controles
              </button>
            </div>
          </div>
        </div>
      </div>

      {{-- Resultados --}}
      <div class="card bg-dark border-secondary">
        <div class="card-header d-flex justify-content-between">
          <h5 class="mb-0">📊 Resultados</h5>
          <button class="btn btn-outline-light btn-sm" onclick="clearResults()">Limpiar</button>
        </div>
        <div class="card-body">
          <div id="results-log" class="border rounded p-3" style="height: 400px; overflow-y: auto; background-color: #1a1a1a; font-family: monospace; font-size: 12px;">
            <div class="text-muted">Logs aparecerán aquí...</div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<style>
.test-success { color: #28a745; }
.test-error { color: #dc3545; }
.test-warning { color: #ffc107; }
.test-info { color: #17a2b8; }
</style>

<script>
const resultsLog = document.getElementById('results-log');

function log(message, type = 'info') {
  const timestamp = new Date().toLocaleTimeString();
  const entry = document.createElement('div');
  entry.className = `test-${type}`;
  entry.innerHTML = `[${timestamp}] ${message}`;
  resultsLog.appendChild(entry);
  resultsLog.scrollTop = resultsLog.scrollHeight;
}

function clearResults() {
  resultsLog.innerHTML = '<div class="text-muted">Logs aparecerán aquí...</div>';
}

async function testAPI(endpoint) {
  log(`🔄 Probando: ${endpoint}`, 'info');
  
  try {
    const response = await fetch(endpoint);
    const data = await response.json();
    
    if (response.ok && data.success) {
      const count = data.tracks?.length || data.items?.length || data.playlists?.length || 0;
      log(`✅ ${endpoint} - OK (${count} items)`, 'success');
    } else {
      log(`❌ ${endpoint} - Error: ${data.error || 'Respuesta inválida'}`, 'error');
    }
  } catch (error) {
    log(`❌ ${endpoint} - Error de red: ${error.message}`, 'error');
  }
}

function testPlayback() {
  log('🔄 Probando reproducción...', 'info');
  
  if (window.playSpotify) {
    // URI de ejemplo de Dua Lipa - Levitating
    window.playSpotify('spotify:track:0VjIjW4GlULA3NkC7c2vSc');
    log('✅ Función playSpotify() existe y fue llamada', 'success');
    
    setTimeout(() => {
      if (window.isSpotifyPlaying && window.reproduciendo) {
        log('✅ Estado de reproducción actualizado correctamente', 'success');
      } else {
        log('⚠️ Estado de reproducción no se actualizó (normal si no hay Premium)', 'warning');
      }
    }, 2000);
  } else {
    log('❌ Función playSpotify() no disponible', 'error');
  }
}

function testSeek() {
  log('🔄 Probando seek...', 'info');
  
  const progressBar = document.getElementById('barra-progreso');
  if (progressBar) {
    // Simular seek al 50%
    progressBar.value = 50;
    progressBar.dispatchEvent(new Event('input'));
    log('✅ Barra de progreso encontrada y seek simulado', 'success');
  } else {
    log('❌ Barra de progreso no encontrada', 'error');
  }
}

function testVolume() {
  log('🔄 Probando control de volumen...', 'info');
  
  const volumeBar = document.getElementById('barra-volumen');
  if (volumeBar) {
    const originalVolume = volumeBar.value;
    volumeBar.value = 0.5;
    volumeBar.dispatchEvent(new Event('input'));
    log(`✅ Volumen cambiado de ${originalVolume} a 0.5`, 'success');
  } else {
    log('❌ Control de volumen no encontrado', 'error');
  }
}

function testHeart() {
  log('🔄 Probando botón de favoritos...', 'info');
  
  if (window.toggleCurrentTrackHeart) {
    log('✅ Función toggleCurrentTrackHeart() disponible', 'success');
    if (window.currentTrackId) {
      log(`ℹ️ Track actual ID: ${window.currentTrackId}`, 'info');
    } else {
      log('⚠️ No hay track actual (reproduce algo primero)', 'warning');
    }
  } else {
    log('❌ Función de favoritos no disponible', 'error');
  }
}

function testControls() {
  log('🔄 Probando controles avanzados...', 'info');
  
  // Test repeat
  fetch('/api/spotify/repeat', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    },
    body: JSON.stringify({ state: 'off' })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      log('✅ Control de repetir funcionando', 'success');
    } else {
      log('❌ Error en control de repetir', 'error');
    }
  })
  .catch(error => {
    log(`❌ Error testando controles: ${error.message}`, 'error');
  });
}

async function runAllTests() {
  log('🚀 Iniciando tests automáticos...', 'info');
  clearResults();
  
  // Tests de API
  const apis = [
    '/api/spotify/recently-played',
    '/api/spotify/recommendations?seed_genres=pop',
    '/api/spotify/saved-tracks',
    '/api/spotify/top/tracks',
    '/api/spotify/playlists',
    '/api/spotify/queue'
  ];
  
  for (const api of apis) {
    await testAPI(api);
    await new Promise(resolve => setTimeout(resolve, 500)); // Pausa entre requests
  }
  
  // Tests de funcionalidad
  testPlayback();
  await new Promise(resolve => setTimeout(resolve, 1000));
  testSeek();
  testVolume();
  testHeart();
  testControls();
  
  log('🎉 Tests completados!', 'success');
}

// Test inicial de conexión
document.addEventListener('DOMContentLoaded', () => {
  log('📱 Página de diagnóstico cargada', 'info');
  log(`ℹ️ Usuario: {{ auth()->user()->name ?? 'No autenticado' }}`, 'info');
  log(`ℹ️ Spotify Token: {{ auth()->user()->spotify_token ? 'Disponible' : 'No disponible' }}`, 'info');
});
</script>
@endsection
