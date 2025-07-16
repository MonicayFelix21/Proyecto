@extends('layouts.app')

@section('title', 'Spotify Avanzado')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-12">
      <div class="d-flex align-items-center mb-4">
        <h1 class="h3 mb-0">🎵 Funcionalidades Avanzadas de Spotify</h1>
      </div>
      
      <div class="alert alert-info">
        <strong>¡Nuevas funciones disponibles!</strong> Explora tu música con recomendaciones, historial, favoritos y más.
      </div>

      {{-- Navegación por pestañas --}}
      <ul class="nav nav-pills mb-4" id="advanced-tabs" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="recently-tab" data-bs-toggle="pill" data-bs-target="#recently" type="button">
            🕒 Recientes
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="recommendations-tab" data-bs-toggle="pill" data-bs-target="#recommendations" type="button">
            🎯 Recomendaciones
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="favorites-tab" data-bs-toggle="pill" data-bs-target="#favorites" type="button">
            💝 Favoritos
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="top-tab" data-bs-toggle="pill" data-bs-target="#top" type="button">
            📊 Top Personal
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="playlists-tab" data-bs-toggle="pill" data-bs-target="#playlists" type="button">
            📋 Playlists
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button class="nav-link" id="queue-tab" data-bs-toggle="pill" data-bs-target="#queue" type="button">
            🎵 Cola
          </button>
        </li>
      </ul>

      {{-- Contenido de las pestañas --}}
      <div class="tab-content" id="advanced-content">
        
        {{-- 🕒 Canciones recientes --}}
        <div class="tab-pane fade show active" id="recently" role="tabpanel">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>🕒 Reproducido recientemente</h5>
            <button class="btn btn-outline-light btn-sm" onclick="loadRecentlyPlayed()">
              <i class="bi bi-arrow-clockwise"></i> Actualizar
            </button>
          </div>
          <div id="recently-content" class="row">
            <div class="col-12 text-center py-4">
              <div class="spinner-border text-success" role="status"></div>
              <p class="mt-2">Cargando canciones recientes...</p>
            </div>
          </div>
        </div>

        {{-- 🎯 Recomendaciones --}}
        <div class="tab-pane fade" id="recommendations" role="tabpanel">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>🎯 Recomendaciones para ti</h5>
            <div class="d-flex gap-2">
              <select class="form-select form-select-sm" id="genre-select" style="width: auto;">
                <option value="pop,rock">Pop & Rock</option>
                <option value="electronic,dance">Electronic & Dance</option>
                <option value="hip-hop,rap">Hip-Hop & Rap</option>
                <option value="jazz,blues">Jazz & Blues</option>
                <option value="classical,ambient">Classical & Ambient</option>
                <option value="latin,reggaeton">Latin & Reggaeton</option>
              </select>
              <button class="btn btn-outline-light btn-sm" onclick="loadRecommendations()">
                <i class="bi bi-magic"></i> Generar
              </button>
            </div>
          </div>
          <div id="recommendations-content" class="row">
            <div class="col-12 text-center py-4">
              <i class="bi bi-magic" style="font-size: 3rem; color: #1db954;"></i>
              <p class="mt-2">Haz clic en "Generar" para obtener recomendaciones personalizadas</p>
            </div>
          </div>
        </div>

        {{-- 💝 Favoritos --}}
        <div class="tab-pane fade" id="favorites" role="tabpanel">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>💝 Tus canciones favoritas</h5>
            <button class="btn btn-outline-light btn-sm" onclick="loadSavedTracks()">
              <i class="bi bi-heart-fill"></i> Cargar
            </button>
          </div>
          <div id="favorites-content" class="row">
            <div class="col-12 text-center py-4">
              <i class="bi bi-heart" style="font-size: 3rem; color: #1db954;"></i>
              <p class="mt-2">Haz clic en "Cargar" para ver tus canciones guardadas</p>
            </div>
          </div>
        </div>

        {{-- 📊 Top personal --}}
        <div class="tab-pane fade" id="top" role="tabpanel">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>📊 Tu música favorita</h5>
            <div class="d-flex gap-2">
              <select class="form-select form-select-sm" id="top-type" style="width: auto;">
                <option value="tracks">🎵 Canciones</option>
                <option value="artists">🎤 Artistas</option>
              </select>
              <select class="form-select form-select-sm" id="time-range" style="width: auto;">
                <option value="short_term">Último mes</option>
                <option value="medium_term">Últimos 6 meses</option>
                <option value="long_term">Últimos años</option>
              </select>
              <button class="btn btn-outline-light btn-sm" onclick="loadTopItems()">
                <i class="bi bi-trophy"></i> Cargar
              </button>
            </div>
          </div>
          <div id="top-content" class="row">
            <div class="col-12 text-center py-4">
              <i class="bi bi-trophy" style="font-size: 3rem; color: #1db954;"></i>
              <p class="mt-2">Descubre tu música más escuchada</p>
            </div>
          </div>
        </div>

        {{-- 📋 Playlists --}}
        <div class="tab-pane fade" id="playlists" role="tabpanel">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>📋 Tus playlists</h5>
            <button class="btn btn-outline-light btn-sm" onclick="loadPlaylists()">
              <i class="bi bi-list-ul"></i> Cargar
            </button>
          </div>
          <div id="playlists-content" class="row">
            <div class="col-12 text-center py-4">
              <i class="bi bi-music-note-list" style="font-size: 3rem; color: #1db954;"></i>
              <p class="mt-2">Explora todas tus playlists</p>
            </div>
          </div>
        </div>

        {{-- 🎵 Cola de reproducción --}}
        <div class="tab-pane fade" id="queue" role="tabpanel">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5>🎵 Cola de reproducción</h5>
            <button class="btn btn-outline-light btn-sm" onclick="loadQueue()">
              <i class="bi bi-list-ol"></i> Actualizar
            </button>
          </div>
          <div id="queue-content">
            <div class="text-center py-4">
              <i class="bi bi-list-ol" style="font-size: 3rem; color: #1db954;"></i>
              <p class="mt-2">Ver qué viene después en tu reproducción</p>
            </div>
          </div>
        </div>

      </div>

      {{-- Controles adicionales --}}
      <div class="card bg-dark border-secondary mt-4">
        <div class="card-header">
          <h5 class="mb-0">🎮 Controles Avanzados</h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <h6>Modo repetir</h6>
              <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-light btn-sm" onclick="setRepeat('off')">
                  <i class="bi bi-arrow-right"></i> Desactivado
                </button>
                <button type="button" class="btn btn-outline-light btn-sm" onclick="setRepeat('context')">
                  <i class="bi bi-repeat"></i> Lista
                </button>
                <button type="button" class="btn btn-outline-light btn-sm" onclick="setRepeat('track')">
                  <i class="bi bi-repeat-1"></i> Canción
                </button>
              </div>
            </div>
            <div class="col-md-6">
              <h6>Modo aleatorio</h6>
              <div class="btn-group" role="group">
                <button type="button" class="btn btn-outline-light btn-sm" onclick="setShuffle(false)">
                  <i class="bi bi-list-ol"></i> Orden
                </button>
                <button type="button" class="btn btn-outline-light btn-sm" onclick="setShuffle(true)">
                  <i class="bi bi-shuffle"></i> Aleatorio
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<style>
.nav-pills .nav-link {
  color: #b3b3b3;
  background: transparent;
  border-radius: 20px;
  margin-right: 8px;
}

