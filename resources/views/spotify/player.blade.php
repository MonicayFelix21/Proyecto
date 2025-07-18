@extends('layouts.app')

@section('title', 'Reproductor de Spotify')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <!-- Solo mostrar si es Premium -->
            @if(auth()->user()->isSpotifyPremium())
                <div class="card bg-dark text-white">
                    <div class="card-header">
                        <h4 class="mb-0">
                            <i class="fab fa-spotify text-success me-2"></i>
                            Reproductor Web de Spotify
                            <span class="badge bg-success ms-2">PREMIUM</span>
                        </h4>
                    </div>
                    <div class="card-body">
                        <!-- Estado del reproductor -->
                        <div id="player-status" class="alert alert-info">
                            <i class="fas fa-spinner fa-spin me-2"></i>
                            Inicializando reproductor...
                        </div>

                        <!-- Información de la canción actual -->
                        <div id="current-track" class="row mb-4" style="display: none;">
                            <div class="col-md-2">
                                <img id="track-image" src="" alt="Album cover" class="img-fluid rounded">
                            </div>
                            <div class="col-md-10">
                                <h5 id="track-name" class="text-white"></h5>
                                <p id="track-artist" class="text-muted mb-1"></p>
                                <p id="track-album" class="text-muted mb-0"></p>
                            </div>
                        </div>

                        <!-- Controles del reproductor -->
                        <div class="row mb-4">
                            <div class="col-12 text-center">
                                <div class="btn-group btn-group-lg" role="group">
                                    <button id="prev-btn" class="btn btn-outline-light" title="Anterior">
                                        <i class="fas fa-backward"></i>
                                    </button>
                                    <button id="play-pause-btn" class="btn btn-success" title="Reproducir/Pausar">
                                        <i class="fas fa-play"></i>
                                    </button>
                                    <button id="next-btn" class="btn btn-outline-light" title="Siguiente">
                                        <i class="fas fa-forward"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Control de volumen -->
                        <div class="row mb-4">
                            <div class="col-md-6 offset-md-3">
                                <label for="volume-slider" class="form-label">
                                    <i class="fas fa-volume-up me-2"></i>Volumen
                                </label>
                                <input type="range" class="form-range" id="volume-slider" 
                                       min="0" max="100" value="50">
                                <div class="d-flex justify-content-between">
                                    <small class="text-muted">0%</small>
                                    <small class="text-muted">100%</small>
                                </div>
                            </div>
                        </div>

                        <!-- Búsqueda de canciones -->
                        <div class="row">
                            <div class="col-md-8 offset-md-2">
                                <div class="input-group">
                                    <input type="text" id="search-input" class="form-control" 
                                           placeholder="Buscar canciones, artistas o álbumes...">
                                    <button id="search-btn" class="btn btn-success" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                                <div id="search-results" class="mt-3"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-warning">
                    <h5><i class="fas fa-crown me-2"></i>Se requiere Spotify Premium</h5>
                    <p>El reproductor web solo está disponible para usuarios con Spotify Premium.</p>
                    <a href="{{ route('spotify.account.status') }}" class="btn btn-success">
                        Verificar mi cuenta
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@if(auth()->user()->isSpotifyPremium())
<script src="https://sdk.scdn.co/spotify-player.js"></script>
<script>
// Token de Spotify desde el servidor
const SPOTIFY_TOKEN = '{{ $spotify_token }}';
let player;
let device_id;

// Función que se ejecuta cuando el SDK está listo
window.onSpotifyWebPlaybackSDKReady = () => {
    const token = SPOTIFY_TOKEN;
    player = new Spotify.Player({
        name: 'Reproductor Web Laravel-Spotify',
        getOAuthToken: cb => { cb(token); },
        volume: 0.5
    });

    // Eventos del reproductor
    player.addListener('ready', ({ device_id: ready_device_id }) => {
        console.log('Reproductor listo con Device ID', ready_device_id);
        device_id = ready_device_id;
        updatePlayerStatus('✅ Reproductor listo - Puedes buscar y reproducir música', 'success');
    });

    player.addListener('not_ready', ({ device_id }) => {
        console.log('Dispositivo no disponible', device_id);
        updatePlayerStatus('❌ Dispositivo no disponible', 'danger');
    });

    player.addListener('player_state_changed', (state) => {
        if (!state) return;
        
        updateCurrentTrack(state.track_window.current_track);
        updatePlayPauseButton(state.paused);
    });

    // Conectar el reproductor
    player.connect().then(success => {
        if (success) {
            console.log('Conectado exitosamente al SDK');
        } else {
            console.error('Error al conectar');
            updatePlayerStatus('❌ Error al conectar con Spotify', 'danger');
        }
    });
};

