@auth
    <div id="premium-status-badge" class="d-flex align-items-center">
        <span class="spinner-border spinner-border-sm text-light me-2" role="status" style="display: none;" id="premium-loading">
            <span class="visually-hidden">Cargando...</span>
        </span>
        <a href="{{ route('spotify.account.status') }}" 
           class="text-decoration-none d-flex align-items-center" 
           id="premium-link">
            <span id="premium-text" class="fw-semibold">Premium</span>
            <i class="fas fa-crown ms-1" id="premium-crown" style="display: none;"></i>
        </a>
    </div>

    <script>
    // Verificar estado Premium al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        checkPremiumStatus();
    });

    async function checkPremiumStatus() {
        const loading = document.getElementById('premium-loading');
        const text = document.getElementById('premium-text');
        const crown = document.getElementById('premium-crown');
        const link = document.getElementById('premium-link');
        
        try {
            loading.style.display = 'inline-block';
            
            const response = await fetch('/api/spotify/is-premium', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            });
            
            const data = await response.json();
            
            if (data.is_premium) {
                text.textContent = 'Premium';
                text.className = 'fw-semibold text-warning';
                crown.style.display = 'inline';
                crown.className = 'fas fa-crown ms-1 text-warning';
                link.title = 'Tienes Spotify Premium activo';
            } else {
                text.textContent = 'Obtener Premium';
                text.className = 'fw-semibold text-white-50';
                crown.style.display = 'none';
                link.title = 'Verificar estado de tu cuenta';
            }
        } catch (error) {
            console.error('Error verificando Premium:', error);
            text.textContent = 'Premium';
            text.className = 'fw-semibold text-white-50';
        } finally {
            loading.style.display = 'none';
        }
    }
    </script>
@else
    <a href="#" class="text-white-50 fw-semibold">Premium</a>
@endauth