.nav-pills .nav-link:hover {
  color: white;
  background-color: #282828;
}

.nav-pills .nav-link.active {
  color: black;
  background-color: #1db954;
}

.track-item, .artist-item, .playlist-item {
  background-color: #181818;
  border-radius: 8px;
  padding: 12px;
  margin-bottom: 8px;
  transition: all 0.2s ease;
  cursor: pointer;
}

.track-item:hover, .artist-item:hover, .playlist-item:hover {
  background-color: #282828;
  transform: translateY(-1px);
}

.track-item img, .artist-item img, .playlist-item img {
  border-radius: 4px;
  object-fit: cover;
}

.heart-btn {
  background: none;
  border: none;
  color: #b3b3b3;
  font-size: 16px;
  transition: all 0.2s ease;
}

.heart-btn:hover {
  color: #1db954;
  transform: scale(1.1);
}

.heart-btn.active {
  color: #1db954;
}

.queue-item {
  padding: 8px 12px;
  border-radius: 4px;
  margin-bottom: 4px;
  background-color: #181818;
  transition: background-color 0.2s ease;
}

.queue-item:hover {
  background-color: #282828;
}

.queue-item.current {
  background-color: #1db954;
  color: black;
}
</style>

<script>
// Funciones para cargar contenido

function loadRecentlyPlayed() {
  const content = document.getElementById('recently-content');
  content.innerHTML = '<div class="col-12 text-center py-4"><div class="spinner-border text-success"></div></div>';
  
  fetch('/api/spotify/recently-played?limit=20')
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        displayTracks(data.tracks, 'recently-content');
      } else {
        showError('recently-content', data.error);
      }
    })
    .catch(error => showError('recently-content', error.message));
}

