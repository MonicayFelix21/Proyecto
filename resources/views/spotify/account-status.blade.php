@extends('layouts.app')

@section('title', 'Estado de la Cuenta de Spotify')

@section('content')
<div class="container mt-4">
    <div class="row justify    if (!data.connected) {
        content.innerHTML = `
            <div class="alert alert-warning">
                <h5><i class="fas fa-exclamation-triangle me-2"></i>Cuenta no conectada</h5>
                <p>${data.message}</p>
                <a href="/auth/spotify" class="btn btn-success">
                    <i class="fab fa-spotify"></i> Conectar Spotify
                </a>
            </div>
        `;
        return;
    }
    
    // Verificar si hay problema de permisos
    if (data.subscription_info && data.subscription_info.product === 'scope_missing') {
        content.innerHTML = `
            <div class="alert alert-danger">
                <h5><i class="fas fa-exclamation-triangle me-2"></i>Permisos insuficientes</h5>
                <p>${data.subscription_info.error_message}</p>
                <div class="mt-3">
                    <a href="/spotify/reset-connection" class="btn btn-warning me-2">
                        <i class="fas fa-sync-alt"></i> Reiniciar conexión
                    </a>
                    <a href="/auth/spotify" class="btn btn-success">
                        <i class="fab fa-spotify"></i> Reconectar con permisos completos
                    </a>
                </div>
                <small class="text-muted mt-2 d-block">
                    <strong>Problema:</strong> La aplicación no tiene permisos para ver el tipo de cuenta (Premium/Gratuito).
                    Necesitas reconectar para otorgar el permiso "user-read-private".
                </small>
            </div>
        `;
        return;
    }ter">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Estado de tu Cuenta de Spotify</h4>
                </div>
                <div class="card-body">
                    <div id="account-status-loading" class="text-center">
                        <div class="spinner-border text-success" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <p class="mt-2">Verificando tu cuenta de Spotify...</p>
                    </div>

                    <div id="account-status-content" style="display: none;">
                        <!-- El contenido se cargará dinámicamente -->
                    </div>

                    <div class="mt-4">
                        <button id="refresh-status" class="btn btn-outline-success" onclick="checkAccountStatus()">
                            <i class="fas fa-sync-alt"></i> Actualizar Estado
                        </button>
                        
                        @if(!auth()->user()->hasSpotifyConnected())
                            <a href="{{ route('spotify.login') }}" class="btn btn-success ms-2">
                                <i class="fab fa-spotify"></i> Conectar Spotify
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Información adicional sobre Premium -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">¿Qué incluye Spotify Premium?</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <p class="text-muted">
                                <strong>Nota:</strong> Todos los tipos de Premium (Individual, Familiar, Duo, Estudiantes) 
                                aparecen como "Premium" en la API de Spotify y tienen las mismas características principales.
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i> Música sin anuncios</li>
                                <li><i class="fas fa-check text-success me-2"></i> Escucha sin conexión</li>
                                <li><i class="fas fa-check text-success me-2"></i> Reproduce cualquier canción</li>
                                <li><i class="fas fa-check text-success me-2"></i> Saltos ilimitados</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check text-success me-2"></i> Calidad de audio superior</li>
                                <li><i class="fas fa-check text-success me-2"></i> Sesiones grupales</li>
                                <li><i class="fas fa-check text-success me-2"></i> Escucha en cualquier dispositivo</li>
                                <li><i class="fas fa-check text-success me-2"></i> Organiza la cola de reproducción</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6 class="mb-2">Tipos de planes Premium disponibles:</h6>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center py-2">
                                            <small><strong>Individual</strong><br>1 cuenta</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center py-2">
                                            <small><strong>Duo</strong><br>2 cuentas</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center py-2">
                                            <small><strong>Familiar</strong><br>6 cuentas</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card bg-light">
                                        <div class="card-body text-center py-2">
                                            <small><strong>Estudiantes</strong><br>1 cuenta con descuento</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="text-muted mt-2 small">
                                <i class="fas fa-info-circle me-1"></i>
                                Todos estos planes aparecen como "Premium" en tu cuenta y tienen acceso completo a las funciones Premium.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    checkAccountStatus();
});

async function checkAccountStatus() {
    const loading = document.getElementById('account-status-loading');
    const content = document.getElementById('account-status-content');
    const refreshBtn = document.getElementById('refresh-status');
    
    loading.style.display = 'block';
    content.style.display = 'none';
    refreshBtn.disabled = true;
    
    try {
        const response = await fetch('/api/spotify/account-status', {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            }
        });
        
        const data = await response.json();
        displayAccountStatus(data);
    } catch (error) {
        console.error('Error:', error);
        content.innerHTML = `
            <div class="alert alert-danger">
                <h5>Error al verificar la cuenta</h5>
                <p>No se pudo conectar con Spotify. Por favor, intenta de nuevo más tarde.</p>
            </div>
        `;
    } finally {
        loading.style.display = 'none';
        content.style.display = 'block';
        refreshBtn.disabled = false;
    }
}

function displayAccountStatus(data) {
    const content = document.getElementById('account-status-content');
    
    if (!data.connected) {
        content.innerHTML = `
            <div class="alert alert-warning">
                <h5><i class="fas fa-exclamation-triangle me-2"></i>Cuenta no conectada</h5>
                <p>${data.message}</p>
                <a href="/spotify/login" class="btn btn-success">
                    <i class="fab fa-spotify"></i> Conectar Spotify
                </a>
            </div>
        `;
        return;
    }
    
    const isPremium = data.is_premium;
    const info = data.subscription_info;
    
    content.innerHTML = `
        <div class="alert ${isPremium ? 'alert-success' : 'alert-info'}">
            <div class="d-flex align-items-center mb-3">
                <i class="fab fa-spotify fa-2x me-3"></i>
                <div>
                    <h5 class="mb-1">${data.message}</h5>
                    <small class="text-muted">Conectado como: ${info.display_name || info.email || 'Usuario'}</small>
                </div>
                ${isPremium ? '<span class="badge bg-success ms-auto fs-6">PREMIUM</span>' : '<span class="badge bg-secondary ms-auto fs-6">GRATUITO</span>'}
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <h6>Información de la cuenta:</h6>
                <ul class="list-unstyled">
                    <li><strong>Tipo de cuenta:</strong> ${info.product_display || info.product.charAt(0).toUpperCase() + info.product.slice(1)}</li>
                    <li><strong>País:</strong> ${info.country || 'No disponible'}</li>
                    <li><strong>Seguidores:</strong> ${info.followers.toLocaleString()}</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h6>Estado de Premium:</h6>
                <p class="${isPremium ? 'text-success' : 'text-muted'}">
                    <i class="fas ${isPremium ? 'fa-check-circle' : 'fa-times-circle'} me-2"></i>
                    ${isPremium ? 'Tienes acceso a todas las funciones Premium' : 'Cuenta gratuita con limitaciones'}
                </p>
                ${isPremium ? `
                    <div class="alert alert-info p-2 mt-2">
                        <small>
                            <i class="fas fa-info-circle me-1"></i>
                            <strong>¿Plan Familiar?</strong> Todos los tipos Premium (Individual, Familiar, Duo, Estudiantes) 
                            aparecen como "Premium" y tienen las mismas características.
                        </small>
                    </div>
                ` : `
                    <a href="https://www.spotify.com/premium/" target="_blank" class="btn btn-success btn-sm">
                        <i class="fas fa-crown me-1"></i> Obtener Premium
                    </a>
                `}
            </div>
        </div>
    `;
}
</script>
@endsection
