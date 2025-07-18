{{-- resources/views/partials/spotify-modal.blade.php --}}
@push('styles')
<style>
  /* Oscurecer + desenfocar backdrop */
  .modal-backdrop.show {
    background-color: rgba(0, 0, 0, 0.85) !important;
    backdrop-filter: blur(4px);
  }

  /* Contenedor modal */
  .spotify-modal {
    background: linear-gradient(180deg, #2f343d 0%, #121416 100%);
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7);
  }

  /* Ajustar tamaño del diálogo */
  .spotify-modal .modal-dialog {
    max-width: 80vw;
    width: 800px;
  }

  /* Grid: 33% imagen / 67% contenido */
  .spotify-modal .row.g-0 {
    margin: 0;
  }
  .spotify-modal-img {
    padding: 0;
  }
  .spotify-modal-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }

  .spotify-modal-body {
    padding: 2rem 2rem 2rem 2rem;
    color: #fff;
  }

  /* Tipografía */
  .spotify-modal-body h2 {
    font-size: 2.25rem;
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: 1.5rem;
  }

  /* Botones */
  .btn-spotify-green {
    background-color: #1db954;
    border-color: #1db954;
    color: #000;
    font-size: 1.125rem;
    padding: 0.75rem 1.5rem;
    border-radius: 24px;
    transition: background-color 0.2s ease;
  }
  .btn-spotify-green:hover {
    background-color: #17a44d;
  }

  .btn-outline-light {
    color: #fff;
    border-color: rgba(255,255,255,0.6);
    font-size: 1.125rem;
    padding: 0.75rem 1.5rem;
    border-radius: 24px;
    transition: background-color 0.2s ease, border-color 0.2s ease;
  }
  .btn-outline-light:hover {
    background-color: rgba(255,255,255,0.1);
    border-color: #fff;
  }

  /* Enlace “Iniciar sesión” */
  .spotify-modal-body a.text-decoration-underline {
    font-size: 0.875rem;
    color: rgba(255,255,255,0.7);
  }
  .spotify-modal-body a.text-decoration-underline:hover {
    color: #fff;
  }

  /* Botón cerrar */
  .btn-close-white {
    filter: brightness(1.2);
    width: 32px;
    height: 32px;
  }
</style>
@endpush

<div class="modal fade" id="playModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:800px; width:90vw;">
    <div class="modal-content spotify-modal d-flex position-relative">
      <div class="row g-0 w-100">
        <div class="col-12 col-md-4 spotify-modal-img">
          <img
            id="playModalImg"
            src=""
            alt="Portada de la canción"
            class="w-100 h-100 object-fit-cover"
          >
        </div>
        <div class="col-12 col-md-8 spotify-modal-body d-flex flex-column justify-content-center">
          <h2>Empieza a escuchar con una cuenta de Spotify gratis</h2>
          <a href="{{ url('/registro') }}"
             class="btn btn-spotify-green btn-lg w-100 mb-3">
            Regístrate gratis
          </a>
<a href="https://www.spotify.com/mx/download/windows/?referrer=dwp"
   target="_blank"
   rel="noopener"
   class="btn btn-outline-light btn-lg w-100 mb-3">
  Descargar aplicación
</a>

          <a href="{{ url('/login') }}"
             class="text-white-50 small text-decoration-underline">
            ¿Ya tienes cuenta? Iniciar sesión
          </a>
        </div>
      </div>
      <button type="button"
              class="btn-close btn-close-white position-absolute top-0 end-0 m-3"
              data-bs-dismiss="modal"
              aria-label="Cerrar">
      </button>
    </div>
  </div>
</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const modalEl = document.getElementById('playModal');
    const bsModal = new bootstrap.Modal(modalEl);

    document.querySelectorAll('.song-card .play-button').forEach(btn => {
      btn.addEventListener('click', () => {
        const imgSrc = btn.closest('.song-card').querySelector('img').src;
        document.getElementById('playModalImg').src = imgSrc;
        bsModal.show();
      });
    });
  });
</script>
@endpush