function loadRecommendations() {
  const content = document.getElementById('recommendations-content');
  const genres = document.getElementById('genre-select').value;
  
  content.innerHTML = '<div class="col-12 text-center py-4"><div class="spinner-border text-success"></div></div>';
  
  fetch(`/api/spotify/recommendations?seed_genres=${genres}&limit=20`)
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        displayTracks(data.tracks, 'recommendations-content');
      } else {
        showError('recommendations-content', data.error);
      }
    })
    .catch(error => showError('recommendations-content', error.message));
}

function loadSavedTracks() {
  const content = document.getElementById('favorites-content');
  content.innerHTML = '<div class="col-12 text-center py-4"><div class="spinner-border text-success"></div></div>';
  
  fetch('/api/spotify/saved-tracks?limit=20')
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        displayTracks(data.tracks, 'favorites-content', true);
      } else {
        showError('favorites-content', data.error);
      }
    })
    .catch(error => showError('favorites-content', error.message));
}

function loadTopItems() {
  const content = document.getElementById('top-content');
  const type = document.getElementById('top-type').value;
  const timeRange = document.getElementById('time-range').value;
  
  content.innerHTML = '<div class="col-12 text-center py-4"><div class="spinner-border text-success"></div></div>';
  
  fetch(`/api/spotify/top/${type}?time_range=${timeRange}&limit=20`)
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        if (type === 'tracks') {
          displayTracks(data.items, 'top-content');
        } else {
          displayArtists(data.items, 'top-content');
        }
      } else {
        showError('top-content', data.error);
      }
    })
    .catch(error => showError('top-content', error.message));
}

function loadPlaylists() {
  const content = document.getElementById('playlists-content');
  content.innerHTML = '<div class="col-12 text-center py-4"><div class="spinner-border text-success"></div></div>';
  
  fetch('/api/spotify/playlists?limit=20')
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        displayPlaylists(data.playlists, 'playlists-content');
      } else {
        showError('playlists-content', data.error);
      }
    })
    .catch(error => showError('playlists-content', error.message));
}

function loadQueue() {
  const content = document.getElementById('queue-content');
  content.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-success"></div></div>';
  
  fetch('/api/spotify/queue')
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        displayQueue(data.queue, 'queue-content');
      } else {
        showError('queue-content', data.error);
      }
    })
    .catch(error => showError('queue-content', error.message));
}

// Funciones de visualización

function displayTracks(tracks, containerId, showHeart = false) {
  const container = document.getElementById(containerId);
  if (!tracks.length) {
    container.innerHTML = '<div class="col-12 text-center py-4"><p>No hay canciones disponibles</p></div>';
    return;
  }
  
  const html = tracks.map(track => `
    <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
      <div class="track-item" onclick="playTrack('${track.uri}')">
        <img src="${track.image || '/imagenes/default.png'}" alt="${track.name}" 
             style="width: 100%; aspect-ratio: 1; margin-bottom: 8px;">
        <h6 class="mb-1 text-truncate">${track.name}</h6>
        <p class="mb-0 text-muted small text-truncate">${track.artists}</p>
        ${showHeart ? `
          <button class="heart-btn active float-end" onclick="event.stopPropagation(); toggleHeart('${track.id}', this)">
            <i class="bi bi-heart-fill"></i>
          </button>
        ` : ''}
      </div>
    </div>
  `).join('');
  
  container.innerHTML = `<div class="row">${html}</div>`;
}

