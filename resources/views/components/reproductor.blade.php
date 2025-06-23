<div id="reproductor"
     class="fixed-bottom bg-black px-4 py-2"
     style="z-index:1050; height: 90px; border-top: 1px solid #282828;">

  <div class="container-fluid d-flex justify-content-between align-items-center px-0">
{{-- INFORMACIÓN DE LA CANCIÓN ACTUAL --}}
<div class="d-flex align-items-center gap-2" id="info-cancion" style="min-width: 180px;">
  <img id="img-cancion"
       src="{{ asset('imagenes/default.png') }}"
       alt="Portada"
       style="width: 48px; height: 48px; object-fit: cover; border-radius: 4px;">
  <div class="d-flex flex-column">
    <span id="titulo-cancion" class="fw-semibold text-white small">Título</span>
    <span id="artista-cancion" class="text-white-50 small">Artista</span>
  </div>
</div>

    {{-- CONTROLES Y BARRA CENTRADA --}}
    <div class="d-flex flex-column align-items-center justify-content-center mx-auto"
         style="flex: 1; max-width: 700px;">

      {{-- Controles superiores --}}
      <div class="d-flex justify-content-center align-items-center gap-4 mb-2">
        <i class="bi bi-shuffle text-white-50 fs-5" role="button"></i>
        <i class="bi bi-skip-start-fill text-white-50 fs-5" role="button"></i>
<button id="btn-reproducir"
        class="btn border-0 rounded-circle d-flex justify-content-center align-items-center"
        style="width: 36px; height: 36px; background-color: white; color: black; padding: 0;">
  <i class="bi bi-play-fill" style="font-size: 16px; margin-left: 1px;"></i>
</button>
        <i class="bi bi-skip-end-fill text-white-50 fs-5" role="button"></i>
<i class="bi bi-repeat text-white-50 fs-5" role="button" title="Repetir"></i>
      </div>

{{-- Barra de progreso con tiempos alineados correctamente --}}
<div class="d-flex align-items-center justify-content-center gap-2 w-100 px-3" style="max-width: 640px;">
  <span id="tiempo-actual" class="text-white-50 small" style="min-width: 40px; text-align: right;">0:00</span>
  <input id="barra-progreso"
         type="range"
         min="0" max="100" value="0"
         class="form-range flex-grow-1"
         style="height: 1px; accent-color: #1db954;">
  <span id="tiempo-total" class="text-white-50 small" style="min-width: 40px; text-align: left;">0:00</span>
</div>
  </div>

  {{-- Controles de volumen (lado derecho) --}}
<div class="d-none d-md-flex align-items-center gap-2 ms-4" style="min-width: 120px;">
  <i class="bi bi-volume-up text-white-50 fs-5"></i>
  <input id="barra-volumen"
         type="range"
         min="0" max="1" step="0.01" value="1"
         class="form-range"
         style="height: 2px; accent-color: #1db954;">
</div>

</div>

{{-- ESTILOS SPOTIFY --}}
<style>
  #reproductor i:hover {
    color: white !important;
    transform: scale(1.1);
    transition: all 0.2s ease;
  }
  #btn-reproducir {
    transition: all 0.2s ease-in-out;
  }
  @media (max-width: 768px) {
    #barra-volumen {
      display: none;
    }
  }
</style>

@auth

@push('scripts')
<script>
  const audio = new Audio();
  let reproduciendo = false;

  const btnPlay = document.getElementById('btn-reproducir');
  const barra = document.getElementById('barra-progreso');
  const volumen = document.getElementById('barra-volumen');
  const tiempoActual = document.getElementById('tiempo-actual');
  const tiempoTotal = document.getElementById('tiempo-total');

  function formato(t) {
    const min = Math.floor(t / 60);
    const sec = Math.floor(t % 60).toString().padStart(2, "0");
    return `${min}:${sec}`;
  }

  // Click global del botón central del reproductor
  btnPlay?.addEventListener('click', () => {
    if (reproduciendo) {
      audio.pause();
      btnPlay.innerHTML = '<i class="bi bi-play-fill fs-5"></i>';
      btnPlay.style.backgroundColor = "white";
      btnPlay.style.color = "black";

      // Icono de la tarjeta también se actualiza
      document.querySelectorAll('.play-button i').forEach(i => {
        i.classList.remove('bi-pause');
        i.classList.add('bi-play-fill');
      });

    } else {
      audio.play().catch(e => console.error("Error:", e));
      btnPlay.innerHTML = '<i class="bi bi-pause-fill fs-5"></i>';
      btnPlay.style.backgroundColor = "#1db954";
      btnPlay.style.color = "white";

      // Encuentra la tarjeta activa y actualiza el icono
      document.querySelectorAll('.song-card').forEach(card => {
        if (card.dataset.audio === audio.src) {
          const icon = card.querySelector('.play-button i');
          icon?.classList.remove('bi-play-fill');
          icon?.classList.add('bi-pause');
        }
      });
    }
    reproduciendo = !reproduciendo;
  });

  audio.addEventListener('loadedmetadata', () => {
    tiempoTotal.textContent = formato(audio.duration);
  });

  audio.addEventListener('timeupdate', () => {
    barra.value = (audio.currentTime / audio.duration) * 100 || 0;
    tiempoActual.textContent = formato(audio.currentTime);
  });

  barra?.addEventListener('input', () => {
    audio.currentTime = (barra.value / 100) * audio.duration;
  });

  volumen?.addEventListener('input', () => {
    audio.volume = volumen.value;
  });

  window.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.song-card').forEach(card => {
      const btn = card.querySelector('.play-button');
      btn?.addEventListener('click', () => {
        const src = card.dataset.audio;
        const titulo = card.dataset.titulo;
        const artista = card.dataset.artista;
        const imagen = card.dataset.imagen;

        if (!src) return;

        // Si es otra canción
        if (audio.src !== src) {
          audio.pause();
          audio.src = src;
          audio.currentTime = 0;
          audio.play().catch(e => console.error("Error:", e));

          // Actualiza información del reproductor
          document.getElementById('img-cancion').src = imagen;
          document.getElementById('titulo-cancion').textContent = titulo;
          document.getElementById('artista-cancion').textContent = artista;

          // Resetea todos los íconos a play
          document.querySelectorAll('.play-button i').forEach(i => {
            i.classList.remove('bi-pause');
            i.classList.add('bi-play-fill');
          });

          // Este se pone en pause
          btn.querySelector('i').classList.remove('bi-play-fill');
          btn.querySelector('i').classList.add('bi-pause');

          btnPlay.innerHTML = '<i class="bi bi-pause-fill fs-5"></i>';
          btnPlay.style.backgroundColor = "#1db954";
          btnPlay.style.color = "white";
          reproduciendo = true;

        } else {
          // Si es la misma canción (toggle)
          if (audio.paused) {
            audio.play();
            btn.querySelector('i').classList.remove('bi-play-fill');
            btn.querySelector('i').classList.add('bi-pause');

            btnPlay.innerHTML = '<i class="bi bi-pause-fill fs-5"></i>';
            btnPlay.style.backgroundColor = "#1db954";
            btnPlay.style.color = "white";
            reproduciendo = true;

          } else {
            audio.pause();
            btn.querySelector('i').classList.remove('bi-pause');
            btn.querySelector('i').classList.add('bi-play-fill');

            btnPlay.innerHTML = '<i class="bi bi-play-fill fs-5"></i>';
            btnPlay.style.backgroundColor = "white";
            btnPlay.style.color = "black";
            reproduciendo = false;
          }
        }
      });
    });
  });
</script>
@endpush
@endauth