// Función para actualizar el estado del reproductor
function updatePlayerStatus(message, type = 'info') {
    const statusDiv = document.getElementById('player-status');
    statusDiv.className = `alert alert-${type}`;
    statusDiv.innerHTML = message;
}

// Función para actualizar la información de la canción actual
function updateCurrentTrack(track) {
    if (!track) return;
    
    document.getElementById('track-image').src = track.album.images[0]?.url || '';
    document.getElementById('track-name').textContent = track.name;
    document.getElementById('track-artist').textContent = track.artists.map(artist => artist.name).join(', ');
    document.getElementById('track-album').textContent = track.album.name;
    
    document.getElementById('current-track').style.display = 'block';
}

// Función para actualizar el botón play/pause
function updatePlayPauseButton(is_paused) {
    const btn = document.getElementById('play-pause-btn');
    const icon = btn.querySelector('i');
    
    if (is_paused) {
        icon.className = 'fas fa-play';
        btn.title = 'Reproducir';
    } else {
        icon.className = 'fas fa-pause';
        btn.title = 'Pausar';
    }
}

// Event listeners para controles
document.getElementById('play-pause-btn').addEventListener('click', () => {
    player.togglePlay();
});

document.getElementById('next-btn').addEventListener('click', () => {
    player.nextTrack();
});

document.getElementById('prev-btn').addEventListener('click', () => {
    player.previousTrack();
});

document.getElementById('volume-slider').addEventListener('input', (e) => {
    const volume = e.target.value / 100;
    player.setVolume(volume);
});

// Búsqueda de canciones
document.getElementById('search-btn').addEventListener('click', searchTracks);
document.getElementById('search-input').addEventListener('keypress', (e) => {
    if (e.key === 'Enter') searchTracks();
});

async function searchTracks() {
    const query = document.getElementById('search-input').value;
    if (!query.trim()) return;
    
    try {
        const response = await fetch(`/spotify/search?q=${encodeURIComponent(query)}&type=track&limit=10`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const data = await response.json();
        displaySearchResults(data.tracks?.items || []);
    } catch (error) {
        console.error('Error en búsqueda:', error);
    }
}

function displaySearchResults(tracks) {
    const resultsDiv = document.getElementById('search-results');
    
    if (tracks.length === 0) {
        resultsDiv.innerHTML = '<p class="text-muted">No se encontraron resultados</p>';
        return;
    }
    
    const tracksList = tracks.map(track => `
        <div class="card bg-secondary text-white mb-2">
            <div class="card-body p-3">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <img src="${track.album.images[2]?.url || ''}" alt="Album" width="50" height="50" class="rounded">
                    </div>
                    <div class="col">
                        <h6 class="mb-1">${track.name}</h6>
                        <small class="text-muted">${track.artists.map(a => a.name).join(', ')}</small>
                    </div>
                    <div class="col-auto">
                        <button class="btn btn-success btn-sm" onclick="playTrack('${track.uri}')">
                            <i class="fas fa-play"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
    
    resultsDiv.innerHTML = tracksList;
}

// Función para reproducir una canción específica
async function playTrack(trackUri) {
    try {
        const response = await fetch('/api/spotify/player/play', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                track_uri: trackUri,
                device_id: device_id
            })
        });
        
        if (response.ok) {
            updatePlayerStatus('🎵 Reproduciendo canción...', 'success');
        }
    } catch (error) {
        console.error('Error al reproducir:', error);
        updatePlayerStatus('❌ Error al reproducir la canción', 'danger');
    }
}
</script>
@endif
@endsection