function displayArtists(artists, containerId) {
  const container = document.getElementById(containerId);
  if (!artists.length) {
    container.innerHTML = '<div class="col-12 text-center py-4"><p>No hay artistas disponibles</p></div>';
    return;
  }
  
  const html = artists.map(artist => `
    <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
      <div class="artist-item text-center">
        <img src="${artist.image || '/imagenes/default.png'}" alt="${artist.name}" 
             style="width: 120px; height: 120px; border-radius: 50%; margin-bottom: 12px;">
        <h6 class="mb-1">${artist.name}</h6>
        <p class="mb-1 text-muted small">${artist.followers?.toLocaleString()} seguidores</p>
        <p class="mb-0 text-muted small">${artist.genres?.slice(0, 2).join(', ')}</p>
      </div>
    </div>
  `).join('');
  
  container.innerHTML = `<div class="row">${html}</div>`;
}

function displayPlaylists(playlists, containerId) {
  const container = document.getElementById(containerId);
  if (!playlists.length) {
    container.innerHTML = '<div class="col-12 text-center py-4"><p>No hay playlists disponibles</p></div>';
    return;
  }
  
  const html = playlists.map(playlist => `
    <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
      <div class="playlist-item">
        <img src="${playlist.image || '/imagenes/default.png'}" alt="${playlist.name}" 
             style="width: 100%; aspect-ratio: 1; margin-bottom: 8px;">
        <h6 class="mb-1 text-truncate">${playlist.name}</h6>
        <p class="mb-1 text-muted small text-truncate">${playlist.description || 'Sin descripción'}</p>
        <p class="mb-0 text-muted small">${playlist.tracks_total} canciones • ${playlist.owner}</p>
      </div>
    </div>
  `).join('');
  
  container.innerHTML = `<div class="row">${html}</div>`;
}

function displayQueue(queue, containerId) {
  const container = document.getElementById(containerId);
  let html = '';
  
  if (queue.currently_playing) {
    html += `
      <div class="queue-item current mb-3">
        <strong>🔊 Reproduciendo ahora:</strong><br>
        <strong>${queue.currently_playing.name}</strong><br>
        <small>${queue.currently_playing.artists}</small>
      </div>
    `;
  }
  
  if (queue.queue && queue.queue.length) {
    html += '<h6 class="mt-3 mb-2">Siguiente:</h6>';
    queue.queue.forEach((track, index) => {
      html += `
        <div class="queue-item">
          <strong>${index + 1}. ${track.name}</strong><br>
          <small class="text-muted">${track.artists}</small>
        </div>
      `;
    });
  } else {
    html += '<p class="text-center py-4">No hay canciones en la cola</p>';
  }
  
  container.innerHTML = html;
}

function showError(containerId, message) {
  const container = document.getElementById(containerId);
  container.innerHTML = `
    <div class="col-12 text-center py-4">
      <i class="bi bi-exclamation-triangle text-warning" style="font-size: 2rem;"></i>
      <p class="mt-2 text-warning">Error: ${message}</p>
    </div>
  `;
}

// Funciones de interacción

function playTrack(uri) {
  if (window.playSpotify) {
    window.playSpotify(uri);
  } else {
    console.warn('playSpotify no disponible');
  }
}

function toggleHeart(trackId, button) {
  fetch('/api/spotify/toggle-saved-track', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    },
    body: JSON.stringify({ track_id: trackId })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      const icon = button.querySelector('i');
      if (data.is_saved) {
        icon.className = 'bi bi-heart-fill';
        button.classList.add('active');
      } else {
        icon.className = 'bi bi-heart';
        button.classList.remove('active');
      }
    }
  })
  .catch(error => console.error('Error:', error));
}

function setRepeat(mode) {
  fetch('/api/spotify/repeat', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    },
    body: JSON.stringify({ state: mode })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      console.log(`Modo repetir: ${mode}`);
    }
  });
}

function setShuffle(enabled) {
  fetch('/api/spotify/shuffle', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
    },
    body: JSON.stringify({ state: enabled ? 'true' : 'false' })
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      console.log(`Modo aleatorio: ${enabled}`);
    }
  });
}

// Cargar contenido inicial
document.addEventListener('DOMContentLoaded', function() {
  loadRecentlyPlayed();
});
</script>
@endsection
